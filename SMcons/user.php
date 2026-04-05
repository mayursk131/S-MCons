<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - SM Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body {
            background-color: 
            color: 
        }
        .navbar {
            background-color: 
            border-bottom: 1px solid var(--gold);
        }
        .card {
            background-color: 
            border: 1px solid 
        }
        .text-gold {
            color: var(--gold) !important;
        }
        .btn-gold {
            background-color: var(--gold);
            color: 
            border: none;
        }
        .btn-gold:hover {
            background-color: 
            color: 
        }
        .btn-outline-gold {
            border-color: var(--gold);
            color: var(--gold);
        }
        .btn-outline-gold:hover {
            background-color: var(--gold);
            color: 
        }
        .table-dark {
            background-color: 
        }
        .table-dark th,
        .table-dark td {
            border-color: 
        }
        .form-control {
            background-color: 
            border: 1px solid 
            color: 
        }
        .form-control:focus {
            background-color: 
            border-color: var(--gold);
            color: 
            box-shadow: 0 0 0 0.2rem rgba(218, 165, 32, 0.25);
        }
        .alert {
            background-color: 
            border: 1px solid 
        }
        .custom-input {
            background-color: 
            border: 1px solid 
            color: 
        }
        .custom-input:focus {
            background-color: 
            border-color: var(--gold) !important;
            color: 
            box-shadow: 0 0 0 0.2rem rgba(218, 165, 32, 0.25) !important;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
        header('Location: user-login.php');
        exit;
    }

    
    require_once 'backend/config/database.php';
    ?>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-gold" href="#">
                <strong>SM Construction</strong> - User Portal
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="user.loadStats()">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="user.viewProperties()">Properties</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="user.viewBookings()">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="user.viewInquiries()">My Inquiries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="user.viewProfile()">Profile</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="viewProfile()">My Profile</a></li>
                            <li><a class="dropdown-item" href="#" onclick="changePassword()">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="logout()">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <!-- Welcome Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body">
                        <h2 class="text-gold mb-2">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
                        <p class="mb-0">Manage your properties, bookings, and inquiries from your personal dashboard.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="myBookings">0</h3>
                        <p class="card-text">My Bookings</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="myInquiries">0</h3>
                        <p class="card-text">My Inquiries</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="availableProperties">0</h3>
                        <p class="card-text">Available Properties</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="myFeedback">0</h3>
                        <p class="card-text">My Feedback</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title text-gold">Browse Properties</h5>
                        <p class="card-text">Explore available properties</p>
                        <button class="btn btn-gold" onclick="viewProperties()">VIEW PROPERTIES</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title text-gold">Book Property</h5>
                        <p class="card-text">Schedule a property visit</p>
                        <button class="btn btn-outline-gold" onclick="bookProperty()">BOOK NOW</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title text-gold">Contact Us</h5>
                        <p class="card-text">Send us an inquiry</p>
                        <button class="btn btn-outline-gold" onclick="sendInquiry()">SEND INQUIRY</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body text-center">
                        <h5 class="card-title text-gold">AI Assistant</h5>
                        <p class="card-text">Get instant answers</p>
                        <button class="btn btn-outline-gold" onclick="chatWithAI()">CHAT NOW</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark text-white">
                    <div class="card-header bg-gold text-black d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" id="contentTitle">Dashboard Overview</h5>
                        <div id="actionButtons" class="d-none">
                            <button class="btn btn-sm btn-outline-dark" onclick="refreshData()">🔄 Refresh</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="mainContent">
                            <div class="text-center">
                                <h4 class="text-gold mb-3">Welcome to Your Dashboard</h4>
                                <p>Use the navigation above or quick action buttons to manage your account and explore properties.</p>
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card bg-secondary text-white mb-3">
                                            <div class="card-body">
                                                <h6>Recent Activity</h6>
                                                <p class="small text-muted">No recent activity</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-secondary text-white mb-3">
                                            <div class="card-body">
                                                <h6>Quick Stats</h6>
                                                <p class="small text-muted">Loading your statistics...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="formContainer" class="d-none"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts/utils/language.js"></script>
    <script src="scripts/components/user.js"></script>
</body>
</html>