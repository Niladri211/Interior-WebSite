# Raman Group – Construction, Interior & Fabrication Services

A client-demo-level dynamic service business web application developed with **PHP 8.x**, **MySQL**, **Bootstrap 5**, **JavaScript**, **Font Awesome**, and **PDO**.

---

## 🌟 Features & Highlights

- **3 Service Verticals Under One Unified Brand**:
  1. **Construction**: Residential, Commercial, Renovation, Civil Works, Structural Works, Turnkey Construction.
  2. **Interior Design**: Home Interiors, Living Room, Bedroom, Kitchen, Office, Modular Furniture, False Ceiling, Lighting & Décor.
  3. **Fabrication**: Steel Fabrication, MS Fabrication, SS Fabrication, Gates, Grills, Railings, Staircases, Structural Fabrication, Custom Metal Works.
- **15 Complete Public Pages**:
  - `index.php` (Home)
  - `about.php` (About Us & Leadership Team)
  - `services.php` (Services Overview Hub)
  - `services-construction.php` (Construction Vertical)
  - `services-interior.php` (Interior Vertical)
  - `services-fabrication.php` (Fabrication Vertical)
  - `service-detail.php` (Dynamic Service Specs & BOQ Request)
  - `projects.php` (Filterable Portfolio)
  - `project-detail.php` (Project Scope of Work, Materials, Gallery)
  - `gallery.php` (Photo Gallery with Custom Lightbox)
  - `testimonials.php` (Client Ratings & Reviews)
  - `faq.php` (Accordion FAQs)
  - `contact.php` (Contact Information, Map, AJAX Enquiry Form)
  - `quote.php` (Quotation Request Form with File Upload)
  - `404.php` (Custom 404 Error Page)
- **Custom Interactive Image Lightbox**:
  - Zoom In (`+`), Zoom Out (`-`), Previous (`Left Arrow`), Next (`Right Arrow`), Reset Zoom, Close (`Esc`).
- **Comprehensive Demo Data**:
  - **28 Showcase Projects** (10 Construction, 10 Interior, 8 Fabrication)
  - **23 Detailed Services**
  - **8 Testimonials**, **10 Gallery Items**, **8 FAQs**, **Site Settings**
- **Executive Admin Portal (`/admin/login.php`)**:
  - Secure PHP Session authentication (`password_hash()` and `password_verify()`)
  - CSRF Token Protection across forms
  - Stat counters & quick lead overview
  - 10 Full Management Modules:
    1. Service Management
    2. Project Management
    3. Project Image Gallery Manager
    4. Photo Gallery Management
    5. Enquiry Lead Management & Workflow Status (New, Contacted, In Discussion, Quotation Sent, Converted, Closed)
    6. Quote Request Management & File Attachment Viewer (Pending, Under Review, Quotation Prepared, Quotation Sent, Approved, Rejected)
    7. Testimonial Management
    8. Team Member Management
    9. FAQ Management
    10. Global Website Settings

---

## 🚀 XAMPP Installation & Setup Instructions

Follow these step-by-step instructions to set up and run the project locally using XAMPP:

### Step 1: Install XAMPP
Download and install [XAMPP](https://www.apachefriends.org/) (Version supporting PHP 8.x) on your system.

### Step 2: Start Apache and MySQL Services
Open the **XAMPP Control Panel** and start both the **Apache** and **MySQL** services.

### Step 3: Place Project Files in `htdocs`
Copy or move the `Interior Website` folder into your XAMPP `htdocs` directory:
- **Windows Path**: `C:\xampp\htdocs\Interior Website`

### Step 4: Create Database in phpMyAdmin
1. Open your web browser and go to: `http://localhost/phpmyadmin/`
2. Click on **New** in the left sidebar to create a database.
3. Database Name: `buildcraft_db`
4. Collation: `utf8mb4_unicode_ci`
5. Click **Create**.

### Step 5: Import `database.sql`
1. Select `buildcraft_db` from the left menu in phpMyAdmin.
2. Click on the **Import** tab at the top bar.
3. Click **Choose File** and select the `database.sql` file located inside the project root directory.
4. Click **Import** at the bottom of the page to execute and import all 13 tables with demo data.

### Step 6: Verify Database Configuration
The database connection settings are located in `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'buildcraft_db');
```
*Note: If your XAMPP MySQL root user has a password set, update `DB_PASS` accordingly.*

### Step 7: Open the Application in Browser
Open your browser and navigate to:
- **Public Website**: `http://localhost/Interior%20Website/`
- **Admin Dashboard**: `http://localhost/Interior%20Website/admin/login.php`

---

## 🔐 Default Admin Credentials

- **Admin Login URL**: `http://localhost/Interior%20Website/admin/login.php`
- **Username**: `admin`
- **Password**: `admin123`

---

## 📂 Project Structure

```
Interior Website/
├── config/
│   └── database.php           # PDO database connection
├── includes/
│   ├── functions.php          # Security, CSRF, XSS, upload helper, SVG generator
│   ├── auth.php               # Session auth & admin route protection
│   ├── header.php             # HTML head, Bootstrap 5, Font Awesome
│   ├── navbar.php             # Responsive top bar & menu with CTAs
│   └── footer.php             # Site footer, floating WhatsApp & scroll top
├── assets/
│   ├── css/
│   │   ├── style.css          # Main theme stylesheet
│   │   └── admin.css          # Admin panel dark sidebar & dashboard UI
│   └── js/
│       ├── main.js            # Animated counters, AJAX form handlers, scroll top
│       ├── gallery-lightbox.js # Custom Lightbox controls (Zoom, Nav, Esc)
│       └── admin.js           # Admin interactive search, file preview
├── uploads/                   # Uploaded project files, quotes, gallery images
├── database.sql               # Complete SQL schema & 28+ showcase projects
├── index.php                  # Home page
├── about.php                  # About Us
├── services.php               # Services Overview Hub
├── services-construction.php  # Construction Services
├── services-interior.php      # Interior Services
├── services-fabrication.php   # Fabrication Services
├── service-detail.php         # Service Details page
├── projects.php               # Projects Portfolio
├── project-detail.php        # Project Details showcase
├── gallery.php                # Photo Gallery with Lightbox
├── testimonials.php           # Testimonials page
├── faq.php                    # FAQ page with accordion
├── contact.php                # Contact Us & Enquiry Form
├── quote.php                  # Dedicated Quote Form with File Upload
├── 404.php                    # Custom 404 Error page
├── README.md                  # Setup Instructions
└── admin/
    ├── login.php              # Admin Login
    ├── logout.php             # Admin Logout
    ├── index.php              # Admin Dashboard
    ├── services.php           # Service Management
    ├── projects.php           # Project Management
    ├── project-images.php      # Extra Project Gallery Images Manager
    ├── gallery.php            # Photo Gallery Management
    ├── enquiries.php          # Enquiry Lead Management
    ├── quote-requests.php     # Quotation Workflow Management
    ├── testimonials.php       # Testimonials Management
    ├── team.php               # Team Members Management
    ├── faqs.php               # FAQ Management
    └── settings.php           # Global Site Settings
```

---

## 🔒 Security Measures Implemented
- **PDO Prepared Statements** for 100% SQL Injection protection.
- **XSS Sanitization** using `e()` htmlspecialchars helpers.
- **CSRF Token Generation & Verification** on all form POST requests.
- **Secure Admin Authentication** using `password_hash()` and `password_verify()`.
- **Strict File Upload Validation**: Allowed Extensions (`jpg`, `jpeg`, `png`, `webp`, `pdf`, `doc`, `docx`), MIME-type checks, 10MB limit, unique file renaming.
