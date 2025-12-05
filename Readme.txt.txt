Installation Guide
Prerequisites
-------------------------------------------------------------------------------
1. XAMPP v8.0 or higher
2. Web Browser (Chrome/Firefox/Edge)
3. Internet connection for email features
Step-by-Step Installation
--------------------------------------------------------------------------------

1. XAMPP Setup
- Download XAMPP v8.0+ from https://www.apachefriends.org
- Run installer and follow prompts
- Install to default location (C:\xampp)

2. Database Setup --(optional, just in case there is no internet to access main database,it will attempt to use fallback or localhost database)
- Start XAMPP Control Panel
- Start Apache and MySQL services
- Open browser: http://localhost/phpmyadmin
- Create new database:
- Click 'Import' tab
- Choose file: database/ example-space-db.sql
	- (it will automatically create database and import all the data)
	(also make sure the database dont have same name as ----)
- Click 'Go' to import

3.Project Files Setup
- Navigate to C:\xampp\htdocs
- Create new folder: UNTY-PGRSYS
- Extract all project files to this folder
- Verify folder structure:

* C:\xampp\htdocs\UNTY-PGRSYS\app
* C:\xampp\htdocs\UNTY-PGRSYS\admin
* C:\xampp\htdocs\UNTY-PGRSYS\config
* C:\xampp\htdocs\UNTY-PGRSYS\log
* C:\xampp\htdocs\UNTY-PGRSYS\public
* C:\xampp\htdocs\UNTY-PGRSYS\uploads


4. Access the System
- Ensure Apache & MySQL are running
- Open browser
- Go to: http://localhost/UNTY-PGRSYS


Login Credentials
-------------------------------------------------------------------------------
2. Admin Account:
- username: admin
- Password: admin
- Role: Administrator
- Access: User and report management

3. Test User Account: (you can also register)
- username: user11
- Password: user11
- Role: User
- Access: Report Uploading/

Access Points
--------------------------------------------------------------------------------
1. Admin Panel:
- URL: http://localhost/UNTY-PGRSYS/admin/dashboard.php
- Report Management

2. User Panel:
- URL: http://localhost/UNTY-PGRSYS/public/users/dashboard.php
- User Management Panel


System Features
---------------------------------------------------------------------------------
1. Admin Panel- user validation
- Update roles (User)
- Delete accounts
2. Admin Panel:
- Analytic Dashboard
- User Management
- Report Management

2. User Panel
- Report Uploading

Common Issues & Solutions
----------------------------------------------------------------------------------
1. Database Connection Error
- Verify in app\database\Db.php:
```php
$servername = "mysql-f33c54e-fontejoedel1-8150.k.aivencloud.com";
$username = "Jho_del";
$password = "AVNS_qlduRudNkrNnyj_HUYV";
$database = "unity_pgsys_db";
$port = "24340";
$ssl_ca_path = __DIR__ .'../../../config/ca.pem';

    // --- Secondary (Localhost) Credentials (Fallback) ---
$backup_servername = "localhost";
$backup_username = "root";
$backup_password = "";
$backup_database = "unity_pgsys_db";

also verify if the cert: config\ca.pem
```

- Check XAMPP services are running
- Verify database exists
2. Upload Not Working
- Check uploads folder permissions
- Verify PHP settings in php.ini:
* upload_max_filesize = 10M
* post_max_size = 10M
* max_execution_time = 300
* extension=zip
* extension=gd
* extension=mbstring
* extension=xml
* extension=dom
* extension=simplexml