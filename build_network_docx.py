import os
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH

def create_network_infrastructure_docx():
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
    r_sub = p_sub.add_run("Network Infrastructure & Cloud Topology Technical Specification")
    r_sub.font.size = Pt(13)
    r_sub.font.color.rgb = RGBColor(0x47, 0x55, 0x69)
    p_sub.paragraph_format.space_after = Pt(16)

    # Horizontal Divider Line
    p_div = doc.add_paragraph()
    r_div = p_div.add_run("―" * 58)
    r_div.font.color.rgb = RGBColor(0xCB, 0xD5, 0xE1)
    p_div.paragraph_format.space_after = Pt(16)

    # ---------------------------------------------------------
    # SECTION 1: EXECUTIVE OVERVIEW
    # ---------------------------------------------------------
    h1 = doc.add_heading(level=1)
    r_h1 = h1.add_run("1. Network Infrastructure & System Topology Overview")
    r_h1.font.name = 'Arial'
    r_h1.font.size = Pt(16)
    r_h1.bold = True
    r_h1.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc1 = doc.add_paragraph()
    p_disc1.add_run("Executive Architecture Breakdown (Isa-Isahin):\n").bold = True
    p_disc1.add_run(
        "The Network Infrastructure for HourWash Laundry Shop Management System adopts a cloud-hosted, hybrid local-and-remote network topology designed to provide secure, resilient communication across store operations, customer web traffic, and external cloud integrations. The network topology connects three primary user entry points (Customers, Store Staff, and System Administrators) through encrypted gateways and localized routers to the centralized Web Application Server and Database engine:\n\n"
        "1. External Web User Gateway (Outside Customer Traffic):\n"
        "   • Outside Users (Customers): Access the application remotely via smartphones or desktop browsers across public cellular or home internet networks.\n"
        "   • Router (Internet Gateway): Manages inbound HTTPS (Port 443) transport, dynamic IP filtering, and SSL/TLS termination, routing customer web requests into the Cloud Server boundary.\n\n"
        "2. Central Cloud Hosting & Application Server Node:\n"
        "   • Internet / Cloud Hosting (Render Web Server): Hosts the containerized Laravel 11/12 application runtime, executing HTTP routing, Breeze authentication session checks, and Blade template rendering.\n"
        "   • Database Cylinder (MySQL Relational Engine): Stores relational tables (users, customer_profiles, staff_profiles, services, machines, orders, order_status_history, qr_codes, qr_scan_logs, sms_notifications, email_notifications, customer_feedbacks). Connects bi-directionally to the Web Application via PDO TCP/IP socket connections (Port 3306).\n"
        "   • QR Code Tracking Engine (System Feature): Embedded QR verification component interacting bi-directionally with the Web Application to generate and audit unique order QR tokens (api.qrserver.com).\n\n"
        "3. Store Local Area Network (Inside Store Operations):\n"
        "   • Inside Users (Administrator / Owner & Staff Operator): Internal store operators connecting through the store's physical network. (Note: Rider role has been completely phased out of system scope).\n"
        "   • Router (Local Area Network): Dedicated store LAN router managing local cashier workstations, staff mobile tablets, and machine terminal IP addresses, routing internal traffic bi-directionally to the Cloud Web Application via encrypted gateway tunnels.\n\n"
        "4. External Third-Party Cloud Services & Gateways:\n"
        "   • SMS Notification Service (TextBee API Gateway): Dispatches automated SMS order status alerts to customer mobile phones via api.textbee.dev.\n"
        "   • Email Notification Service (Brevo Transactional Email Gateway): Sends transactional order confirmations and password reset links via api.brevo.com.\n"
        "   • AI Assistant / Chatbot (OpenAI Cloud API & Ollama Local LLM): Processes natural language customer support queries using OpenAI (gpt-3.5-turbo) and local Ollama (gemma3:1b)."
    )

    # ---------------------------------------------------------
    # SECTION 2: NETWORK INFRASTRUCTURE DIAGRAM
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h2 = doc.add_heading(level=1)
    r_h2 = h2.add_run("2. System Network Infrastructure Diagram")
    r_h2.font.name = 'Arial'
    r_h2.font.size = Pt(16)
    r_h2.bold = True
    r_h2.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_fig = doc.add_paragraph()
    p_fig.alignment = WD_ALIGN_PARAGRAPH.LEFT
    p_fig.paragraph_format.space_before = Pt(12)
    p_fig.paragraph_format.space_after = Pt(4)
    run_fig = p_fig.add_run()
    run_fig.add_picture('diagrams/network_infrastructure_diagram.png', width=Inches(6.2))

    p_cap = doc.add_paragraph()
    p_cap.alignment = WD_ALIGN_PARAGRAPH.LEFT
    r_cap = p_cap.add_run("Figure 1: HourWash System Complete Network Infrastructure & Cloud Topology Diagram (Zero Arrow Gaps & 3-Role Scope)")
    r_cap.italic = True
    r_cap.font.size = Pt(9.5)
    r_cap.font.color.rgb = RGBColor(0x47, 0x55, 0x69)

    p_interp = doc.add_paragraph()
    p_interp.add_run("Figure 1 Interpretation (Step-by-Step Data Flow):\n").bold = True
    p_interp.add_run(
        "Figure 1 details the complete physical and logical network links connecting all hardware devices, routers, cloud servers, and third-party APIs. Step 1: Outside Customer users submit web requests through the Internet Gateway Router into the Cloud Hosted Web Application. Step 2: Inside store operators (Administrator/Owner and Staff) connect through the Store LAN Router, reaching the same centralized Cloud Web Application. Step 3: The Web Application executes business logic against the MySQL Database and QR Code Tracking Engine. Step 4: Asynchronous events trigger outbound API dispatches to TextBee SMS Gateway, Brevo Email Gateway, and the OpenAI/Ollama AI Chatbot Engine."
    )

    # ---------------------------------------------------------
    # SECTION 3: COMPONENT SPECIFICATION TABLE & PROTOCOLS
    # ---------------------------------------------------------
    doc.add_paragraph().paragraph_format.space_after = Pt(12)
    h3 = doc.add_heading(level=1)
    r_h3 = h3.add_run("3. Detailed Node Component & Protocol Specifications")
    r_h3.font.name = 'Arial'
    r_h3.font.size = Pt(16)
    r_h3.bold = True
    r_h3.font.color.rgb = RGBColor(0x0F, 0x17, 0x2A)

    p_disc3 = doc.add_paragraph()
    p_disc3.add_run("Network Node Breakdown & Security Protocols (Isa-Isahin):\n").bold = True
    p_disc3.add_run(
        "• Outside User (Customer Node): Mobile smartphones and laptops operating on public cellular (4G/5G) or residential Wi-Fi networks. Communicates via TLS-encrypted HTTPS (Port 443).\n"
        "• Router (Internet Gateway Node): Public edge router providing NAT, DDoS mitigation, firewall domain filtering, and port forwarding.\n"
        "• Internet / Cloud Hosting (Render Web Server): Cloud virtual private server hosting Ubuntu Linux, Nginx Web Server, PHP 8.5 FPM, and Laravel 11 application framework.\n"
        "• Database Cylinder Node: MySQL 8.0 relational database engine listening on TCP/IP Port 3306, protected by database access credentials and localhost bindings.\n"
        "• Router (Local Area Network Node): Commercial dual-band store Wi-Fi router handling DHCP IP reservation for cashier PCs, workstation scale hardware, and staff tablets.\n"
        "• Inside Users Node (Administrator & Staff): Local network users authenticated through role-specific Laravel Breeze Session middleware.\n"
        "• TextBee SMS Gateway API (api.textbee.dev): Outbound REST API endpoint dispatching real-time SMS notifications via HTTPS POST JSON payloads.\n"
        "• Brevo Email Gateway API (api.brevo.com): Outbound SMTP/REST API endpoint transmitting transactional HTML emails for order updates and password resets.\n"
        "• AI Assistant Engine (OpenAI Cloud / Ollama LLM): Dual AI engine utilizing OpenAI Cloud REST API (api.openai.com) and local Ollama daemon (http://127.0.0.1:11434)."
    )

    output_filename = "Hour_Wash_Network_Infrastructure.docx"
    doc.save(output_filename)
    print(f"NETWORK INFRASTRUCTURE DOCX SUCCESSFULLY CREATED AND SAVED TO: {output_filename}")

if __name__ == '__main__':
    create_network_infrastructure_docx()
