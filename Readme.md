# Time2Eat - Bamenda Food Delivery Platform

This README.md serves as a detailed prompt for designing and building a food ordering web application tailored for Bamenda, Cameroon. It outlines the vision, features, tech stack, user roles, database schema, installation steps, and step-by-step prompts to build the app. Use this as a guide to ensure the app is modern, user-friendly, and scalable. Focus on reusable code (e.g., shared components, functions, classes), good practices (e.g., MVC pattern in PHP), and minimal code solutions. Keep code comments minimal—only for complex logic or non-obvious sections.

The app connects customers with local restaurants and delivery riders for seamless ordering, with real-time tracking and affiliate features. Ensure full functionality: data import/export, consistent design across pages (including dashboards), role-based login redirection, payment processing, notifications, search/cart management, order management (cancellations/refunds), ratings/reviews, affiliate codes, security, logging, and backups. Public-facing pages are limited to index, browse, and about; other functionality is in role-specific dashboards. Admin-editable contact info (e.g., email, phone, address) appears in footer/about page.

## 🎨 Design

Clean, intuitive mobile first UI/UX with high-quality images of local Bamenda dishes (e.g., ndolé, eru). Use warm colors (reds/oranges) for CTAs, neutrals for backgrounds, sans-serif fonts (Inter/Poppins), and mobile-first layouts (320px up) with Tailwind CSS (`tw-` prefix, `@layer components` for customs, `tailwind-merge` for class conflicts). Optimize images (WebP, lazy loading), ensure touch-friendly buttons (min 44px), swipeable carousels, collapsible sidebars, and WCAG AA accessibility. Test responsiveness with `container mx-auto`, `grid grid-cols-1 md:grid-cols-2`, and `hover:tw-scale-105` for subtle animations. use glasmorphism, visual hierarchy

## 🚀 Features (Prompt for Implementation)

Build these core features with an emphasis on security, performance, and mobile responsiveness:
- **Multi-role System**: Implement role-based access control (RBAC) using PHP sessions or JWT for Customer, Vendor, Delivery Rider, and Admin.
- **Real-time Order Tracking**: Use PHP with polling (or WebSockets via Ratchet if feasible) for live order status and delivery progress with map integration.
- **Responsive Design**: Mobile-first; test on devices from 320px width up.
- **Secure Authentication**: Use PHP's password_hash, email verification, and role-specific dashboard redirection (e.g., customer to order page, admin to analytics).
- **Real-time Updates**: Browser alerts or push notifications (e.g., OneSignal if using JS); email/SMS for order confirmations/status changes (Twilio/SendGrid).
- **Progressive Web App (PWA)**: Installable with manifest.json, service worker for offline menu browsing, and push notifications.
- **Header Enhancements**: Browse icon in nav linking to browse page for quick dish/restaurant search.
- **Main Page Sections**: Hero, Download App (PWA "Add to Home Screen" button, QR code), Featured Restaurants (carousel), How It Works (infographic), Testimonials (reviews with ratings), Popular Dishes (grid), Footer (admin-editable contact, social, legal).
- **Browse Page**: Global search and browsing of dishes/restaurants with filters (cuisine, price, location); grid/list format with add-to-cart.
- **About Page**: Platform info, mission, team, admin-editable contact details; includes contact form.
- **Admin Popup Notifications**: Admin creates/sends notifications via dashboard; display as modals/banners on index for all/targeted users.
- **Data Management**: PHP script for CSV/JSON import (menus, users, restaurants); Excel analytics export with PhpSpreadsheet.
- **Additional Enhancements**: 
  - Payment gateways (Mobile Money, Orange Money, Stripe/PayPal; XAF currency).
  - Global search with filters (browse page).
  - Cart management (sessions/DB, add/remove/update).
  - Order management (cancellations/refunds by admin/vendor).
  - Ratings/reviews (post-order feedback, stars, comments).
  - Security: CAPTCHA on login/signup, rate limiting, input sanitization (last).
  - Logging/error reporting (log to DB/file, email errors to admin).
  - Backup/restore: Admin tool for DB dumps or cron-based backups.
  - Loading animation.
  - Multi-language support (English/French; PHP i18n).

## 🛠️ Tech Stack (Guidelines for Efficient Coding)

- **Frontend**: HTML5 (semantic <section>, <nav> for accessibility).
- **Styling**: Tailwind CSS (rapid, reusable classes).
- **UI Components**: Feather Icons (SVGs) + Google Icons (Material Symbols) for carts, maps, etc., with ARIA labels.
- **Backend**: PHP (8+). Use Composer for dependencies (e.g., PhpSpreadsheet, minimal). MVC structure for code reuse (base Controller class). Minimal comments.
- **Database**: MySQL (or MariaDB for hosting compatibility).
- **Other**: Google Maps API (or OpenStreetMap; switchable via config). PHP CSV handling or libraries for imports/exports. Use PHP traits for shared logic.
- **Best Practices**: Validate inputs, handle errors, optimize images for speed. Test PWA edge cases (e.g., no internet). Role detection in login (switch on user->role). Adhere to DRY and SOLID principles.

## 📱 User Roles (Detailed Functional Prompt)

Implement dashboards for each role with role-specific views. Use PHP to check roles on page load. Dashboards should have consistent design (left sidebar nav, main content, header with user info).

### Customer
- Browse restaurants/menus with filters (cuisine, price); add to cart, purchase.
- Receive affiliate payments (referral bonuses; admin sets percentage, payout via wallet).
- Place orders with customization (extras, notes).
- Track orders in real-time with map (rider location).
- Manage multiple delivery addresses (geolocation if possible).
- View order history, reorder favorites.
- Request role upgrade to Vendor/Rider (admin approval).
- Rate/review orders post-delivery.
- Manage wishlist/favorites (save menu items).
- View/download invoices.
- Chat with vendors/admins for order inquiries.
- Manage saved payment methods for faster checkout.

### Vendor
- Manage restaurant profile (logo, hours, location).
- Upload/manage menu items (images, prices, categories; bulk import).
- Process orders (accept/reject, update status).
- Track fulfillment with live map.
- View sales analytics (Chart.js charts, Excel export).
- Manage inventory (stock levels; auto-disable out-of-stock).
- Create vendor-specific affiliate codes (admin approval).
- Chat with customers/riders.
- Request payouts for earnings.

### Delivery Rider
- Accept/reject delivery requests (push notifications).
- Navigate via map (distance-based payment calculation).
- Update status (picked up, en route, delivered).
- Track earnings (food cost + distance fee; base + per km).
- Performance dashboard (ratings, completed deliveries).
- Note: Order cost = food + delivery fee (distance-based via API).
- Manage availability schedule (working hours/days).
- Update vehicle info (verification).
- Chat with vendors/customers.
- Report delivery issues/incidents.

### Admin
- Manage affiliate commissions (individual/bulk editing).
- User management (approve roles, ban users).
- Approve restaurants/vendors.
- Platform analytics (orders, revenue; Excel export).
- Configure settings (delivery costs, map API key).
- Monitor orders in real-time (live dashboard).
- Validate withdrawals (threshold e.g., 10,000 XAF; toggleable).
- Manage delivery system (set rates, map integration; Google/OpenStreetMap).
- Data import tool (file uploads to DB).
- Backup/restore functionality.
- Send popup notifications (create/schedule; target, duration, content).
- Edit contact info (email, phone, address for footer/about).
- Manage menu categories (add/edit/delete).
- Resolve disputes (order complaints).
- View/search system logs for auditing.
- Manage taxes/platform fees (configure rates).

## 📊 Database Schema (Expanded for Scalability)

Use MySQL. Create tables with indexes for performance. Include timestamps and soft deletes where useful. Add tables for reviews, logs if needed.

- **users**: id (PK, auto-inc), username (varchar), email (varchar unique), password (varchar), role (enum: 'customer','vendor','rider','admin'), affiliate_rate (decimal default 0), balance (decimal), created_at (timestamp).
- **restaurants**: id (PK), vendor_id (FK users), name (varchar), address (text), latitude (decimal), longitude (decimal), approved (bool default 0), image_url (varchar).
- **menu_items**: id (PK), restaurant_id (FK), name (varchar), description (text), price (decimal), image_url (varchar), category_id (FK categories), stock (int default 0).
- **orders**: id (PK), customer_id (FK), restaurant_id (FK), rider_id (FK nullable), status (enum: 'pending','preparing','out_for_delivery','delivered','cancelled'), total_cost (decimal), delivery_address (text), created_at (timestamp).
- **order_items**: id (PK), order_id (FK), menu_item_id (FK), quantity (int), customizations (json).
- **deliveries**: id (PK), order_id (FK), rider_id (FK), pickup_lat (decimal), pickup_long (decimal), delivery_lat (decimal), delivery_long (decimal), distance (decimal), cost (decimal), status (enum).
- **affiliates**: id (PK), user_id (FK), referral_code (varchar unique), earnings (decimal), withdrawal_threshold (decimal default 10000), approved (bool).
- **payments**: id (PK), order_id (FK), amount (decimal), method (varchar), status (enum: 'paid','pending','failed').
- **reviews**: id (PK), order_id (FK), user_id (FK), rating (int 1-5), comment (text), created_at (timestamp).
- **logs**: id (PK), user_id (FK), action (varchar), details (text), timestamp (timestamp).
- **popup_notifications**: id (PK), message (text), target (varchar, e.g., 'all' or roles), start_date (datetime), end_date (datetime), active (bool), created_by (FK users, admin only).
- **site_settings**: id (PK), key (varchar, e.g., 'contact_email', 'contact_phone', 'contact_address'), value (text), updated_at (timestamp).
- **categories**: id (PK), name (varchar unique), description (text).
- **disputes**: id (PK), order_id (FK), initiator_id (FK users), description (text), status (enum: 'open','resolved','closed'), resolution (text), created_at (timestamp).
- **messages**: id (PK), sender_id (FK users), receiver_id (FK users), message (text), order_id (FK nullable), timestamp (timestamp), read (bool default 0).
- **wishlists**: id (PK), user_id (FK users), menu_item_id (FK menu_items), added_at (timestamp).
- **rider_schedules**: id (PK), rider_id (FK users), day (enum: 'monday','tuesday', etc.), start_time (time), end_time (time), active (bool default 1).
- **payment_methods**: id (PK), user_id (FK users), method_type (varchar, e.g., 'mobile_money'), details (json), default (bool default 0).
- **analytics**: Query dynamically for views; store aggregates if performance needed.

## 🔧 Installation (Simplified for Shared Hosting/Local)

Focus on easy setup without Git or advanced tools. Assume PHP/MySQL hosting (e.g., cPanel, local XAMPP). Build an installation file (ZIP archive with installer script) for compatibility.

1. **Build Installation File**: Package project into Time2Eat-v1.0.zip with all folders, .env.example, database.sql, import scripts, and installer.php. Installer checks PHP 8+, prompts for DB credentials, creates .env, imports SQL schema, sets up admin user, configures permissions, and self-deletes for security. Use relative paths, detect hosting type (shared/VPS), handle permissions via PHP (e.g., chmod). Test on XAMPP, HostGator, cloud (Heroku-like, PHP-focused).

2. **Download/Upload Files**: Manually download ZIP, extract to server/local directory.

3. **Run Installer**: Visit http://yourdomain/installer.php; follow prompts to configure .env (DB_HOST, DB_NAME, DB_USER, DB_PASS, MAP_API_KEY).

4. **Set Up Database**: Installer auto-creates database if permitted; otherwise, use phpMyAdmin with database.sql.

5. **Complete Setup**: Installer verifies setup, redirects to homepage. For shared hosting, FTP upload ZIP and extract.

6. **Test**: Visit http://localhost/Time2Eat or domain; fix permissions (e.g., 755 for folders).

7. **Troubleshooting**: Check PHP error logs; ensure PDO, GD (images), ZipArchive (exports) extensions.

## ✅ Step-by-Step Prompts for Building the App

Use these step-by-step prompts to build the app iteratively. Each prompt is designed to be self-contained and detailed for execution in a single Augment request, incorporating relevant context from the tech stack, features, design, user roles, database schema, and best practices (e.g., MVC pattern, DRY/SOLID principles, mobile-first Tailwind CSS with `tw-` prefix, minimal comments, PHP 8+, Composer for dependencies). Start with core features, then expand. Aim for iterative builds: core auth first, then features. Review generated code for security, reusability, and alignment with the overall app vision.

1. **Plan and Set Up Project Structure**: Generate a complete PHP MVC project skeleton for a food delivery app called Time2Eat, including folders for public (static assets, index.php), src (with config for .env/database connections, controllers for handling requests, models for DB interactions, views for HTML templates), dashboards (role-specific PHP views), and scripts (for imports/exports). Use PHP 8+ with Composer for dependencies, adhere to DRY and SOLID principles, include .env.example for DB_HOST, DB_NAME, DB_USER, DB_PASS, MAP_API_KEY. Ensure mobile-first design with Tailwind CSS setup (via CDN or build script, using `tw-` prefix to avoid conflicts), and prepare for PWA with manifest.json. Focus on reusability with shared components, minimal code/comments.

2. **Install and Configure Dependencies**: Write a complete Composer.json file and setup script for the Time2Eat food delivery app, including dependencies like PhpSpreadsheet for Excel exports, Ratchet for WebSockets (real-time tracking), and any minimal others needed for features like payments (e.g., Stripe SDK if applicable). Include commands to install via Composer, configure Tailwind CSS (with `tw-` prefix, `@layer components` for custom styles, tailwind-merge for class conflicts). Ensure compatibility with PHP 8+, MySQL, and shared hosting. Sanity-check versions for security/performance, align with MVC structure, and prepare for features like PWA service workers and map APIs (Google/OpenStreetMap switchable via config).

3. **Build Semantic HTML Layouts**: Generate complete HTML/PHP views for public pages (index/home with hero, featured restaurants carousel, footer; browse with search/filters grid; about with sections like 'Our Story' and contact form) in the Time2Eat app. Use semantic HTML5 (<section>, <nav>), mobile-first Tailwind CSS (`tw-` prefix, grid-cols-1 md:grid-cols-2, container mx-auto, lazy loading images), integrate browse icon in nav, ensure PWA compatibility with manifest.json and "Add to Home Screen" button in download section. Pull dynamic content from DB (e.g., site_settings for contact), include high-quality image placeholders (e.g., ![Food](https://via.placeholder.com/400x300)), subtle animations (hover:tw-scale-105), ARIA labels, and align with design (warm colors, sans-serif fonts).

4. **Implement Icons and Basic UI**: Generate PHP view code to integrate Feather Icons and Google Material Symbols into the Time2Eat app for elements like search (browse icon), carts, maps, with ARIA labels for accessibility. Use Tailwind CSS (`tw-` prefix) for styling, ensure mobile-first responsiveness (min-h-[44px] buttons), include in nav, cart management, and dashboards. Add basic UI components like loading animation, modals for popups, and align with overall design (warm CTAs, image optimization). Keep reusable via traits/classes, minimal comments, adhere to DRY/SOLID.

5. **Develop PHP Backend Basics**: Create a complete reusable base Controller class and routes.php for request handling in the Time2Eat MVC app using PHP 8+. Follow SOLID principles, include error handling, input validation, session/JWT for auth. Prepare for role-based access, integrate with models/views, use traits for shared logic (e.g., DB connections from config). Minimal comments except complex logic, align with tech stack (Composer, MySQL), and support features like real-time updates and PWA.

6. **Create and Set Up Database Schema**: Generate full MySQL CREATE TABLE statements with indexes, timestamps, soft deletes for the Time2Eat app schema: users (id, username, email unique, password, role enum, affiliate_rate, balance, created_at), restaurants, menu_items, orders, order_items, deliveries, affiliates, payments, reviews, logs, popup_notifications, site_settings, categories, disputes, messages, wishlists, rider_schedules, payment_methods, analytics (dynamic queries). Include import script via PHP/phpMyAdmin, prepare for performance (aggregates if needed), align with user roles and features like affiliates, real-time tracking.

7. **Implement Secure Authentication**: Write complete PHP code for user authentication in Time2Eat: login/signup forms/views, using password_hash, sessions/JWT, email verification, role detection from users table (enum: customer/vendor/rider/admin), automatic redirection to role-specific dashboards (e.g., customer to orders). Include input sanitization, rate limiting, CAPTCHA on forms. Use MVC (controller handles logic, model for DB), Tailwind for UI (mobile-first, `tw-` prefix), align with security best practices, minimal comments.

8. **Develop Multi-Role Dashboards**: Generate complete PHP views and controllers for multi-role dashboards in Time2Eat: customer (orders, profile, affiliates), vendor (profile, menus, orders), rider (deliveries, earnings), admin (analytics, user management). Use consistent design (left sidebar nav collapsible on mobile, main content, header with user info) via Tailwind (`tw-` prefix, grid/flex), PHP role checks on load. Include sidebar links per role, integrate with DB models, ensure reusability (shared components), mobile-first, align with user roles/features.

9. **Add Order Placement and Customization**: Create complete PHP controller, model, views for order placement in Time2Eat: cart management (sessions/DB, add/remove/update), customization (extras/notes via JSON), checkout with affiliate code validation, total calculation (food + delivery). Integrate with orders/order_items tables, notifications, payments. Use MVC, Tailwind for UI (grid for cart, forms), input validation, align with customer flow, mobile-first design.

10. **Integrate Real-Time Tracking**: Generate complete PHP code for real-time order tracking in Time2Eat: polling or WebSockets with Ratchet for status updates (pending/preparing/out_for_delivery/delivered), map integration (Google/OpenStreetMap via config, show rider location). Include controllers/models for deliveries table, views with live map in dashboards. Test with API keys, ensure performance, mobile-first Tailwind UI, align with order management features.

11. **Build Affiliate System**: Write complete PHP models, controllers, views for affiliate system in Time2Eat: referral codes (unique in affiliates table), earnings tracking, commissions (admin sets rates), withdrawals (threshold 10000 XAF, admin validation). Integrate with users/balance, dashboards (customer/vendor views), payouts via wallet. Use MVC, secure validation, Tailwind UI, align with roles/features.

12. **Add PWA Features and Download Section**: Generate complete JavaScript/PHP code for PWA in Time2Eat: manifest.json, service worker for offline menu browsing/caching, push notifications. Add "Add to Home Screen" button/QR in index download section. Integrate with views (e.g., offline fallbacks), test edge cases (no internet), align with tech stack (minimal JS), mobile-first.

13. **Implement Vendor/Menu Management**: Create complete PHP script, controller, views for vendor menu management in Time2Eat: upload/manage items (images, prices, categories, stock in menu_items table), bulk CSV import, inventory (auto-disable out-of-stock). Include profile editing (restaurants table), dashboards integration. Use MVC, file handling (GD for images), validation, Tailwind UI, align with vendor role.

14. **Add Rider Acceptance and Navigation**: Generate complete PHP controller, views for rider dashboard in Time2Eat: accept/reject deliveries (push notifications), status updates (deliveries table), map navigation (distance/cost calculation via API). Include earnings tracking, schedule/vehicle management (rider_schedules). Use MVC, real-time integration, Tailwind (mobile-friendly map), align with rider role/features.

15. **Set Up Admin Tools**: Write complete PHP code for admin dashboard tools in Time2Eat: analytics (queries/charts with Chart.js, Excel export via PhpSpreadsheet), approvals (users/restaurants), backups (DB dumps), popup notifications (insert to popup_notifications, schedule/target), contact editing (site_settings). Use MVC, secure forms, Tailwind UI, align with admin role.

16. **Integrate Payments and Notifications**: Generate complete PHP integration for payments and notifications in Time2Eat: gateways (Mobile Money/Orange Money/Stripe/PayPal with XAF intending to use https://tranzak.net/ for mtm mobile payments), process in checkout (payments table), email/SMS (Twilio/SendGrid) for confirmations/status changes. Include controllers for handling, error logging, align with orders/features, secure (input sanitization).

17. **Add Search, Ratings/Reviews, Cancellations/Refunds**: Create complete PHP functionality for Time2Eat: global search with filters (browse page, query menu_items/restaurants), ratings/reviews (post-order insert to reviews), cancellations/refunds (update orders, admin/vendor process). Use MVC, DB joins, Tailwind for UI (stars, forms), align with customer/vendor/admin roles.

18. **Implement Security Features**: Generate complete PHP code for security in Time2Eat: CAPTCHA on login/signup, rate limiting, input sanitization everywhere, action logging to logs table. Include global error reporting (email to admin). Use as last step, audit manually, integrate with auth/controllers, minimal impact on performance.

19. **Add Main Page Sections**: Write complete PHP views for index page sections in Time2Eat: featured restaurants (carousel from DB), how it works (infographic), testimonials (pull from reviews), popular dishes (grid). Use Tailwind (swipeable on mobile, lazy images), dynamic queries, align with design (warm colors, animations).

20. **Test Responsiveness and Cross-Browser**: Provide complete testing guidelines and scripts (PHP/JS) for Time2Eat: simulate devices (320px+), cross-browser checks, fix responsiveness issues in Tailwind layouts (e.g., media queries via prefixes). Include tools for device emulation, ensure PWA/offline works.

21. **Optimize Performance**: Generate complete PHP code for performance optimization in Time2Eat: image compression/optimization (GD, WebP), browser caching headers, DB query caching. Include lazy loading, minify CSS/JS, test load times (<3s), align with features like real-time and PWA.

22. **Add Error Handling and Security Audits**: Write complete PHP global error handler for Time2Eat: log to DB/file (logs table), email admin on critical errors, user-friendly messages. Include security audit checklist (e.g., OWASP), integrate with all controllers, minimal overhead.

23. **Build and Test Installation ZIP/File**: Create complete PHP installer script and ZIP structure for Time2Eat: self-deleting installer.php (check PHP 8+, DB form, create .env, import schema, set admin), include database.sql, test on local/shared/cloud hosting. Ensure compatibility (relative paths, permissions).

24. **Deploy to Hosting and Test Live**: Provide complete deployment guide and test scripts for Time2Eat: upload ZIP, run installer, verify features (role redirection, exports, popups, real-time), debug large codebases. Include live testing for security/performance, full functionality check.

## 📄 License

MIT License – free to use and modify.

The Root Cause: File Loading Order
The core of the problem is the order in which your application loads its files.

The constants like DB_HOST, DB_NAME, etc., are defined in the file e:\Wamp64\www\Eat\config\config.php. This file is responsible for reading your .env file and making those values available to the rest of your application as PHP constants.
The error occurs because the code that uses DB_HOST (in database.php) is executed before the code that defines DB_HOST (in config/config.php) has been loaded.
Looking at your e:\Wamp64\www\Eat\bootstrap\app.php file, it loads config/app.php, but it doesn't appear to load config/config.php. The application proceeds to run the router and controllers without ever loading the essential database constants.

How to Fix It (Conceptual)
To resolve this, you need to ensure config/config.php is loaded very early in the application's lifecycle, before any database connection is attempted. A logical place to include it would be within bootstrap/app.php, right after the ROOT_PATH is defined and before the main Application class is run.

