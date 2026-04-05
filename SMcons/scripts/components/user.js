
class UserPanel {
    constructor() {
        this.init();
        this.loadStats();
    }

    init() {
        console.log('User Panel Initialized');
        this.setupEventListeners();
        this.initializeLanguage();
    }

    initializeLanguage() {
        
        if (typeof languageManager !== 'undefined') {
            
        }
    }

    updateLanguage() {
        
        this.updateStatsDisplay();
    }

    updateStatsDisplay() {
        
    }

    setupEventListeners() {
        
        setInterval(() => this.loadStats(), 30000);
    }

    async loadStats() {
        try {
            const response = await fetch('backend/api/user_api.php?action=stats');
            const data = await response.json();

            if (data.success) {
                document.getElementById('myBookings').textContent = data.bookings || 0;
                document.getElementById('myInquiries').textContent = data.inquiries || 0;
                document.getElementById('availableProperties').textContent = data.properties || 0;
                document.getElementById('myFeedback').textContent = data.feedback || 0;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    
    async viewProperties() {
        this.showLoading('Loading available properties...');
        document.getElementById('contentTitle').textContent = 'Available Properties';

        try {
            const response = await fetch('backend/api/user_api.php?action=properties');
            const data = await response.json();

            if (data.success) {
                this.displayProperties(data.properties);
            } else {
                this.showError('Failed to load properties');
            }
        } catch (error) {
            this.showError('Error loading properties: ' + error.message);
        }
    }

    displayProperties(properties) {
        let html = `
            <div class="row">
        `;

        if (properties && properties.length > 0) {
            properties.forEach(property => {
                const statusClass = property.status === 'available' ? 'success' : 'warning';
                html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-dark text-white h-100">
                            <div class="card-body">
                                <h6 class="card-title text-gold">${property.property_name}</h6>
                                <p class="card-text small text-muted">${property.location}</p>
                                <div class="mb-2">
                                    <strong>₹${parseFloat(property.price).toLocaleString()}</strong>
                                </div>
                                <div class="mb-2">
                                    <small>Type: ${property.property_type}</small><br>
                                    <small>Size: ${property.size} sq ft</small>
                                </div>
                                <div class="mb-3">
                                    <span class="badge bg-${statusClass}">${property.status}</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-gold btn-sm" onclick="user.viewPropertyDetails(${property.id})">View Details</button>
                                    <button class="btn btn-outline-gold btn-sm" onclick="user.bookProperty(${property.id})">Book Visit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            html += '<div class="col-12"><p class="text-center text-muted">No properties available at the moment.</p></div>';
        }

        html += '</div>';
        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('actionButtons').classList.remove('d-none');
    }

    async viewPropertyDetails(propertyId) {
        this.showLoading('Loading property details...');

        try {
            const response = await fetch(`backend/api/user_api.php?action=property_details&id=${propertyId}`);
            const data = await response.json();

            if (data.success) {
                this.displayPropertyDetails(data.property);
            } else {
                this.showError('Failed to load property details');
            }
        } catch (error) {
            this.showError('Error loading property details: ' + error.message);
        }
    }

    displayPropertyDetails(property) {
        const html = `
            <div class="row">
                <div class="col-md-8">
                    <h4 class="text-gold mb-3">${property.property_name}</h4>
                    <div class="mb-3">
                        <strong>Location:</strong> ${property.location}
                    </div>
                    <div class="mb-3">
                        <strong>Price:</strong> ₹${parseFloat(property.price).toLocaleString()}
                    </div>
                    <div class="mb-3">
                        <strong>Type:</strong> ${property.property_type}
                    </div>
                    <div class="mb-3">
                        <strong>Size:</strong> ${property.size} sq ft
                    </div>
                    <div class="mb-3">
                        <strong>Description:</strong><br>
                        ${property.description || 'No description available.'}
                    </div>
                    <div class="mb-3">
                        <strong>Amenities:</strong> ${property.amenities || 'Not specified'}
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-${property.status === 'available' ? 'success' : 'warning'}">${property.status}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h6 class="text-gold">Quick Actions</h6>
                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-gold" onclick="user.bookProperty(${property.id})">Book Property Visit</button>
                                <button class="btn btn-outline-gold" onclick="user.sendPropertyInquiry(${property.id})">Send Inquiry</button>
                                <button class="btn btn-outline-gold" onclick="user.viewProperties()">← Back to Properties</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('contentTitle').textContent = 'Property Details';
    }

    
    async viewBookings() {
        this.showLoading('Loading your bookings...');
        document.getElementById('contentTitle').textContent = 'My Bookings';

        try {
            const response = await fetch('backend/api/user_api.php?action=my_bookings');
            const data = await response.json();

            if (data.success) {
                this.displayBookings(data.bookings);
            } else {
                this.showError('Failed to load bookings');
            }
        } catch (error) {
            this.showError('Error loading bookings: ' + error.message);
        }
    }

    displayBookings(bookings) {
        let html = `
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Property</th>
                            <th>Visit Date</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (bookings && bookings.length > 0) {
            bookings.forEach(booking => {
                const statusClass = booking.status === 'confirmed' ? 'success' : booking.status === 'pending' ? 'warning' : 'danger';
                html += `
                    <tr>
                        <td>${booking.id}</td>
                        <td>${booking.property_name || 'N/A'}</td>
                        <td>${booking.visit_date || 'N/A'}</td>
                        <td><span class="badge bg-${statusClass}">${booking.status}</span></td>
                        <td>${booking.created_at}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning" onclick="user.viewBookingDetails(${booking.id})">View</button>
                            ${booking.status === 'pending' ? `<button class="btn btn-sm btn-outline-danger" onclick="user.cancelBooking(${booking.id})">Cancel</button>` : ''}
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="6" class="text-center text-muted">No bookings found.</td></tr>';
        }

        html += '</tbody></table></div>';
        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('actionButtons').classList.remove('d-none');
    }

    bookProperty(propertyId = null) {
        const html = `
            <h5 class="text-gold mb-3">Book Property Visit</h5>
            <form id="bookingForm" onsubmit="user.submitBooking(event)">
                <input type="hidden" name="property_id" value="${propertyId || ''}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Property ID</label>
                        <input type="number" class="form-control custom-input" name="property_id_input"
                               value="${propertyId || ''}" placeholder="Enter property ID" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Visit Date</label>
                        <input type="date" class="form-control custom-input" name="visit_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Visit Time</label>
                        <select class="form-control custom-input" name="visit_time" required>
                            <option value="">Select time</option>
                            <option value="10:00">10:00 AM</option>
                            <option value="11:00">11:00 AM</option>
                            <option value="12:00">12:00 PM</option>
                            <option value="14:00">2:00 PM</option>
                            <option value="15:00">3:00 PM</option>
                            <option value="16:00">4:00 PM</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Phone Number</label>
                        <input type="tel" class="form-control custom-input" name="phone" placeholder="Your phone number" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-light">Message (Optional)</label>
                        <textarea class="form-control custom-input" name="message" rows="3" placeholder="Any special requirements or questions..."></textarea>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold">Submit Booking</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="user.hideForm()">Cancel</button>
                </div>
            </form>
        `;

        document.getElementById('formContainer').innerHTML = html;
        document.getElementById('formContainer').classList.remove('d-none');
        document.getElementById('mainContent').classList.add('d-none');
        document.getElementById('contentTitle').textContent = 'Book Property Visit';
    }

    async submitBooking(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        data.action = 'book_property';

        try {
            const response = await fetch('backend/api/user_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess('Booking submitted successfully! We will contact you soon.');
                this.hideForm();
                this.viewBookings(); 
                this.loadStats(); 
            } else {
                this.showError('Failed to submit booking: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error submitting booking: ' + error.message);
        }
    }

    
    async viewInquiries() {
        this.showLoading('Loading your inquiries...');
        document.getElementById('contentTitle').textContent = 'My Inquiries';

        try {
            const response = await fetch('backend/api/user_api.php?action=my_inquiries');
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

    displayInquiries(inquiries) {
        let html = `
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        if (inquiries && inquiries.length > 0) {
            inquiries.forEach(inquiry => {
                const statusClass = inquiry.status === 'responded' ? 'success' : inquiry.status === 'pending' ? 'warning' : 'info';
                html += `
                    <tr>
                        <td>${inquiry.id}</td>
                        <td>${inquiry.subject || 'General Inquiry'}</td>
                        <td>${this.truncateText(inquiry.message, 50)}</td>
                        <td><span class="badge bg-${statusClass}">${inquiry.status}</span></td>
                        <td>${inquiry.timestamp}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="user.viewInquiryDetails(${inquiry.id})">View</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            html += '<tr><td colspan="6" class="text-center text-muted">No inquiries found.</td></tr>';
        }

        html += '</tbody></table></div>';
        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('actionButtons').classList.remove('d-none');
    }

    sendInquiry(propertyId = null) {
        const html = `
            <h5 class="text-gold mb-3">Send Inquiry</h5>
            <form id="inquiryForm" onsubmit="user.submitInquiry(event)">
                <input type="hidden" name="property_id" value="${propertyId || ''}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Subject</label>
                        <input type="text" class="form-control custom-input" name="subject"
                               placeholder="Inquiry subject" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label text-light">Phone Number</label>
                        <input type="tel" class="form-control custom-input" name="phone"
                               placeholder="Your phone number" required>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-light">Message</label>
                        <textarea class="form-control custom-input" name="message" rows="5"
                                  placeholder="Your inquiry message..." required></textarea>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold">Send Inquiry</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="user.hideForm()">Cancel</button>
                </div>
            </form>
        `;

        document.getElementById('formContainer').innerHTML = html;
        document.getElementById('formContainer').classList.remove('d-none');
        document.getElementById('mainContent').classList.add('d-none');
        document.getElementById('contentTitle').textContent = 'Send Inquiry';
    }

    async submitInquiry(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        data.action = 'send_inquiry';

        try {
            const response = await fetch('backend/api/user_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess('Inquiry sent successfully! We will respond soon.');
                this.hideForm();
                this.viewInquiries(); 
                this.loadStats(); 
            } else {
                this.showError('Failed to send inquiry: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error sending inquiry: ' + error.message);
        }
    }

    
    async viewProfile() {
        this.showLoading('Loading your profile...');
        document.getElementById('contentTitle').textContent = 'My Profile';

        try {
            const response = await fetch('backend/api/user_api.php?action=profile');
            const data = await response.json();

            if (data.success) {
                this.displayProfile(data.profile);
            } else {
                this.showError('Failed to load profile');
            }
        } catch (error) {
            this.showError('Error loading profile: ' + error.message);
        }
    }

    async editProfile() {
        this.showLoading('Loading profile editor...');
        try {
            const response = await fetch('backend/api/user_api.php?action=profile');
            const data = await response.json();

            if (!data.success) {
                this.showError('Failed to load profile details.');
                return;
            }

            const profile = data.profile;
            const html = `
                <h5 class="text-gold mb-3">Edit Profile</h5>
                <form id="editProfileForm" onsubmit="user.submitProfileUpdate(event)">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">Full Name</label>
                            <input type="text" name="full_name" class="form-control custom-input" value="${profile.full_name}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">Phone</label>
                            <input type="text" name="phone" class="form-control custom-input" value="${profile.phone}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">City</label>
                            <input type="text" name="city" class="form-control custom-input" value="${profile.city || ''}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">State</label>
                            <input type="text" name="state" class="form-control custom-input" value="${profile.state || ''}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-light">Pincode</label>
                            <input type="text" name="pincode" class="form-control custom-input" value="${profile.pincode || ''}">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-gold">Save Changes</button>
                        <button type="button" class="btn btn-outline-secondary" onclick="user.viewProfile()">Cancel</button>
                    </div>
                </form>
            `;

            document.getElementById('mainContent').innerHTML = html;
            document.getElementById('actionButtons').classList.add('d-none');
        } catch (error) {
            this.showError('Error loading profile editor: ' + error.message);
        }
    }

    async submitProfileUpdate(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        data.action = 'update_profile';

        try {
            const response = await fetch('backend/api/user_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();
            if (result.success) {
                this.showSuccess('Profile updated successfully');
                this.viewProfile();
            } else {
                this.showError('Update failed: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error updating profile: ' + error.message);
        }
    }

    changePassword() {
        const html = `
            <h5 class="text-gold mb-3">Change Password</h5>
            <form id="changePasswordForm" onsubmit="user.submitChangePassword(event)">
                <div class="mb-3">
                    <label class="form-label text-light">Current Password</label>
                    <input type="password" name="current_password" class="form-control custom-input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-light">New Password</label>
                    <input type="password" name="new_password" class="form-control custom-input" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-light">Confirm New Password</label>
                    <input type="password" name="confirm_new_password" class="form-control custom-input" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gold">Change Password</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="user.viewProfile()">Cancel</button>
                </div>
            </form>
        `;

        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('contentTitle').textContent = 'Change Password';
        document.getElementById('actionButtons').classList.add('d-none');
    }

    async submitChangePassword(event) {
        event.preventDefault();

        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData.entries());
        if (data.new_password !== data.confirm_new_password) {
            this.showError('New password and confirmation do not match');
            return;
        }

        data.action = 'change_password';

        try {
            const response = await fetch('backend/api/user_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(data)
            });

            const result = await response.json();
            if (result.success) {
                this.showSuccess('Password changed successfully');
                this.viewProfile();
            } else {
                this.showError('Password change failed: ' + (result.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error changing password: ' + error.message);
        }
    }

    async viewBookingDetails(bookingId) {
        this.showLoading('Loading booking details...');

        try {
            const response = await fetch(`backend/api/user_api.php?action=booking_details&id=${bookingId}`);
            const data = await response.json();
            if (!data.success) {
                this.showError('Failed to fetch booking details');
                return;
            }

            const b = data.booking;
            const html = `
                <h5 class="text-gold mb-3">Booking Details</h5>
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <p><strong>Property:</strong> ${b.property_name}</p>
                        <p><strong>Visit Date:</strong> ${b.visit_date}</p>
                        <p><strong>Visit Time:</strong> ${b.visit_time}</p>
                        <p><strong>Status:</strong> <span class="badge bg-${b.status === 'confirmed' ? 'success' : b.status === 'pending' ? 'warning' : 'danger'}">${b.status}</span></p>
                        <p><strong>Message:</strong> ${b.message || 'No message'}</p>
                        <p><strong>Created:</strong> ${b.created_at}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <button class="btn btn-outline-gold" onclick="user.viewBookings()">Back to bookings</button>
                    ${b.status === 'pending' ? `<button class="btn btn-outline-danger" onclick="user.cancelBooking(${b.id})">Cancel Booking</button>` : ''}
                </div>
            `;

            document.getElementById('mainContent').innerHTML = html;
        } catch (error) {
            this.showError('Error fetching booking details: ' + error.message);
        }
    }

    async cancelBooking(bookingId) {
        if (!confirm('Cancel this booking?')) return;

        try {
            const response = await fetch('backend/api/user_api.php?action=cancel_booking&id=' + bookingId, { method: 'POST' });
            const data = await response.json();

            if (data.success) {
                this.showSuccess('Booking cancelled successfully');
                this.viewBookings();
            } else {
                this.showError('Cancel failed: ' + (data.error || 'Unknown error'));
            }
        } catch (error) {
            this.showError('Error cancelling booking: ' + error.message);
        }
    }

    displayProfile(profile) {
        const html = `
            <div class="row">
                <div class="col-md-8">
                    <h4 class="text-gold mb-3">Profile Information</h4>
                    <div class="card bg-dark text-white mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Username:</strong> ${profile.username}</p>
                                    <p><strong>Full Name:</strong> ${profile.full_name}</p>
                                    <p><strong>Email:</strong> ${profile.email}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Phone:</strong> ${profile.phone}</p>
                                    <p><strong>User Type:</strong> ${profile.user_type}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-${profile.status === 'active' ? 'success' : 'warning'}">${profile.status}</span></p>
                                </div>
                            </div>
                            <p><strong>Member Since:</strong> ${profile.created_at}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <h6 class="text-gold">Account Actions</h6>
                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-gold" onclick="user.editProfile()">Edit Profile</button>
                                <button class="btn btn-outline-gold" onclick="user.changePassword()">Change Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('actionButtons').classList.remove('d-none');
    }

    
    chatWithAI() {
        const html = `
            <div class="row">
                <div class="col-12">
                    <h4 class="text-gold mb-3">AI Assistant</h4>
                    <div class="card bg-dark text-white">
                        <div class="card-body">
                            <div id="chatMessages" class="mb-3" style="height: 300px; overflow-y: auto; border: 1px solid #444; padding: 10px; background: #111;">
                                <p class="text-muted">Welcome! How can I help you with your property needs today?</p>
                            </div>
                            <div class="input-group">
                                <input type="text" id="chatInput" class="form-control custom-input"
                                       placeholder="Ask me anything about properties, loans, or our services...">
                                <button class="btn btn-gold" onclick="user.sendChatMessage()">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('mainContent').innerHTML = html;
        document.getElementById('contentTitle').textContent = 'AI Assistant';

        
        document.getElementById('chatInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                user.sendChatMessage();
            }
        });
    }

    async sendChatMessage() {
        const input = document.getElementById('chatInput');
        const message = input.value.trim();

        if (!message) return;

        
        this.addChatMessage('user', message);
        input.value = '';

        try {
            const response = await fetch('backend/api/user_api.php?action=chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ message: message })
            });

            const data = await response.json();

            if (data.success) {
                this.addChatMessage('ai', data.response);
            } else {
                this.addChatMessage('ai', 'Sorry, I encountered an error. Please try again.');
            }
        } catch (error) {
            this.addChatMessage('ai', 'Sorry, I\'m having trouble connecting. Please try again later.');
        }
    }

    addChatMessage(sender, message) {
        const chatMessages = document.getElementById('chatMessages');
        const messageDiv = document.createElement('div');
        messageDiv.className = `mb-2 ${sender === 'user' ? 'text-end' : ''}`;
        messageDiv.innerHTML = `
            <small class="text-muted">${sender === 'user' ? 'You' : 'AI Assistant'}:</small><br>
            <span class="${sender === 'user' ? 'text-gold' : 'text-light'}">${message}</span>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    
    truncateText(text, maxLength) {
        if (!text) return 'N/A';
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    showLoading(message) {
        document.getElementById('mainContent').innerHTML = `
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
        this.showAlert(message, 'danger', true);
    }

    showAlert(message, type, replace = false) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        const mainContent = document.getElementById('mainContent');

        if (replace) {
            mainContent.innerHTML = alertHtml;
        } else {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = alertHtml;
            mainContent.prepend(tempDiv.firstElementChild);
        }

        
        const actionButtons = document.getElementById('actionButtons');
        if (actionButtons) {
            actionButtons.classList.remove('d-none');
        }
    }

    hideForm() {
        document.getElementById('formContainer').classList.add('d-none');
        document.getElementById('mainContent').classList.remove('d-none');
    }

    refreshData() {
        
        const title = document.getElementById('contentTitle').textContent;
        switch (title) {
            case 'Available Properties':
                this.viewProperties();
                break;
            case 'My Bookings':
                this.viewBookings();
                break;
            case 'My Inquiries':
                this.viewInquiries();
                break;
            case 'My Profile':
                this.viewProfile();
                break;
            default:
                this.loadStats();
        }
    }
}


function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'backend/auth/logout.php';
    }
}


let user;
document.addEventListener('DOMContentLoaded', function() {
    user = new UserPanel();
});