# Time2Eat - Setup Guide

This guide will help you set up the Time2Eat food delivery application on your local development environment or production server.

## 📋 Requirements

- **PHP 8.0+** with extensions:
  - PDO (MySQL)
  - JSON
  - mbstring
  - cURL
  - GD
- **MySQL 5.7+** or **MariaDB 10.3+**
- **Apache** with mod_rewrite enabled
- **Composer** (for dependency management)

## 🚀 Quick Setup (WAMP/XAMPP)

### 1. Download and Extract
- Download the project files
- Extract to your web server directory (e.g., `C:\wamp64\www\Time2Eat`)

### 2. Configure Environment
```bash
# Copy the environment file
cp .env.example .env

# Edit .env with your database credentials
DB_HOST=localhost
DB_NAME=time2eat
DB_USER=root
DB_PASS=
```

### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install
```

### 4. Set Up Database
```bash
# Run the database installation script
php scripts/install_database.php
```

### 5. Set Permissions (Linux/Mac)
```bash
chmod -R 755 storage/
chmod -R 755 logs/
chmod -R 644 .env
```

### 6. Access Application
- Open your browser and go to: `http://localhost/Time2Eat`
- Admin login: `admin@time2eat.com` / `admin123`

## 🔧 Manual Setup

### 1. Database Setup
Create a MySQL database and import the schema:

```sql
CREATE DATABASE time2eat CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Environment Configuration
Update your `.env` file with the correct values:

```env
# Database
DB_HOST=localhost
DB_NAME=time2eat
DB_USER=your_username
DB_PASS=your_password

# Application
APP_NAME="Time2Eat"
APP_URL=http://localhost/Time2Eat
APP_ENV=development
APP_DEBUG=true

# Security
APP_KEY=your-secret-key-here
JWT_SECRET=your-jwt-secret-key

# APIs (Optional)
MAP_API_KEY=your-google-maps-api-key
STRIPE_PUBLIC_KEY=your-stripe-public-key
STRIPE_SECRET_KEY=your-stripe-secret-key
```

### 3. Apache Configuration
Ensure your `.htaccess` file is working and mod_rewrite is enabled:

```apache
# Enable mod_rewrite in Apache
a2enmod rewrite

# Restart Apache
systemctl restart apache2
```

## 📁 Project Structure

```
Time2Eat/
├── config/                 # Configuration files
│   ├── config.php         # Main configuration
│   └── database.php       # Database connection
├── dashboards/            # Role-specific dashboard views
├── logs/                  # Application logs
├── public/                # Static assets
│   ├── css/              # Stylesheets
│   ├── js/               # JavaScript files
│   └── images/           # Images and media
├── scripts/               # Utility scripts
│   └── install_database.php
├── src/                   # Application source code
│   ├── controllers/       # Controllers (MVC)
│   ├── core/             # Core classes (Router, Controller, Model)
│   ├── helpers/          # Helper functions
│   ├── middleware/       # Middleware classes
│   ├── models/           # Models (MVC)
│   ├── services/         # Service classes
│   ├── traits/           # Reusable traits
│   └── views/            # Views (MVC)
│       ├── auth/         # Authentication views
│       ├── errors/       # Error pages
│       ├── home/         # Public pages
│       └── layouts/      # Layout templates
├── storage/               # File storage and cache
├── tests/                 # PHPUnit tests
├── .env.example          # Environment template
├── .htaccess             # Apache configuration
├── composer.json         # PHP dependencies
├── index.php             # Application entry point
├── manifest.json         # PWA manifest
└── sw.js                 # Service worker
```

## 🎨 Features Included

### ✅ Completed (Step 1)
- **MVC Architecture**: Clean separation of concerns
- **Modern Design**: Tailwind CSS with glassmorphism effects
- **Mobile-First**: Responsive design optimized for mobile
- **PWA Ready**: Manifest and service worker included
- **Security**: Input sanitization, CSRF protection, rate limiting
- **Authentication**: Secure login/registration system
- **Database**: MySQL with proper relationships
- **Routing**: Clean URLs with .htaccess
- **Error Handling**: Custom 404/500 pages
- **Configuration**: Environment-based configuration

### 🔄 Next Steps (Following README.md)
- User roles and permissions (Customer, Vendor, Rider, Admin)
- Restaurant management system
- Menu and order management
- Payment integration (Mobile Money, Orange Money, Stripe)
- Real-time order tracking
- Delivery rider system
- Admin dashboard and analytics
- Email/SMS notifications
- File upload system
- API endpoints for mobile app

## 🛠️ Development Commands

```bash
# Install dependencies
composer install

# Run tests
composer test

# Code analysis
composer analyse

# Code formatting
composer cs-fix

# Start development server
composer serve
# or
php -S localhost:8000 -t public

# Database operations
php scripts/install_database.php
php scripts/seed_database.php
```

## 🔒 Security Considerations

1. **Change default credentials** after installation
2. **Update APP_KEY** in .env file
3. **Set proper file permissions** (755 for directories, 644 for files)
4. **Enable HTTPS** in production
5. **Configure firewall** rules
6. **Regular backups** of database and files
7. **Keep dependencies updated**

## 📱 PWA Features

- **Offline Support**: Service worker caches essential files
- **Install Prompt**: Users can install as native app
- **Push Notifications**: Real-time order updates
- **Background Sync**: Offline order synchronization

## 🐛 Troubleshooting

### Common Issues

1. **500 Internal Server Error**
   - Check Apache error logs
   - Verify .htaccess is working
   - Ensure mod_rewrite is enabled
   - Check file permissions

2. **Database Connection Failed**
   - Verify database credentials in .env
   - Ensure MySQL service is running
   - Check database exists

3. **Composer Dependencies**
   - Run `composer install`
   - Check PHP version compatibility
   - Verify required extensions are installed

4. **File Permissions**
   - Set proper permissions on storage/ and logs/
   - Ensure web server can write to these directories

### Getting Help

- Check the main README.md for detailed implementation steps
- Review error logs in logs/ directory
- Verify configuration in .env file
- Test database connection with scripts/install_database.php

## 📞 Support

For technical support or questions:
- Email: dev@time2eat.com
- Documentation: See main README.md
- Issues: Check error logs and configuration

---

**Next Step**: Follow the main README.md file to continue with Step 2 and implement the complete food delivery system with all features.
