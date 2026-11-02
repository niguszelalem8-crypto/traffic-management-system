TRAFFICPRO - FUNCTIONAL STUDENT PROJECT

REQUIREMENTS
- Windows + XAMPP (Apache, PHP 8+, MySQL/MariaDB)

INSTALL
1. Extract traffic-management-system.zip.
2. Copy the traffic-management-system folder into C:\xampp\htdocs\.
3. Open XAMPP Control Panel and start Apache and MySQL.
4. Open http://localhost/phpmyadmin.
5. Import database/traffic_management.sql. It creates the database and tables.
6. Open config/database.php and confirm local credentials. Default XAMPP is host=localhost, user=root, password empty.
7. Visit http://localhost/traffic-management-system/register.php and create a driver account.
8. Create the first officer account at http://localhost/traffic-management-system/officer-register.php.
   This page works for initial setup only while the database has no officer account. After the first officer is created, only a signed-in officer can use this page to register another officer.
9. Sign in at http://localhost/traffic-management-system/login.php and choose the correct account type.

FEATURES
- Driver registration and password hashing.
- Separate officer and driver access checks.
- Officer vehicle/driver/violation/fine records and reports.
- Drivers can view matched violations/fines and submit payment records.
- Officer payment inbox, dashboard, search, print report, live-traffic information, about and contact pages.
- PDO prepared statements, session regeneration, CSRF checks on POST forms.

IMPORTANT
This is an educational prototype, not an official traffic authority system. Driver records are linked to user accounts by matching full name, which is suitable only for a demo; a production version should add an explicit verified user_id link and identity verification. Payment submission is only a record, not a real bank/mobile-money transfer. Live traffic is sample information, not a live feed. Do not use real sensitive records until the application has undergone security, privacy, legal and operational review. Use HTTPS and secure database credentials for hosting.
