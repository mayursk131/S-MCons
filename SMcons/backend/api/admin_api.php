<?php
session_start();
header('Content-Type: application/json');


require_once '../config/database.php';


if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}


$conn = getDatabaseConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$table = $_GET['table'] ?? $_POST['table'] ?? '';
$id = $_GET['id'] ?? $_POST['id'] ?? 0;


error_log("Admin action: $action on table: $table with ID: $id");

switch ($action) {
    case 'stats':
        echo getStats($conn);
        break;
        
    case 'tables':
        echo getTables($conn);
        break;
        
    case 'table_data':
        echo getTableData($conn, $table);
        break;
        
    case 'chats':
        echo getChats($conn);
        break;
        
    case 'feedback':
        echo getFeedback($conn);
        break;
        
    case 'inquiries':
        echo getInquiries($conn);
        break;
        
    case 'projects':
        echo getProjects($conn);
        break;
        
    case 'create':
        echo createRecord($conn, $table, $_POST);
        break;
        
    case 'update':
        echo updateRecord($conn, $table, $id, $_POST);
        break;
        
    case 'delete':
        echo deleteRecord($conn, $table, $id);
        break;
        
    case 'delete_chat':
        echo deleteChat($conn, $id);
        break;
        
    case 'delete_feedback':
        echo deleteFeedback($conn, $id);
        break;
        
    case 'clear_data':
        echo clearAllData($conn);
        break;
        
    case 'maintenance':
        echo toggleMaintenance($conn);
        break;
        
    case 'staff':
        echo getStaff($conn);
        break;
        
    case 'staff_performance':
        echo getStaffPerformance($conn);
        break;
        
    case 'incentives_report':
        echo getIncentivesReport($conn);
        break;
        
    case 'staff_details':
        echo getStaffDetails($conn, $id);
        break;
        
    case 'update_staff':
        echo updateStaff($conn, $id, $_POST);
        break;
        
    case 'delete_staff':
        echo deleteStaff($conn, $id);
        break;
}

function countTable($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    if (!$result || $result->num_rows === 0) {
        return 0;
    }

    $row = $conn->query("SELECT COUNT(*) as count FROM `$tableName`")->fetch_assoc();
    return $row['count'] ?? 0;
}

function getStats($conn) {
    $chats = countTable($conn, 'chats');
    $feedback = countTable($conn, 'feedback_submissions');
    $inquiries = countTable($conn, 'inquiries');
    $projects = countTable($conn, 'projects');
    $properties = countTable($conn, 'property_availability');
    
    return json_encode([
        'success' => true,
        'chats' => $chats,
        'feedback' => $feedback,
        'inquiries' => $inquiries,
        'projects' => $projects,
        'properties' => $properties,
        'status' => 'ONLINE'
    ]);
}

function getTables($conn) {
    $result = $conn->query("SHOW TABLES");
    $tables = [];
    
    while ($row = $result->fetch_row()) {
        $tables[] = $row[0];
    }
    
    return json_encode(['success' => true, 'tables' => $tables]);
}

function getTableData($conn, $table) {
    if (empty($table)) {
        return json_encode(['success' => false, 'error' => 'Table name required']);
    }
    
    
    $structure_result = $conn->query("DESCRIBE `$table`");
    $columns = [];
    $primary_key = 'id';
    
    while ($row = $structure_result->fetch_assoc()) {
        $columns[] = [
            'name' => $row['Field'],
            'type' => $row['Type'],
            'null' => $row['Null'] === 'YES',
            'key' => $row['Key']
        ];
        
        if ($row['Key'] === 'PRI') {
            $primary_key = $row['Field'];
        }
    }
    
    
    $data_result = $conn->query("SELECT * FROM `$table` ORDER BY `$primary_key` DESC LIMIT 100");
    $data = [];
    
    while ($row = $data_result->fetch_assoc()) {
        $data[] = $row;
    }
    
    return json_encode([
        'success' => true,
        'table' => $table,
        'columns' => $columns,
        'primary_key' => $primary_key,
        'data' => $data
    ]);
}

function getInquiries($conn) {
    $result = $conn->query("SELECT * FROM inquiries ORDER BY id DESC LIMIT 50");
    $inquiries = [];
    
    while ($row = $result->fetch_assoc()) {
        $inquiries[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'phone' => $row['phone'] ?? '',
            'email' => $row['email'],
            'message' => $row['message'],
            'timestamp' => $row['timestamp'] ?? date('Y-m-d H:i:s')
        ];
    }
    
    return json_encode(['success' => true, 'inquiries' => $inquiries]);
}

function getProjects($conn) {
    $result = $conn->query("SELECT * FROM projects ORDER BY id DESC LIMIT 50");
    $projects = [];
    
    while ($row = $result->fetch_assoc()) {
        $projects[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'] ?? '',
            'status' => $row['status'] ?? 'active',
            'created_at' => $row['created_at'] ?? date('Y-m-d H:i:s')
        ];
    }
    
    return json_encode(['success' => true, 'projects' => $projects]);
}

function createRecord($conn, $table, $data) {
    if (empty($table) || empty($data)) {
        return json_encode(['success' => false, 'error' => 'Table name and data required']);
    }
    
    
    unset($data['action'], $data['table']);
    
    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), '?');
    $types = str_repeat('s', count($columns));
    
    $sql = "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...array_values($data));
    
    if ($stmt->execute()) {
        return json_encode(['success' => true, 'id' => $conn->insert_id]);
    } else {
        return json_encode(['success' => false, 'error' => $stmt->error]);
    }
}

function updateRecord($conn, $table, $id, $data) {
    if (empty($table) || empty($id) || empty($data)) {
        return json_encode(['success' => false, 'error' => 'Table name, ID, and data required']);
    }
    
    
    unset($data['action'], $data['table'], $data['id']);
    
    $set_clauses = [];
    $values = [];
    $types = '';
    
    foreach ($data as $column => $value) {
        $set_clauses[] = "`$column` = ?";
        $values[] = $value;
        $types .= 's';
    }
    
    $values[] = $id;
    $types .= 'i';
    
    $sql = "UPDATE `$table` SET " . implode(', ', $set_clauses) . " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$values);
    
    if ($stmt->execute()) {
        return json_encode(['success' => true]);
    } else {
        return json_encode(['success' => false, 'error' => $stmt->error]);
    }
}

function deleteRecord($conn, $table, $id) {
    if (empty($table) || empty($id)) {
        return json_encode(['success' => false, 'error' => 'Table name and ID required']);
    }
    
    $stmt = $conn->prepare("DELETE FROM `$table` WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return json_encode(['success' => true]);
    } else {
        return json_encode(['success' => false, 'error' => 'Failed to delete']);
    }
}

function getFeedback($conn) {
    
    $result = $conn->query("SHOW TABLES LIKE 'feedback_submissions'");
    if ($result && $result->num_rows > 0) {
        $result = $conn->query("SELECT * FROM feedback_submissions ORDER BY id DESC LIMIT 50");
        $feedback = [];

        while ($row = $result->fetch_assoc()) {
            $feedback[] = [
                'id' => $row['id'],
                'name' => $row['user_name'] ?? 'Anonymous',
                'email' => $row['email'] ?? '',
                'message' => $row['suggestions'] ?? '',
                'rating' => $row['rating'] ?? 0,
                'timestamp' => $row['submitted_at'] ?? date('Y-m-d H:i:s')
            ];
        }

        return json_encode(['success' => true, 'feedback' => $feedback]);
    }

    
    $result = $conn->query("SELECT * FROM feedback ORDER BY id DESC LIMIT 50");
    $feedback = [];

    while ($row = $result->fetch_assoc()) {
        $feedback[] = [
            'id' => $row['id'],
            'name' => $row['name'] ?? 'Anonymous',
            'email' => $row['email'] ?? '',
            'message' => $row['message'] ?? '',
            'rating' => $row['rating'] ?? 0,
            'timestamp' => $row['timestamp'] ?? date('Y-m-d H:i:s')
        ];
    }

    return json_encode(['success' => true, 'feedback' => $feedback]);
}

function deleteChat($conn, $id) {
    $id = (int)$id;
    if ($id <= 0) {
        return json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
    
    $stmt = $conn->prepare("DELETE FROM chats WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return json_encode(['success' => true]);
    } else {
        return json_encode(['success' => false, 'error' => 'Failed to delete']);
    }
}

function deleteFeedback($conn, $id) {
    $id = (int)$id;
    if ($id <= 0) {
        return json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
    
    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return json_encode(['success' => true]);
    } else {
        return json_encode(['success' => false, 'error' => 'Failed to delete']);
    }
}

function clearAllData($conn) {
    try {
        $tables = ['chats', 'feedback_submissions', 'feedback', 'inquiries', 'projects', 'bookings', 'property_availability'];
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                $conn->query("DELETE FROM `$table`");
                $conn->query("ALTER TABLE `$table` AUTO_INCREMENT = 1");
            }
        }

        return json_encode(['success' => true]);
    } catch (Exception $e) {
        return json_encode(['success' => false, 'error' => 'Failed to clear data']);
    }
}

function toggleMaintenance($conn) {
    
    $maintenance_file = '../../maintenance.mode';
    
    if (file_exists($maintenance_file)) {
        unlink($maintenance_file);
        return json_encode(['success' => true, 'maintenance' => false]);
    } else {
        file_put_contents($maintenance_file, 'MAINTENANCE');
        return json_encode(['success' => true, 'maintenance' => true]);
    }
}

function createBackup($conn) {
    try {
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        $backup_file = '../../backups/' . $filename;
        
        
        if (!is_dir('../../backups')) {
            mkdir('../../backups', 0755, true);
        }
        
        
        $tables = [];
        $result = $conn->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }
        
        $sql = "";
        
        
        foreach ($tables as $table) {
            $sql .= "-- Table: $table\n";
            
            
            $result = $conn->query("SHOW CREATE TABLE $table");
            $row = $result->fetch_row();
            $sql .= $row[1] . ";\n\n";
            
            
            $result = $conn->query("SELECT * FROM $table");
            while ($row = $result->fetch_assoc()) {
                $sql .= "INSERT INTO $table VALUES (";
                $values = [];
                foreach ($row as $value) {
                    $values[] = is_null($value) ? 'NULL' : "'" . $conn->real_escape_string($value) . "'";
                }
                $sql .= implode(', ', $values) . ");\n";
            }
            $sql .= "\n\n";
        }
        
        
        if (file_put_contents($backup_file, $sql)) {
            return json_encode(['success' => true, 'filename' => $filename]);
        } else {
            return json_encode(['success' => false, 'error' => 'Failed to create backup file']);
        }
        
    } catch (Exception $e) {
        return json_encode(['success' => false, 'error' => 'Backup failed: ' . $e->getMessage()]);
    }
}

function getStaff($conn) {
    $result = $conn->query("SELECT * FROM staff ORDER BY id DESC");
    $staff = [];
    
    while ($row = $result->fetch_assoc()) {
        $staff[] = $row;
    }
    
    return json_encode(['success' => true, 'staff' => $staff]);
}

function getStaffPerformance($conn) {
    $result = $conn->query("SELECT * FROM staff WHERE status = 'active' ORDER BY current_month_clients DESC, clients_brought DESC");
    $performance = [];
    
    while ($row = $result->fetch_assoc()) {
        $performance[] = $row;
    }
    
    return json_encode(['success' => true, 'performance' => $performance]);
}

function getIncentivesReport($conn) {
    
    $summary_result = $conn->query("
        SELECT 
            SUM(total_incentive) as total_incentives,
            SUM(clients_brought) as total_clients,
            COUNT(CASE WHEN status = 'active' THEN 1 END) as active_staff,
            AVG(CASE WHEN clients_brought > 0 THEN total_incentive / clients_brought END) as avg_incentive_per_client
        FROM staff
    ");
    
    $summary = $summary_result->fetch_assoc();
    
    
    $staff_result = $conn->query("
        SELECT staff_name, designation, clients_brought, total_incentive, status 
        FROM staff 
        ORDER BY total_incentive DESC
    ");
    
    $staff_details = [];
    while ($row = $staff_result->fetch_assoc()) {
        $staff_details[] = $row;
    }
    
    return json_encode([
        'success' => true,
        'incentives' => [
            'total_incentives' => $summary['total_incentives'] ?? 0,
            'total_clients' => $summary['total_clients'] ?? 0,
            'active_staff' => $summary['active_staff'] ?? 0,
            'avg_incentive_per_client' => $summary['avg_incentive_per_client'] ?? 0,
            'staff_details' => $staff_details
        ]
    ]);
}

function getStaffDetails($conn, $id) {
    $id = (int)$id;
    if ($id <= 0) {
        return json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
    
    $stmt = $conn->prepare("SELECT * FROM staff WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        return json_encode(['success' => true, 'staff' => $staff]);
    } else {
        return json_encode(['success' => false, 'error' => 'Staff member not found']);
    }
}

function updateStaff($conn, $id, $data) {
    $id = (int)$id;
    if ($id <= 0) {
        return json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
    
    $allowed_fields = ['staff_name', 'phone', 'email', 'designation', 'clients_brought', 'total_incentive', 'monthly_target', 'current_month_clients', 'status', 'joined_date'];
    $updates = [];
    $types = '';
    $values = [];
    
    foreach ($allowed_fields as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $types .= 's';
            $values[] = $data[$field];
        }
    }
    
    if (empty($updates)) {
        return json_encode(['success' => false, 'error' => 'No fields to update']);
    }
    
    $types .= 'i';
    $values[] = $id;
    
    $sql = "UPDATE staff SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$values);
    
    if ($stmt->execute()) {
        return json_encode(['success' => true]);
    } else {
        return json_encode(['success' => false, 'error' => 'Failed to update staff member']);
    }
}

function deleteStaff($conn, $id) {
    $id = (int)$id;
    if ($id <= 0) {
        return json_encode(['success' => false, 'error' => 'Invalid ID']);
    }
    
    $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        return json_encode(['success' => true]);
    } else {
        return json_encode(['success' => false, 'error' => 'Failed to delete staff member']);
    }
}

$conn->close();
?>
