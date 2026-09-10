-- =========================================================
-- Database Schema & Seed Data for Raman Group
-- Database Name: buildcraft_db
-- =========================================================

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS site_settings;
DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS faqs;
DROP TABLE IF EXISTS team_members;
DROP TABLE IF EXISTS testimonials;
DROP TABLE IF EXISTS quote_requests;
DROP TABLE IF EXISTS enquiries;
DROP TABLE IF EXISTS gallery;
DROP TABLE IF EXISTS project_images;
DROP TABLE IF EXISTS projects;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS service_categories;
DROP TABLE IF EXISTS admins;
SET FOREIGN_KEY_CHECKS = 1;

-- ---------------------------------------------------------
-- 1. Table: admins
-- ---------------------------------------------------------
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 1b. Table: customers
-- ---------------------------------------------------------
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    address TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 2. Table: service_categories
-- ---------------------------------------------------------
CREATE TABLE service_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    icon VARCHAR(50) DEFAULT 'fa-building',
    image VARCHAR(255) DEFAULT '',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 3. Table: services
-- ---------------------------------------------------------
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    short_description TEXT,
    detailed_description TEXT,
    features TEXT,
    benefits TEXT,
    image VARCHAR(255) DEFAULT '',
    icon VARCHAR(50) DEFAULT 'fa-tools',
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 4. Table: projects
-- ---------------------------------------------------------
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    category_id INT NOT NULL,
    service_id INT DEFAULT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    location VARCHAR(150) DEFAULT '',
    client_name VARCHAR(100) DEFAULT '',
    start_date DATE DEFAULT NULL,
    completion_date DATE DEFAULT NULL,
    budget DECIMAL(15,2) DEFAULT NULL,
    status VARCHAR(50) DEFAULT 'Completed',
    short_description TEXT,
    description TEXT,
    scope_of_work TEXT,
    materials_used TEXT,
    highlights TEXT,
    featured_image VARCHAR(255) DEFAULT '',
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 5. Table: project_images
-- ---------------------------------------------------------
CREATE TABLE project_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 6. Table: gallery
-- ---------------------------------------------------------
CREATE TABLE gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT '',
    location VARCHAR(150) DEFAULT '',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 7. Table: enquiries
-- ---------------------------------------------------------
CREATE TABLE enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL,
    service_category VARCHAR(100) DEFAULT '',
    project_type VARCHAR(100) DEFAULT '',
    location VARCHAR(150) DEFAULT '',
    budget_range VARCHAR(50) DEFAULT '',
    message TEXT NOT NULL,
    preferred_contact_date DATE DEFAULT NULL,
    status ENUM('New', 'Contacted', 'In Discussion', 'Quotation Sent', 'Converted', 'Closed') DEFAULT 'New',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 8. Table: quote_requests
-- ---------------------------------------------------------
CREATE TABLE quote_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT DEFAULT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(100) NOT NULL,
    service_category VARCHAR(100) DEFAULT '',
    project_type VARCHAR(100) DEFAULT '',
    location VARCHAR(150) DEFAULT '',
    estimated_budget VARCHAR(50) DEFAULT '',
    preferred_start_date DATE DEFAULT NULL,
    project_description TEXT NOT NULL,
    additional_requirements TEXT DEFAULT NULL,
    reference_file VARCHAR(255) DEFAULT '',
    quotation_amount DECIMAL(15,2) DEFAULT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    admin_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 9. Table: testimonials
-- ---------------------------------------------------------
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_title VARCHAR(100) DEFAULT '',
    company VARCHAR(100) DEFAULT '',
    rating INT DEFAULT 5,
    content TEXT NOT NULL,
    client_image VARCHAR(255) DEFAULT '',
    project_type VARCHAR(100) DEFAULT '',
    is_featured TINYINT(1) DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 10. Table: team_members
-- ---------------------------------------------------------
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    designation VARCHAR(100) NOT NULL,
    bio TEXT,
    image VARCHAR(255) DEFAULT '',
    experience_years INT DEFAULT 0,
    email VARCHAR(100) DEFAULT '',
    phone VARCHAR(30) DEFAULT '',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 11. Table: faqs
-- ---------------------------------------------------------
CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT DEFAULT NULL,
    question VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 12. Table: contact_messages
-- ---------------------------------------------------------
CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(30) DEFAULT '',
    subject VARCHAR(150) DEFAULT '',
    message TEXT NOT NULL,
    status ENUM('Unread', 'Read', 'Replied') DEFAULT 'Unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ---------------------------------------------------------
-- 13. Table: site_settings
-- ---------------------------------------------------------
CREATE TABLE site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- SEED DATA INSERTIONS
-- =========================================================

-- Admin credentials: username: admin | password: admin123
INSERT INTO admins (username, email, password_hash, full_name) VALUES
('admin', 'admin@ramangroup.com', '$2y$10$.ZHWGZFrzpk0nGZkG/SfIulufm0UN73JYZcR/oZ9HG1ra1oDvbx6u', 'Super Admin');

-- Service Categories
INSERT INTO service_categories (id, name, slug, description, icon, sort_order) VALUES
(1, 'Construction', 'construction', 'Comprehensive civil, structural, commercial and residential construction solutions.', 'fa-hard-hat', 1),
(2, 'Interior Design', 'interior', 'Bespoke interior architecture, modular systems, and luxury spatial styling.', 'fa-couch', 2),
(3, 'Fabrication', 'fabrication', 'Precision structural metalwork, heavy MS/SS fabrication, gates, and custom engineering.', 'fa-industry', 3);

-- Site Settings
INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Raman Group – Construction, Interior & Fabrication Services'),
('tagline', 'Building Spaces. Designing Experiences.'),
('phone', '+91 98765 43210'),
('secondary_phone', '+91 98765 43211'),
('email', 'info@ramangroup.com'),
('support_email', 'projects@ramangroup.com'),
('address', 'Raman Towers, Plot No. 45, Industrial Business Park, Sector 62, Noida, UP - 201309'),
('business_hours', 'Mon - Sat: 9:00 AM - 7:00 PM (Sunday Closed)'),
('whatsapp_number', '919876543210'),
('google_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14008.11479261771!2d77.3621434!3d28.6289291!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390ce5456ef36d9f%3A0x3b7191b1286136c8!2sSector%2062%2C%20Noida%2C%20Uttar%20Pradesh!5e0!3m2!1sen!2sin!4v1700000000000!5m2!1sen!2sin'),
('facebook_url', 'https://facebook.com/ramangroup'),
('instagram_url', 'https://instagram.com/ramangroup'),
('linkedin_url', 'https://linkedin.com/company/ramangroup'),
('youtube_url', 'https://youtube.com/ramangroup'),
('years_experience', '18+'),
('completed_projects', '450+'),
('happy_clients', '380+'),
('expert_team', '65+');


-- Services
-- Construction Services (6 items)
INSERT INTO services (id, category_id, title, slug, short_description, detailed_description, features, benefits, image, icon, is_featured, is_active) VALUES
(1, 1, 'Residential Construction', 'residential-construction',
'Custom luxury villa construction, high-rise apartments, and independent home builds using IS-spec materials and seismic engineering.',
'Raman Group delivers end-to-end residential construction services, taking projects from initial plot excavation to turnkey handover. We specialize in contemporary luxury residences, duplexes, multi-story apartments, and custom villa communities built to withstand environmental stress while maximizing natural ventilation and aesthetic brilliance.',
'High-grade M25/M30 concrete mix design; Waterproofing warranties; Seismic zone compliant structural framing; Smart home pre-wiring integration; Complete architectural clearance assistance',
'Zero structural delay guarantee; 10-year structural warranty; Transparent rate card & material specs; Dedicated site engineer assigned to every project',
'assets/images/services/residential-construction.jpg', 'fa-home', 1, 1),

(2, 1, 'Commercial Construction', 'commercial-construction',
'State-of-the-art office complexes, retail centers, technology parks, and hospitality developments engineered for modern commerce.',
'Our commercial division handles large-footprint structural developments including corporate headquarters, shopping plazas, diagnostic centers, and industrial hubs. We incorporate green building standards, high-efficiency HVAC pathways, robust fire safety systems, and flexible spatial layouts.',
'Heavy foundation piling; Post-tensioned slab construction; Integrated fire-suppression networks; Glass curtain wall structural engineering; High-capacity power grid planning',
'Faster ROI through accelerated build schedules; Full local authority compliance & approvals; Energy-efficient building envelope design; Scalable infrastructure',
'assets/images/services/commercial-construction.jpg', 'fa-building', 1, 1),

(3, 1, 'Renovation & Restoration', 'renovation-restoration',
'Structural retrofitting, architectural modernization, expansion, and heritage restoration for residential and commercial assets.',
'Transform aging structures with our comprehensive renovation and retrofitting solutions. We perform non-destructive concrete testing, column jacketing, beam reinforcement, floor plan re-configuration, facade replacement, and MEP upgrades to restore structural integrity and double property valuation.',
'Non-destructive structural audits; Carbon fiber rebar wrapping & column jacketing; Facade overhang reinforcement; Plumbing & electrical grid overhauls; Clean interior demolition',
'3x extended building lifecycle; Enhanced safety compliance; Up to 40% value appreciation; Minimal operational downtime during work',
'assets/images/services/renovation-restoration.jpg', 'fa-tools', 0, 1),

(4, 1, 'Civil Works', 'civil-works',
'Heavy earthmoving, foundation piling, drainage infrastructure, retaining walls, and site preparation for large industrial projects.',
'We execute large-scale civil engineering projects with precision earthwork machinery and certified engineers. From land grading, deep foundation piling, and storm water canalization to concrete pavement laying and slope stabilization retaining structures.',
'Soil load bearing capacity testing; Micro-piling & raft foundation engineering; Heavy RCC retaining wall casting; Underground storm water drainage grids; High-load asphalt and concrete paving',
'Heavy equipment fleet deployment; Zero soil displacement safety record; Rigorous geotechnical compliance; Certified material testing lab',
'assets/images/services/civil-works.jpg', 'fa-trowel-bricks', 0, 1),

(5, 1, 'Structural Works', 'structural-works',
'RCC frame construction, pre-stressed concrete beams, steel framing systems, and seismic load management for complexes.',
'Precision structural engineering forms the core backbone of Raman Group. We execute heavy RCC superstructures, composite steel-concrete frames, long-span post-tension slabs, and cantilevered overhangs designed by senior structural engineers using SAP2000 and ETABS dynamic modeling.',
'Fe-550D TMT reinforcement bars; Computer-controlled concrete batching; Ultrasonic weld and beam inspection; High-strength structural steel joints; Thermal expansion joint installation',
'Certified earthquake resistance; High load endurance margins; Reduced column density for expansive open spans; Precision laser leveling',
'assets/images/services/structural-works.jpg', 'fa-cubes', 0, 1),

(6, 1, 'Turnkey Construction', 'turnkey-construction',
'Hassle-free complete project execution from concept, architecture, permits, procurement, civil build to final interior fit-outs.',
'Single-point accountability for your entire building journey. Our turnkey package spans architectural drafting, municipal sanctioning, raw material sourcing, structural erection, MEP installation, finishing, and luxury interior styling delivered on a fixed budget and timeline.',
'Single-window master contract; Daily cloud video/photo progress reporting; Fixed timeline with penalty delay clauses; Material price lock protection; End-to-end occupancy certificate clearance',
'Complete peace of mind; No multi-vendor management headaches; Guaranteed budget cap; Guaranteed completion date',
'assets/images/services/turnkey-construction.jpg', 'fa-key', 1, 1),


-- Interior Services (8 items)
(7, 2, 'Home Interiors', 'home-interiors',
'Complete luxury home interior transformations curated with bespoke furniture, lighting concepts, and premium surface finishes.',
'Our residential interior service crafts cohesive living environments tailored to your lifestyle. We blend ergonomically planned spatial layouts, custom wall paneling, Italian marble flooring treatments, ambient lighting control, and hand-selected upholstery to create sanctuary-like homes.',
'3D Photorealistic VR walkthroughs; Custom veneer and lacquer finishes; Smart lighting scene programming; Imported wallpaper & textured wall artistry; Custom acoustics & soundproofing',
'Personalized mood boards; Premium non-toxic eco-friendly materials; 5-year warranty on joinery; Maximum spatial utility',
'assets/images/services/home-interiors.jpg', 'fa-couch', 1, 1),

(8, 2, 'Living Room Interiors', 'living-room-interiors',
'Stunning statement living rooms featuring TV unit consoles, decorative accent walls, ambient chandeliers, and luxury seating.',
'Make an unforgettable impression with living room designs that harmonize entertainment, conversation, and spatial warmth. We craft floating media walls, back-lit onyx onyx marble panels, custom sectionals, fluted wall louvers, and architectural ceiling drops.',
'CNC-routed fluted wall panels; Integrated LED profile cove lighting; Custom Italian leather sectional sofas; Quartz & marble coffee table suites; Acoustic acoustic wall paneling',
'Spacious visual aesthetic; Smart entertainment wire management; Durable stain-resistant fabrics; Modern luxury ambiance',
'assets/images/services/living-room-interiors.jpg', 'fa-tv', 0, 1),

(9, 2, 'Bedroom Interiors', 'bedroom-interiors',
'Serene, ergonomic master bedroom suites with upholstered headboards, walk-in closets, vanity nooks, and mood lighting.',
'Designed for ultimate relaxation and organized storage. We engineer floor-to-ceiling sliding wardrobes with tinted glass, custom velvet padded accent headboard walls, hidden jewelry drawers, automated blackout curtain tracks, and reading nooks.',
'Walk-in wardrobes with sensor lighting; Custom fabric & leather padded headboards; Concealed HVAC & linear slot diffusers; Integrated bedside charging docks; Sound-dampening wall cladding',
'Restful acoustic climate; Seamless storage efficiency; Bespoke vanity and study nooks; High-end hotel suite luxury',
'assets/images/services/bedroom-interiors.jpg', 'fa-bed', 0, 1),

(10, 2, 'Kitchen Interiors', 'kitchen-interiors',
'Modern ergonomic modular kitchens equipped with soft-close hardware, quartz countertops, pull-out larders, and smart appliances.',
'We construct high-durability kitchen environments engineered with marine-grade BWP plywood, ceramic/lacquered glass fronts, Blum/Hettich soft-close hardware, Quartz/Granite anti-bacterial countertops, and optimized golden work triangle layouts.',
'Marine-grade 710 BWP Plywood carcasses; Blum Blumotion tandem drawers & lift systems; Quartz waterfall countertops; Built-in tall larder and magic corner units; Chimney & hobs integration',
'100% boiling waterproof structures; High heat & scratch resistance; Easy-clean surfaces; 10-year hardware warranty',
'assets/images/services/kitchen-interiors.jpg', 'fa-utensils', 1, 1),

(11, 2, 'Office Interiors', 'office-interiors',
'High-performance corporate workspaces, executive cabins, boardrooms, breakout zones, and ergonomic workstation clusters.',
'Boost organizational productivity with workplace interiors designed for collaboration, acoustic comfort, and brand representation. We engineer glass partition systems, ergonomic height-adjustable workstations, acoustic ceiling clouds, and high-tech AV boardrooms.',
'Double-glazed acoustic glass partitions; Cable management raceways & pop-up boxes; Acoustic ceiling baffles & carpet tiling; Executive desk suites & ergonomic mesh seating; Access control & biometric integration',
'Enhanced employee well-being & focus; Flexible hot-desking options; Professional corporate branding alignment; High sound insulation',
'assets/images/services/office-interiors.jpg', 'fa-briefcase', 1, 1),

(12, 2, 'Modular Furniture', 'modular-furniture',
'Factory-finished modular wardrobes, storage units, TV units, and office desks manufactured on precision German machinery.',
'Zero on-site dust and razor-sharp perfection. Our state-of-the-art manufacturing plant produces modular wardrobes, bookshelves, shoe consoles, and office desking using high-density HDMR boards, PUR edge-banding, and concealed German fittings.',
'PUR zero-joint laser edge banding; HDMR moisture-resistant core panels; Concealed multi-lock wardrobe mechanisms; Anti-scratch anti-fingerprint acrylic surfaces; Modular assembly for easy re-location',
'Rapid 30-day factory production; Precision 0.1mm tolerances; High moisture resistance; Easy modular panel replacement',
'assets/images/services/modular-furniture.jpg', 'fa-boxes-stacked', 0, 1),

(13, 2, 'False Ceiling Solutions', 'false-ceiling.jpg',
'Architectural Gypsum, POP, wooden louver, and metal grid false ceilings with magnetic track lighting channels.',
'Elevate spatial dimensions with designer ceiling profiles. We craft multi-tiered Gypsum ceilings, warmth-adding teak wood baffle grids, magnetic track lighting layouts, and acoustic micro-perforated ceiling panels for residences and offices.',
'Saint-Gobain Gypsum board installations; Galvanized steel ceiling framework; Magnetic low-voltage light track channels; Concealed AC grill integrations; Moisture-resistant ceiling tiles',
'Effective thermal insulation; Concealed electrical and ducting work; Dramatic lighting scenes; Acoustic sound control',
'assets/images/services/false-ceiling.jpg', 'fa-layer-group', 0, 1),

(14, 2, 'Lighting and Décor', 'lighting-decor',
'Layered architectural lighting designs, magnetic tracks, chandeliers, custom artwork, wallpapers, and soft furnishings.',
'Lighting transforms spaces from mundane to magical. We curate layered lighting setups combining focal magnetic spots, ambient COB profile strips, statement crystal chandeliers, art accent washers, along with matching drapery, rugs, and wall art.',
'High-CRI (95+) architectural LED fixtures; Smart DALI / Zigbee dimming controls; Custom brass & glass chandeliers; Handpicked fine art canvas curation; Custom motorized drapery',
'Energy-efficient LED technology; Mood-enhancing spatial warmth; Accentuated interior highlights; Complete visual harmony',
'assets/images/services/lighting-decor.jpg', 'fa-lightbulb', 0, 1),


-- Fabrication Services (9 items)
(15, 3, 'Steel Fabrication', 'steel-fabrication',
'Heavy structural steel engineering, PEB industrial sheds, steel bridges, warehouse trusses, and heavy load frames.',
'Raman Group handles heavy structural steel fabrication for industrial plants, logistics warehouses, overhead crane gantries, and commercial steel buildings. We weld and assemble high-yield steel section beams under rigorous AWS welding protocols.',
'AWS-certified MIG/TIG welding; Submerged arc beam fabrication; Sandblasting & epoxy zinc primer coating; Pre-engineered steel building (PEB) design; High-tensile bolt connections',
'Accelerated structural setup; Massive clear-span capabilities; Weather-proof protective coatings; Complete weld radiograph testing',
'assets/images/services/steel-fabrication.jpg', 'fa-industry', 1, 1),

(16, 3, 'MS Fabrication', 'ms-fabrication',
'Custom mild steel framing, industrial platforms, storage racks, machinery bases, and structural support enclosures.',
'Versatile and cost-effective mild steel solutions tailored for commercial, residential, and industrial applications. We manufacture heavy-duty MS mezzanines, fire escape staircases, utility platforms, security grates, and heavy equipment chassis.',
'Precision plasma & CNC laser cutting; Heavy gauge IS-2062 mild steel plates; Hot-dip galvanizing options; Powder coating finish in custom RAL colors; Structural load testing verification',
'High structural strength-to-cost ratio; Custom geometry capabilities; Corrosion-resistant finishing options; Long service life',
'assets/images/services/ms-fabrication.jpg', 'fa-hammer', 0, 1),

(17, 3, 'SS Fabrication', 'ss-fabrication',
'High-grade Stainless Steel (304 / 316) custom fixtures, architectural handrails, clean-room equipment, and decorative trim.',
'Uncompromising aesthetic finish and anti-corrosion resilience. We specialize in mirror-polished and brushed satin finish SS 304/316 fabrication for luxury architectural railings, hotel lobby features, pharmaceutical cleanrooms, and exterior facades.',
'Grade 304 & Marine Grade 316 stainless steel; Mirror (#8) and Hairline satin brush finishes; Argon gas TIG welding with seam grinding; PVD titanium gold/rose-gold color coatings; Laser cutting & NC bending',
'100% rust proof performance; Ultra-hygienic smooth surfaces; Premium mirror & gold luxury appeal; Zero maintenance requirement',
'assets/images/services/ss-fabrication.jpg', 'fa-shield-halved', 1, 1),

(18, 3, 'Designer Gates', 'designer-gates',
'Automated sliding gates, heavy wrought iron gates, modern laser-cut metal privacy gates, and compound wall entries.',
'Make a powerful entry statement with our custom designed safety gates. We fabricate heavy motor-compatible sliding gates, swing gates with teak/WPC wood overlays, CNC laser-cut geometry panels, and classical forged wrought iron gates.',
'Italian automated motor drive integration; Heavy-duty ball bearing hinges & guide rollers; Laser-cut 6mm steel pattern panels; WPC/Wood composite cladding options; Multi-layer anti-rust epoxy painting',
'High security perimeter protection; Smooth whisper-quiet automated operation; Striking kerb appeal; Weatherproof durability',
'assets/images/services/designer-gates.jpg', 'fa-door-closed', 0, 1),

(19, 3, 'Safety Grills', 'safety-grills',
'High-security window grills, balcony safety screens, burglar bars, and decorative architectural screening grates.',
'Protect your property without sacrificing architectural beauty. We build custom wrought iron grills, sleek stainless steel rod systems, CNC perforated security screens, and invisible wire balcony safety screens.',
'Solid MS square rod construction; Tamper-proof wall anchor anchors; Decorative scrollwork & geometric patterns; Hot-dip galvanized anti-corrosion coating; Custom powder coating',
'Maximum intrusion deterrence; Child-safe balcony protection; High ventilation & daylight pass-through; Elegant design profiles',
'assets/images/services/safety-grills.jpg', 'fa-border-all', 0, 1),

(20, 3, 'Architectural Railings', 'architectural-railings',
'Glass & SS composite railings, wrought iron balcony railings, brass handrails, and industrial safety guardrails.',
'Custom engineered stair and balcony railings that elevate safety and design aesthetics. Options include frameless toughened glass mounted on SS spigots, PVD coated stainless steel balustrades, brass accent handrails, and forged iron scrollwork.',
'12mm toughened / 1.52mm PVB laminated glass; SS 304 heavy spigots and top rail channels; Seamless curved pipe welding & grinding; Wooden top-rail integrations; Ergonomic grip design specs',
'Tested to withstand 1.5 kN/m lateral push load; Crystal-clear views; Rust and corrosion resistance; Modern luxury aesthetic',
'assets/images/services/architectural-railings.jpg', 'fa-bars-staggered', 0, 1),

(21, 3, 'Staircase Structures', 'staircase-structures',
'Spiral staircases, floating mono-stringer metal stairs, fire escape stair towers, and helical architectural stairs.',
'Engineering metal staircases that serve as striking architectural centerpieces. We fabricate cantilevered floating steel stair stringers with hardwood treads, compact spiral staircases, and robust exterior fire emergency escape stairwells.',
'Structural steel mono-stringer beam engineering; Heavy teak / marble tread mounting plates; Precision spiral center-post geometry; Anti-skid tread surface treatments; Concealed structural wall mounting anchors',
'Space-saving spatial efficiency; High load capacity; Dynamic modern architectural centerpiece; High fire rating compliance',
'assets/images/services/staircase-structures.jpg', 'fa-stairs', 0, 1),

(22, 3, 'Structural Fabrication', 'structural-fabrication',
'Canopy structures, skylight metal frames, factory catwalks, heavy equipment supports, and stadium seating trusses.',
'Specialized heavy structural metalwork engineered for complex architectural demands. We build space-frame glass skylight structures, petrol pump canopies, factory inspection catwalks, and heavy pipe racks.',
'3D structural node welding; High-load clear glass canopy frameworks; Heavy-duty galvanized grating platforms; Structural safety handrails; Vibration-dampening mount pads',
'Precision engineered load distribution; Weather-resistant structural sealing; Long span clear openings; High safety safety margins',
'assets/images/services/structural-fabrication.jpg', 'fa-warehouse', 0, 1),

(23, 3, 'Custom Metal Works', 'custom-metal-works',
'Bespoke metal art installations, brass room dividers, metal furniture frames, pergolas, and architectural facades.',
'Unleash creative architectural concepts with our precision custom metal studio. We turn custom metal drawings into reality, crafting brass partition screens, Corten steel outdoor sculptures, metal pergolas, and laser-cut building facade screens.',
'Laser cutting, waterjet machining & CNC bending; Corten steel weather-patina artwork; Brass, copper & bronze metal patinas; Custom aluminum louvers & facade panels; Architectural mock-up fabrication',
'Bespoke 1-of-1 architectural elements; High durability in extreme weather; Premium luxury finish options; Turnkey fabrication & erection',
'assets/images/services/custom-metal-works.jpg', 'fa-gem', 0, 1);


-- =========================================================
-- PROJECTS DATA (28 Total: 10 Construction, 10 Interior, 8 Fabrication)
-- =========================================================

-- Construction Projects (10 Items)
INSERT INTO projects (id, category_id, service_id, title, slug, location, client_name, start_date, completion_date, status, short_description, description, scope_of_work, materials_used, highlights, featured_image, is_featured, is_active) VALUES
(1, 1, 1, 'The Grand Horizon Luxury Villas', 'grand-horizon-luxury-villas', 'Sector 128, Noida', 'Apex Real Estate Ventures', '2023-01-15', '2024-06-30', 'Completed',
'Gated community of 12 ultraluxury 5-BHK villas featuring private infinity pools and smart home integration.',
'Raman Group executed complete civil, structural, and finishing works for The Grand Horizon enclave. Built on raft foundation with high-grade M30 concrete, the villas showcase double-height living spaces, cantilevered sun decks, solar power integration, and underground utility networks.',
'Excavation, foundation piling, RCC frame superstructure, waterproofing, brickwork, plastering, external textured paint, marble flooring base, driveways.',
'UltraTech PPC Cement, Tata Tiscon Fe550D TMT, Asian Paints Apex Ultima, Nexion Vitrified Tiles, Hindware Sanitaryware.',
'12 Luxury 5000 sq.ft villas completed 2 months ahead of schedule; 100% waterproof baseline; Earth-quake resistant design.',
'assets/images/projects/construction-1.jpg', 1, 1),

(2, 1, 2, 'Vanguard IT Business Park', 'vanguard-it-business-park', 'Cyber City, Gurugram', 'Vanguard Infrastructure Ltd.', '2022-08-10', '2024-03-15', 'Completed',
'A modern 14-story commercial IT tower with 250,000 sq.ft of Grade-A office space and triple basement parking.',
'Designed for tech enterprises, Vanguard IT Park features post-tensioned floor slabs to maximize column-free office spaces. Raman Group managed civil construction, double-glazed glass curtain wall structural framing, HVAC chiller plant basements, and high-speed elevator shafts.',
'Deep basement excavation, diaphragm walling, post-tensioned concrete slabs, fire tower shafts, glass facade steel framing, external hardscaping.',
'Jindal Steel beams, Ambuja Cement, Saint-Gobain structural glass panels, Schneider electric distribution grids, Grundfos pumps.',
'Column-free floor plates of 18,000 sq.ft; LEED Gold certified construction standards; Integrated 3-level basement parking.',
'assets/images/projects/construction-2.jpg', 1, 1),

(3, 1, 1, 'Skyline Residency Duplex Heights', 'skyline-residency-duplex-heights', 'Indirapuram, Ghaziabad', 'Skyline Infra Projects', '2023-03-01', '2024-08-20', 'Completed',
'Premium 18-story residential tower offering 72 premium duplex apartments with panoramic city views.',
'Constructed using modern aluminum formwork (Mivan technology) for rapid construction cycle and crack-free smooth monolithic concrete walls. Complete seismic zone IV compliance incorporated.',
'Mivan aluminum shuttering, RCC monolithic shear walls, plumbing risers, fire escape stair towers, elevator installation, exterior weather shield coating.',
'JSW Steel TMT, ACC Concrete, Dulux Weathershield, Astral CPVC pipes, Schneider switchgear.',
'Built 1 floor every 7 days using Mivan technology; High acoustic insulation between floors; Earthquake resistant shear wall matrix.',
'assets/images/projects/construction-3.jpg', 0, 1),

(4, 1, 3, 'Heritage Palace Hotel Restoration', 'heritage-palace-hotel-restoration', 'Civil Lines, Jaipur', 'Rajputana Heritage Hospitality', '2023-05-12', '2024-04-18', 'Completed',
'Complete structural retrofitting and heritage modernization of a 70-year-old hospitality estate.',
'Raman Group restored weakened RCC columns using carbon fiber wrapping and micro-concrete jacketing, repaired traditional sandstone facades, upgraded all MEP utilities, and constructed a new rear banquet pavilion.',
'Structural audit, column carbon fiber wrapping, micro-concrete jacketing, facade stone repointing, MEP rewiring, banqueting hall extension build.',
'Fosroc micro-concrete, Sika Carbon Fiber wrap, Dholpur Sandstone, Finolex cabling, Jaquar bath fittings.',
'Restored 45 guest rooms while preserving architectural heritage; Doubled load capacity of main banquet floor.',
'assets/images/projects/construction-4.jpg', 0, 1),

(5, 1, 6, 'Emerald Heights Turnkey Township', 'emerald-heights-turnkey-township', 'Greater Noida West', 'Emerald Developer Group', '2023-02-01', '2024-11-30', 'Completed',
'End-to-end turnkey construction of 24 independent residential row houses including boundary infrastructure.',
'Full master contract execution. From initial land grading, municipal drainage setup, RCC construction to premium internal plastering, driveways, boundary gates, and solar streetlight network.',
'Site development, underground storm drains, row house construction, road paving, utility connection clearance.',
'Tata Steel, Wonder Cement, Havells electricals, Kajaria tiles, Asian Paints.',
'Delivered 24 row houses on fixed turnkey price lock; Zero budget overrun; Complete occupancy certificate delivered.',
'assets/images/projects/construction-5.jpg', 1, 1),

(6, 1, 4, 'Apex Logistics Park Civil Earthworks', 'apex-logistics-park-civil', 'Sohna Road, Gurugram', 'Apex Logistics Corp', '2023-07-15', '2024-01-20', 'Completed',
'Massive 15-acre civil site development, heavy raft foundations, and concrete heavy vehicle aprons.',
'Conducted 45,000 cu.m of earth cut & fill, micro-piling foundation pads for heavy storage bays, RCC retaining retaining walls, and 150mm thick M30 grade concrete industrial flooring.',
'Land grading, soil compaction, micro-piling, RCC retaining wall construction, laser screed concrete flooring.',
'Ultratech M30/M40 concrete, Fiber-reinforced concrete additive, Corromax steel mesh.',
'Laser-screed ultra-flat industrial flooring with FM2 tolerance; Heavy vehicle axle load capacity up to 60 tons.',
'assets/images/projects/construction-6.jpg', 0, 1),

(7, 1, 5, 'Matrix Tower Structural Steel Frame', 'matrix-tower-structural-frame', 'Sector 63, Noida', 'Matrix Enterprises', '2023-09-01', '2024-05-10', 'Completed',
'Composite RCC and heavy structural steel frame construction for a 6-story commercial showspace.',
'Features long-span steel I-beams combined with metal deck slab casting. Allowed large open floor spans without interior columns, ideal for automotive showrooms and retail galleries.',
'Structural steel fabrication, column erection, decking sheet laying, shear stud welding, concrete slab pouring.',
'SAIL E350 Structural Steel, JSW decking sheets, Hilti anchor bolts, Ultratech concrete.',
'24-meter clear span without central columns; Reduced overall building weight by 25%; Faster structural lock-in.',
'assets/images/projects/construction-7.jpg', 0, 1),

(8, 1, 1, 'Serenity Farmhouse Villa Estate', 'serenity-farmhouse-villa-estate', 'Chattarpur, New Delhi', 'Private Estate Client', '2023-04-10', '2024-07-15', 'Completed',
'Luxury 8,000 sq.ft single-level modern courtyard villa with exposed concrete finishes and glass pavilions.',
'Constructed with fair-faced exposed RCC walls, cantilevered roof slabbing, subterranean wine cellar, and integrated perimeter security infrastructure.',
'Subterranean excavation, fair-face concrete formwork casting, cantilever slab engineering, swimming pool civil shell.',
'White cement blended concrete, Grade 316 SS rebar, Waterproofing membranes, Schott glass panels.',
'Striking architectural exposed concrete finish; 4-meter cantilevered patio roof; Custom heated pool civil structure.',
'assets/images/projects/construction-8.jpg', 0, 1),

(9, 1, 2, 'St. Mary Community Hospital Wing', 'st-mary-hospital-wing', 'Faridabad, Haryana', 'St. Mary Health Foundation', '2023-01-20', '2024-02-28', 'Completed',
'Construction of a new 50-bed emergency care wing with specialized radiation shielding walls.',
'Civil construction engineered to meet strict medical facility codes. Includes heavy barite concrete shielding walls for radiology units, anti-bacterial plastering, and emergency power backup vaults.',
'RCC framing, heavy barite concrete vault casting, anti-microbial wall renders, medical gas pipeline conduits, elevator wells.',
'Barite aggregate concrete, Tata TMT, Armstrong ceiling framework, Epoxy wall coatings.',
'Zero-radiation leakage certified radiology vault; Anti-microbial wall finish; 100% compliance with hospital building norms.',
'assets/images/projects/construction-9.jpg', 0, 1),

(10, 1, 3, 'Metro Plaza Mall Exterior Modernization', 'metro-plaza-mall-renovation', 'Rajouri Garden, New Delhi', 'Metro Retail Infra', '2023-10-01', '2024-04-30', 'Completed',
'Structural expansion, entrance atrium rebuild, and facade modernization for a busy retail shopping mall.',
'Raman Group added a 3-story glass steel atrium canopy, expanded footfall corridors, and reinforced main floor slabs for heavy escalators while keeping retail operations active.',
'Structural slab strengthening, steel atrium erection, glass canopy installation, escalator pit civil construction.',
'Jindal steel tubes, Toughened laminated glass, Sika structural adhesive, Pidilite building chemicals.',
'Executed during night shifts with zero interruption to active mall retail; Created iconic 20m high glass entry atrium.',
'assets/images/projects/construction-10.jpg', 0, 1),


-- Interior Projects (10 Items)
(11, 2, 7, 'The Penthouse at Imperial Towers', 'penthouse-imperial-towers', 'Golf Course Road, Gurugram', 'Private Client', '2023-06-01', '2023-12-15', 'Completed',
'A 6,500 sq.ft ultra-luxury penthouse featuring Italian marble, fluted timber paneling, and smart automation.',
'Complete interior architecture and luxury fit-out. Features book-matched Michelangelo marble flooring, custom brass inlaid wall louvers, motorized Italian leather furniture, and integrated Lutron lighting scenes.',
'Interior layout drafting, false ceiling engineering, Italian marble flooring laying, bespoke joinery, smart home AV automation.',
'Michelangelo Marble, Teak Veneer, Antique Brass trim, Lutron lighting controllers, Poliform furniture.',
'Featured in Architectural Digest India; Integrated smart mirror displays; Handcrafted Italian marble fireplace.',
'assets/images/projects/interior-1.jpg', 1, 1),

(12, 2, 11, 'Fintech Innovations HQ', 'fintech-innovations-hq', 'Cyber City, Gurugram', 'Fintech Innovations Ltd', '2023-04-15', '2023-09-30', 'Completed',
'Modern 35,000 sq.ft agile tech corporate office with acoustic pods, executive boardrooms, and gaming cafeteria.',
'Designed for 300+ tech professionals. Features open-plan collaborative desking, frameless double-glazed glass partitions, custom acoustic felt wall baffles, biophilic greenery walls, and high-tech video conference cabins.',
'Space planning, acoustic ceiling insulation, glass office partitioning, custom modular desking, electrical raceways, cafeteria interior.',
'Interface Carpet Tiles, Saint-Gobain Glass, Armstrong Acoustic Baffles, Herman Miller seating, Polyflor vinyl.',
'30% sound reverberation reduction; Ergonomic sit-stand workstation integration; Vibrant brand color accents.',
'assets/images/projects/interior-2.jpg', 1, 1),

(13, 2, 10, 'Chef Signature Modular Kitchen Villa', 'chef-signature-modular-kitchen', 'Vasant Vihar, New Delhi', 'Mr. Vikram Malhotra', '2023-08-10', '2023-11-20', 'Completed',
'State-of-the-art island culinary kitchen with lacquered glass fronts, quartz waterfall island, and Blum motion.',
'Designed for a passionate home chef. Includes twin pull-out pantries, concealed appliance garages, integrated wine chiller column, under-cabinet sensor lighting, and high-suction ceiling hood.',
'Civil kitchen modification, plumbing relocate, marine plywood carcass assembly, lacquered glass shutter fitting, quartz countertop installation.',
'710 BWP Plywood, Blum Servo-Drive hardware, Caesarstone Quartz, Hafele architectural lighting, Siemens appliances.',
'Motorized touch-to-open Blum drawers; 100% waterproof construction; Built-in coffee workstation nook.',
'assets/images/projects/interior-3.jpg', 0, 1),

(14, 2, 8, 'Aura Luxury Living & Media Room', 'aura-luxury-living-room', 'Sector 15, Noida', 'Dr. S. K. Nanda', '2023-07-01', '2023-09-15', 'Completed',
'Opulent formal living lounge featuring a 120-inch laser projection media wall, onyx back-lit bar, and plush seating.',
'Transformed a 1,200 sq.ft living hall into a multipurpose entertainment lounge. Features translucent onyx marble back-lit bar counter, acoustically treated velvet wall panels, and magnetic track lighting.',
'Wall acoustic panelling, back-lit onyx masonry, custom media wall console, hardwood flooring, ambient lighting installation.',
'Natural Honey Onyx, Acoustic Velvet fabric, Walnut timber, Osram LED strips, Sony 4K laser projector setup.',
'Striking back-lit onyx bar showcase; Concealed 7.1 Dolby Atmos speaker integration; Warm luxury hospitality feel.',
'assets/images/projects/interior-4.jpg', 0, 1),

(15, 2, 9, 'Royal Master Suite & Walk-in Closet', 'royal-master-suite-closet', 'Greater Kailash, New Delhi', 'Mrs. Ritu Kapoor', '2023-09-15', '2023-12-05', 'Completed',
'Bespoke master suite featuring a 14-foot diamond-tufted velvet headboard, tinted glass walk-in wardrobe, and vanity.',
'Designed for ultimate luxury. Features a floor-to-ceiling tinted glass wardrobe with internal proximity sensor lighting, concealed safe, custom bronze mirror vanity nook, and plush silk carpet flooring.',
'Bedroom interior redesign, wardrobe carcass & shutter fabrication, tufted headboard upholstery, ceiling drop design.',
'HDMR boards, Tinted toughened glass, Hafele wardrobe fittings, Silk carpet, Emerald velvet fabric.',
'Sensor-activated warm wardrobe LED bars; Soft-close jewelry pull-out trays; High acoustic privacy.',
'assets/images/projects/interior-5.jpg', 1, 1),

(16, 2, 11, 'NextGen Coworking Hub', 'nextgen-coworking-hub', 'Noida Electronic City', 'NextGen Spaces', '2023-02-10', '2023-07-25', 'Completed',
'Vibrant 20,000 sq.ft coworking hub with industrial exposed ceiling, phone booths, hot desks, and event arena.',
'Industrial-chic workplace design incorporating exposed painted HVAC ducts, wooden baffle ceiling drops, neon brand signage, custom phone booths, and high-density flexible seating.',
'Civil interior layout, exposed duct painting, wooden ceiling louvers, metal desk framing, power track installation.',
'Pine wood timber, Steel framing, Merino laminates, Greenpanel MDF, Philips LED track lights.',
'Accommodates 220 workspace seats; 4 Soundproof phone booths; High-impact Instagrammable aesthetic.',
'assets/images/projects/interior-6.jpg', 0, 1),

(17, 2, 12, 'Modular Executive Villa Furniture Suite', 'modular-executive-furniture-suite', 'Sushant Lok, Gurugram', 'Private Client', '2023-11-01', '2024-01-15', 'Completed',
'Complete factory-crafted modular wardrobe and storage package for a 4-story luxury residence.',
'Manufactured in our state-of-the-art automated plant. Delivered 14 custom sliding wardrobes, 4 study units, 6 vanity cabinets, and shoe consoles with 0.1mm precision PUR edge-banding.',
'Factory 3D drafting, CNC panel cutting, PUR edge banding, transport, dust-free assembly on site.',
'Action TESA HDMR, Hettich Sensys hinges, Rehau edge bands, Acrylic high-gloss sheets.',
'Assembled in 8 days with zero site dust; PUR zero-joint waterproof edges; 10-year hardware warranty.',
'assets/images/projects/interior-7.jpg', 0, 1),

(18, 2, 13, 'Luxe Lounge Ceiling & Lighting Studio', 'luxe-lounge-ceiling-lighting', 'South Extension, New Delhi', 'Luxe Retails', '2023-10-10', '2023-12-20', 'Completed',
'Architectural multi-tier gypsum false ceiling with magnetic track lighting and decorative teak wood louvers.',
'Created a dynamic ceiling sculpture for a luxury jewellery boutique. Integrates recessed magnetic spot lights, continuous COB LED strips, and CNC-cut wooden ceiling baffles.',
'Ceiling framing, Gypsum board installation, magnetic track channel fitting, timber baffle mounting, COB light testing.',
'Saint-Gobain Gypsum, Custom Brass magnetic track lights, Teak wood louvers, Osram LED drivers.',
'98 CRI lighting true color rendering for jewelry display; Modern sculptural ceiling layout; Low energy draw.',
'assets/images/projects/interior-8.jpg', 0, 1),

(19, 2, 7, 'Minimalist Japandi Villa Interior', 'minimalist-japandi-villa-interior', 'Sector 50, Noida', 'Mr. A. Rastogi', '2023-05-01', '2023-08-30', 'Completed',
'Harmonious Japandi style interior pairing warm natural white oak, micro-cement walls, and minimalist neutral decor.',
'A peaceful home design embracing simplicity. Features seamless micro-cement plaster walls, light oak joinery, low-profile Japanese style platform beds, paper pendant lamps, and sliding Shoji-inspired screen doors.',
'Wall micro-cement rendering, oak wood joinery fabrication, custom low furniture craft, soft linen drapery.',
'Ideal Work Micro-cement, Natural White Oak timber, Natural Linen fabrics, Rice paper light fixtures.',
'Calming organic aesthetic; Seamless micro-cement continuous flooring; High natural daylight optimization.',
'assets/images/projects/interior-9.jpg', 0, 1),

(20, 2, 11, 'Dr. Smile Dental Clinic & Spa', 'dr-smile-dental-clinic-spa', 'Green Park, New Delhi', 'Dr. Ananya Roy', '2023-03-15', '2023-06-20', 'Completed',
'Calming, hygienic luxury dental clinic interior featuring curved corridors, pastel tones, and concealed equipment.',
'Replaced traditional cold clinical aesthetics with a warm spa-like patient lounge. Features curved ribbed glass partitions, anti-microbial solid surface countertops, ambient indirect illumination, and acoustic consultation rooms.',
'Layout planning, curved drywall erection, solid surface desk fabrication, dental chair utility plumbing, acoustic doors.',
'Corian Solid Surface, Fluted Glass, Anti-microbial laminate, Havells LED indirect lighting.',
'Anxiety-reducing warm spa design; 100% anti-bacterial surfaces; Seamless hidden utility line routing.',
'assets/images/projects/interior-10.jpg', 0, 1),


-- Fabrication Projects (8 Items)
(21, 3, 15, 'Industrial PEB Warehouse Shed', 'industrial-peb-warehouse-shed', 'Manesar Industrial Area, Gurugram', 'Logistics Park India', '2023-01-10', '2023-06-15', 'Completed',
'50,000 sq.ft heavy structural steel Pre-Engineered Building (PEB) warehouse with 30-meter clear span trusses.',
'Raman Group fabricated and erected heavy steel column sections, roof trusses, crane beams, and insulated metal roofing sheets. Built to withstand 160 km/h wind speeds and heavy overhead crane loads.',
'Structural steel design, shop MIG welding, sandblasting, epoxy primer coating, site crane erection, roof sheeting.',
'SAIL Steel IS-2062 Grade E250/E350, Tata BlueScope Galvalume roof sheets, High-tensile grade 8.8 bolts.',
'30-Meter clear span without internal pillars; Erection completed in 45 days; 10-Ton overhead crane support beam.',
'assets/images/images/fabrication-1.jpg', 1, 1),

(22, 3, 17, 'The Grand Atrium SS 316 Glass Railing', 'grand-atrium-ss-glass-railing', 'Mall of Noida, Sector 18', 'Apex Retail Malls', '2023-05-15', '2023-09-10', 'Completed',
'1,200 linear feet of heavy mirror-polished SS 316 and 15mm toughened glass atrium safety railings.',
'Fabricated custom heavy-duty stainless steel base shoe covers and handrails with mirror polish (#8 finish). Installed around multi-story mall atrium voids for high safety and crystal-clear structural visibility.',
'SS 316 CNC laser plate cutting, TIG welding, mirror polishing, 15mm glass panel mounting, safety load testing.',
'Grade 316 Stainless Steel, 15mm Toughened Clear Glass, Sika structural silicone sealant.',
'Passed 2.0 kN/m heavy crowd load pressure testing; Mirror finish with zero weld seam visibility; Weather resistant.',
'assets/images/fabrication-2.jpg', 1, 1),

(23, 3, 18, 'Automated Laser-Cut Steel Security Gate', 'automated-laser-cut-security-gate', 'Vasant Kunj, New Delhi', 'Brigadier R. S. Mehta (Retd.)', '2023-08-01', '2023-09-25', 'Completed',
'22-foot automated heavy sliding gate featuring 8mm CNC laser-cut geometric pattern steel and teak WPC louvers.',
'Engineered a heavy-duty motorized sliding entry gate equipped with dual Italian Beninca motor drive, infrared safety sensors, backup battery, and anti-corrosion hot-dip galvanizing.',
'Gate CAD design, CNC laser cutting, frame structural welding, hot-dip galvanizing, motor installation & testing.',
'8mm Mild Steel plate, WPC timber slats, Beninca Italian Sliding Gate Motor, Epoxy Primer & PU Paint.',
'Smooth whisper-quiet remote operation; Heavy wind resistance; Hot-dip galvanized 20-year rust guarantee.',
'assets/images/fabrication-3.jpg', 0, 1),

(24, 3, 21, 'Floating Structural Mono-Stringer Staircase', 'floating-mono-stringer-staircase', 'Golf Course Extension, Gurugram', 'Mr. D. Chawla', '2023-07-10', '2023-09-15', 'Completed',
'Architectural center mono-stringer steel staircase with cantilevered solid teak wood treads and SS glass guardrail.',
'Fabricated a central 300x150mm heavy steel box section stringer beam anchored into concrete floor slabs. Treads cantilever outward effortlessly with zero visible underside supports.',
'Structural steel stringer beam fabrication, floor anchor bracket welding, teak wood tread mounting, glass balustrade fit.',
'IS-2062 Heavy Steel Box Section, 45mm Solid Burma Teak Treads, 12mm Toughened Glass.',
'Striking sculptural modern staircase design; Deflection tested to under 1.5mm under 500kg load; Clean minimalist footprint.',
'assets/images/fabrication-4.jpg', 0, 1),

(25, 3, 16, 'Corporate Fire Escape Stair Tower', 'corporate-fire-escape-stair-tower', 'Okhla Industrial Area, New Delhi', 'Apex Commercial Complex', '2023-03-01', '2023-05-20', 'Completed',
'5-Story external structural steel emergency fire escape staircase tower with anti-skid chequered steps.',
'Engineered external structural steel escape tower with heavy hot-dip galvanizing for complete weatherproof exposure. Features continuous safety handrails and anti-slip stair treads.',
'Structural calculation, 5-story steel cage fabrication, hot-dip galvanizing, site crane hoisting & anchor bolting.',
'Heavy Steel Channels & Angles, 6mm Chequered Steel Tread Plates, Galvanized handrail pipes.',
'100% Fire Department safety clearance compliance; Hot-dip galvanized for zero maintenance; Rapid emergency evacuation capacity.',
'assets/images/fabrication-5.jpg', 0, 1),

(26, 3, 22, 'Glass Skylight Structural Steel Space Canopy', 'glass-skylight-steel-canopy', 'Civil Lines, Delhi', 'Hotel Royal Grand', '2023-09-01', '2023-11-15', 'Completed',
'A 2,400 sq.ft clear glass skylight canopy supported by a precision fabricated steel space-frame grid.',
'Fabricated a lightweight high-strength tubular steel space frame spanning over a hotel courtyard. Fitted with laminated solar-control glass panels for year-round weather protection and natural lighting.',
'Tubular steel truss fabrication, high-precision node joint welding, site crane lifting, glass seal installation.',
'Tata Structura Hollow Steel Tubes, 13.52mm SentryGlas Laminated Glass, Dow Corning 795 sealant.',
'Leak-proof 10-year warranty seal; 80% natural daylight transmission with 60% solar heat reduction.',
'assets/images/fabrication-6.jpg', 0, 1),

(27, 3, 23, 'PVD Gold SS Architectural Partition Screens', 'pvd-gold-ss-partition-screens', 'Model Town, New Delhi', 'Luxe Jewelers', '2023-10-01', '2023-11-28', 'Completed',
'Custom PVD Titanium Gold coated SS 304 laser-cut geometric room divider screens for a high-end luxury showroom.',
'Precision CNC laser-cut 4mm stainless steel panels with electroplated PVD Rose-Gold finish. Assembled into floor-to-ceiling rotatable architectural divider partitions.',
'CAD pattern drafting, CNC fiber laser cutting, PVD titanium coating, brass pivot hinge assembly, installation.',
'Grade 304 Stainless Steel, PVD Titanium Gold Coating, Solid Brass Pivot Hardware.',
'Scratch-resistant PVD gold finish that never tarnishes; Rotatable 360-degree spatial adjustment; Luxury visual focus.',
'assets/images/fabrication-7.jpg', 1, 1),

(28, 3, 19, 'Custom Wrought Iron Balcony Safety Grills', 'wrought-iron-balcony-safety-grills', 'Rajendra Nagar, Ghaziabad', 'Raman Residency Association', '2023-04-01', '2023-06-10', 'Completed',
'Custom forged wrought iron safety grills and decorative window guard rails for 36 residential apartments.',
'Hand-forged ornamental scrolls combined with heavy solid square steel bars. Finished with zinc phosphate anti-rust treatment and matte black PU enamel paint.',
'Hand forging scrollwork, frame assembly, anti-rust dipping, spray painting, multi-point wall anchoring.',
'Solid 14mm Square Steel Bars, Forged Iron Scrolls, Asian Paints PU Enamel Black.',
'High burglar-proof protection; Elegant classical architectural styling; Anti-rust coating for long outdoor durability.',
'assets/images/fabrication-8.jpg', 0, 1);


-- =========================================================
-- GALLERY DATA (10 Items)
-- =========================================================
INSERT INTO gallery (id, category_id, title, image_path, location, is_active) VALUES
(1, 1, 'Commercial Tower Foundation Piling', 'assets/images/gallery/gallery-1.jpg', 'Gurugram', 1),
(2, 1, 'Luxury Villa Civil Structure Casting', 'assets/images/gallery/gallery-2.jpg', 'Noida', 1),
(3, 2, 'Penthouse Book-Matched Marble Living Hall', 'assets/images/gallery/gallery-3.jpg', 'Gurugram', 1),
(4, 2, 'Modern Modular Island Kitchen Fitting', 'assets/images/gallery/gallery-4.jpg', 'New Delhi', 1),
(5, 2, 'Corporate Office Glass Conference Room', 'assets/images/gallery/gallery-5.jpg', 'Noida', 1),
(6, 3, 'Heavy PEB Steel Truss Welding', 'assets/images/gallery/gallery-6.jpg', 'Manesar', 1),
(7, 3, 'SS 316 Glass Atrium Railing Assembly', 'assets/images/gallery/gallery-7.jpg', 'Noida', 1),
(8, 3, 'Automated Motorized Laser-Cut Sliding Gate', 'assets/images/gallery/gallery-8.jpg', 'New Delhi', 1),
(9, 1, 'Renovation & Column Jacketing Work', 'assets/images/gallery/gallery-9.jpg', 'Jaipur', 1),
(10, 2, 'Japandi Style Bedroom Micro-Cement Finish', 'assets/images/gallery/gallery-10.jpg', 'Noida', 1);


-- =========================================================
-- TESTIMONIALS (8 Items)
-- =========================================================
INSERT INTO testimonials (id, client_name, client_title, company, rating, content, client_image, project_type, is_featured, is_active) VALUES
(1, 'Rajesh Singhania', 'Managing Director', 'Singhania Logistics Ltd.', 5,
'Raman Group delivered our 50,000 sq.ft industrial PEB warehouse ahead of schedule with flawless precision. Their structural steel fabrication quality and site safety standards are truly world-class.',
'assets/images/testimonials/client-1.jpg', 'Structural Steel Fabrication', 1, 1),

(2, 'Sunita Sharma', 'Homeowner', 'Grand Horizon Villas', 5,
'From civil foundation to turnkey luxury interior finishes, Raman Group transformed our dream villa into reality. The attention to detail in modular kitchen joinery and marble floor laying was superb!',
'assets/images/testimonials/client-2.jpg', 'Turnkey Residential Villa', 1, 1),

(3, 'Amitabh Verma', 'Vice President - Facilities', 'Fintech Innovations', 5,
'We engaged Raman Group for our 35,000 sq.ft corporate office interior. Their team executed double-glazed glass partitions and acoustic ceilings seamlessly. Highly professional execution!',
'assets/images/testimonials/client-3.jpg', 'Corporate Office Interior', 1, 1),

(4, 'Dr. K. S. Oberoi', 'Director', 'Oberoi Diagnostic Center', 5,
'Executing radiology vault concrete shielding requires extreme precision. Raman Group civil team nailed the barite concrete specs on the first pass and cleared all safety radiation audits instantly.',
'assets/images/testimonials/client-4.jpg', 'Hospital Civil Infrastructure', 0, 1),

(5, 'Meera Kapoor', 'Interior Architect', 'Studio Spatial', 5,
'As an architect, finding a reliable fabrication partner who can execute complex SS 316 glass railings and PVD gold metal screens is tough. Raman Group is now our go-to partner!',
'assets/images/testimonials/client-5.jpg', 'Architectural SS Fabrication', 1, 1),

(6, 'Vikramaditya Rathore', 'General Manager', 'Rajputana Heritage Hotel', 5,
'Restoring a 70-year-old heritage hotel without compromising structural stability is challenging. Raman Group carbon fiber column jacketing and civil restoration saved our historic property.',
'assets/images/testimonials/client-6.jpg', 'Heritage Civil Renovation', 0, 1),

(7, 'Ananya Deshmukh', 'Operations Lead', 'NextGen Coworking', 5,
'The team created an incredible industrial-chic vibe for our coworking space. Their factory modular furniture precision saved us weeks of site work.',
'assets/images/testimonials/client-7.jpg', 'Modular Workspace Interior', 0, 1),

(8, 'Brig. Harpal Singh (Retd.)', 'Resident Association President', 'Chattarpur Estates', 5,
'Raman Group fabricated our automated compound security gates and wrought iron perimeter grills. Extremely sturdy, smooth automation, and magnificent kerb appeal!',
'assets/images/testimonials/client-8.jpg', 'Automated Security Gates', 0, 1);


-- =========================================================
-- TEAM MEMBERS (4 Items)
-- =========================================================
INSERT INTO team_members (id, name, designation, bio, image, experience_years, email, phone, sort_order, is_active) VALUES
(1, 'Ramanpreet Singh', 'Founder & Managing Director', 'Over 22 years of civil engineering and structural steel fabrication expertise leading large industrial and turnkey projects.', 'assets/images/team/team-1.jpg', 22, 'raman@ramangroup.com', '+91 98765 43210', 1, 1),
(2, 'Ar. Neha Saxena', 'Head of Interior Architecture', 'Gold medalist architect specialized in luxury residential interiors, spatial acoustics, and contemporary workplace ergonomics.', 'assets/images/team/team-2.jpg', 14, 'neha@ramangroup.com', '+91 98765 43212', 2, 1),
(3, 'Eng. Rajiv Malhotra', 'Chief Structural Engineer', 'Certified M.Tech structural consultant with deep mastery in seismic load modeling, PEB steel structures, and high-rise foundations.', 'assets/images/team/team-3.jpg', 18, 'rajiv@ramangroup.com', '+91 98765 43213', 3, 1),
(4, 'Suresh Vishwakarma', 'Head of Metal Fabrication', 'Master craftsman managing automated CNC laser cutting, AWS certified welding lines, and stainless steel finishing.', 'assets/images/team/team-4.jpg', 20, 'suresh@ramangroup.com', '+91 98765 43214', 4, 1);


-- =========================================================
-- FAQS (8 Items)
-- =========================================================
INSERT INTO faqs (id, category_id, question, answer, sort_order, is_active) VALUES
(1, 1, 'Does Raman Group provide complete Turnkey Construction services?',
'Yes! Our turnkey construction package covers everything from architectural layout planning, municipal building plan approval, civil excavation, structural RCC building, utility installation (MEP), to final luxury interior fit-outs under a single master contract with fixed price & timeline guarantees.', 1, 1),

(2, 1, 'What structural warranty do you offer on residential and commercial builds?',
'We provide an industry-leading 10-year structural warranty on all civil RCC framing and structural steel erection, backed by rigorous IS-spec material testing certificates and non-destructive quality audits.', 2, 1),

(3, 2, 'How do you ensure zero site dust during modular interior installation?',
'All our modular wardrobes, kitchen cabinets, and desking units are manufactured at our automated factory using German CNC machinery and PUR edge-banding. Panels arrive on-site pre-drilled and flat-packed, requiring simple dust-free mechanical assembly.', 3, 1),

(4, 2, 'Can I view 3D visual designs before interior execution begins?',
'Absolutely. We create photorealistic 3D renders and interactive 360-degree VR walkthroughs for all interior projects so you can experience exact materials, lighting, textures, and spatial flows prior to fabrication.', 4, 1),

(5, 3, 'What grades of stainless steel do you use for exterior railings and gates?',
'We exclusively use Grade 304 and Marine-Grade 316 Stainless Steel for outdoor railings, gates, and facade screens. SS 316 offers superior resistance to corrosion and environmental weathering.', 5, 1),

(6, 3, 'Are your automated sliding gates compatible with smart home systems?',
'Yes, our automated gates feature Italian motor drives (Beninca / Came) that integrate seamlessly with smartphone apps, RFID vehicle tags, video door phones, and smart home automation hubs.', 6, 1),

(7, NULL, 'What is the typical timeline for a complete villa construction project?',
'A standard 4,000 to 6,000 sq.ft luxury villa typically takes 10 to 14 months from excavation to turnkey completion, depending on elevation complexity and basement requirements. We provide a detailed MS Project timeline upon contract signoff.', 7, 1),

(8, NULL, 'How can I request a site inspection and detailed cost estimate?',
'You can submit a query through our "Get a Quote" form or contact us directly on WhatsApp at +91 98765 43210. Our technical site engineer will conduct a free site survey and deliver an itemized BOQ within 48 hours.', 8, 1);

-- =========================================================
-- SAMPLE ENQUIRIES & QUOTE REQUESTS FOR DEMO DASHBOARD
-- =========================================================
INSERT INTO enquiries (full_name, phone, email, service_category, project_type, location, budget_range, message, preferred_contact_date, status, admin_notes) VALUES
('Rohan Mehta', '+91 98111 22334', 'rohan.mehta@gmail.com', 'Construction', 'Turnkey Villa', 'Sector 150, Noida', '₹1 Cr - ₹2 Cr', 'Looking for end-to-end turnkey construction of a 4500 sq ft 5BHK luxury villa on Plot 42.', '2024-09-15', 'New', 'Customer called via website form.'),
('Priya Sharma', '+91 98222 33445', 'priya.sharma@outlook.com', 'Interior Design', 'Home Interiors', 'Golf Course Extension, Gurugram', '₹25 Lakh - ₹50 Lakh', 'Require full interior design for a 4BHK 2800 sq ft apartment including modular kitchen & living room wall panels.', '2024-09-12', 'In Discussion', 'Sent catalog and initial moodboard.'),
('Vikram Gupta', '+91 98333 44556', 'vgupta@techno.com', 'Fabrication', 'Steel Fabrication', 'Manesar Industrial Area', '₹50 Lakh - ₹1 Cr', 'Need quote for 25,000 sq ft structural steel PEB shed fabrication and crane beam erection.', '2024-09-10', 'Quotation Sent', 'BOQ prepared and emailed to client.');

INSERT INTO quote_requests (full_name, phone, email, service_category, project_type, location, estimated_budget, project_description, status, admin_notes) VALUES
('Sunil Agarwal', '+91 98444 55667', 'sunil@agarwalinfra.com', 'Construction', 'Commercial Building', 'Greater Noida West', '₹3 Cr - ₹5 Cr', 'Requirement for G+4 commercial complex civil structural work. Blueprint attached in request.', 'Pending', 'Awaiting structural review.'),
('Kavita Reddy', '+91 98555 66778', 'kavita.reddy@gmail.com', 'Interior Design', 'Modular Kitchen', 'Vasant Kunj, Delhi', '₹10 Lakh - ₹15 Lakh', 'Looking for acrylic finish lacquered glass modular kitchen with Blum hardware and quartz top.', 'Quotation Prepared', 'Quote draft #RQ-204 ready.');
