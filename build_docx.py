import os
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def create_system_design_docx():
    doc = docx.Document()

    # Page Setup - Normal Margins (1 inch)
    for section in doc.sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(1.0)

    # Styles Setup
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Arial'
    normal_style.font.size = Pt(11)
    normal_style.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A) # Dark slate
    normal_style.paragraph_format.line_spacing = 1.15
    normal_style.paragraph_format.space_after = Pt(6)

    # Document Header / Title
    p_title = doc.add_paragraph()
    p_title.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_title = p_title.add_run("HOUR WASH LAUNDRY SHOP MANAGEMENT SYSTEM")
    r_title.bold = True
    r_title.font.size = Pt(20)
    r_title.font.color.rgb = RGBColor(0x03, 0x69, 0xA1) # Deep Blue

    p_sub = doc.add_paragraph()
    p_sub.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_sub = p_sub.add_run("System Design Specification & Unified Modeling Language (UML) Diagrams")
    r_sub.font.size = Pt(13)
    r_sub.font.color.rgb = RGBColor(0x47, 0x55, 0x69)
    p_sub.paragraph_format.space_after = Pt(18)

    # Horizontal Divider Line
    p_div = doc.add_paragraph()
    r_div = p_div.add_run("―" * 55)
    r_div.font.color.rgb = RGBColor(0xCB, 0xD5, 0xE1)
    p_div.paragraph_format.space_after = Pt(18)

    # ---------------------------------------------------------
    # SECTION 1: SYSTEM DESIGN DIAGRAM
    # ---------------------------------------------------------
    h1 = doc.add_heading(level=1)
    r_h1 = h1.add_run("System Design Diagram")
    r_h1.font.name = 'Arial'
    r_h1.font.size = Pt(16)
    r_h1.bold = True
    r_h1.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc1 = doc.add_paragraph()
    p_disc1.add_run("System Design Discussion:\n").bold = True
    p_disc1.add_run(
        "The HourWash Laundry Shop Management System adopts a modern, multi-tiered, decoupled application architecture designed to deliver high reliability, system performance, and real-time operational efficiency for laundry shop management. Built on top of the Laravel 11/12 framework (running on PHP 8.5) and styled using Tailwind CSS and Vite, the architecture enforces a strict Separation of Concerns across six distinct functional layers:\n\n"
        "1. Presentation / User Interface Layer: Consists of four tailored web and mobile portals:\n"
        "   • Customer Web Portal: Enables clients to submit laundry requests, select wash/dry/fold services, track real-time order status, apply promotional coupon codes, scan QR codes, and submit ratings & feedback.\n"
        "   • Staff Management Console: Provides store operators with laundry queue control, load weight recording, washing machine and dryer fleet allocation, brownout time extension (+60 mins) triggers, and digital receipt printing.\n"
        "   • Rider Mobile Dashboard: Equips delivery personnel with real-time pickup and delivery task assignments, customer delivery addresses, and live status update controls.\n"
        "   • Admin Analytics Portal: Grants system administrators control over user profiles, service pricing catalogs, machine inventory, promotional campaigns, financial sales/profit analytics, and audit logs.\n\n"
        "2. Security & Routing Middleware Layer: Intercepts all inbound HTTP/HTTPS requests to enforce Role-Based Access Control (RBAC) via AdminMiddleware, StaffMiddleware, CustomerMiddleware, and RiderMiddleware, while maintaining security headers (CSRF protection, XSS filtering, content security policy).\n\n"
        "3. Application Controller Layer: Processes user requests and business rules through dedicated controllers including LaundryController, MachineController, ServiceController, QrScanLogController, and AnalyticsController.\n\n"
        "4. Domain & Asynchronous Service Layer: Executes complex business workflows, including SmsNotificationService for real-time customer SMS alerts, EmailNotificationService for order mailables (OrderStatusUpdated), and SendSmsJob for background job queuing.\n\n"
        "5. Persistence Layer: Utilizes a relational MySQL database accessed via Laravel's Eloquent ORM. Tables store normalized entity records including users, customer_profiles, staff_profiles, orders, laundries, machines, pickup_deliveries, promotions, qr_codes, and qr_scan_logs.\n\n"
        "6. External Integration Layer: Connects third-party cloud REST APIs and SMTP gateways, including Twilio/Semaphore SMS API for automated SMS dispatching and SMTP Mail Gateway for email communications."
    )

    # Insert Figure 1
    p_fig1 = doc.add_paragraph()
    p_fig1.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig1.paragraph_format.space_before = Pt(12)
    p_fig1.paragraph_format.space_after = Pt(4)
    run_fig1 = p_fig1.add_run()
    run_fig1.add_picture('diagrams/system_design_diagram.png', width=Inches(6.2))

    p_cap1 = doc.add_paragraph()
    p_cap1.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap1 = p_cap1.add_run("Figure 1: High-Level System Architecture Diagram of HourWash Laundry Shop Management System")
    r_cap1.italic = True
    r_cap1.font.size = Pt(9.5)
    r_cap1.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp1 = doc.add_paragraph()
    p_interp1.add_run("Figure 1 Interpretation:\n").bold = True
    p_interp1.add_run(
        "Figure 1 illustrates the end-to-end multi-tier architectural request flow of the HourWash system. When a user interacts with one of the front-end web portals (Presentation Layer), the request is transmitted via HTTPS to the Web Server. The Security & Middleware Layer inspects user authorization tokens and CSRF credentials before routing the request to the appropriate Application Controller. The controller executes business logic, invokes Eloquent ORM models to query or mutate records in the MySQL Persistence Layer, and dispatches background tasks to the Service Layer when asynchronous notifications (SMS/Email) are needed. This decoupled setup ensures high throughput, robust security, and seamless fault tolerance."
    )

    # ---------------------------------------------------------
    # SECTION 2: USE CASE DIAGRAM
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h2_uc = doc.add_heading(level=1)
    r_h2_uc = h2_uc.add_run("Use Case Diagram")
    r_h2_uc.font.name = 'Arial'
    r_h2_uc.font.size = Pt(16)
    r_h2_uc.bold = True
    r_h2_uc.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc2 = doc.add_paragraph()
    p_disc2.add_run("Use Case Diagram Discussion:\n").bold = True
    p_disc2.add_run(
        "The Use Case Diagram defines the functional boundary of the HourWash system and illustrates the interactions between system features and its four primary human actors: Customer, Staff / Store Operator, Rider / Logistics Personnel, and System Administrator:\n\n"
        "• Customer Actor: Responsible for account creation and login (UC1), placing laundry orders and selecting wash/dry/fold services (UC2), requesting pickup & delivery options (UC3), tracking real-time order status (UC4), applying promotional discount coupons (UC5), and submitting order ratings & feedback (UC6).\n"
        "• Staff / Store Operator Actor: Responsible for receiving and weighing incoming laundry loads (UC7), assigning idle washing machines and dryers (UC8), applying brownout time extensions (+60 mins) during store power interruptions (UC9), and scanning order QR codes to issue digital receipts (UC10).\n"
        "• Rider / Logistics Personnel Actor: Accesses the Rider Dashboard to view active task queues (UC11), updates pickup logistics status (UC12), and updates delivery logistics status to 'Delivered' (UC13).\n"
        "• System Administrator Actor: Manages user accounts and role permissions (UC14), configures service items and weight pricing tariffs (UC15), manages machine fleet inventory and promotions (UC16), and analyzes sales, daily profit reports, and audit logs (UC17)."
    )

    p_fig2 = doc.add_paragraph()
    p_fig2.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig2.paragraph_format.space_before = Pt(12)
    p_fig2.paragraph_format.space_after = Pt(4)
    run_fig2 = p_fig2.add_run()
    run_fig2.add_picture('diagrams/use_case_diagram.png', width=Inches(6.2))

    p_cap2 = doc.add_paragraph()
    p_cap2.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap2 = p_cap2.add_run("Figure 2: HourWash System Use Case Diagram")
    r_cap2.italic = True
    r_cap2.font.size = Pt(9.5)
    r_cap2.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp2 = doc.add_paragraph()
    p_interp2.add_run("Figure 2 Interpretation:\n").bold = True
    p_interp2.add_run(
        "Figure 2 visually highlights the functional scope of the HourWash system across its 17 core use cases enclosed within the system boundary box. Associations connect each actor to their permissible interactions. Use cases contain structural relationships such as <<include>> for mandatory sub-processes (e.g., Placing an order includes selecting services) and <<extend>> for optional paths (e.g., Applying a promo code during order placement). This ensures clarity in system requirements and role boundary enforcement."
    )

    # ---------------------------------------------------------
    # SECTION 3: CLASS DIAGRAM
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h3_cd = doc.add_heading(level=1)
    r_h3_cd = h3_cd.add_run("Class Diagram")
    r_h3_cd.font.name = 'Arial'
    r_h3_cd.font.size = Pt(16)
    r_h3_cd.bold = True
    r_h3_cd.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc3 = doc.add_paragraph()
    p_disc3.add_run("Class Diagram Discussion:\n").bold = True
    p_disc3.add_run(
        "The Class Diagram presents the static object-oriented domain model of the HourWash application, detailing core entity classes, data attributes, methods, visibility access modifiers (+ public, - private), and structural associations. The domain entities directly mirror Laravel Eloquent ORM models:\n\n"
        "• User: Central authentication class containing user ID, name, email, role, and password hash. Has a 1-to-1 relationship with CustomerProfile and StaffProfile, and a 1-to-many relationship with Order.\n"
        "• Order: Primary transaction entity storing order_number, user_id, total_amount, discount_amount, and order status (Pending, Weighed, In Wash, In Dry, Ready, Delivered, Cancelled). Maintains relationships with Laundry (1-to-many), PickupDelivery (1-to-1), QrCode (1-to-1), OrderStatusHistory (1-to-many), and CustomerFeedback (1-to-1).\n"
        "• Laundry: Represents load items within an order, storing weight_kg, service_id, and machine_id. Links an Order to an assigned Machine and Service.\n"
        "• Machine: Represents physical store equipment (washing machines and dryers). Contains machine_number, type (Washer/Dryer), status (Idle, Washing, Drying, Out of Service), and brownout_extension (boolean). Implements methods assignOrder() and addBrownoutTime().\n"
        "• PickupDelivery: Manages logistics state including rider_id, pickup_address, delivery_address, and delivery_status.\n"
        "• QrCode & QrScanLog: Handles order verification. QrCode stores the unique hash string, while QrScanLog maintains immutable audit logs of scan events (scanned_by staff ID, scanned_at timestamp)."
    )

    p_fig3 = doc.add_paragraph()
    p_fig3.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig3.paragraph_format.space_before = Pt(12)
    p_fig3.paragraph_format.space_after = Pt(4)
    run_fig3 = p_fig3.add_run()
    run_fig3.add_picture('diagrams/class_diagram.png', width=Inches(6.2))

    p_cap3 = doc.add_paragraph()
    p_cap3.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap3 = p_cap3.add_run("Figure 3: HourWash System Unified Class Diagram")
    r_cap3.italic = True
    r_cap3.font.size = Pt(9.5)
    r_cap3.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp3 = doc.add_paragraph()
    p_interp3.add_run("Figure 3 Interpretation:\n").bold = True
    p_interp3.add_run(
        "Figure 3 depicts entity structures and association multiplicities within the HourWash application. One User can own multiple Order instances (1 to 0..*). Each Order contains one or more Laundry line items (1 to 1..*), each assigned to a physical Machine (0..* to 0..1). Order tracking is linked to QrCode (1 to 0..1), which accumulates audit scan entries in QrScanLog (1 to 0..*). This design enforces database normalized form and object-relational mapping consistency."
    )

    # ---------------------------------------------------------
    # SECTION 4: SEQUENCE DIAGRAMS
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h4_sd = doc.add_heading(level=1)
    r_h4_sd = h4_sd.add_run("Sequence Diagram")
    r_h4_sd.font.name = 'Arial'
    r_h4_sd.font.size = Pt(16)
    r_h4_sd.bold = True
    r_h4_sd.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc4 = doc.add_paragraph()
    p_disc4.add_run("Sequence Diagram Discussion:\n").bold = True
    p_disc4.add_run(
        "Sequence diagrams illustrate the dynamic behavioral interactions, object lifelines, and temporal message sequences between system components during key execution scenarios. Four sequence diagrams represent critical workflows within the HourWash system, aligned with specific use cases."
    )

    # Sequence Diagram 1
    h4_sd1 = doc.add_heading(level=2)
    r_h4_sd1 = h4_sd1.add_run("Sequence Diagram 1")
    r_h4_sd1.font.name = 'Arial'
    r_h4_sd1.font.size = Pt(13)
    r_h4_sd1.bold = True
    r_h4_sd1.font.color.rgb = RGBColor(0x03, 0x69, 0xA1)

    p_align1 = doc.add_paragraph()
    p_align1.add_run("Alignment with Use Case Diagram Feature/Process Name:\n").bold = True
    p_align1.add_run("Customer Order Placement & Service Scheduling (UC2, UC5)")

    p_fig4 = doc.add_paragraph()
    p_fig4.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig4.paragraph_format.space_before = Pt(12)
    p_fig4.paragraph_format.space_after = Pt(4)
    run_fig4 = p_fig4.add_run()
    run_fig4.add_picture('diagrams/sequence_diagram_1.png', width=Inches(6.2))

    p_cap4 = doc.add_paragraph()
    p_cap4.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap4 = p_cap4.add_run("Figure 4: Sequence Diagram for Customer Order Placement and Service Scheduling")
    r_cap4.italic = True
    r_cap4.font.size = Pt(9.5)
    r_cap4.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp4 = doc.add_paragraph()
    p_interp4.add_run("Figure 4 Interpretation:\n").bold = True
    p_interp4.add_run(
        "Figure 4 traces message execution across six lifelines: Customer, Order UI (Blade), OrderController, PromotionModel, Order & Laundry Models, and MySQL Database. The customer submits order preferences (POST /laundry/store). OrderController validates input, calls PromotionModel to compute promo code discounts, and instructs the Order Model to persist records in MySQL. Upon receiving the generated Order ID, OrderController redirects the customer to a confirmation view displaying order summary details and live tracking options."
    )

    # Sequence Diagram 2
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    h4_sd2 = doc.add_heading(level=2)
    r_h4_sd2 = h4_sd2.add_run("Sequence Diagram 2")
    r_h4_sd2.font.name = 'Arial'
    r_h4_sd2.font.size = Pt(13)
    r_h4_sd2.bold = True
    r_h4_sd2.font.color.rgb = RGBColor(0x03, 0x69, 0xA1)

    p_align2 = doc.add_paragraph()
    p_align2.add_run("Alignment with Use Case Diagram Feature/Process Name:\n").bold = True
    p_align2.add_run("Laundry Processing & Machine Allocation Management (UC7, UC8)")

    p_fig5 = doc.add_paragraph()
    p_fig5.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig5.paragraph_format.space_before = Pt(12)
    p_fig5.paragraph_format.space_after = Pt(4)
    run_fig5 = p_fig5.add_run()
    run_fig5.add_picture('diagrams/sequence_diagram_2.png', width=Inches(6.2))

    p_cap5 = doc.add_paragraph()
    p_cap5.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap5 = p_cap5.add_run("Figure 5: Sequence Diagram for Machine Allocation and Laundry Status Updates")
    r_cap5.italic = True
    r_cap5.font.size = Pt(9.5)
    r_cap5.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp5 = doc.add_paragraph()
    p_interp5.add_run("Figure 5 Interpretation:\n").bold = True
    p_interp5.add_run(
        "Figure 5 models machine assignment and notification dispatch. Store staff select an available machine on the Staff Dashboard (POST /staff/machine/assign). MachineController calls Machine::assignOrder(), transitioning machine state to 'Washing'. It records the status change in OrderStatusHistory and triggers SmsNotificationService to enqueue SendSmsJob. The customer receives an automated SMS update while the staff dashboard displays active cycle timers."
    )

    # Sequence Diagram 3
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    h4_sd3 = doc.add_heading(level=2)
    r_h4_sd3 = h4_sd3.add_run("Sequence Diagram 3")
    r_h4_sd3.font.name = 'Arial'
    r_h4_sd3.font.size = Pt(13)
    r_h4_sd3.bold = True
    r_h4_sd3.font.color.rgb = RGBColor(0x03, 0x69, 0xA1)

    p_align3 = doc.add_paragraph()
    p_align3.add_run("Alignment with Use Case Diagram Feature/Process Name:\n").bold = True
    p_align3.add_run("Pickup & Delivery Logistics Operations (UC3, UC11, UC12, UC13)")

    p_fig6 = doc.add_paragraph()
    p_fig6.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig6.paragraph_format.space_before = Pt(12)
    p_fig6.paragraph_format.space_after = Pt(4)
    run_fig6 = p_fig6.add_run()
    run_fig6.add_picture('diagrams/sequence_diagram_3.png', width=Inches(6.2))

    p_cap6 = doc.add_paragraph()
    p_cap6.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap6 = p_cap6.add_run("Figure 6: Sequence Diagram for Pickup and Delivery Logistics Operations")
    r_cap6.italic = True
    r_cap6.font.size = Pt(9.5)
    r_cap6.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp6 = doc.add_paragraph()
    p_interp6.add_run("Figure 6 Interpretation:\n").bold = True
    p_interp6.add_run(
        "Figure 6 illustrates logistics coordination between Customer, Rider, Rider Dashboard, PickupDeliveryController, PickupDelivery Model, and MySQL Database. Upon receiving assigned delivery tasks, the rider updates status toggles ('En Route', 'Picked Up', 'Delivered'). Each update sends an HTTP POST request to PickupDeliveryController, which updates database records and dispatches live tracking notifications to the customer portal."
    )

    # Sequence Diagram 4
    doc.add_paragraph().paragraph_format.space_after = Pt(8)
    h4_sd4 = doc.add_heading(level=2)
    r_h4_sd4 = h4_sd4.add_run("Sequence Diagram 4")
    r_h4_sd4.font.name = 'Arial'
    r_h4_sd4.font.size = Pt(13)
    r_h4_sd4.bold = True
    r_h4_sd4.font.color.rgb = RGBColor(0x03, 0x69, 0xA1)

    p_align4 = doc.add_paragraph()
    p_align4.add_run("Alignment with Use Case Diagram Feature/Process Name:\n").bold = True
    p_align4.add_run("QR Code Order Verification & Payment Processing (UC10)")

    p_fig7 = doc.add_paragraph()
    p_fig7.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig7.paragraph_format.space_before = Pt(12)
    p_fig7.paragraph_format.space_after = Pt(4)
    run_fig7 = p_fig7.add_run()
    run_fig7.add_picture('diagrams/sequence_diagram_4.png', width=Inches(6.2))

    p_cap7 = doc.add_paragraph()
    p_cap7.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap7 = p_cap7.add_run("Figure 7: Sequence Diagram for QR Code Verification and Payment Processing")
    r_cap7.italic = True
    r_cap7.font.size = Pt(9.5)
    r_cap7.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp7 = doc.add_paragraph()
    p_interp7.add_run("Figure 7 Interpretation:\n").bold = True
    p_interp7.add_run(
        "Figure 7 depicts the verification flow when staff scan a customer's QR code token. QrScanLogController receives the hash, queries QrCode::findByHash(), records an audit log entry in QrScanLog (capturing staff ID and timestamp), and verifies payment status. Upon verification, the digital receipt engine compiles an itemized transaction receipt that is rendered on screen and printed."
    )

    # ---------------------------------------------------------
    # SECTION 5: PACKAGE DIAGRAM
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h5_pd = doc.add_heading(level=1)
    r_h5_pd = h5_pd.add_run("Package Diagram")
    r_h5_pd.font.name = 'Arial'
    r_h5_pd.font.size = Pt(16)
    r_h5_pd.bold = True
    r_h5_pd.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc5 = doc.add_paragraph()
    p_disc5.add_run("Package Diagram Discussion:\n").bold = True
    p_disc5.add_run(
        "The Package Diagram illustrates the modular subsystem architecture and package dependency structure of the HourWash codebase. Following Laravel's MVC and Service-Oriented conventions, classes are organized into cohesive namespaces:\n\n"
        "• App\\Http\\Controllers: Handles HTTP requests, coordinates domain services, and returns Blade view responses.\n"
        "• App\\Http\\Middleware: Encapsulates authorization rules (AdminMiddleware, StaffMiddleware, CustomerMiddleware, RiderMiddleware) and security headers.\n"
        "• App\\Models: Encapsulates Eloquent ORM entity models (User, Order, Laundry, Machine, Service, PickupDelivery, QrCode).\n"
        "• App\\Services: Contains external integration logic (SmsNotificationService, EmailNotificationService).\n"
        "• App\\Jobs & App\\Mail: Manages queued asynchronous jobs (SendSmsJob) and mailable instances (OrderStatusUpdated).\n"
        "• Database\\Migrations: Houses database schema migration scripts.\n"
        "• Resources\\Views: Contains server-side Blade templates organized by user role."
    )

    p_fig8 = doc.add_paragraph()
    p_fig8.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig8.paragraph_format.space_before = Pt(12)
    p_fig8.paragraph_format.space_after = Pt(4)
    run_fig8 = p_fig8.add_run()
    run_fig8.add_picture('diagrams/package_diagram.png', width=Inches(6.2))

    p_cap8 = doc.add_paragraph()
    p_cap8.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap8 = p_cap8.add_run("Figure 8: HourWash System Package Diagram")
    r_cap8.italic = True
    r_cap8.font.size = Pt(9.5)
    r_cap8.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp8 = doc.add_paragraph()
    p_interp8.add_run("Figure 8 Interpretation:\n").bold = True
    p_interp8.add_run(
        "Figure 8 details the clean architectural packaging of the codebase. Dependencies flow inwards: Controllers depend on Middleware for route authorization and import Eloquent Models for data access. Async services interact via defined job interfaces. This modular design prevents tight coupling, supports unit testing, and facilitates maintainability."
    )

    # ---------------------------------------------------------
    # SECTION 6: DEPLOYMENT DIAGRAM
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h6_dd = doc.add_heading(level=1)
    r_h6_dd = h6_dd.add_run("Deployment Diagram")
    r_h6_dd.font.name = 'Arial'
    r_h6_dd.font.size = Pt(16)
    r_h6_dd.bold = True
    r_h6_dd.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc6 = doc.add_paragraph()
    p_disc6.add_run("Deployment Diagram Discussion:\n").bold = True
    p_disc6.add_run(
        "The Deployment Diagram details the physical hardware nodes, execution environments, network topology, and communication protocols required to host the HourWash system in production:\n\n"
        "1. Client Devices Node: Desktop and mobile web browsers executing JavaScript/Vite bundles and rendering Tailwind CSS user interfaces.\n"
        "2. Web Application Server Node: Ubuntu Linux or Windows Server hosting Nginx/Apache Web Server, PHP 8.5 FPM runtime, Laravel 11 application engine, and Artisan task scheduler.\n"
        "3. Database Server Node: Dedicated database node running MySQL Server 8.0 with InnoDB storage engine, maintaining encrypted database tables and automated daily backups.\n"
        "4. External SMS Gateway & Mail Server: Cloud REST API services (Twilio/Semaphore) and SMTP Mail servers for automated SMS and transactional email dispatching."
    )

    p_fig9 = doc.add_paragraph()
    p_fig9.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig9.paragraph_format.space_before = Pt(12)
    p_fig9.paragraph_format.space_after = Pt(4)
    run_fig9 = p_fig9.add_run()
    run_fig9.add_picture('diagrams/deployment_diagram.png', width=Inches(6.2))

    p_cap9 = doc.add_paragraph()
    p_cap9.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap9 = p_cap9.add_run("Figure 9: HourWash System Deployment Diagram")
    r_cap9.italic = True
    r_cap9.font.size = Pt(9.5)
    r_cap9.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp9 = doc.add_paragraph()
    p_interp9.add_run("Figure 9 Interpretation:\n").bold = True
    p_interp9.add_run(
        "Figure 9 illustrates the network communication channels connecting hardware nodes. Client Devices communicate with the Application Server via secure HTTPS (Port 443). The Web Application Server queries the Database Server over encrypted MySQL TCP/IP connections (Port 3306). Outbound REST API calls (HTTPS Port 443) route notification requests to the external SMS Gateway. Node separation ensures security, data privacy, and infrastructure scalability."
    )

    # Save output docx file
    output_filename = "Hour_Wash_System_Design_Diagrams.docx"
    doc.save(output_filename)
    print(f"DOCUMENT SUCCESSFULLY SAVED TO: {output_filename}")

if __name__ == '__main__':
    create_system_design_docx()
