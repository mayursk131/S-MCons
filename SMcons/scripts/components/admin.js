
class AdminPanel {
    constructor() {
        this.init();
        this.loadStats();
    }

    init() {
        console.log('Admin Panel Initialized');
        this.setupEventListeners();
        this.loadTables();
        this.initializeLanguage();
    }

    initializeLanguage() {
        
        if (typeof languageManager !== 'undefined') {
            const selectorContainer = document.getElementById('languageSelectorContainer');
            if (selectorContainer) {
                selectorContainer.innerHTML = languageManager.createLanguageSelector();
            }
        }
    }

    updateLanguage() {
        
        this.updateStatsDisplay();
        if (this.currentTable) {
            this.loadTableData();
        }
    }

    updateStatsDisplay() {
        
        const statsElements = {
            'totalChats': 'total_chats',
            'totalFeedback': 'total_feedback', 
            'totalProjects': 'total_projects',
            'systemStatus': 'system_status'
        };

        Object.keys(statsElements).forEach(id => {
            const element = document.querySelector(`#${id} + p`);
            if (element && typeof languageManager !== 'undefined') {
                element.textContent = languageManager.t(statsElements[id]);
            }
        });
    }

    setupEventListeners() {
        
        setInterval(() => this.loadStats(), 30000);
        
        
        document.getElementById('tableSelector').addEventListener('change', (e) => {
            if (e.target.value) {
                this.loadTableData();
            }
        });
    }

    async loadTables() {
        try {
            const response = await fetch('backend/api/admin_api.php?action=tables');
            const data = await response.json();
            
            if (data.success) {
                const selector = document.getElementById('tableSelector');
                selector.innerHTML = '<option value="">Select Table</option>';
                
                data.tables.forEach(table => {
                    const option = document.createElement('option');
                    option.value = table;
                    option.textContent = table;
                    selector.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading tables:', error);
        }
    }

    async loadStats() {
        try {
            const response = await fetch('backend/api/admin_api.php?action=stats');
            const data = await response.json();
            
            if (data.success) {
                document.getElementById('totalChats').textContent = data.chats || 0;
                document.getElementById('totalFeedback').textContent = data.feedback || 0;
                document.getElementById('totalProjects').textContent = data.projects || 0;
                document.getElementById('totalProperties').textContent = data.properties || 0;
                document.getElementById('systemStatus').textContent = data.status || 'ONLINE';
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async loadTableData() {
        const table = document.getElementById('tableSelector').value;
        if (!table) {
            this.showError(typeof languageManager !== 'undefined' ? languageManager.t('please_select_table') : 'Please select a table');
            return;
        }
        
        const loadingMsg = typeof languageManager !== 'undefined' 
            ? languageManager.t('loading_table_data').replace('{table}', table)
            : `Loading ${table} data...`;
        
        this.showLoading(loadingMsg);
        this.currentTable = table;
        
        try {
            const response = await fetch(`backend/api/admin_api.php?action=table_data&table=${table}`);
            const data = await response.json();
            
            if (data.success) {
                this.displayTableData(data);
                document.getElementById('actionButtons').classList.remove('d-none');
            } else {
                this.showError(typeof languageManager !== 'undefined' ? languageManager.t('failed_to_load_data') : 'Failed to load table data');
            }
        } catch (error) {
            const errorMsg = typeof languageManager !== 'undefined' 
                ? languageManager.t('error_loading_data') + error.message
                : 'Error loading table data: ' + error.message;
            this.showError(errorMsg);
        }
    }

    displayTableData(tableData) {
        const { table, columns, data, primary_key } = tableData;
        
        const tableLabel = typeof languageManager !== 'undefined' ? languageManager.t('table') : 'Table';
        const actionsLabel = typeof languageManager !== 'undefined' ? languageManager.t('actions') : 'Actions';
        const editLabel = typeof languageManager !== 'undefined' ? languageManager.t('edit') : 'Edit';
        const deleteLabel = typeof languageManager !== 'undefined' ? languageManager.t('delete') : 'Delete';
        const noDataLabel = typeof languageManager !== 'undefined' ? languageManager.t('no_data_found') : 'No data found';
        
        let html = `
            <div class="table-responsive">
                <h5 class="text-gold mb-3">${tableLabel}: ${table}</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
        `;
        
        
        columns.forEach(col => {
            html += `<th>${col.name}</th>`;
        });
        html += `<th>${actionsLabel}</th></tr></thead><tbody>`;
        
        
        if (data && data.length > 0) {
            data.forEach(row => {
                html += '<tr>';
                columns.forEach(col => {
                    const value = row[col.name] || '';
                    html += `<td>${this.truncateText(value, 50)}</td>`;
                });
                html += `
                    <td>
                        <button class="btn btn-sm btn-outline-warning" onclick="admin.editRecord('${table}', '${primary_key}', ${row[primary_key]})">${editLabel}</button>
                        <button class="btn btn-sm btn-outline-danger" onclick="admin.deleteRecord('${table}', '${primary_key}', ${row[primary_key]})">${deleteLabel}</button>
                    </td>
                </tr>
                `;
            });
        } else {
            html += `<tr><td colspan="${columns.length + 1}" class="text-center text-muted">${noDataLabel}</td></tr>`;
        }
        
        html += '</tbody></table></div>';
        
        document.getElementById('dataDisplay').innerHTML = html;
        this.currentTableData = tableData;
    }

    async viewChats() {
        this.showLoading('Loading chat data...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=chats');
            const data = await response.json();
            
            if (data.success) {
                this.displayChats(data.chats);
            } else {
                this.showError('Failed to load chats');
            }
        } catch (error) {
            this.showError('Error loading chats: ' + error.message);
        }
    }

    displayChats(chats) {
        let html = `
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Prompt</th>
                            <th>Response</th>
                            <th>Timestamp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (chats && chats.length > 0) {
            chats.forEach(chat => {
                html += `
                    <tr>
                        <td>${chat.id}</td>
                        <td>${this.truncateText(chat.prompt, 50)}</td>
                        <td>${this.truncateText(chat.response, 50)}</td>
                        <td>${chat.timestamp || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="admin.deleteChat(${chat.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="5" class="text-center text-muted">No chats found</td></tr>';
        }

        html += `
                    </tbody>
                </table>
            </div>
        `;

        document.getElementById('dataDisplay').innerHTML = html;
    }

    async viewFeedback() {
        this.showLoading('Loading feedback data...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=feedback');
            const data = await response.json();
            
            if (data.success) {
                this.displayFeedback(data.feedback);
            } else {
                this.showError('Failed to load feedback');
            }
        } catch (error) {
            this.showError('Error loading feedback: ' + error.message);
        }
    }

    displayFeedback(feedback) {
        let html = `
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Rating</th>
                            <th>Timestamp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (feedback && feedback.length > 0) {
            feedback.forEach(item => {
                html += `
                    <tr>
                        <td>${item.id}</td>
                        <td>${item.name || 'N/A'}</td>
                        <td>${item.email || 'N/A'}</td>
                        <td>${this.truncateText(item.message, 50)}</td>
                        <td>${this.displayRating(item.rating)}</td>
                        <td>${item.timestamp || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="admin.deleteFeedback(${item.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="7" class="text-center text-muted">No feedback found</td></tr>';
        }

        html += `
                    </tbody>
                </table>
            </div>
        `;

        document.getElementById('dataDisplay').innerHTML = html;
    }

    async viewInquiries() {
        this.showLoading('Loading inquiries data...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=inquiries');
            const data = await response.json();
            
            if (data.success) {
                this.displayInquiries(data.inquiries);
            } else {
                this.showError('Failed to load inquiries');
            }
        } catch (error) {
            this.showError('Error loading inquiries: ' + error.message);
        }
    }

    async viewProjects() {
        this.showLoading('Loading projects data...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=projects');
            const data = await response.json();
            
            if (data.success) {
                this.displayProjects(data.projects);
            } else {
                this.showError('Failed to load projects');
            }
        } catch (error) {
            this.showError('Error loading projects: ' + error.message);
        }
    }

    displayInquiries(inquiries) {
        let html = `
            <div class="table-responsive">
                <h5 class="text-gold mb-3">Contact Inquiries</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Timestamp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (inquiries && inquiries.length > 0) {
            inquiries.forEach(inquiry => {
                html += `
                    <tr>
                        <td>${inquiry.id}</td>
                        <td>${inquiry.name}</td>
                        <td>${inquiry.phone || 'N/A'}</td>
                        <td>${inquiry.email}</td>
                        <td>${this.truncateText(inquiry.message, 50)}</td>
                        <td>${inquiry.timestamp}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger" onclick="admin.deleteInquiry(${inquiry.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="7" class="text-center text-muted">No inquiries found</td></tr>';
        }

        html += '</tbody></table></div>';
        document.getElementById('dataDisplay').innerHTML = html;
    }

    displayProjects(projects) {
        let html = `
            <div class="table-responsive">
                <h5 class="text-gold mb-3">Projects</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (projects && projects.length > 0) {
            projects.forEach(project => {
                html += `
                    <tr>
                        <td>${project.id}</td>
                        <td>${project.title}</td>
                        <td>${this.truncateText(project.description, 50)}</td>
                        <td><span class="badge bg-${project.status === 'active' ? 'success' : project.status === 'completed' ? 'primary' : 'warning'}">${project.status}</span></td>
                        <td>${project.created_at}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" onclick="admin.editProject(${project.id})">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="admin.deleteProject(${project.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="6" class="text-center text-muted">No projects found</td></tr>';
        }

        html += '</tbody></table></div>';
        document.getElementById('dataDisplay').innerHTML = html;
    }

    
    async deleteRecord(table, primaryKey, id) {
        if (!confirm(`Are you sure you want to delete this record from ${table}?`)) return;
        
        try {
            const response = await fetch(`backend/api/admin_api.php?action=delete&table=${table}&id=${id}`);
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Record deleted successfully');
                this.loadTableData(); 
                this.loadStats(); 
            } else {
                this.showError('Failed to delete record');
            }
        } catch (error) {
            this.showError('Error deleting record: ' + error.message);
        }
    }

    async editRecord(table, primaryKey, id) {
        if (!this.currentTableData) return;
        
        const record = this.currentTableData.data.find(row => row[primaryKey] == id);
        if (!record) return;
        
        this.showEditForm(table, primaryKey, id, record, this.currentTableData.columns);
    }

    showEditForm(table, primaryKey, id, record, columns) {
        let html = `
            <h5 class="text-gold mb-3">Edit Record - ${table}</h5>
            <form id="editForm" onsubmit="admin.saveEdit(event, '${table}', '${primaryKey}', ${id})">
                <div class="row">
        `;
        
        columns.forEach(col => {
            if (col.name === primaryKey) return; 
            
            const value = record[col.name] || '';
            const required = !col.null ? 'required' : '';
            
            if (col.type.includes('TEXT') || col.type.includes('varchar')) {
                if (col.type.includes('TEXT') || col.type.includes('longtext')) {
                    html += `
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-light">${col.name}</label>
                            <textarea class="form-control custom-input" name="${col.name}" ${required}>${value}</textarea>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">${col.name}</label>
                            <input type="text" class="form-control custom-input" name="${col.name}" value="${value}" ${required}>
                        </div>
                    `;
                }
            } else if (col.type.includes('INT')) {
                html += `
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">${col.name}</label>
                        <input type="number" class="form-control custom-input" name="${col.name}" value="${value}" ${required}>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">${col.name}</label>
                        <input type="text" class="form-control custom-input" name="${col.name}" value="${value}" ${required}>
                    </div>
                `;
            }
        });
        
        html += `
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold">Save Changes</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="admin.hideForm()">Cancel</button>
                </div>
            </form>
        `;
        
        document.getElementById('formContainer').innerHTML = html;
        document.getElementById('formContainer').classList.remove('d-none');
        document.getElementById('dataDisplay').classList.add('d-none');
    }

    async saveEdit(event, table, primaryKey, id) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch(`backend/api/admin_api.php?action=update&table=${table}&id=${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Record updated successfully');
                this.hideForm();
                this.loadTableData(); 
                this.loadStats(); 
            } else {
                this.showError('Failed to update record: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error updating record: ' + error.message);
        }
    }

    showCreateForm() {
        if (!this.currentTableData) {
            this.showError('Please select a table first');
            return;
        }
        
        const { table, columns } = this.currentTableData;
        
        let html = `
            <h5 class="text-gold mb-3">Add New Record - ${table}</h5>
            <form id="createForm" onsubmit="admin.createRecord(event, '${table}')">
                <div class="row">
        `;
        
        columns.forEach(col => {
            const required = !col.null ? 'required' : '';
            
            if (col.type.includes('TEXT') || col.type.includes('varchar')) {
                if (col.type.includes('TEXT') || col.type.includes('longtext')) {
                    html += `
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-light">${col.name} ${required ? '*' : ''}</label>
                            <textarea class="form-control custom-input" name="${col.name}" ${required}></textarea>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">${col.name} ${required ? '*' : ''}</label>
                            <input type="text" class="form-control custom-input" name="${col.name}" ${required}>
                        </div>
                    `;
                }
            } else if (col.type.includes('INT')) {
                html += `
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">${col.name} ${required ? '*' : ''}</label>
                        <input type="number" class="form-control custom-input" name="${col.name}" ${required}>
                    </div>
                `;
            } else {
                html += `
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">${col.name} ${required ? '*' : ''}</label>
                        <input type="text" class="form-control custom-input" name="${col.name}" ${required}>
                    </div>
                `;
            }
        });
        
        html += `
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold">Create Record</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="admin.hideForm()">Cancel</button>
                </div>
            </form>
        `;
        
        document.getElementById('formContainer').innerHTML = html;
        document.getElementById('formContainer').classList.remove('d-none');
        document.getElementById('dataDisplay').classList.add('d-none');
    }

    async createRecord(event, table) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        data.table = table;
        data.action = 'create';
        
        try {
            const response = await fetch('backend/api/admin_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Record created successfully');
                this.hideForm();
                this.loadTableData(); 
                this.loadStats(); 
            } else {
                this.showError('Failed to create record: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error creating record: ' + error.message);
        }
    }

    hideForm() {
        document.getElementById('formContainer').classList.add('d-none');
        document.getElementById('dataDisplay').classList.remove('d-none');
    }

    refreshData() {
        if (this.currentTable) {
            this.loadTableData();
        }
    }

    async exportTableData() {
        if (!this.currentTableData) {
            this.showError('Please select a table first');
            return;
        }
        
        const { table, data } = this.currentTableData;
        
        
        let csv = Object.keys(data[0] || {}).join(',') + '\n';
        data.forEach(row => {
            csv += Object.values(row).map(val => `"${val}"`).join(',') + '\n';
        });
        
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${table}_export.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
        
        this.showSuccess(`${table} data exported successfully`);
    }

    async clearData() {
        if (!confirm('Are you sure you want to clear all data? This action cannot be undone!')) return;
        
        try {
            const response = await fetch('backend/api/admin_api.php?action=clear_data', {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('All data cleared successfully');
                this.loadStats(); 
                document.getElementById('dataDisplay').innerHTML = '<p class="text-success text-center">All data has been cleared</p>';
            } else {
                this.showError('Failed to clear data');
            }
        } catch (error) {
            this.showError('Error clearing data: ' + error.message);
        }
    }

    async toggleMaintenance() {
        try {
            const response = await fetch('backend/api/admin_api.php?action=maintenance', {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.success) {
                const status = data.maintenance ? 'ENABLED' : 'DISABLED';
                this.showSuccess(`Maintenance mode ${status}`);
            } else {
                this.showError('Failed to toggle maintenance mode');
            }
        } catch (error) {
            this.showError('Error toggling maintenance: ' + error.message);
        }
    }

    viewLogs() {
        const logs = `
            <div class="logs-container">
                <h5>System Logs</h5>
                <div class="log-entry">
                    <span class="text-muted">[${new Date().toLocaleString()}]</span> 
                    <span class="text-success">INFO:</span> Admin panel loaded successfully
                </div>
                <div class="log-entry">
                    <span class="text-muted">[${new Date().toLocaleString()}]</span> 
                    <span class="text-info">DEBUG:</span> Database connection established
                </div>
                <div class="log-entry">
                    <span class="text-muted">[${new Date().toLocaleString()}]</span> 
                    <span class="text-warning">WARN:</span> High memory usage detected
                </div>
            </div>
        `;
        document.getElementById('dataDisplay').innerHTML = logs;
    }

    async backupData() {
        this.showLoading('Creating backup...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=backup', {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Backup created successfully: ' + data.filename);
            } else {
                this.showError('Failed to create backup');
            }
        } catch (error) {
            this.showError('Error creating backup: ' + error.message);
        }
    }

    
    truncateText(text, maxLength) {
        if (!text) return 'N/A';
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    displayRating(rating) {
        if (!rating) return 'N/A';
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= rating ? '★' : '☆';
        }
        return stars;
    }

    showLoading(message) {
        document.getElementById('dataDisplay').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-gold" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">${message}</p>
            </div>
        `;
    }

    showSuccess(message) {
        this.showAlert(message, 'success');
    }

    showError(message) {
        this.showAlert(message, 'danger');
    }

    showAlert(message, type) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = alertHtml;
        document.getElementById('dataDisplay').prepend(tempDiv.firstElementChild);
    }

    
    async viewStaff() {
        this.showLoading('Loading staff data...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=staff');
            const data = await response.json();
            
            if (data.success) {
                this.displayStaff(data.staff);
            } else {
                this.showError('Failed to load staff data');
            }
        } catch (error) {
            this.showError('Error loading staff data: ' + error.message);
        }
    }

    displayStaff(staff) {
        let html = `
            <div class="table-responsive">
                <h5 class="text-gold mb-3">Staff Management</h5>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Clients Brought</th>
                            <th>Total Incentive</th>
                            <th>Monthly Target</th>
                            <th>Current Month</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (staff && staff.length > 0) {
            staff.forEach(member => {
                const statusClass = member.status === 'active' ? 'success' : member.status === 'inactive' ? 'warning' : 'danger';
                html += `
                    <tr>
                        <td>${member.id}</td>
                        <td>${member.staff_name}</td>
                        <td>${member.designation}</td>
                        <td>${member.phone}</td>
                        <td>${member.email}</td>
                        <td><span class="badge bg-primary">${member.clients_brought}</span></td>
                        <td><span class="text-gold">₹${parseFloat(member.total_incentive).toLocaleString()}</span></td>
                        <td>${member.monthly_target}</td>
                        <td><span class="badge bg-info">${member.current_month_clients}</span></td>
                        <td><span class="badge bg-${statusClass}">${member.status}</span></td>
                        <td>${member.joined_date}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" onclick="admin.editStaff(${member.id})">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="admin.deleteStaff(${member.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="12" class="text-center text-muted">No staff found</td></tr>';
        }

        html += '</tbody></table></div>';
        document.getElementById('dataDisplay').innerHTML = html;
    }

    async viewStaffPerformance() {
        this.showLoading('Loading staff performance data...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=staff_performance');
            const data = await response.json();
            
            if (data.success) {
                this.displayStaffPerformance(data.performance);
            } else {
                this.showError('Failed to load staff performance data');
            }
        } catch (error) {
            this.showError('Error loading staff performance data: ' + error.message);
        }
    }

    displayStaffPerformance(performance) {
        let html = `
            <div class="row">
                <div class="col-12">
                    <h5 class="text-gold mb-3">Staff Performance Report</h5>
                </div>
            </div>
            <div class="row">
        `;

        if (performance && performance.length > 0) {
            performance.forEach(member => {
                const achievementRate = member.monthly_target > 0 ? (member.current_month_clients / member.monthly_target * 100).toFixed(1) : 0;
                const statusClass = achievementRate >= 100 ? 'success' : achievementRate >= 75 ? 'warning' : 'danger';
                
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-dark text-white h-100">
                            <div class="card-body">
                                <h6 class="card-title text-gold">${member.staff_name}</h6>
                                <p class="card-text small text-muted">${member.designation}</p>
                                <div class="mb-2">
                                    <small>Clients This Month: <span class="text-info">${member.current_month_clients}/${member.monthly_target}</span></small>
                                    <div class="progress mt-1" style="height: 6px;">
                                        <div class="progress-bar bg-${statusClass}" style="width: ${Math.min(achievementRate, 100)}%"></div>
                                    </div>
                                    <small class="text-${statusClass}">${achievementRate}% achieved</small>
                                </div>
                                <div class="mb-2">
                                    <small>Total Clients: <span class="text-primary">${member.clients_brought}</span></small>
                                </div>
                                <div class="mb-2">
                                    <small>Total Incentive: <span class="text-gold">₹${parseFloat(member.total_incentive).toLocaleString()}</span></small>
                                </div>
                                <div class="mt-3">
                                    <span class="badge bg-${member.status === 'active' ? 'success' : 'secondary'}">${member.status}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html += '<div class="col-12"><p class="text-center text-muted">No staff performance data found</p></div>';
        }

        html += '</div>';
        document.getElementById('dataDisplay').innerHTML = html;
    }

    async viewIncentives() {
        this.showLoading('Loading incentives report...');
        try {
            const response = await fetch('backend/api/admin_api.php?action=incentives_report');
            const data = await response.json();
            
            if (data.success) {
                this.displayIncentives(data.incentives);
            } else {
                this.showError('Failed to load incentives report');
            }
        } catch (error) {
            this.showError('Error loading incentives report: ' + error.message);
        }
    }

    displayIncentives(incentives) {
        let html = `
            <div class="table-responsive">
                <h5 class="text-gold mb-3">Incentives Report</h5>
                <div class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h6>Total Incentives Paid</h6>
                                    <h4>₹${parseFloat(incentives.total_incentives || 0).toLocaleString()}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h6>Total Clients Brought</h6>
                                    <h4>${incentives.total_clients || 0}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h6>Active Staff</h6>
                                    <h4>${incentives.active_staff || 0}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h6>Avg Incentive per Client</h6>
                                    <h4>₹${incentives.avg_incentive_per_client ? parseFloat(incentives.avg_incentive_per_client).toLocaleString() : 0}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Designation</th>
                            <th>Clients Brought</th>
                            <th>Total Incentive</th>
                            <th>Avg per Client</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (incentives.staff_details && incentives.staff_details.length > 0) {
            incentives.staff_details.forEach(member => {
                const avgPerClient = member.clients_brought > 0 ? (parseFloat(member.total_incentive) / member.clients_brought).toFixed(0) : 0;
                html += `
                    <tr>
                        <td>${member.staff_name}</td>
                        <td>${member.designation}</td>
                        <td><span class="badge bg-primary">${member.clients_brought}</span></td>
                        <td><span class="text-gold">₹${parseFloat(member.total_incentive).toLocaleString()}</span></td>
                        <td><span class="text-info">₹${avgPerClient}</span></td>
                        <td><span class="badge bg-${member.status === 'active' ? 'success' : 'secondary'}">${member.status}</span></td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="6" class="text-center text-muted">No incentives data found</td></tr>';
        }

        html += '</tbody></table></div>';
        document.getElementById('dataDisplay').innerHTML = html;
    }

    async editStaff(id) {
        this.showLoading('Loading staff details...');
        try {
            const response = await fetch(`backend/api/admin_api.php?action=staff_details&id=${id}`);
            const data = await response.json();
            
            if (data.success) {
                this.showStaffEditForm(data.staff);
            } else {
                this.showError('Failed to load staff details');
            }
        } catch (error) {
            this.showError('Error loading staff details: ' + error.message);
        }
    }

    showStaffEditForm(staff) {
        const html = `
            <h5 class="text-gold mb-3">Edit Staff Member</h5>
            <form id="editStaffForm" onsubmit="admin.saveStaffEdit(event, ${staff.id})">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Staff Name *</label>
                        <input type="text" class="form-control custom-input" name="staff_name" value="${staff.staff_name}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Designation *</label>
                        <input type="text" class="form-control custom-input" name="designation" value="${staff.designation}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Phone *</label>
                        <input type="text" class="form-control custom-input" name="phone" value="${staff.phone}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Email *</label>
                        <input type="email" class="form-control custom-input" name="email" value="${staff.email}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Clients Brought</label>
                        <input type="number" class="form-control custom-input" name="clients_brought" value="${staff.clients_brought}" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Total Incentive (₹)</label>
                        <input type="number" class="form-control custom-input" name="total_incentive" value="${staff.total_incentive}" step="0.01" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Monthly Target</label>
                        <input type="number" class="form-control custom-input" name="monthly_target" value="${staff.monthly_target}" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Current Month Clients</label>
                        <input type="number" class="form-control custom-input" name="current_month_clients" value="${staff.current_month_clients}" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Status</label>
                        <select class="form-select custom-input" name="status">
                            <option value="active" ${staff.status === 'active' ? 'selected' : ''}>Active</option>
                            <option value="inactive" ${staff.status === 'inactive' ? 'selected' : ''}>Inactive</option>
                            <option value="terminated" ${staff.status === 'terminated' ? 'selected' : ''}>Terminated</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Joined Date</label>
                        <input type="date" class="form-control custom-input" name="joined_date" value="${staff.joined_date}" required>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold">Save Changes</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="admin.hideForm()">Cancel</button>
                </div>
            </form>
        `;
        
        document.getElementById('formContainer').innerHTML = html;
        document.getElementById('formContainer').classList.remove('d-none');
        document.getElementById('dataDisplay').classList.add('d-none');
    }

    async saveStaffEdit(event, id) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const response = await fetch(`backend/api/admin_api.php?action=update_staff&id=${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess('Staff member updated successfully');
                this.hideForm();
                this.viewStaff(); 
            } else {
                this.showError('Failed to update staff member: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error updating staff member: ' + error.message);
        }
    }

    async deleteStaff(id) {
        if (!confirm('Are you sure you want to delete this staff member? This action cannot be undone!')) return;
        
        try {
            const response = await fetch(`backend/api/admin_api.php?action=delete_staff&id=${id}`, {
                method: 'POST'
            });
            const data = await response.json();
            
            if (data.success) {
                this.showSuccess('Staff member deleted successfully');
                this.viewStaff(); 
            } else {
                this.showError('Failed to delete staff member');
            }
        } catch (error) {
            this.showError('Error deleting staff member: ' + error.message);
        }
    }
}


function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'backend/auth/logout.php';
    }
}


let admin;
document.addEventListener('DOMContentLoaded', function() {
    admin = new AdminPanel();
});
