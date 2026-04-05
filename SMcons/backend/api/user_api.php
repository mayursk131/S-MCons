<?php
session_start();


if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');


require_once '../config/database.php';

$user_id = $_SESSION['user_id'];

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'stats':
            getUserStats();
            break;

        case 'properties':
            getAvailableProperties();
            break;

        case 'property_details':
            getPropertyDetails();
            break;

        case 'my_bookings':
            getUserBookings();
            break;

        case 'my_inquiries':
            getUserInquiries();
            break;

        case 'profile':
            getUserProfile();
            break;

        case 'book_property':
            bookProperty();
            break;

        case 'cancel_booking':
            cancelBooking();
            break;

        case 'booking_details':
            getBookingDetails();
            break;

        case 'update_profile':
            updateUserProfile();
            break;

        case 'change_password':
            changeUserPassword();
            break;

        case 'send_inquiry':
            sendInquiry();
            break;

        case 'chat':
            handleChat();
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    error_log("User API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Internal server error']);
}

function getUserStats() {
    global $conn, $user_id;

    try {
        
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM bookings WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $bookings = $stmt->get_result()->fetch_assoc()['count'];

        
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM inquiries WHERE email = (SELECT email FROM users WHERE id = ?)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $inquiries = $stmt->get_result()->fetch_assoc()['count'];

        
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM property_availability WHERE status = 'available'");
        $stmt->execute();
        $properties = $stmt->get_result()->fetch_assoc()['count'];

        
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM feedback_submissions WHERE email = (SELECT email FROM users WHERE id = ?)");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $feedback = $stmt->get_result()->fetch_assoc()['count'];

        echo json_encode([
            'success' => true,
            'bookings' => $bookings,
            'inquiries' => $inquiries,
            'properties' => $properties,
            'feedback' => $feedback
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getAvailableProperties() {
    global $conn;

    try {
        $stmt = $conn->prepare("SELECT * FROM property_availability WHERE status = 'available' ORDER BY created_at DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        $properties = [];
        while ($row = $result->fetch_assoc()) {
            $properties[] = $row;
        }

        echo json_encode(['success' => true, 'properties' => $properties]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getPropertyDetails() {
    global $conn;

    $property_id = $_GET['id'] ?? 0;

    if (!$property_id) {
        echo json_encode(['success' => false, 'error' => 'Property ID required']);
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM property_availability WHERE id = ?");
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $property = $result->fetch_assoc();
            echo json_encode(['success' => true, 'property' => $property]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Property not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getUserBookings() {
    global $conn, $user_id;

    try {
        $stmt = $conn->prepare("
            SELECT b.*,
                   p.property_name,
                   p.location
            FROM bookings b
            LEFT JOIN property_availability p ON b.property_id = p.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }

        echo json_encode(['success' => true, 'bookings' => $bookings]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getUserInquiries() {
    global $conn, $user_id;

    try {
        $stmt = $conn->prepare("
            SELECT i.*,
                   CASE
                       WHEN TIMESTAMPDIFF(HOUR, i.timestamp, NOW()) < 24 THEN 'pending'
                       WHEN TIMESTAMPDIFF(HOUR, i.timestamp, NOW()) < 168 THEN 'in_progress'
                       ELSE 'responded'
                   END as status
            FROM inquiries i
            WHERE i.email = (SELECT email FROM users WHERE id = ?)
            ORDER BY i.timestamp DESC
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $inquiries = [];
        while ($row = $result->fetch_assoc()) {
            $inquiries[] = $row;
        }

        echo json_encode(['success' => true, 'inquiries' => $inquiries]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getUserProfile() {
    global $conn, $user_id;

    try {
        $stmt = $conn->prepare("SELECT id, username, email, full_name, phone, user_type, status, created_at FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $profile = $result->fetch_assoc();
            echo json_encode(['success' => true, 'profile' => $profile]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Profile not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function bookProperty() {
    global $conn, $user_id;

    $property_id = $_POST['property_id_input'] ?? $_POST['property_id'] ?? 0;
    $visit_date = $_POST['visit_date'] ?? '';
    $visit_time = $_POST['visit_time'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    if (!$property_id || !$visit_date || !$visit_time || !$phone) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        return;
    }

    try {
        
        $stmt = $conn->prepare("SELECT id FROM property_availability WHERE id = ? AND status = 'available'");
        $stmt->bind_param("i", $property_id);
        $stmt->execute();

        if ($stmt->get_result()->num_rows === 0) {
            echo json_encode(['success' => false, 'error' => 'Property not available']);
            return;
        }

        
        $stmt = $conn->prepare("
            INSERT INTO bookings (user_id, property_id, visit_date, visit_time, phone, message, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
        ");
        $stmt->bind_param("iissss", $user_id, $property_id, $visit_date, $visit_time, $phone, $message);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Booking submitted successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to create booking']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function sendInquiry() {
    global $conn, $user_id;

    $subject = $_POST['subject'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';
    $property_id = $_POST['property_id'] ?? null;

    if (!$subject || !$phone || !$message) {
        echo json_encode(['success' => false, 'error' => 'All fields are required']);
        return;
    }

    try {
        
        $stmt = $conn->prepare("SELECT email, full_name FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            echo json_encode(['success' => false, 'error' => 'User not found']);
            return;
        }

        
        $stmt = $conn->prepare("
            INSERT INTO inquiries (name, email, phone, message, subject, property_id, timestamp)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("sssssi", $user['full_name'], $user['email'], $phone, $message, $subject, $property_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Inquiry sent successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to send inquiry']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function getBookingDetails() {
    global $conn, $user_id;

    $booking_id = $_GET['id'] ?? 0;
    if (!$booking_id) {
        echo json_encode(['success' => false, 'error' => 'Booking ID required']);
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ? AND user_id = ?");
        $stmt->bind_param('ii', $booking_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            echo json_encode(['success' => true, 'booking' => $result->fetch_assoc()]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Booking not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function cancelBooking() {
    global $conn, $user_id;

    $booking_id = $_GET['id'] ?? $_POST['id'] ?? 0;
    if (!$booking_id) {
        echo json_encode(['success' => false, 'error' => 'Booking ID required']);
        return;
    }

    try {
        $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->bind_param('ii', $booking_id, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Booking cancelled successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Unable to cancel booking']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function updateUserProfile() {
    global $conn, $user_id;

    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $city = $_POST['city'] ?? '';
    $state = $_POST['state'] ?? '';
    $pincode = $_POST['pincode'] ?? '';

    if (!$full_name || !$phone) {
        echo json_encode(['success' => false, 'error' => 'Full name and phone are required']);
        return;
    }

    try {
        $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, city = ?, state = ?, pincode = ? WHERE id = ?");
        $stmt->bind_param('sssssi', $full_name, $phone, $city, $state, $pincode, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows >= 0) {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Profile update failed']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function changeUserPassword() {
    global $conn, $user_id;

    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    if (!$current_password || !$new_password) {
        echo json_encode(['success' => false, 'error' => 'Password fields are required']);
        return;
    }

    if (strlen($new_password) < 8) {
        echo json_encode(['success' => false, 'error' => 'New password must be at least 8 characters']);
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows !== 1) {
            echo json_encode(['success' => false, 'error' => 'User not found']);
            return;
        }

        $user = $result->fetch_assoc();
        if (!password_verify($current_password, $user['password'])) {
            echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
            return;
        }

        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param('si', $hashed, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Password change failed']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function handleChat() {
    global $conn, $user_id;

    $message = $_POST['message'] ?? '';

    if (!$message) {
        echo json_encode(['success' => false, 'error' => 'Message is required']);
        return;
    }

    try {
        
        $stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if (!$user) {
            echo json_encode(['success' => false, 'error' => 'User not found']);
            return;
        }

        
        $response = generateAIResponse($message);

        
        $stmt = $conn->prepare("
            INSERT INTO chats (prompt, response, user_id, timestamp)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssi", $message, $response, $user_id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'response' => $response]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save chat']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function generateAIResponse($message) {
    $message = strtolower($message);

    
    if (strpos($message, 'price') !== false || strpos($message, 'cost') !== false) {
        return "Our property prices range from ₹50 lakhs to ₹5 crores depending on location, size, and amenities. Would you like me to show you properties in a specific price range?";
    }

    if (strpos($message, 'location') !== false || strpos($message, 'area') !== false) {
        return "We have properties available in prime locations across Bangalore, including Whitefield, Electronic City, Koramangala, and Indiranagar. Which area interests you most?";
    }

    if (strpos($message, 'loan') !== false || strpos($message, 'finance') !== false) {
        return "We offer home loan assistance through our partner banks. You can get loans up to 90% of the property value with competitive interest rates starting from 6.5%. Would you like me to connect you with our loan advisors?";
    }

    if (strpos($message, 'visit') !== false || strpos($message, 'book') !== false) {
        return "You can easily book a property visit through your dashboard. Just browse our available properties and click 'Book Visit' to schedule a time that works for you.";
    }

    if (strpos($message, 'amenities') !== false || strpos($message, 'facilities') !== false) {
        return "Our properties come with modern amenities including 24/7 security, swimming pool, gym, children's play area, parking, and power backup. Specific amenities vary by property.";
    }

    
    if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
        return "Hello! I'm your AI assistant for SM Construction. How can I help you with your property needs today?";
    }

    if (strpos($message, 'thank') !== false) {
        return "You're welcome! Feel free to ask me anything else about our properties or services.";
    }

    
    return "Thank you for your question! For detailed information about our properties, pricing, or to schedule a visit, please use the options in your dashboard or contact our sales team directly.";
}
?>