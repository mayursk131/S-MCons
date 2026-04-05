<?php



$db_host = 'localhost';
$db_user = 'mayur'; 
$db_pass = '123';
$db_name = 'contact_db';


$conn = new mysqli($db_host, $db_user, $db_pass);


if ($conn->connect_error) {
    die("<div style='color:red;'>Connection failed: " . $conn->connect_error . "</div>");
}

echo "<h3>Setting up your system...</h3>";


$sql_db = "CREATE DATABASE IF NOT EXISTS " . $db_name;
if ($conn->query($sql_db) === TRUE) {
    echo "<p style='color:green;'>✔ Database '" . $db_name . "' ready.</p>";
} else {
    die("<p style='color:red;'>✘ Error creating database: " . $conn->error . "</p>");
}


require_once 'backend/config/database.php';
$conn->select_db($db_name);



$sql_inquiry = "CREATE TABLE IF NOT EXISTS inquiries (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_inquiry) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'inquiries' ready.</p>";
}


$sampleInquiries = [
    [
        'name' => 'Rahul Sharma',
        'phone' => '+91-9876543210',
        'email' => 'rahul.sharma@email.com',
        'message' => 'I am interested in 3 BHK apartments in Paris City. Please provide more details about availability and pricing.'
    ],
    [
        'name' => 'Rahul Sharma',
        'phone' => '+91-9876543210',
        'email' => 'rahul.sharma@email.com',
        'message' => 'Do you have any upcoming drive events for property visit at Corporate Plaza? I would like to attend.'
    ],
    [
        'name' => 'Priya Patel',
        'phone' => '+91-9876543211',
        'email' => 'priya.patel@email.com',
        'message' => 'Looking for investment opportunities in commercial properties. Can you share the current market rates?'
    ],
    [
        'name' => 'Amit Kumar',
        'phone' => '+91-9876543212',
        'email' => 'amit.kumar@email.com',
        'message' => 'Interested in Infinity Heights project. Please send me the floor plans and amenities details.'
    ],
    [
        'name' => 'Kavita Singh',
        'phone' => '+91-9876543213',
        'email' => 'kavita.singh@email.com',
        'message' => 'I need information about loan options available for property purchase in Corporate Plaza.'
    ],
    [
        'name' => 'Vikram Joshi',
        'phone' => '+91-9876543214',
        'email' => 'vikram.joshi@email.com',
        'message' => 'Planning to buy a villa. What are the current offers and discounts available?'
    ],
    [
        'name' => 'Meera Gupta',
        'phone' => '+91-9876543215',
        'email' => 'meera.gupta@email.com',
        'message' => 'Interested in 2 BHK apartments for my parents. Please provide senior citizen discounts if any.'
    ],
    [
        'name' => 'Suresh Verma',
        'phone' => '+91-9876543216',
        'email' => 'suresh.verma@email.com',
        'message' => 'Looking for retirement home options. Need peaceful location with good amenities.'
    ],
    [
        'name' => 'Anjali Mehta',
        'phone' => '+91-9876543217',
        'email' => 'anjali.mehta@email.com',
        'message' => 'Corporate client interested in bulk purchase. Please arrange a meeting with management.'
    ],
    [
        'name' => 'Rohit Shah',
        'phone' => '+91-9876543218',
        'email' => 'rohit.shah@email.com',
        'message' => 'Startup founder looking for office space. Need modern facilities and good connectivity.'
    ],
    [
        'name' => 'Pallavi Nair',
        'phone' => '+91-9876543219',
        'email' => 'pallavi.nair@email.com',
        'message' => 'Young professional seeking 1 BHK apartment near IT hub. Budget constraints apply.'
    ],
    [
        'name' => 'Rajesh Kumar',
        'phone' => '+91-9876543220',
        'email' => 'rajesh.kumar@email.com',
        'message' => 'NRI investor interested in property purchase. Need guidance on legal procedures and taxes.'
    ],
    [
        'name' => 'Sneha Reddy',
        'phone' => '+91-9876543221',
        'email' => 'sneha.reddy@email.com',
        'message' => 'First-time home buyer. Please explain the complete process and documentation required.'
    ]
];

foreach ($sampleInquiries as $inquiry) {
    $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, message) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE name = VALUES(name)");
    $stmt->bind_param('ssss', $inquiry['name'], $inquiry['phone'], $inquiry['email'], $inquiry['message']);
    $stmt->execute();
    $stmt->close();
}


$sql_feedback = "CREATE TABLE IF NOT EXISTS feedback_submissions (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) DEFAULT 'Anonymous',
    rating INT(1) NOT NULL,
    subject VARCHAR(50) NOT NULL,
    suggestions TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_feedback) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'feedback_submissions' ready.</p>";
}


$sampleFeedback = [
    [
        'user_name' => 'Rahul Sharma',
        'rating' => 5,
        'subject' => 'Excellent Service',
        'suggestions' => 'The property tour was very informative. Staff was knowledgeable and helpful.'
    ],
    [
        'user_name' => 'Rahul Sharma',
        'rating' => 5,
        'subject' => 'A+ Experience',
        'suggestions' => 'Booking and site visit were seamless. Great communication and timely follow-up.'
    ],
    [
        'user_name' => 'Priya Patel',
        'rating' => 4,
        'subject' => 'Good Experience',
        'suggestions' => 'Overall good experience. Would like more information about green building features.'
    ],
    [
        'user_name' => 'Amit Kumar',
        'rating' => 5,
        'subject' => 'Outstanding Support',
        'suggestions' => 'Excellent customer support. Very responsive and professional team.'
    ],
    [
        'user_name' => 'Kavita Singh',
        'rating' => 3,
        'subject' => 'Average Experience',
        'suggestions' => 'The property is good but parking facility needs improvement.'
    ],
    [
        'user_name' => 'Vikram Joshi',
        'rating' => 5,
        'subject' => 'Highly Satisfied',
        'suggestions' => 'Great location and amenities. Worth the investment.'
    ],
    [
        'user_name' => 'Meera Gupta',
        'rating' => 4,
        'subject' => 'Good Value',
        'suggestions' => 'Good value for money. Minor issues with common area maintenance.'
    ],
    [
        'user_name' => 'Suresh Verma',
        'rating' => 5,
        'subject' => 'Perfect Choice',
        'suggestions' => 'Ideal for senior citizens. Peaceful environment and good facilities.'
    ],
    [
        'user_name' => 'Anjali Mehta',
        'rating' => 4,
        'subject' => 'Professional Service',
        'suggestions' => 'Professional approach. Would recommend to others.'
    ],
    [
        'user_name' => 'Rohit Shah',
        'rating' => 3,
        'subject' => 'Needs Improvement',
        'suggestions' => 'Internet connectivity in sample flats needs to be better.'
    ],
    [
        'user_name' => 'Pallavi Nair',
        'rating' => 5,
        'subject' => 'Amazing Experience',
        'suggestions' => 'Loved the modern design and community feel. Perfect for young professionals.'
    ],
    [
        'user_name' => 'Rajesh Kumar',
        'rating' => 4,
        'subject' => 'Satisfied Customer',
        'suggestions' => 'Good investment option. Market is growing in this area.'
    ],
    [
        'user_name' => 'Sneha Reddy',
        'rating' => 5,
        'subject' => 'Exceptional Quality',
        'suggestions' => 'Construction quality is excellent. Very happy with the purchase.'
    ]
];

foreach ($sampleFeedback as $feedback) {
    $stmt = $conn->prepare("INSERT INTO feedback_submissions (user_name, rating, subject, suggestions) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating = VALUES(rating)");
    $stmt->bind_param('siss', $feedback['user_name'], $feedback['rating'], $feedback['subject'], $feedback['suggestions']);
    $stmt->execute();
    $stmt->close();
}



$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    user_type ENUM('buyer','investor','agent','other') NOT NULL DEFAULT 'buyer',
    status ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
    profile_image VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    pincode VARCHAR(10),
    date_of_birth DATE,
    gender ENUM('male','female','other'),
    occupation VARCHAR(100),
    income_range VARCHAR(50),
    preferred_property_type VARCHAR(100),
    budget_range VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    phone_verified BOOLEAN DEFAULT FALSE
)";

if ($conn->query($sql_users) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'users' ready.</p>";
}


$sampleUsers = [
    [
        'username' => 'rahul_sharma',
        'email' => 'rahul.sharma@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Rahul Sharma',
        'phone' => '+91-9876543210',
        'user_type' => 'buyer',
        'status' => 'active',
        'address' => '123 MG Road, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560001',
        'date_of_birth' => '1985-03-15',
        'gender' => 'male',
        'occupation' => 'Software Engineer',
        'income_range' => '10-15 LPA',
        'preferred_property_type' => '3 BHK Apartment',
        'budget_range' => '80-100 Lakhs',
        'email_verified' => true,
        'phone_verified' => true
    ],
    [
        'username' => 'priya_patel',
        'email' => 'priya.patel@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Priya Patel',
        'phone' => '+91-9876543211',
        'user_type' => 'buyer',
        'status' => 'active',
        'address' => '456 Brigade Road, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560025',
        'date_of_birth' => '1990-07-22',
        'gender' => 'female',
        'occupation' => 'Doctor',
        'income_range' => '15-20 LPA',
        'preferred_property_type' => '4 BHK Villa',
        'budget_range' => '1.5-2 Crores',
        'email_verified' => true,
        'phone_verified' => true
    ],
    [
        'username' => 'amit_kumar',
        'email' => 'amit.kumar@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Amit Kumar',
        'phone' => '+91-9876543212',
        'user_type' => 'investor',
        'status' => 'active',
        'address' => '789 Residency Road, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560025',
        'date_of_birth' => '1982-11-08',
        'gender' => 'male',
        'occupation' => 'Business Owner',
        'income_range' => 'Above 50 LPA',
        'preferred_property_type' => 'Commercial Space',
        'budget_range' => '5-10 Crores',
        'email_verified' => true,
        'phone_verified' => false
    ],
    [
        'username' => 'kavita_singh',
        'email' => 'kavita.singh@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Kavita Singh',
        'phone' => '+91-9876543213',
        'user_type' => 'buyer',
        'status' => 'active',
        'address' => '321 Jayanagar, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560011',
        'date_of_birth' => '1988-05-30',
        'gender' => 'female',
        'occupation' => 'Teacher',
        'income_range' => '5-10 LPA',
        'preferred_property_type' => '2 BHK Apartment',
        'budget_range' => '40-60 Lakhs',
        'email_verified' => true,
        'phone_verified' => true
    ],
    [
        'username' => 'vikram_joshi',
        'email' => 'vikram.joshi@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Vikram Joshi',
        'phone' => '+91-9876543214',
        'user_type' => 'agent',
        'status' => 'active',
        'address' => '654 Koramangala, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560034',
        'date_of_birth' => '1980-12-10',
        'gender' => 'male',
        'occupation' => 'Real Estate Agent',
        'income_range' => '10-15 LPA',
        'preferred_property_type' => 'All Types',
        'budget_range' => 'Variable',
        'email_verified' => true,
        'phone_verified' => true
    ],
    [
        'username' => 'meera_gupta',
        'email' => 'meera.gupta@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Meera Gupta',
        'phone' => '+91-9876543215',
        'user_type' => 'buyer',
        'status' => 'active',
        'address' => '987 Indiranagar, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560038',
        'date_of_birth' => '1992-09-14',
        'gender' => 'female',
        'occupation' => 'Marketing Manager',
        'income_range' => '8-12 LPA',
        'preferred_property_type' => '3 BHK Apartment',
        'budget_range' => '70-90 Lakhs',
        'email_verified' => false,
        'phone_verified' => true
    ],
    [
        'username' => 'suresh_verma',
        'email' => 'suresh.verma@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Suresh Verma',
        'phone' => '+91-9876543216',
        
        'user_type' => 'buyer',
        'status' => 'active',
        'address' => '147 Whitefield, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560066',
        'date_of_birth' => '1978-01-25',
        'gender' => 'male',
        'occupation' => 'Retired',
        'income_range' => 'Pension',
        'preferred_property_type' => '2 BHK Apartment',
        'budget_range' => '30-50 Lakhs',
        'email_verified' => true,
        'phone_verified' => true
    ],
    [
        'username' => 'anjali_mehta',
        'email' => 'anjali.mehta@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Anjali Mehta',
        'phone' => '+91-9876543217',
        'user_type' => 'investor',
        'status' => 'active',
        'address' => '258 HSR Layout, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560102',
        'date_of_birth' => '1986-06-18',
        'gender' => 'female',
        'occupation' => 'Chartered Accountant',
        'income_range' => '20-30 LPA',
        'preferred_property_type' => 'Investment Properties',
        'budget_range' => '2-5 Crores',
        'email_verified' => true,
        'phone_verified' => false
    ],
    [
        'username' => 'rohit_shah',
        'email' => 'rohit.shah@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Rohit Shah',
        'phone' => '+91-9876543218',
        'user_type' => 'buyer',
        'status' => 'inactive',
        'address' => '369 Electronic City, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560100',
        'date_of_birth' => '1995-04-12',
        'gender' => 'male',
        'occupation' => 'Startup Founder',
        'income_range' => 'Variable',
        'preferred_property_type' => 'Office Space',
        'budget_range' => '1-2 Crores',
        'email_verified' => false,
        'phone_verified' => false
    ],
    [
        'username' => 'pallavi_nair',
        'email' => 'pallavi.nair@email.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'full_name' => 'Pallavi Nair',
        'phone' => '+91-9876543219',
        'user_type' => 'buyer',
        'status' => 'active',
        'address' => '741 Sarjapur Road, Bangalore',
        'city' => 'Bangalore',
        'state' => 'Karnataka',
        'pincode' => '560035',
        'date_of_birth' => '1993-08-27',
        'gender' => 'female',
        'occupation' => 'Designer',
        'income_range' => '6-10 LPA',
        'preferred_property_type' => 'Studio Apartment',
        'budget_range' => '25-40 Lakhs',
        'email_verified' => true,
        'phone_verified' => true
    ]
];

foreach ($sampleUsers as $user) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, user_type, status, address, city, state, pincode, date_of_birth, gender, occupation, income_range, preferred_property_type, budget_range, email_verified, phone_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE full_name = VALUES(full_name), phone = VALUES(phone), status = VALUES(status)");
    $stmt->bind_param('sssssssssssssssssii', $user['username'], $user['email'], $user['password'], $user['full_name'], $user['phone'], $user['user_type'], $user['status'], $user['address'], $user['city'], $user['state'], $user['pincode'], $user['date_of_birth'], $user['gender'], $user['occupation'], $user['income_range'], $user['preferred_property_type'], $user['budget_range'], $user['email_verified'], $user['phone_verified']);
    $stmt->execute();
    $stmt->close();
}
$sql_property_availability = "CREATE TABLE IF NOT EXISTS property_availability (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    property_name VARCHAR(255) NOT NULL UNIQUE,
    total_units INT NOT NULL DEFAULT 0,
    sold_units INT NOT NULL DEFAULT 0,
    available_units INT NOT NULL DEFAULT 0,
    bhk_3_sold INT NOT NULL DEFAULT 0,
    bhk_3_total INT NOT NULL DEFAULT 0,
    bhk_4_sold INT NOT NULL DEFAULT 0,
    bhk_4_total INT NOT NULL DEFAULT 0,
    bhk_45_sold INT NOT NULL DEFAULT 0,
    bhk_45_total INT NOT NULL DEFAULT 0,
    floor_1_status ENUM('available','sold','reserved') NOT NULL DEFAULT 'available',
    floor_2_status ENUM('available','sold','reserved') NOT NULL DEFAULT 'available',
    floor_3_status ENUM('available','sold','reserved') NOT NULL DEFAULT 'available',
    floor_4_status ENUM('available','sold','reserved') NOT NULL DEFAULT 'available',
    floor_5_status ENUM('available','sold','reserved') NOT NULL DEFAULT 'available',
    floor_6_status ENUM('available','sold','reserved') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_property_availability) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'property_availability' ready.</p>";
}


$sampleProperties = [
    [
        'property_name' => 'Paris City',
        'total_units' => 150,
        'sold_units' => 120,
        'available_units' => 30,
        'bhk_3_sold' => 45,
        'bhk_3_total' => 50,
        'bhk_4_sold' => 60,
        'bhk_4_total' => 70,
        'bhk_45_sold' => 15,
        'bhk_45_total' => 30,
        'floor_1_status' => 'available',
        'floor_2_status' => 'available',
        'floor_3_status' => 'available',
        'floor_4_status' => 'available',
        'floor_5_status' => 'reserved',
        'floor_6_status' => 'sold'
    ],
    [
        'property_name' => 'Corporate Plaza',
        'total_units' => 100,
        'sold_units' => 85,
        'available_units' => 15,
        'bhk_3_sold' => 35,
        'bhk_3_total' => 40,
        'bhk_4_sold' => 40,
        'bhk_4_total' => 45,
        'bhk_45_sold' => 10,
        'bhk_45_total' => 15,
        'floor_1_status' => 'available',
        'floor_2_status' => 'sold',
        'floor_3_status' => 'available',
        'floor_4_status' => 'reserved',
        'floor_5_status' => 'available',
        'floor_6_status' => 'available'
    ],
    [
        'property_name' => 'Infinity Heights',
        'total_units' => 200,
        'sold_units' => 160,
        'available_units' => 40,
        'bhk_3_sold' => 70,
        'bhk_3_total' => 80,
        'bhk_4_sold' => 75,
        'bhk_4_total' => 90,
        'bhk_45_sold' => 15,
        'bhk_45_total' => 30,
        'floor_1_status' => 'sold',
        'floor_2_status' => 'available',
        'floor_3_status' => 'available',
        'floor_4_status' => 'available',
        'floor_5_status' => 'sold',
        'floor_6_status' => 'available'
    ],
    [
        'property_name' => 'Green Valley Residency',
        'total_units' => 120,
        'sold_units' => 95,
        'available_units' => 25,
        'bhk_3_sold' => 40,
        'bhk_3_total' => 45,
        'bhk_4_sold' => 45,
        'bhk_4_total' => 50,
        'bhk_45_sold' => 10,
        'bhk_45_total' => 25,
        'floor_1_status' => 'available',
        'floor_2_status' => 'available',
        'floor_3_status' => 'sold',
        'floor_4_status' => 'available',
        'floor_5_status' => 'reserved',
        'floor_6_status' => 'sold'
    ],
    [
        'property_name' => 'Royal Gardens',
        'total_units' => 180,
        'sold_units' => 140,
        'available_units' => 40,
        'bhk_3_sold' => 55,
        'bhk_3_total' => 65,
        'bhk_4_sold' => 70,
        'bhk_4_total' => 80,
        'bhk_45_sold' => 15,
        'bhk_45_total' => 35,
        'floor_1_status' => 'sold',
        'floor_2_status' => 'sold',
        'floor_3_status' => 'available',
        'floor_4_status' => 'available',
        'floor_5_status' => 'available',
        'floor_6_status' => 'reserved'
    ],
    [
        'property_name' => 'Metro View Towers',
        'total_units' => 250,
        'sold_units' => 200,
        'available_units' => 50,
        'bhk_3_sold' => 85,
        'bhk_3_total' => 100,
        'bhk_4_sold' => 95,
        'bhk_4_total' => 110,
        'bhk_45_sold' => 20,
        'bhk_45_total' => 40,
        'floor_1_status' => 'available',
        'floor_2_status' => 'available',
        'floor_3_status' => 'available',
        'floor_4_status' => 'sold',
        'floor_5_status' => 'sold',
        'floor_6_status' => 'available'
    ],
    [
        'property_name' => 'Lake View Apartments',
        'total_units' => 90,
        'sold_units' => 70,
        'available_units' => 20,
        'bhk_3_sold' => 30,
        'bhk_3_total' => 35,
        'bhk_4_sold' => 35,
        'bhk_4_total' => 40,
        'bhk_45_sold' => 5,
        'bhk_45_total' => 15,
        'floor_1_status' => 'sold',
        'floor_2_status' => 'available',
        'floor_3_status' => 'available',
        'floor_4_status' => 'reserved',
        'floor_5_status' => 'available',
        'floor_6_status' => 'sold'
    ],
    [
        'property_name' => 'Sunrise Enclave',
        'total_units' => 160,
        'sold_units' => 130,
        'available_units' => 30,
        'bhk_3_sold' => 50,
        'bhk_3_total' => 60,
        'bhk_4_sold' => 65,
        'bhk_4_total' => 75,
        'bhk_45_sold' => 15,
        'bhk_45_total' => 25,
        'floor_1_status' => 'available',
        'floor_2_status' => 'available',
        'floor_3_status' => 'sold',
        'floor_4_status' => 'available',
        'floor_5_status' => 'reserved',
        'floor_6_status' => 'sold'
    ],
    [
        'property_name' => 'Palm Grove Villas',
        'total_units' => 80,
        'sold_units' => 60,
        'available_units' => 20,
        'bhk_3_sold' => 25,
        'bhk_3_total' => 30,
        'bhk_4_sold' => 30,
        'bhk_4_total' => 35,
        'bhk_45_sold' => 5,
        'bhk_45_total' => 15,
        'floor_1_status' => 'sold',
        'floor_2_status' => 'available',
        'floor_3_status' => 'available',
        'floor_4_status' => 'reserved',
        'floor_5_status' => 'available',
        'floor_6_status' => 'sold'
    ],
    [
        'property_name' => 'Crystal Heights',
        'total_units' => 220,
        'sold_units' => 180,
        'available_units' => 40,
        'bhk_3_sold' => 75,
        'bhk_3_total' => 85,
        'bhk_4_sold' => 85,
        'bhk_4_total' => 100,
        'bhk_45_sold' => 20,
        'bhk_45_total' => 35,
        'floor_1_status' => 'available',
        'floor_2_status' => 'sold',
        'floor_3_status' => 'available',
        'floor_4_status' => 'available',
        'floor_5_status' => 'sold',
        'floor_6_status' => 'reserved'
    ]
];

foreach ($sampleProperties as $p) {
    $stmt = $conn->prepare("INSERT INTO property_availability (property_name, total_units, sold_units, available_units, bhk_3_sold, bhk_3_total, bhk_4_sold, bhk_4_total, bhk_45_sold, bhk_45_total, floor_1_status, floor_2_status, floor_3_status, floor_4_status, floor_5_status, floor_6_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE total_units = VALUES(total_units), sold_units = VALUES(sold_units), available_units = VALUES(available_units), bhk_3_sold = VALUES(bhk_3_sold), bhk_3_total = VALUES(bhk_3_total), bhk_4_sold = VALUES(bhk_4_sold), bhk_4_total = VALUES(bhk_4_total), bhk_45_sold = VALUES(bhk_45_sold), bhk_45_total = VALUES(bhk_45_total), floor_1_status = VALUES(floor_1_status), floor_2_status = VALUES(floor_2_status), floor_3_status = VALUES(floor_3_status), floor_4_status = VALUES(floor_4_status), floor_5_status = VALUES(floor_5_status), floor_6_status = VALUES(floor_6_status)");
    $stmt->bind_param('siiiiiiiiissssss', $p['property_name'], $p['total_units'], $p['sold_units'], $p['available_units'], $p['bhk_3_sold'], $p['bhk_3_total'], $p['bhk_4_sold'], $p['bhk_4_total'], $p['bhk_45_sold'], $p['bhk_45_total'], $p['floor_1_status'], $p['floor_2_status'], $p['floor_3_status'], $p['floor_4_status'], $p['floor_5_status'], $p['floor_6_status']);
    $stmt->execute();
    $stmt->close();
}


$sql_bookings = "CREATE TABLE IF NOT EXISTS bookings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    property_id INT(11) UNSIGNED,
    property_name VARCHAR(255),
    user_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    visit_date DATE,
    visit_time TIME,
    message TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES property_availability(id) ON DELETE SET NULL
)";


$sql_alter_bookings = "ALTER TABLE bookings 
    ADD COLUMN IF NOT EXISTS user_id INT(11) UNSIGNED,
    ADD COLUMN IF NOT EXISTS property_id INT(11) UNSIGNED,
    ADD COLUMN IF NOT EXISTS visit_date DATE,
    ADD COLUMN IF NOT EXISTS visit_time TIME,
    ADD COLUMN IF NOT EXISTS status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ADD CONSTRAINT fk_bookings_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_bookings_property_id FOREIGN KEY (property_id) REFERENCES property_availability(id) ON DELETE SET NULL";

if ($conn->query($sql_bookings) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'bookings' ready.</p>";
    
    
    $columns_to_add = [
        "user_id INT(11) UNSIGNED",
        "property_id INT(11) UNSIGNED", 
        "visit_date DATE",
        "visit_time TIME",
        "status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending'",
        "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
    ];
    
    foreach ($columns_to_add as $column) {
        $column_name = explode(' ', $column)[0];
        $result = $conn->query("SHOW COLUMNS FROM bookings LIKE '$column_name'");
        if ($result->num_rows == 0) {
            $conn->query("ALTER TABLE bookings ADD COLUMN $column");
        }
    }
    
    
    try { $conn->query("ALTER TABLE bookings ADD CONSTRAINT fk_bookings_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE"); } catch(Exception $e) {}
    try { $conn->query("ALTER TABLE bookings ADD CONSTRAINT fk_bookings_property_id FOREIGN KEY (property_id) REFERENCES property_availability(id) ON DELETE SET NULL"); } catch(Exception $e) {}
} else {
    echo "<p style='color:red;'>✘ Error creating bookings table: " . $conn->error . "</p>";
}


$sampleBookings = [
    [
        'user_id' => 1,
        'property_id' => 1,
        'property_name' => 'Paris City',
        'user_name' => 'Rahul Sharma',
        'email' => 'rahul.sharma@email.com',
        'phone' => '+91-9876543210',
        'visit_date' => '2024-02-15',
        'visit_time' => '10:00:00',
        'message' => 'Interested in 3 BHK apartment on 5th floor. Please confirm availability.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 1,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Rahul Sharma',
        'email' => 'rahul.sharma@email.com',
        'phone' => '+91-9876543210',
        'visit_date' => '2024-02-20',
        'visit_time' => '15:00:00',
        'message' => 'Looking for a corporate office site visit for my startup team.',
        'status' => 'pending'
    ],
    [
        'user_id' => 2,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Priya Patel',
        'email' => 'priya.patel@email.com',
        'phone' => '+91-9876543211',
        'visit_date' => '2024-02-16',
        'visit_time' => '14:00:00',
        'message' => 'Need office space of 2000 sq ft. Budget around 2 crores.',
        'status' => 'pending'
    ],
    [
        'user_id' => 3,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Amit Kumar',
        'email' => 'amit.kumar@email.com',
        'phone' => '+91-9876543212',
        'visit_date' => '2024-02-17',
        'visit_time' => '11:00:00',
        'message' => 'Looking for 4 BHK villa with garden. Investment purpose.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 4,
        'property_name' => 'Paris City',
        'user_name' => 'Kavita Singh',
        'email' => 'kavita.singh@email.com',
        'phone' => '+91-9876543213',
        'visit_date' => '2024-02-18',
        'visit_time' => '15:00:00',
        'message' => '2 BHK apartment for my parents. Ground floor preferred.',
        'status' => 'pending'
    ],
    [
        'user_id' => 5,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Vikram Joshi',
        'email' => 'vikram.joshi@email.com',
        'phone' => '+91-9876543214',
        'visit_date' => '2024-02-19',
        'visit_time' => '12:00:00',
        'message' => 'Show house available for site visit this weekend.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 6,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Meera Gupta',
        'email' => 'meera.gupta@email.com',
        'phone' => '+91-9876543215',
        'visit_date' => '2024-02-20',
        'visit_time' => '16:00:00',
        'message' => 'Retail space required for restaurant business.',
        'status' => 'pending'
    ],
    [
        'user_id' => 7,
        'property_id' => 1,
        'property_name' => 'Paris City',
        'user_name' => 'Suresh Verma',
        'email' => 'suresh.verma@email.com',
        'phone' => '+91-9876543216',
        'visit_date' => '2024-02-21',
        'visit_time' => '10:30:00',
        'message' => '1 BHK for retirement. Need ramp access for wheelchair.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 8,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Anjali Mehta',
        'email' => 'anjali.mehta@email.com',
        'phone' => '+91-9876543217',
        'visit_date' => '2024-02-22',
        'visit_time' => '13:00:00',
        'message' => 'Bulk booking for 10 apartments. Corporate rates requested.',
        'status' => 'pending'
    ],
    [
        'user_id' => 9,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Rohit Shah',
        'email' => 'rohit.shah@email.com',
        'phone' => '+91-9876543218',
        'visit_date' => '2024-02-23',
        'visit_time' => '11:30:00',
        'message' => 'Co-working space for startup team of 20 people.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 10,
        'property_id' => 1,
        'property_name' => 'Paris City',
        'user_name' => 'Pallavi Nair',
        'email' => 'pallavi.nair@email.com',
        'phone' => '+91-9876543219',
        'visit_date' => '2024-02-24',
        'visit_time' => '14:30:00',
        'message' => 'Studio apartment for single professional. Budget friendly option.',
        'status' => 'pending'
    ],
    [
        'user_id' => 11,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Rajesh Kumar',
        'email' => 'rajesh.kumar@email.com',
        'phone' => '+91-9876543220',
        'visit_date' => '2024-02-25',
        'visit_time' => '15:30:00',
        'message' => 'NRI purchase. Need guidance on payment options and documentation.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 12,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Sneha Reddy',
        'email' => 'sneha.reddy@email.com',
        'phone' => '+91-9876543221',
        'visit_date' => '2024-02-26',
        'visit_time' => '16:30:00',
        'message' => 'First home purchase. Need complete guidance and loan assistance.',
        'status' => 'pending'
    ]
];


$userIdByEmail = [];
$propertyIdByName = [];
$res = $conn->query("SELECT id, email FROM users");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $userIdByEmail[$row['email']] = (int)$row['id'];
    }
}
$res = $conn->query("SELECT id, property_name FROM property_availability");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $propertyIdByName[$row['property_name']] = (int)$row['id'];
    }
}

foreach ($sampleBookings as &$booking) {
    
    $booking['user_id'] = $userIdByEmail[$booking['email']] ?? 0;
    $booking['property_id'] = $propertyIdByName[$booking['property_name']] ?? 0;
}
unset($booking);

foreach ($sampleBookings as $booking) {
    
    if (empty($booking['user_id']) || empty($booking['property_id'])) {
        continue;
    }

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, property_id, property_name, user_name, email, phone, visit_date, visit_time, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE user_name = VALUES(user_name)");
    $stmt->bind_param('iissssssss', $booking['user_id'], $booking['property_id'], $booking['property_name'], $booking['user_name'], $booking['email'], $booking['phone'], $booking['visit_date'], $booking['visit_time'], $booking['message'], $booking['status']);
    $stmt->execute();
    $stmt->close();
}


$sql_bookings = "CREATE TABLE IF NOT EXISTS bookings (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    property_id INT(11) UNSIGNED,
    property_name VARCHAR(255),
    user_name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    visit_date DATE,
    visit_time TIME,
    message TEXT,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (property_id) REFERENCES property_availability(id) ON DELETE SET NULL
)";


$sql_alter_bookings = "ALTER TABLE bookings 
    ADD COLUMN IF NOT EXISTS user_id INT(11) UNSIGNED,
    ADD COLUMN IF NOT EXISTS property_id INT(11) UNSIGNED,
    ADD COLUMN IF NOT EXISTS visit_date DATE,
    ADD COLUMN IF NOT EXISTS visit_time TIME,
    ADD COLUMN IF NOT EXISTS status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ADD CONSTRAINT fk_bookings_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_bookings_property_id FOREIGN KEY (property_id) REFERENCES property_availability(id) ON DELETE SET NULL";

if ($conn->query($sql_bookings) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'bookings' ready.</p>";
    
    
    $columns_to_add = [
        "user_id INT(11) UNSIGNED",
        "property_id INT(11) UNSIGNED", 
        "visit_date DATE",
        "visit_time TIME",
        "status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending'",
        "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
    ];
    
    foreach ($columns_to_add as $column) {
        $column_name = explode(' ', $column)[0];
        $result = $conn->query("SHOW COLUMNS FROM bookings LIKE '$column_name'");
        if ($result->num_rows == 0) {
            $conn->query("ALTER TABLE bookings ADD COLUMN $column");
        }
    }
    
    
    try { $conn->query("ALTER TABLE bookings ADD CONSTRAINT fk_bookings_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE"); } catch(Exception $e) {}
    try { $conn->query("ALTER TABLE bookings ADD CONSTRAINT fk_bookings_property_id FOREIGN KEY (property_id) REFERENCES property_availability(id) ON DELETE SET NULL"); } catch(Exception $e) {}
} else {
    echo "<p style='color:red;'>✘ Error creating bookings table: " . $conn->error . "</p>";
}


$sampleBookings = [
    [
        'user_id' => 1,
        'property_id' => 1,
        'property_name' => 'Paris City',
        'user_name' => 'Rahul Sharma',
        'email' => 'rahul.sharma@email.com',
        'phone' => '+91-9876543210',
        'visit_date' => '2024-02-15',
        'visit_time' => '10:00:00',
        'message' => 'Interested in 3 BHK apartment on 5th floor. Please confirm availability.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 1,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Rahul Sharma',
        'email' => 'rahul.sharma@email.com',
        'phone' => '+91-9876543210',
        'visit_date' => '2024-02-20',
        'visit_time' => '15:00:00',
        'message' => 'Looking for a corporate office site visit for my startup team.',
        'status' => 'pending'
    ],
    [
        'user_id' => 2,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Priya Patel',
        'email' => 'priya.patel@email.com',
        'phone' => '+91-9876543211',
        'visit_date' => '2024-02-16',
        'visit_time' => '14:00:00',
        'message' => 'Need office space of 2000 sq ft. Budget around 2 crores.',
        'status' => 'pending'
    ],
    [
        'user_id' => 3,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Amit Kumar',
        'email' => 'amit.kumar@email.com',
        'phone' => '+91-9876543212',
        'visit_date' => '2024-02-17',
        'visit_time' => '11:00:00',
        'message' => 'Looking for 4 BHK villa with garden. Investment purpose.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 4,
        'property_name' => 'Paris City',
        'user_name' => 'Kavita Singh',
        'email' => 'kavita.singh@email.com',
        'phone' => '+91-9876543213',
        'visit_date' => '2024-02-18',
        'visit_time' => '15:00:00',
        'message' => '2 BHK apartment for my parents. Ground floor preferred.',
        'status' => 'pending'
    ],
    [
        'user_id' => 5,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Vikram Joshi',
        'email' => 'vikram.joshi@email.com',
        'phone' => '+91-9876543214',
        'visit_date' => '2024-02-19',
        'visit_time' => '12:00:00',
        'message' => 'Show house available for site visit this weekend.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 6,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Meera Gupta',
        'email' => 'meera.gupta@email.com',
        'phone' => '+91-9876543215',
        'visit_date' => '2024-02-20',
        'visit_time' => '16:00:00',
        'message' => 'Retail space required for restaurant business.',
        'status' => 'pending'
    ],
    [
        'user_id' => 7,
        'property_id' => 1,
        'property_name' => 'Paris City',
        'user_name' => 'Suresh Verma',
        'email' => 'suresh.verma@email.com',
        'phone' => '+91-9876543216',
        'visit_date' => '2024-02-21',
        'visit_time' => '10:30:00',
        'message' => '1 BHK for retirement. Need ramp access for wheelchair.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 8,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Anjali Mehta',
        'email' => 'anjali.mehta@email.com',
        'phone' => '+91-9876543217',
        'visit_date' => '2024-02-22',
        'visit_time' => '13:00:00',
        'message' => 'Bulk booking for 10 apartments. Corporate rates requested.',
        'status' => 'pending'
    ],
    [
        'user_id' => 9,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Rohit Shah',
        'email' => 'rohit.shah@email.com',
        'phone' => '+91-9876543218',
        'visit_date' => '2024-02-23',
        'visit_time' => '11:30:00',
        'message' => 'Co-working space for startup team of 20 people.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 10,
        'property_id' => 1,
        'property_name' => 'Paris City',
        'user_name' => 'Pallavi Nair',
        'email' => 'pallavi.nair@email.com',
        'phone' => '+91-9876543219',
        'visit_date' => '2024-02-24',
        'visit_time' => '14:30:00',
        'message' => 'Studio apartment for single professional. Budget friendly option.',
        'status' => 'pending'
    ],
    [
        'user_id' => 11,
        'property_id' => 3,
        'property_name' => 'Infinity Heights',
        'user_name' => 'Rajesh Kumar',
        'email' => 'rajesh.kumar@email.com',
        'phone' => '+91-9876543220',
        'visit_date' => '2024-02-25',
        'visit_time' => '15:30:00',
        'message' => 'NRI purchase. Need guidance on payment options and documentation.',
        'status' => 'confirmed'
    ],
    [
        'user_id' => 12,
        'property_id' => 2,
        'property_name' => 'Corporate Plaza',
        'user_name' => 'Sneha Reddy',
        'email' => 'sneha.reddy@email.com',
        'phone' => '+91-9876543221',
        'visit_date' => '2024-02-26',
        'visit_time' => '16:30:00',
        'message' => 'First home purchase. Need complete guidance and loan assistance.',
        'status' => 'pending'
    ]
];


foreach ($sampleBookings as &$booking) {
    
    $booking['user_id'] = $userIdByEmail[$booking['email']] ?? 0;
    $booking['property_id'] = $propertyIdByName[$booking['property_name']] ?? 0;
}
unset($booking);

foreach ($sampleBookings as $booking) {
    if (empty($booking['user_id']) || empty($booking['property_id'])) {
        continue;
    }
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, property_id, property_name, user_name, email, phone, visit_date, visit_time, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE user_name = VALUES(user_name)");
    $stmt->bind_param('iissssssss', $booking['user_id'], $booking['property_id'], $booking['property_name'], $booking['user_name'], $booking['email'], $booking['phone'], $booking['visit_date'], $booking['visit_time'], $booking['message'], $booking['status']);
    $stmt->execute();
    $stmt->close();
}




$sql_drop_staff = "DROP TABLE IF EXISTS staff";
$conn->query($sql_drop_staff);

$sql_staff = "CREATE TABLE staff (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    staff_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    designation VARCHAR(50) NOT NULL,
    clients_brought INT NOT NULL DEFAULT 0,
    total_incentive DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    monthly_target INT NOT NULL DEFAULT 5,
    current_month_clients INT NOT NULL DEFAULT 0,
    status ENUM('active','inactive','terminated') NOT NULL DEFAULT 'active',
    joined_date DATE NOT NULL,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_staff) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'staff' ready.</p>";
}

$sampleStaff = [
    [
        'staff_name' => 'Rajesh Kumar',
        'phone' => '+91-9876543210',
        'email' => 'rajesh.kumar@smconss.com',
        'designation' => 'Senior Sales Executive',
        'clients_brought' => 45,
        'total_incentive' => 225000.00,
        'monthly_target' => 8,
        'current_month_clients' => 6,
        'status' => 'active',
        'joined_date' => '2023-01-15'
    ],
    [
        'staff_name' => 'Priya Sharma',
        'phone' => '+91-9876543211',
        'email' => 'priya.sharma@smconss.com',
        'designation' => 'Sales Executive',
        'clients_brought' => 32,
        'total_incentive' => 160000.00,
        'monthly_target' => 6,
        'current_month_clients' => 4,
        'status' => 'active',
        'joined_date' => '2023-03-20'
    ],
    [
        'staff_name' => 'Amit Singh',
        'phone' => '+91-9876543212',
        'email' => 'amit.singh@smconss.com',
        'designation' => 'Business Development Manager',
        'clients_brought' => 67,
        'total_incentive' => 335000.00,
        'monthly_target' => 10,
        'current_month_clients' => 8,
        'status' => 'active',
        'joined_date' => '2022-08-10'
    ],
    [
        'staff_name' => 'Sneha Patel',
        'phone' => '+91-9876543213',
        'email' => 'sneha.patel@smconss.com',
        'designation' => 'Sales Executive',
        'clients_brought' => 28,
        'total_incentive' => 140000.00,
        'monthly_target' => 5,
        'current_month_clients' => 3,
        'status' => 'active',
        'joined_date' => '2023-06-05'
    ],
    [
        'staff_name' => 'Vikram Joshi',
        'phone' => '+91-9876543214',
        'email' => 'vikram.joshi@smconss.com',
        'designation' => 'Senior Sales Executive',
        'clients_brought' => 52,
        'total_incentive' => 260000.00,
        'monthly_target' => 8,
        'current_month_clients' => 7,
        'status' => 'active',
        'joined_date' => '2022-11-12'
    ],
    [
        'staff_name' => 'Kavita Reddy',
        'phone' => '+91-9876543215',
        'email' => 'kavita.reddy@smconss.com',
        'designation' => 'Sales Executive',
        'clients_brought' => 19,
        'total_incentive' => 95000.00,
        'monthly_target' => 5,
        'current_month_clients' => 2,
        'status' => 'active',
        'joined_date' => '2023-09-18'
    ],
    [
        'staff_name' => 'Rahul Mehta',
        'phone' => '+91-9876543216',
        'email' => 'rahul.mehta@smconss.com',
        'designation' => 'Junior Sales Executive',
        'clients_brought' => 12,
        'total_incentive' => 60000.00,
        'monthly_target' => 4,
        'current_month_clients' => 1,
        'status' => 'active',
        'joined_date' => '2024-01-08'
    ],
    [
        'staff_name' => 'Anjali Gupta',
        'phone' => '+91-9876543217',
        'email' => 'anjali.gupta@smconss.com',
        'designation' => 'Sales Executive',
        'clients_brought' => 38,
        'total_incentive' => 190000.00,
        'monthly_target' => 6,
        'current_month_clients' => 5,
        'status' => 'active',
        'joined_date' => '2023-04-22'
    ]
];

foreach ($sampleStaff as $staff) {
    $stmt = $conn->prepare("INSERT INTO staff (staff_name, phone, email, designation, clients_brought, total_incentive, monthly_target, current_month_clients, status, joined_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE clients_brought = VALUES(clients_brought), total_incentive = VALUES(total_incentive), current_month_clients = VALUES(current_month_clients)");
    $stmt->bind_param('ssssisiiss', $staff['staff_name'], $staff['phone'], $staff['email'], $staff['designation'], $staff['clients_brought'], $staff['total_incentive'], $staff['monthly_target'], $staff['current_month_clients'], $staff['status'], $staff['joined_date']);
    $stmt->execute();
    $stmt->close();
}


$sql_projects = "CREATE TABLE IF NOT EXISTS projects (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('active','completed','planned') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_projects) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'projects' ready.</p>";
}


$sampleProjects = [
    [
        'title' => 'Paris City Residential Complex',
        'description' => 'A premium residential complex featuring modern architecture with world-class amenities including swimming pool, gymnasium, and landscaped gardens.',
        'status' => 'active'
    ],
    [
        'title' => 'Corporate Plaza Business Center',
        'description' => 'State-of-the-art commercial complex designed for modern businesses with high-speed elevators, conference rooms, and 24/7 security.',
        'status' => 'active'
    ],
    [
        'title' => 'Infinity Heights Luxury Apartments',
        'description' => 'Luxury high-rise apartments with panoramic city views, smart home automation, and premium finishing throughout.',
        'status' => 'active'
    ],
    [
        'title' => 'Green Valley Eco-Friendly Homes',
        'description' => 'Sustainable living spaces with solar panels, rainwater harvesting, and extensive green spaces for environmentally conscious residents.',
        'status' => 'completed'
    ],
    [
        'title' => 'Royal Gardens Villa Community',
        'description' => 'Exclusive villa community with private gardens, clubhouse facilities, and proximity to premium schools and hospitals.',
        'status' => 'active'
    ],
    [
        'title' => 'Metro View Commercial Towers',
        'description' => 'Prime commercial location with excellent connectivity to metro station, perfect for retail and office spaces.',
        'status' => 'active'
    ],
    [
        'title' => 'Lake View Waterfront Residences',
        'description' => 'Beautiful waterfront properties offering serene living with lake views and recreational facilities.',
        'status' => 'completed'
    ],
    [
        'title' => 'Sunrise Enclave Gated Community',
        'description' => 'Secure gated community with 24/7 security, children\'s play areas, and community events.',
        'status' => 'active'
    ],
    [
        'title' => 'Palm Grove Luxury Villas',
        'description' => 'Premium villa project with Mediterranean architecture, private pools, and lush landscaping.',
        'status' => 'planned'
    ],
    [
        'title' => 'Crystal Heights Sky Residences',
        'description' => 'Ultra-luxury sky villas with infinity pools, helipad facilities, and concierge services.',
        'status' => 'planned'
    ],
    [
        'title' => 'Urban Oasis Mixed Development',
        'description' => 'Mixed-use development combining residential, commercial, and retail spaces in the heart of the city.',
        'status' => 'active'
    ],
    [
        'title' => 'Heritage Walk Traditional Homes',
        'description' => 'Contemporary homes inspired by traditional architecture, blending modern comfort with cultural heritage.',
        'status' => 'completed'
    ]
];

foreach ($sampleProjects as $project) {
    $stmt = $conn->prepare("INSERT INTO projects (title, description, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE description = VALUES(description)");
    $stmt->bind_param('sss', $project['title'], $project['description'], $project['status']);
    $stmt->execute();
    $stmt->close();
}


$sql_chats = "CREATE TABLE IF NOT EXISTS chats (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) UNSIGNED,
    prompt TEXT NOT NULL,
    response TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";


$sql_alter_chats = "ALTER TABLE chats 
    ADD COLUMN IF NOT EXISTS user_id INT(11) UNSIGNED,
    ADD COLUMN IF NOT EXISTS timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ADD CONSTRAINT fk_chats_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE";

if ($conn->query($sql_chats) === TRUE) {
    echo "<p style='color:green;'>✔ Table 'chats' ready.</p>";
    
    
    $columns_to_add = [
        "user_id INT(11) UNSIGNED",
        "timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP"
    ];
    
    foreach ($columns_to_add as $column) {
        $column_name = explode(' ', $column)[0];
        $result = $conn->query("SHOW COLUMNS FROM chats LIKE '$column_name'");
        if ($result->num_rows == 0) {
            $conn->query("ALTER TABLE chats ADD COLUMN $column");
        }
    }
    
    
    try { $conn->query("ALTER TABLE chats ADD CONSTRAINT fk_chats_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE"); } catch(Exception $e) {}
} else {
    echo "<p style='color:red;'>✘ Error creating chats table: " . $conn->error . "</p>";
}


$sampleChats = [
    [
        'user_id' => 1,
        'prompt' => 'What are the current property prices in Bangalore?',
        'response' => 'Property prices in Bangalore vary by location and type. 2BHK apartments range from ₹40-80 lakhs, 3BHK from ₹70-1.5 crores, and villas from ₹1-5 crores depending on the area and amenities.'
    ],
    [
        'user_id' => 1,
        'prompt' => 'Can I schedule a second visit to Corporate Plaza next week?',
        'response' => 'Absolutely, Rahul. Please select your preferred date/time and our team will confirm availability immediately.'
    ],
    [
        'user_id' => 2,
        'prompt' => 'Tell me about Paris City project',
        'response' => 'Paris City is a premium residential complex with 150 units featuring modern architecture, swimming pool, gymnasium, landscaped gardens, and 24/7 security. Located in prime location with excellent connectivity.'
    ],
    [
        'user_id' => 3,
        'prompt' => 'What loan options are available?',
        'response' => 'We offer home loans through partnered banks with interest rates starting from 6.5%. We also provide guidance for NRI loans, self-employed loans, and first-time homebuyer schemes.'
    ],
    [
        'user_id' => 4,
        'prompt' => 'How to book a site visit?',
        'response' => 'You can book a site visit by calling our helpline at +91-9876543210 or filling out the inquiry form on your user dashboard. Our sales executives will coordinate a convenient time for you.'
    ],
    [
        'user_id' => 5,
        'prompt' => 'What are the payment plans?',
        'response' => 'We offer flexible payment plans including down payment options, construction-linked payments, and EMI schemes. Minimum down payment starts from 10% of property value.'
    ],
    [
        'user_id' => 6,
        'prompt' => 'Are there any current offers?',
        'response' => 'Yes, we have special offers including 5% discount on select units, free car parking, and complimentary modular kitchen. Limited time offer - contact us for details.'
    ],
    [
        'user_id' => 7,
        'prompt' => 'What is the RERA registration status?',
        'response' => 'All our projects are registered with RERA. You can verify the registration details on the official RERA website using our project registration numbers.'
    ],
    [
        'user_id' => 8,
        'prompt' => 'Tell me about amenities in Infinity Heights',
        'response' => 'Infinity Heights offers world-class amenities including rooftop pool, fitness center, children\'s play area, multipurpose hall, 24/7 security, and landscaped gardens.'
    ],
    [
        'user_id' => 9,
        'prompt' => 'What is the expected possession date?',
        'response' => 'Possession dates vary by project. Paris City - December 2025, Infinity Heights - June 2026, Corporate Plaza - March 2025. Exact dates are mentioned in the agreement.'
    ],
    [
        'user_id' => 10,
        'prompt' => 'Do you provide post-sales services?',
        'response' => 'Yes, we provide comprehensive post-sales services including property management, maintenance support, and resale assistance. Our relationship continues even after possession.'
    ],
    [
        'user_id' => 11,
        'prompt' => 'What about property tax and maintenance?',
        'response' => 'Property tax is approximately 0.2-0.5% of property value annually. Maintenance charges range from ₹2-5 per sq ft monthly, covering common area maintenance, security, and utilities.'
    ],
    [
        'user_id' => 12,
        'prompt' => 'How to check property documents?',
        'response' => 'All property documents including title deeds, approvals, and legal papers are available for verification. We provide complete transparency and assist with legal due diligence.'
    ]
];


$conn->query("SET FOREIGN_KEY_CHECKS = 0");

foreach ($sampleChats as $chat) {
    $stmt = $conn->prepare("INSERT INTO chats (user_id, prompt, response) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE response = VALUES(response)");
    $stmt->bind_param('iss', $chat['user_id'], $chat['prompt'], $chat['response']);
    $stmt->execute();
    $stmt->close();
}


$conn->query("SET FOREIGN_KEY_CHECKS = 1");

echo "<hr>";
echo "<p><strong>Setup Complete!</strong> Ab aap apni website use kar sakte hain.</p>";
$conn->close();
?>