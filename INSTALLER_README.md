# Time2Eat Installation System

## Overview

Time2Eat includes a comprehensive, self-deleting installer system that works on any hosting environment. The installer automatically handles database setup, configuration, and initial data population.

## 🚀 Quick Start

1. **Upload Files**: Upload all Time2Eat files to your web server
2. **Set Permissions**: Ensure proper file permissions (755 for directories, 644 for files)
3. **Run Installer**: Navigate to `http://your-domain.com/install.php`
4. **Follow Wizard**: Complete the 6-step installation process
5. **Delete Installer**: Use the cleanup option for security

## 📁 Installer Files

```
install.php                 # Main installer script
install_steps/             # Installation step templates
├── step1_requirements.php # System requirements check
├── step2_database.php     # Database configuration
├── step3_setup.php        # Database setup and import
├── step4_admin.php        # Admin account creation
├── step5_config.php       # Final configuration
└── step6_complete.php     # Installation completion
database/
├── data.sql              # Complete database schema
├── sample_data.sql       # Sample data for testing
└── schema.sql            # Existing schema file
docs/
└── INSTALLATION_GUIDE.md # Detailed installation guide
```

## 🔧 Installation Process

### Step 1: System Requirements Check
- **PHP Version**: Validates PHP 8.0+
- **Extensions**: Checks required PHP extensions
- **Permissions**: Verifies file system permissions
- **Environment**: Tests server compatibility

**Required Extensions:**
- `pdo`, `pdo_mysql` - Database connectivity
- `mbstring` - Multi-byte string support
- `openssl` - Security functions
- `json` - JSON processing
- `curl` - HTTP requests
- `gd` - Image processing

### Step 2: Database Configuration
- **Connection Testing**: Validates database credentials
- **Database Creation**: Creates database if it doesn't exist
- **User Permissions**: Verifies database user privileges
- **Compatibility**: Checks MySQL/MariaDB version

**Supported Databases:**
- MySQL 5.7+
- MariaDB 10.2+
- Percona Server 5.7+

### Step 3: Database Setup
- **Schema Import**: Creates all database tables
- **Indexes**: Sets up performance indexes
- **Triggers**: Installs automatic calculation triggers
- **Sample Data**: Optionally imports demo content

**Database Features:**
- 25+ tables with full relationships
- Automatic rating calculations
- Order total updates
- Affiliate commission tracking
- Performance optimization indexes

### Step 4: Admin Account Creation
- **Security**: Strong password validation
- **Verification**: Email format validation
- **Permissions**: Full admin privileges setup
- **Authentication**: Secure password hashing

### Step 5: Final Configuration
- **Environment File**: Creates `.env` configuration
- **Security Keys**: Generates encryption keys
- **Directories**: Creates required folders
- **Permissions**: Sets proper access rights

### Step 6: Installation Complete
- **Verification**: Confirms successful installation
- **Cleanup**: Option to delete installer files
- **Next Steps**: Guidance for post-installation
- **Security**: Recommendations for production

## 🗄️ Database Schema

### Core Tables
- **users** - Multi-role user management
- **restaurants** - Restaurant information and settings
- **menu_items** - Product catalog with variants
- **orders** - Complete order tracking
- **payments** - Payment processing and history
- **reviews** - Rating and review system

### Advanced Features
- **affiliates** - Referral program management
- **analytics** - User behavior tracking
- **daily_stats** - Performance metrics
- **rider_locations** - Real-time GPS tracking
- **popup_notifications** - Admin messaging system
- **coupons** - Discount code management

### Automatic Calculations
- Restaurant ratings from reviews
- Order totals from line items
- Affiliate commissions
- Daily statistics aggregation
- Inventory tracking

## 🎯 Sample Data

The installer includes comprehensive sample data:

### Restaurants (6 establishments)
- **Mama Grace Kitchen** - African Cuisine
- **Quick Bites Express** - Fast Food
- **Golden Dragon Chinese** - Chinese Food
- **Bamenda Pizza Corner** - Pizza & Italian
- **Healthy Harvest** - Healthy Options
- **BBQ Masters** - Grilled & BBQ

### Menu Items (20+ dishes)
- Traditional Cameroonian dishes (Ndolé, Eru, Jollof Rice)
- International cuisine options
- Beverages and desserts
- Pricing in XAF (Cameroon Francs)

### Users & Roles
- Sample customers with order history
- Restaurant vendors with complete profiles
- Delivery riders with schedules
- Admin account (created during installation)

### System Data
- 8 food categories with icons
- Site settings and configuration
- Payment methods and coupons
- Reviews and ratings
- Analytics and statistics

## 🔒 Security Features

### Installation Security
- **Self-Deletion**: Installer removes itself after completion
- **Permission Checks**: Validates file system security
- **Input Validation**: Sanitizes all user inputs
- **Database Security**: Uses prepared statements
- **Error Handling**: Secure error reporting

### Production Security
- **Environment Protection**: Secure .env file creation
- **Key Generation**: Cryptographically secure keys
- **Access Control**: Proper file permissions
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Input sanitization

## 🌐 Hosting Compatibility

### Shared Hosting
- **cPanel/Plesk**: Full compatibility
- **File Manager**: Web-based installation
- **Database Tools**: Works with hosting DB tools
- **Permissions**: Automatic permission handling

### VPS/Cloud Hosting
- **Linux/Windows**: Cross-platform support
- **Apache/Nginx**: Web server compatibility
- **Docker**: Container-ready installation
- **Load Balancers**: Multi-server support

### Local Development
- **XAMPP/WAMP**: Local development support
- **Docker Compose**: Development containers
- **Vagrant**: Virtual machine compatibility
- **Laravel Valet**: Mac development environment

## 🛠️ Troubleshooting

### Common Issues

1. **Database Connection Failed**
   ```
   Solution: Verify credentials, check server status
   ```

2. **Permission Denied**
   ```bash
   chmod 755 directories
   chmod 644 files
   chmod 777 public/uploads logs cache storage
   ```

3. **PHP Extension Missing**
   ```bash
   # Ubuntu/Debian
   sudo apt install php8.0-extension-name
   
   # CentOS/RHEL
   sudo yum install php-extension-name
   ```

4. **Memory Limit Exceeded**
   ```ini
   ; php.ini
   memory_limit = 256M
   max_execution_time = 300
   ```

### Error Recovery
- **Partial Installation**: Restart from any step
- **Database Issues**: Automatic rollback on errors
- **File Conflicts**: Backup and restore options
- **Configuration Problems**: Manual .env editing

## 📊 Performance Optimization

### Database Optimization
- **Indexes**: Optimized for common queries
- **Triggers**: Automatic calculations
- **Procedures**: Batch operations
- **Partitioning**: Large table optimization

### Application Performance
- **Caching**: Built-in cache system
- **Lazy Loading**: Efficient data loading
- **Image Optimization**: Automatic compression
- **CDN Ready**: Asset optimization

## 🔄 Maintenance

### Regular Tasks
- **Backups**: Automated backup system
- **Log Rotation**: Automatic log cleanup
- **Cache Clearing**: Performance maintenance
- **Security Updates**: Update notifications

### Monitoring
- **Error Logging**: Comprehensive error tracking
- **Performance Metrics**: Built-in analytics
- **User Activity**: Behavior tracking
- **System Health**: Status monitoring

## 📞 Support

### Documentation
- **Installation Guide**: Detailed setup instructions
- **API Documentation**: Developer resources
- **User Manual**: End-user guidance
- **Troubleshooting**: Common issue solutions

### Community
- **Forums**: Community support
- **GitHub**: Issue tracking
- **Discord**: Real-time chat
- **Stack Overflow**: Technical questions

### Professional Support
- **Installation Service**: Professional setup
- **Customization**: Feature development
- **Hosting**: Managed hosting options
- **Training**: User and admin training

## 🎉 Post-Installation

### Essential Configuration
1. **Email Settings** - Configure SMTP for notifications
2. **Payment Gateways** - Set up Stripe, PayPal, Tranzak
3. **SMS Service** - Configure Twilio for SMS notifications
4. **Maps API** - Add Google Maps API key
5. **Social Links** - Update social media profiles

### Content Management
1. **Restaurant Onboarding** - Add partner restaurants
2. **Menu Management** - Upload menu items and images
3. **User Roles** - Configure staff accounts
4. **Site Settings** - Customize branding and content
5. **Payment Methods** - Configure local payment options

### Testing
1. **Order Flow** - Test complete ordering process
2. **Payment Processing** - Verify payment gateways
3. **Notifications** - Test email and SMS delivery
4. **Mobile Experience** - Verify mobile responsiveness
5. **Performance** - Load testing and optimization

The Time2Eat installer provides a professional, secure, and comprehensive setup experience that works reliably across all hosting environments, from shared hosting to enterprise cloud platforms.
