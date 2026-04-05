# Deployment Guide

## Environment Setup

### 1. Server Requirements
- PHP 8.0+ 
- Node.js 16+
- MySQL 8.0+
- Apache/Nginx web server

### 2. Environment Configuration
```bash
# Copy environment template
cp .env.example .env

# Edit the .env file with your actual values
nano .env
```

### 3. Install Dependencies
```bash
# Node.js dependencies
npm install

# PHP dependencies (if using Composer)
composer install
```

### 4. Database Setup
```sql
CREATE DATABASE sm_infra;
CREATE USER 'sm_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON sm_infra.* TO 'sm_user'@'localhost';
FLUSH PRIVILEGES;
```

### 5. File Permissions
```bash
# Set appropriate permissions
chmod -R 755 .
chmod -R 777 assets/uploads/
chmod -R 777 backend/config/
```

### 6. Web Server Configuration

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteBase /SMcons/

# Redirect to index.php
RewriteRule ^$ index.php [L]

# Handle other requests
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ src/pages/$1 [L]
```

#### Nginx
```nginx
location /SMcons/ {
    try_files $uri $uri/ /SMcons/index.php;
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

### 7. Start Services
```bash
# Start Node.js server
cd backend && npm start

# Start PHP built-in server (for development)
php -S localhost:8000
```

## Production Considerations

1. **Security**: Ensure all API keys are stored in environment variables
2. **HTTPS**: Configure SSL certificates
3. **Backup**: Set up regular database backups
4. **Monitoring**: Implement error logging and monitoring
5. **CDN**: Consider using CDN for static assets

## Troubleshooting

### Common Issues
- **404 Errors**: Check file paths and web server configuration
- **Database Connection**: Verify credentials in .env file
- **API Errors**: Ensure Gemini API key is valid and has sufficient quota
- **Permission Issues**: Check file and folder permissions

### Log Locations
- Apache: `/var/log/apache2/error.log`
- Nginx: `/var/log/nginx/error.log`
- Node.js: Console output or configured log file
- PHP: `/var/log/php_errors.log`
