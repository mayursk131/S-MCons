# Admin Guide

## Overview

The S&M Infra admin panel provides comprehensive management capabilities for your website's content, user inquiries, feedback, and system settings.

## Access

### Login Credentials
- **URL**: `/admin-login.html`
- **Access Code**: `CLASSIFIED123` (change immediately after first login)
- **Form Action**: `backend/auth/admin_auth.php`
- **Logout**: `backend/auth/logout.php`

### Security
- Session-based authentication
- Automatic logout after inactivity
- Secure password storage (recommended to update)

## Dashboard Features

### 1. Statistics Overview
- Total inquiries count
- Feedback submissions
- Project views
- System health status

### 2. Inquiry Management
- View all customer inquiries
- Search and filter capabilities
- Export to CSV/Excel
- Mark as resolved/pending

### 3. Feedback Management
- View customer feedback
- Rating analysis
- Subject categorization
- Response tracking

### 4. Database Operations
- Direct SQL table access
- Add/Edit/Delete records
- Data export functionality
- Backup operations

## Navigation Guide

### Main Menu
1. **Dashboard** - Overview and statistics
2. **Inquiries** - Customer contact form submissions
3. **Feedback** - User feedback and ratings
4. **Data Viewer** - Direct database access
5. **Settings** - System configuration

### Quick Actions
- **Clear Data** - Remove all records (use with caution)
- **Export Data** - Download table data
- **Refresh** - Update current view
- **Add Record** - Create new entries

## Language Support

The admin panel supports multiple languages:
- English (default)
- Hindi
- Kannada

### Language Switching
1. Click the language selector in the header
2. Choose your preferred language
3. Interface updates automatically

## Data Management

### Viewing Records
1. Select table from dropdown
2. Click "LOAD TABLE"
3. Browse through records
4. Use pagination for large datasets

### Adding Records
1. Select table
2. Click "ADD RECORD"
3. Fill in the form fields
4. Submit to save

### Editing Records
1. Load the table
2. Click edit icon on desired row
3. Modify the fields
4. Save changes

### Deleting Records
1. Select the record
2. Click delete option
3. Confirm deletion

## Export Features

### Supported Formats
- CSV (Comma Separated Values)
- JSON (JavaScript Object Notation)
- Excel (via CSV import)

### Export Steps
1. Load the desired table
2. Click "EXPORT DATA"
3. Choose format
4. Download file

## Security Best Practices

### Password Management
- Change default password immediately
- Use strong passwords (8+ characters, mixed case, numbers, symbols)
- Update passwords regularly

### Session Security
- Logout after each session
- Clear browser cache if using shared computers
- Monitor login attempts

### Data Protection
- Regular backups
- Secure database credentials
- Limit admin access to authorized personnel

## Troubleshooting

### Common Issues

#### Login Problems
- **Issue**: Cannot login
- **Solution**: Check credentials, clear browser cache, verify session

#### Data Not Loading
- **Issue**: Tables not displaying data
- **Solution**: Check database connection, verify table exists

#### Export Errors
- **Issue**: Download fails
- **Solution**: Check file permissions, ensure data exists

#### Language Issues
- **Issue**: Translation not working
- **Solution**: Check language files, verify JSON format

### Error Messages

#### "Connection Failed"
- Database credentials incorrect
- Database server not running
- Network connectivity issues

#### "Table Not Found"
- Run setup.php to create tables
- Check database name
- Verify table names

#### "Access Denied"
- Session expired
- Incorrect permissions
- Login required

## Maintenance

### Regular Tasks
1. **Weekly**: Review new inquiries and feedback
2. **Monthly**: Export and backup data
3. **Quarterly**: Review security settings
4. **Annually**: Password audit and updates

### Performance Optimization
- Clear old records periodically
- Optimize database tables
- Monitor storage usage
- Update software dependencies

## API Integration

### Gemini AI Chat
- **Endpoint**: `/backend/server.js`
- **Port**: 3000
- **Usage**: Customer support automation
- **Setup**: Configure API key in `.env`

### Form Endpoints
- **Inquiries**: `backend/api/submit.php`
- **Feedback**: `backend/api/feedback.php`
- **Admin API**: `backend/api/admin_api.php`
- **Authentication**: `backend/auth/`

## Support

### Technical Support
- **Email**: tech@sm-infra.in
- **Phone**: +91 7888012200
- **Hours**: 9 AM - 6 PM IST

### Documentation
- [API Documentation](api.md)
- [Deployment Guide](deployment.md)
- [README](../README.md)

---

## Quick Reference

| Feature | Location | Action |
|---------|----------|--------|
| Login | `/admin-login.html` | Enter credentials |
| Dashboard | `/admin.php` | View overview |
| Inquiries | Dashboard → Inquiries | Manage contacts |
| Feedback | Dashboard → Feedback | View ratings |
| Data Export | Dashboard → Data Viewer | Download data |
| Settings | Dashboard header | Configure system |
| API Endpoints | `backend/api/` | Admin operations |
| Auth | `backend/auth/` | Login/logout |

**Last Updated**: March 2026
**Version**: 1.0.0
**Maintained by**: S&M Infra Tech Team
