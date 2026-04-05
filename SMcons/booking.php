<?php
$property = isset($_GET['property']) ? htmlspecialchars($_GET['property']) : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Property - S&M Infrastructures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body {
            background-color: 
            color: 
            font-family: 'Montserrat', sans-serif;
        }
        .booking-container {
            max-width: 800px;
            margin: 100px auto;
            padding: 40px;
            background: 
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .text-gold {
            color: 
        }
        .btn-gold-fill {
            background-color: 
            color: 
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-gold-fill:hover {
            background-color: 
            transform: translateY(-2px);
        }
        .custom-input {
            background: transparent;
            border: none;
            border-bottom: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 0;
            color: 
            padding: 12px 0;
            margin-bottom: 20px;
        }
        .custom-input:focus {
            background: transparent;
            color: 
            border-color: 
            box-shadow: none;
        }
        .booking-header {
            margin-bottom: 40px;
            text-align: center;
        }
        .booking-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 10px;
        }
        .logo-box {
            position: absolute;
            top: 20px;
            left: 20px;
        }
    </style>
</head>

<body>
    <div class="logo-box">
        <a href="index.html" class="text-decoration-none">
            <h2 class="fw-bold mb-0" style="color: 
        </a>
    </div>

    <div class="container">
        <div class="booking-container">
            <div class="booking-header">
                <h6 class="text-gold text-uppercase tracking-extra fw-bold mb-3">Reserve Your Space</h6>
                <h1>Book <span class="text-gold">Now</span></h1>
                <p class="text-white-50">Please fill out the form below to initiate your booking process.</p>
            </div>

            <form action="backend/api/book.php" method="POST">
                <div class="row">
                    <div class="col-md-12">
                        <label class="small text-gold text-uppercase fw-bold mb-2">Selected Property</label>
                        <select name="property_name" class="form-select custom-input" required>
                            <option value="" disabled <?php echo empty($property) ? 'selected' : ''; ?>>Select a Property</option>
                            <option value="Corporate Plaza" <?php echo ($property == 'Corporate Plaza') ? 'selected' : ''; ?>>Corporate Plaza</option>
                            <option value="Infinity Heights" <?php echo ($property == 'Infinity Heights') ? 'selected' : ''; ?>>Infinity Heights</option>
                            <option value="The Grand Residency" <?php echo ($property == 'The Grand Residency') ? 'selected' : ''; ?>>The Grand Residency</option>
                            <option value="Elite Commercial Hub" <?php echo ($property == 'Elite Commercial Hub') ? 'selected' : ''; ?>>Elite Commercial Hub</option>
                            <option value="Paris City" <?php echo ($property == 'Paris City') ? 'selected' : ''; ?>>Paris City</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-gold text-uppercase fw-bold mb-2">Your Full Name</label>
                        <input type="text" name="user_name" class="form-control custom-input" placeholder="Enter your name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-gold text-uppercase fw-bold mb-2">Email Address</label>
                        <input type="email" name="email_address" class="form-control custom-input" placeholder="Enter your email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-gold text-uppercase fw-bold mb-2">Phone Number</label>
                        <input type="tel" name="phone_no" class="form-control custom-input" placeholder="Enter your phone" required>
                    </div>
                    <div class="col-md-12">
                        <label class="small text-gold text-uppercase fw-bold mb-2">Additional Message (Optional)</label>
                        <textarea name="message" class="form-control custom-input" rows="4" placeholder="How can we help you?"></textarea>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-gold-fill px-5 py-3 text-uppercase tracking-widest w-100">Confirm Booking Request</button>
                    <a href="index.html" class="d-block mt-3 text-white-50 small text-decoration-none">Back to Home</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
