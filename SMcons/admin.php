<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin-login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/main.css">
    <style>
        body {
            background-color: 
            color: 
        }
        .dashboard-header {
            background: 
            padding: 20px;
            border-bottom: 1px solid var(--gold);
        }
        .dashboard-content {
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-gold" data-translate="administrator_access">ADMINISTRATOR ACCESS</h1>
                <p class="text-muted" data-translate="operation_control_center">OPERATION CONTROL CENTER</p>
            </div>
            <div id="languageSelectorContainer"></div>
        </div>
    </div>
    <div class="dashboard-content">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="text-light" data-translate="welcome_agent">Welcome, Agent.</h2>
                    <button class="btn btn-outline-danger btn-sm" onclick="logout()" data-translate="logout">LOGOUT</button>
                </div>
                <p class="text-muted" data-translate="central_command">Central command for all operations. Last login: <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
        </div>
        
        <!-- Stats Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="totalChats">0</h3>
                        <p class="card-text" data-translate="total_chats">Total Chats</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="totalFeedback">0</h3>
                        <p class="card-text" data-translate="total_feedback">Feedback</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="totalProjects">0</h3>
                        <p class="card-text" data-translate="total_projects">Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="totalProperties">0</h3>
                        <p class="card-text">Property Availability</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark text-white border-gold">
                    <div class="card-body text-center">
                        <h3 class="text-gold" id="systemStatus">ONLINE</h3>
                        <p class="card-text" data-translate="system_status">System Status</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Tools -->
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-gold" data-translate="database_management">Database Management</h5>
                        <p class="card-text" data-translate="manage_chats_feedback">Manage chats, feedback, and system data.</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-gold" onclick="viewChats()" data-translate="view_chats">VIEW CHATS</button>
                            <button class="btn btn-outline-gold" onclick="viewFeedback()" data-translate="view_feedback">VIEW FEEDBACK</button>
                            <button class="btn btn-outline-gold" onclick="viewInquiries()" data-translate="view_inquiries">VIEW INQUIRIES</button>
                            <button class="btn btn-outline-gold" onclick="viewProjects()" data-translate="view_projects">VIEW PROJECTS</button>
                            <button class="btn btn-outline-danger" onclick="clearData()" data-translate="clear_data">CLEAR DATA</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-gold">Staff Management</h5>
                        <p class="card-text">Manage staff performance and incentives.</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-gold" onclick="viewStaff()">VIEW STAFF</button>
                            <button class="btn btn-outline-gold" onclick="viewStaffPerformance()">STAFF PERFORMANCE</button>
                            <button class="btn btn-outline-gold" onclick="viewIncentives()">INCENTIVES REPORT</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark text-white mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-gold" data-translate="sql_data_viewer">SQL Data Viewer</h5>
                        <p class="card-text" data-translate="direct_database_access">Direct database access and management.</p>
                        <div class="mb-3">
                            <select class="form-select custom-input" id="tableSelector" data-translate-placeholder="select_table">
                                <option value="" data-translate="select_table">Select Table</option>
                            </select>
                        </div>
                        <div class="d-grid gap-2">
                            <button class="btn btn-gold" onclick="loadTableData()" data-translate="load_table">LOAD TABLE</button>
                            <button class="btn btn-outline-gold" onclick="showCreateForm()" data-translate="add_record">ADD RECORD</button>
                            <button class="btn btn-outline-gold" onclick="exportTableData()" data-translate="export_data">EXPORT DATA</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Display Area -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-dark text-white">
                    <div class="card-header bg-gold text-black d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" data-translate="data_viewer_editor">Data Viewer & Editor</h5>
                        <div id="actionButtons" class="d-none">
                            <button class="btn btn-sm btn-outline-dark" onclick="refreshData()" data-translate="refresh">🔄 Refresh</button>
                            <button class="btn btn-sm btn-outline-dark" onclick="showCreateForm()" data-translate="add_new">➕ Add New</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="dataDisplay">
                            <p class="text-muted text-center" data-translate="select_action">Select an action above to display data</p>
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
    <script src="scripts/components/admin.js"></script>
</body>
</html>
