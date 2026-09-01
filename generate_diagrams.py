import os
import matplotlib.pyplot as plt
import matplotlib.patches as patches

os.makedirs('diagrams', exist_ok=True)

PRIMARY_BG = '#FFFFFF'
TEXT_COLOR = '#0F172A'

plt.rcParams['font.sans-serif'] = 'Arial'
plt.rcParams['font.family'] = 'sans-serif'

# -------------------------------------------------------------
# 1. SYSTEM DESIGN DIAGRAM
# -------------------------------------------------------------
def generate_system_design_diagram():
    fig, ax = plt.subplots(figsize=(15, 10.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97.5, "HourWash System Architecture & End-to-End Database Flow (Real Production Integrations)", fontsize=14.5, fontweight='bold', ha='center', color=TEXT_COLOR)

    # Layer 1: Client / Presentation
    rect1 = patches.FancyBboxPatch((3, 76), 94, 17, linewidth=1.6, edgecolor='#0284C7', facecolor='#F0F9FF', boxstyle="round,pad=0.3")
    ax.add_patch(rect1)
    ax.text(5, 90.5, "1. PRESENTATION LAYER (Web Portals & Responsive Sidebar Navigation UI)", fontsize=11, fontweight='bold', color='#0369A1')
    
    ax.text(14, 83, "[ Customer Portal ]\n• Customer Dashboard\n• Book New Order\n• My Order History\n• Frequent User Card\n• Home Dashboard\n• Account Settings", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(38, 83, "[ Staff Console ]\n• Workstation Dashboard\n• Manage Laundry Orders\n• Manage Machines\n• New Walk-in Order\n• QR Scan Logs Outbox\n• Account Settings", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(62, 83, "[ Rider Dashboard ]\n• Rider of Hour Wash\n  (Pickup/Delivery)\n• Home Dashboard\n• Account Settings\n• Proof Photo Upload", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(86, 83, "[ Admin Portal ]\n• Overall Reports\n• Manage Laundry Orders\n• Manage Machines & Users\n• Services & Pricing\n• Live SMS/Email Outbox", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))

    ax.annotate("", xy=(50, 60), xytext=(50, 76), arrowprops=dict(arrowstyle="->", lw=2, color="#334155"))
    ax.text(52, 68, "HTTP / HTTPS Requests (JSON / Blade Forms / REST API)", fontsize=9, color="#475569", fontweight='bold')

    # Layer 2: Routing & Middleware
    rect2 = patches.FancyBboxPatch((3, 48), 94, 12, linewidth=1.6, edgecolor='#7C3AED', facecolor='#F5F3FF', boxstyle="round,pad=0.3")
    ax.add_patch(rect2)
    ax.text(5, 57, "2. SECURITY, AUTHENTICATION & ROUTING MIDDLEWARE LAYER", fontsize=10.5, fontweight='bold', color='#6D28D9')
    ax.text(16, 52.5, "Breeze Auth Session\nVerification", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(38, 52.5, "CustomerMiddleware &\nStaffMiddleware", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(62, 52.5, "RiderMiddleware &\nAdminMiddleware", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(84, 52.5, "SecurityHeaders &\nCSRF Protection", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))

    ax.annotate("", xy=(50, 36), xytext=(50, 48), arrowprops=dict(arrowstyle="->", lw=2, color="#334155"))

    # Layer 3: Controllers & Services
    rect3 = patches.FancyBboxPatch((3, 21), 94, 15, linewidth=1.6, edgecolor='#16A34A', facecolor='#F0FDF4', boxstyle="round,pad=0.3")
    ax.add_patch(rect3)
    ax.text(5, 33.5, "3. APPLICATION CONTROLLERS & LOGIC LAYER (Laravel 11 / PHP 8.5)", fontsize=10.5, fontweight='bold', color='#15803D')
    ax.text(20, 26.5, "Controllers:\nAuth Controllers | LaundryController\nMachineController | ServiceController\nChatbotController (AI Engine)\nQrScanLogController | ProfileController", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))
    ax.text(52, 26.5, "Domain Services:\nSmsNotificationService (TextBee)\nEmailNotificationService (Brevo)\nLoyaltyStampService (12-Stamp Card)\nReceiptGeneratorEngine", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))
    ax.text(82, 26.5, "Asynchronous Jobs & AI:\nSendSmsJob Queue\nOrderStatusUpdated Mail\nOpenAI / Ollama AI Chatbot Engine", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))

    ax.annotate("", xy=(35, 13), xytext=(35, 21), arrowprops=dict(arrowstyle="->", lw=2, color="#334155"))
    ax.text(36.5, 17, "Eloquent ORM (SQL Queries / Relational Reads & Writes)", fontsize=8.5, color="#475569", fontweight='bold')
    ax.annotate("", xy=(82, 13), xytext=(82, 21), arrowprops=dict(arrowstyle="->", lw=2, color="#EA580C"))
    ax.text(83.5, 17, "REST / API Integration", fontsize=8.5, color="#EA580C", fontweight='bold')

    # Layer 4A: Database Persistence
    rect4a = patches.FancyBboxPatch((3, 2), 60, 11, linewidth=1.6, edgecolor='#B45309', facecolor='#FEF3C7', boxstyle="round,pad=0.3")
    ax.add_patch(rect4a)
    ax.text(5, 10.5, "4. PERSISTENCE LAYER (MySQL Database)", fontsize=10, fontweight='bold', color='#B45309')
    ax.text(33, 5.5, "Tables: users (frequent_user_card) | customer_profiles | staff_profiles | services |\nmachines | orders | order_status_history | qr_codes | qr_scan_logs |\npickup_delivery | sms_notifications | email_notifications | customer_feedbacks", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#B45309", lw=1))

    # Layer 4B: External APIs
    rect4b = patches.FancyBboxPatch((65, 2), 32, 11, linewidth=1.6, edgecolor='#EA580C', facecolor='#FFEDD5', boxstyle="round,pad=0.3")
    ax.add_patch(rect4b)
    ax.text(67, 10.5, "5. EXTERNAL API GATEWAYS", fontsize=9.5, fontweight='bold', color='#C2410C')
    ax.text(81, 5.5, "• TextBee SMS Gateway (api.textbee.dev)\n• Brevo Transactional Email (api.brevo.com)\n• OpenAI Cloud LLM & Ollama Local LLM\n• QRServer Engine (api.qrserver.com)", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#EA580C", lw=1))

    plt.tight_layout()
    plt.savefig('diagrams/system_design_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/system_design_diagram.png")

# -------------------------------------------------------------
# 2. USE CASE DIAGRAM
# -------------------------------------------------------------
def draw_actor(ax, x, y, name):
    circle = patches.Circle((x, y + 2.5), 1.2, fc='#FFFFFF', ec='#0F172A', lw=1.5)
    ax.add_patch(circle)
    ax.plot([x, x], [y + 1.3, y - 1.5], color='#0F172A', lw=1.5)
    ax.plot([x - 1.8, x + 1.8], [y + 0.5, y + 0.5], color='#0F172A', lw=1.5)
    ax.plot([x, x - 1.5], [y - 1.5, y - 3.5], color='#0F172A', lw=1.5)
    ax.plot([x, x + 1.5], [y - 1.5, y - 3.5], color='#0F172A', lw=1.5)
    ax.text(x, y - 4.8, name, fontsize=9.5, fontweight='bold', ha='center', va='top', color='#0F172A')

def draw_usecase(ax, x, y, text, w=18, h=2.3):
    ellipse = patches.Ellipse((x, y), w, h, fc='#F0F9FF', ec='#0284C7', lw=1.2)
    ax.add_patch(ellipse)
    ax.text(x, y, text, fontsize=6.0, ha='center', va='center', color='#0F172A', fontweight='bold')

def generate_use_case_diagram():
    fig, ax = plt.subplots(figsize=(15, 11), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 98.5, "HourWash System Use Case Diagram - Matching Exact UI Sidebar Menus", fontsize=14, fontweight='bold', ha='center', color=TEXT_COLOR)

    rect = patches.Rectangle((18, 1), 64, 95, linewidth=2, edgecolor='#334155', facecolor='#FAFAFA', linestyle='--')
    ax.add_patch(rect)
    ax.text(50, 94.8, "HourWash System Boundary", fontsize=11.5, fontweight='bold', ha='center', color='#0369A1')

    draw_actor(ax, 7, 72, "Customer")
    draw_actor(ax, 7, 24, "Staff Operator")
    draw_actor(ax, 93, 72, "Rider Logistics")
    draw_actor(ax, 93, 24, "System Admin")

    # Customer Actor Navigation Use Cases (Screenshot 3 Sidebar)
    draw_usecase(ax, 32, 92, "UC1: Customer Registration")
    draw_usecase(ax, 32, 88, "UC2: Customer Login Auth")
    draw_usecase(ax, 32, 84, "UC3: Forgot Password Reset")
    draw_usecase(ax, 32, 80, "UC4: Customer Dashboard")
    draw_usecase(ax, 32, 76, "UC5: Book New Order")
    draw_usecase(ax, 32, 72, "UC6: My Order History")
    draw_usecase(ax, 32, 68, "UC7: Frequent User Card\n(12-Stamp Loyalty)")
    draw_usecase(ax, 32, 64, "UC8: Home Dashboard")
    draw_usecase(ax, 32, 60, "UC9: Account Settings")

    # Staff Operator Navigation Use Cases (Screenshot 2 Sidebar)
    draw_usecase(ax, 32, 48, "UC10: Staff Login Auth")
    draw_usecase(ax, 32, 44, "UC11: Staff Forgot Password")
    draw_usecase(ax, 32, 40, "UC12: Workstation Dashboard")
    draw_usecase(ax, 32, 36, "UC13: Manage Laundry Orders")
    draw_usecase(ax, 32, 32, "UC14: Manage Machines")
    draw_usecase(ax, 32, 28, "UC15: New Walk-in Order")
    draw_usecase(ax, 32, 24, "UC16: QR Scan Logs Outbox")
    draw_usecase(ax, 32, 20, "UC17: Home Dashboard")
    draw_usecase(ax, 32, 16, "UC18: Account Settings")

    # Rider Logistics Navigation Use Cases (Screenshot 4 Sidebar)
    draw_usecase(ax, 68, 90, "UC19: Rider Login Auth")
    draw_usecase(ax, 68, 85, "UC20: Rider Forgot Password")
    draw_usecase(ax, 68, 80, "UC21: Rider of Hour Wash")
    draw_usecase(ax, 68, 75, "UC22: Update Pickup Status")
    draw_usecase(ax, 68, 70, "UC23: Update Delivery & Proof")
    draw_usecase(ax, 68, 65, "UC24: Home Dashboard")
    draw_usecase(ax, 68, 60, "UC25: Account Settings")

    # System Administrator Navigation Use Cases (Screenshot 1 Sidebar)
    draw_usecase(ax, 68, 48, "UC26: Admin Login Auth")
    draw_usecase(ax, 68, 44, "UC27: Admin Forgot Password")
    draw_usecase(ax, 68, 40, "UC28: Overall Reports & Dash")
    draw_usecase(ax, 68, 36, "UC29: Manage Laundry Orders")
    draw_usecase(ax, 68, 32, "UC30: Manage Machines")
    draw_usecase(ax, 68, 28, "UC31: Services & Pricing")
    draw_usecase(ax, 68, 24, "UC32: Manage Users (Stamps)")
    draw_usecase(ax, 68, 20, "UC33: Live SMS Outbox")
    draw_usecase(ax, 68, 16, "UC34: Live Email Outbox")
    draw_usecase(ax, 68, 12, "UC35: Customer Reviews Outbox")
    draw_usecase(ax, 68, 8, "UC36: QR Scan Logs Outbox")
    draw_usecase(ax, 68, 5, "UC37: Home Dashboard")
    draw_usecase(ax, 68, 2, "UC38: Account Settings")

    # Actor lines
    for uy in [92, 88, 84, 80, 76, 72, 68, 64, 60]:
        ax.plot([9, 23], [72, uy], color='#0284C7', lw=1.0)

    for uy in [48, 44, 40, 36, 32, 28, 24, 20, 16]:
        ax.plot([9, 23], [24, uy], color='#0284C7', lw=1.0)

    for uy in [90, 85, 80, 75, 70, 65, 60]:
        ax.plot([91, 77], [72, uy], color='#16A34A', lw=1.0)

    for uy in [48, 44, 40, 36, 32, 28, 24, 20, 16, 12, 8, 5, 2]:
        ax.plot([91, 77], [24, uy], color='#16A34A', lw=1.0)

    plt.tight_layout()
    plt.savefig('diagrams/use_case_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/use_case_diagram.png")

# -------------------------------------------------------------
# 3. CLASS DIAGRAM (FIXED NO-OVERLAP TEXT LAYOUT)
# -------------------------------------------------------------
def draw_class_box(ax, x, y, name, attrs, methods, w=26, h=24):
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#334155', lw=1.4)
    ax.add_patch(rect)
    
    # Header bar (height = 3.5)
    h_rect = patches.Rectangle((x, y + h - 3.5), w, 3.5, fc='#E0F2FE', ec='#334155', lw=1.4)
    ax.add_patch(h_rect)
    ax.text(x + w/2, y + h - 1.75, name, fontsize=9.5, fontweight='bold', ha='center', va='center', color='#0369A1')
    
    # Divider line under header
    ax.plot([x, x + w], [y + h - 3.5, y + h - 3.5], color='#334155', lw=1.1)

    # Attributes list starting below header line
    attr_start_y = y + h - 4.2
    for i, attr in enumerate(attrs):
        ax.text(x + 0.8, attr_start_y - (i * 1.35), attr, fontsize=7.2, va='top', ha='left', color='#0F172A', fontfamily='monospace')

    # Divider line separating attributes and methods (guaranteed no overlap)
    div_y = attr_start_y - (len(attrs) * 1.35) - 0.6
    ax.plot([x, x + w], [div_y, div_y], color='#334155', lw=1.0)

    # Methods list starting below divider line
    method_start_y = div_y - 0.7
    for j, meth in enumerate(methods):
        ax.text(x + 0.8, method_start_y - (j * 1.35), meth, fontsize=7.2, va='top', ha='left', color='#047857', fontfamily='monospace')

def generate_class_diagram():
    fig, ax = plt.subplots(figsize=(15, 11), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97.8, "HourWash System Complete Domain Class Diagram & Relational Schema", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    # Row 1: CustomerProfile (x=4), User (x=37), StaffProfile (x=70)
    draw_class_box(ax, 37, 71, "User", 
                   ["+id: PK bigint", "+name: string", "+email: string", "+password: hash", "+role: enum", "+frequent_stamps: int"], 
                   ["+authenticate()", "+claimLoyaltyStamp()"], w=26, h=24)

    draw_class_box(ax, 4, 71, "CustomerProfile", 
                   ["+id: PK bigint", "+user_id: FK bigint", "+address: text", "+barangay: string", "+city: string"], 
                   ["+user(): BelongsTo"], w=26, h=24)

    draw_class_box(ax, 70, 71, "StaffProfile", 
                   ["+id: PK bigint", "+user_id: FK bigint", "+employee_id: string", "+position: string", "+status: enum"], 
                   ["+user(): BelongsTo"], w=26, h=24)

    # Row 2: Service (x=4), Order (x=37), Machine (x=70)
    draw_class_box(ax, 37, 37, "Order", 
                   ["+id: PK bigint", "+order_number: string", "+customer_id: FK bigint", "+service_id: FK bigint", "+total_amount: decimal", "+order_status: enum"], 
                   ["+statusHistory(): HasMany", "+pickupDelivery(): HasOne"], w=26, h=26)

    draw_class_box(ax, 4, 37, "Service", 
                   ["+id: PK bigint", "+name: string", "+service_type: string", "+price: decimal", "+estimated_minutes: int"], 
                   ["+orders(): HasMany"], w=26, h=26)

    draw_class_box(ax, 70, 37, "Machine", 
                   ["+id: PK bigint", "+machine_code: string", "+machine_type: enum", "+status: enum", "+current_order_id: FK"], 
                   ["+assignOrder()", "+addBrownoutTime()"], w=26, h=26)

    # Row 3: PickupDelivery (x=4), QrCode & ScanLog (x=37), Sms & Email Log (x=70)
    draw_class_box(ax, 4, 3, "PickupDelivery", 
                   ["+id: PK bigint", "+order_id: FK bigint", "+rider_name: string", "+type: enum", "+status: enum", "+proof_images: json"], 
                   ["+updateStatus()"], w=26, h=26)

    draw_class_box(ax, 37, 3, "QrCode & ScanLog", 
                   ["+id: PK bigint", "+order_id: FK bigint", "+qr_token: string", "+scanned_by: FK bigint", "+scan_type: enum"], 
                   ["+verifyToken()", "+logScan()"], w=26, h=26)

    draw_class_box(ax, 70, 3, "Sms & Email Log", 
                   ["+id: PK bigint", "+order_id: FK bigint", "+phone: string", "+message: text", "+status: enum"], 
                   ["+sendTextBeeSms()", "+sendBrevoEmail()"], w=26, h=26)

    # Association lines with multiplicity labels
    # User <-> CustomerProfile
    ax.plot([37, 30], [83, 83], color='#334155', lw=1.3)
    ax.text(35.5, 84.2, "1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(30.5, 84.2, "0..1", fontsize=8.5, color='#334155', fontweight='bold')

    # User <-> StaffProfile
    ax.plot([63, 70], [83, 83], color='#334155', lw=1.3)
    ax.text(64, 84.2, "1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(68.5, 84.2, "0..1", fontsize=8.5, color='#334155', fontweight='bold')

    # User <-> Order
    ax.plot([50, 50], [71, 63], color='#334155', lw=1.3)
    ax.text(51.2, 69.5, "1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(51.2, 64.5, "0..*", fontsize=8.5, color='#334155', fontweight='bold')

    # Service <-> Order
    ax.plot([37, 30], [50, 50], color='#334155', lw=1.3)
    ax.text(35.5, 51.2, "1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(30.5, 51.2, "0..*", fontsize=8.5, color='#334155', fontweight='bold')

    # Order <-> Machine
    ax.plot([63, 70], [50, 50], color='#334155', lw=1.3)
    ax.text(64, 51.2, "1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(68.5, 51.2, "0..1", fontsize=8.5, color='#334155', fontweight='bold')

    # PickupDelivery <-> QrCode & ScanLog
    ax.plot([30, 37], [16, 16], color='#334155', lw=1.3)
    ax.text(30.8, 17.2, "0..1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(35.5, 17.2, "1", fontsize=8.5, color='#334155', fontweight='bold')

    # QrCode & ScanLog <-> Sms & Email Log
    ax.plot([63, 70], [16, 16], color='#334155', lw=1.3)
    ax.text(64, 17.2, "1", fontsize=8.5, color='#334155', fontweight='bold')
    ax.text(68.5, 17.2, "0..*", fontsize=8.5, color='#334155', fontweight='bold')

    plt.tight_layout()
    plt.savefig('diagrams/class_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/class_diagram.png")

# SEQUENCE HELPER
def draw_sequence_template(ax, title, lifelines, steps):
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 96.5, title, fontsize=11.5, fontweight='bold', ha='center', color=TEXT_COLOR)

    num_l = len(lifelines)
    xs = [9 + i * (82 / (num_l - 1)) for i in range(num_l)]

    for x, name in zip(xs, lifelines):
        ax.text(x, 90, name, fontsize=7.5, fontweight='bold', ha='center', bbox=dict(boxstyle="round,pad=0.3", fc="#E0F2FE", ec="#0284C7", lw=1.2))
        ax.plot([x, x], [85, 7], color='#94A3B8', linestyle='--', lw=1.2)

    y_step = (85 - 11) / (len(steps) + 1)
    for idx, step in enumerate(steps):
        cur_y = 85 - (idx + 1) * y_step
        from_idx, to_idx, label, is_return = step
        fx, tx = xs[from_idx], xs[to_idx]
        ls = '--' if is_return else '-'
        col = '#0284C7' if not is_return else '#64748B'

        ax.annotate("", xy=(tx, cur_y), xytext=(fx, cur_y),
                    arrowprops=dict(arrowstyle="->" if not is_return else "->", lw=1.3, color=col, linestyle=ls))
        
        mid_x = (fx + tx) / 2
        ax.text(mid_x, cur_y + 1.2, f"{idx+1}. {label}", fontsize=6.8, ha='center', va='bottom', color='#0F172A', fontweight='bold', bbox=dict(boxstyle="square,pad=0.1", fc="#FFFFFF", ec="none"))

# -------------------------------------------------------------
# 38 SEQUENCE DIAGRAMS
# -------------------------------------------------------------
ALL_ACCURATE_SEQUENCES = [
    # --- CUSTOMER ROLE (UC1 - UC9) ---
    ("Sequence Diagram 1: UC1 - Customer Registration Flow",
     ["Customer", "Register UI", "RegisteredUserController", "CustomerProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Enter name, email, phone, address", False), (1, 2, "POST /register (payload)", False), (2, 4, "User::create([role='customer'])", False),
      (4, 5, "INSERT INTO users", False), (5, 4, "Return User ID", True), (2, 3, "CustomerProfile::create([user_id])", False),
      (3, 5, "INSERT INTO customer_profiles", False), (2, 1, "Redirect to Customer Dashboard", True), (1, 0, "Display Registration Success Alert", True)],
     "sequence_diagram_1.png"),

    ("Sequence Diagram 2: UC2 - Customer Login Authentication Flow",
     ["Customer", "Login UI", "AuthenticatedSessionController", "CustomerMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter email & password", False), (1, 2, "POST /login (credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE email=?", False), (5, 4, "Return User record & password hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify CustomerMiddleware permissions", True), (2, 1, "Redirect to Customer Dashboard UI", True)],
     "sequence_diagram_2.png"),

    ("Sequence Diagram 3: UC3 - Customer Forgot Password Reset Flow via Brevo Email API",
     ["Customer", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter account email", False), (1, 2, "POST /forgot-password (email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Customer record verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (5, 3, "HTTP 201 Created (Brevo API Accepted)", True), (2, 1, "Display Brevo Reset Link Dispatched Alert", True)],
     "sequence_diagram_3.png"),

    ("Sequence Diagram 4: UC4 - Customer Dashboard Navigation Flow",
     ["Customer", "Customer Dashboard UI", "CustomerDashboardController", "Order Model", "Machine Model", "MySQL DB"],
     [(0, 1, "Click 'Customer Dashboard' nav", False), (1, 2, "GET /customer/dashboard", False), (2, 4, "Order::where('customer_id', user_id)", False),
      (4, 5, "SELECT active orders & quick actions", False), (5, 4, "Return active bookings & quick links", True), (2, 1, "Render live status & quick actions UI", True)],
     "sequence_diagram_4.png"),

    ("Sequence Diagram 5: UC5 - Book New Order Navigation Flow",
     ["Customer", "Book New Order UI", "LaundryController", "Service Model", "Order Model", "MySQL orders Table"],
     [(0, 1, "Click 'Book New Order' nav", False), (1, 2, "POST /customer/orders/book", False), (2, 3, "Service::find(service_id)", False),
      (3, 2, "Return calculated rate per load/kg", True), (2, 4, "Order::create([customer_id, status='pending'])", False), (4, 5, "INSERT INTO orders", False),
      (2, 1, "Redirect to Order Booking Summary", True)],
     "sequence_diagram_5.png"),

    ("Sequence Diagram 6: UC6 - My Order History Navigation Flow & QR Rendering",
     ["Customer", "My Order History UI", "OrderHistoryController", "Order Model", "QrCode Model", "api.qrserver.com REST API"],
     [(0, 1, "Click 'My Order History' nav", False), (1, 2, "GET /customer/orders/history", False), (2, 3, "Order::with('qrCode')->get()", False),
      (3, 4, "SELECT qr_token FROM qr_codes", False), (4, 3, "Return qr_token string", True), (2, 5, "Fetch https://api.qrserver.com/v1/create-qr-code/?data=token", False),
      (5, 1, "Render digital QR code image on receipt", True)],
     "sequence_diagram_6.png"),

    ("Sequence Diagram 7: UC7 - Frequent User Card (12-Stamp Loyalty Rewards) Navigation Flow",
     ["Customer", "Frequent User Card UI", "LoyaltyCardController", "LoyaltyStampService", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Frequent User Card' nav", False), (1, 2, "GET /customer/loyalty-card", False), (2, 4, "User::find(user_id)->frequent_stamps", False),
      (4, 5, "SELECT stamp_count FROM users", False), (5, 4, "Return current stamp count (e.g. 8/12)", True), (2, 3, "LoyaltyStampService::checkRewardEligibility()", False),
      (2, 1, "Render 12-stamp card & reward claim button", True)],
     "sequence_diagram_7.png"),

    ("Sequence Diagram 8: UC8 - Home Dashboard Public Landing Page Navigation Flow",
     ["Customer", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public service rates & store hours", False), (5, 4, "Return public landing metadata", True), (2, 1, "Render public landing page view", True)],
     "sequence_diagram_8.png"),

    ("Sequence Diagram 9: UC9 - Account Settings (Profile & Security) Navigation Flow",
     ["Customer", "Account Settings UI", "ProfileController", "CustomerProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update info", False), (1, 2, "POST /user/profile/update", False), (2, 4, "User::update(['name', 'password'])", False),
      (4, 5, "UPDATE users SET name=?, password=?", False), (2, 3, "CustomerProfile::update(['address'])", False), (2, 1, "Display profile updated success alert", True)],
     "sequence_diagram_9.png"),

    # --- STAFF ROLE (UC10 - UC18) ---
    ("Sequence Diagram 10: UC10 - Staff Login Authentication Flow",
     ["Staff", "Staff Login UI", "AuthenticatedSessionController", "StaffMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter staff credentials", False), (1, 2, "POST /login (staff credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE role='staff'", False), (5, 4, "Return Staff record & password hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify StaffMiddleware permissions", True), (2, 1, "Redirect to Workstation Dashboard", True)],
     "sequence_diagram_10.png"),

    ("Sequence Diagram 11: UC11 - Staff Forgot Password Reset Flow via Brevo Email API",
     ["Staff", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter staff work email", False), (1, 2, "POST /forgot-password (staff email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Staff record verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (2, 1, "Display Brevo reset email dispatched alert", True)],
     "sequence_diagram_11.png"),

    ("Sequence Diagram 12: UC12 - Workstation Dashboard Navigation Flow",
     ["Staff", "Workstation UI", "WorkstationController", "Order Model", "Machine Model", "MySQL DB"],
     [(0, 1, "Click 'Workstation Dashboard' nav", False), (1, 2, "GET /staff/workstation", False), (2, 4, "Order::whereIn('status', ['pending', 'washing'])", False),
      (4, 5, "SELECT active queue & machine timers", False), (5, 4, "Return active cashier & machine metrics", True), (2, 1, "Render queue & cashier processing UI", True)],
     "sequence_diagram_12.png"),

    ("Sequence Diagram 13: UC13 - Manage Laundry Orders Navigation Flow",
     ["Staff", "Manage Orders UI", "LaundryController", "Order Model", "Service Model", "MySQL orders Table"],
     [(0, 1, "Click 'Manage Laundry Orders' nav", False), (1, 2, "POST /staff/orders/weigh (order_id, weight)", False), (2, 3, "Order::find(order_id)", False),
      (3, 4, "Service::find(service_id)", False), (4, 3, "Return service rate per kg", True), (3, 2, "Calculate subtotal = weight * rate", True),
      (2, 5, "UPDATE orders SET status='weighed'", False), (2, 1, "Refresh orders queue table UI", True)],
     "sequence_diagram_13.png"),

    ("Sequence Diagram 14: UC14 - Manage Machines Navigation Flow (+60m Extension & TextBee Alert)",
     ["Staff", "Manage Machines UI", "MachineController", "Machine Model", "SmsNotificationService", "TextBee SMS Gateway"],
     [(0, 1, "Click 'Manage Machines' nav & trigger +60m", False), (1, 2, "POST /staff/machines/brownout", False), (2, 3, "Machine::update(['remaining_minutes' += 60])", False),
      (3, 4, "SmsNotificationService::sendTextBeeSms()", False), (4, 5, "POST https://api.textbee.dev/api/v1/gateway/send-sms", False),
      (5, 4, "HTTP 200 OK (TextBee Device Dispatched)", True), (2, 1, "Refresh active countdown timer (+60m) UI", True)],
     "sequence_diagram_14.png"),

    ("Sequence Diagram 15: UC15 - New Walk-in Order Navigation Flow",
     ["Staff", "New Walk-in Order UI", "WalkinOrderController", "Order Model", "User Model", "MySQL orders Table"],
     [(0, 1, "Click 'New Walk-in Order' nav", False), (1, 2, "POST /staff/orders/walk-in", False), (2, 4, "User::firstOrCreate([customer_phone])", False),
      (4, 5, "INSERT INTO users & orders", False), (5, 4, "Return generated Order ID", True), (2, 1, "Redirect to Receipt & Counter Payment UI", True)],
     "sequence_diagram_15.png"),

    ("Sequence Diagram 16: UC16 - QR Scan Logs Outbox Navigation Flow",
     ["Staff", "QR Scan Logs UI", "QrScanLogController", "QrScanLog Model", "Order Model", "MySQL qr_scan_logs"],
     [(0, 1, "Click 'QR Scan Logs Outbox' nav", False), (1, 2, "GET /staff/qr-logs", False), (2, 4, "QrScanLog::latest()->get()", False),
      (4, 5, "SELECT * FROM qr_scan_logs", False), (5, 4, "Return audit logs of all QR scans", True), (2, 1, "Render QR scan log outbox table", True)],
     "sequence_diagram_16.png"),

    ("Sequence Diagram 17: UC17 - Staff Home Dashboard Navigation Flow",
     ["Staff", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public landing page details", False), (5, 4, "Return store landing metadata", True), (2, 1, "Render public landing page view", True)],
     "sequence_diagram_17.png"),

    ("Sequence Diagram 18: UC18 - Staff Account Settings Navigation Flow",
     ["Staff", "Account Settings UI", "ProfileController", "StaffProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update password", False), (1, 2, "POST /staff/profile/update", False), (2, 4, "User::update(['password'])", False),
      (4, 5, "UPDATE users SET password=? WHERE id=?", False), (2, 1, "Display profile updated alert", True)],
     "sequence_diagram_18.png"),

    # --- RIDER ROLE (UC19 - UC25) ---
    ("Sequence Diagram 19: UC19 - Rider Login Authentication Flow",
     ["Rider", "Rider Login UI", "AuthenticatedSessionController", "RiderMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter rider credentials", False), (1, 2, "POST /login (rider credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE role='rider'", False), (5, 4, "Return Rider record & password hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify RiderMiddleware permissions", True), (2, 1, "Redirect to Rider Dashboard UI", True)],
     "sequence_diagram_19.png"),

    ("Sequence Diagram 20: UC20 - Rider Forgot Password Reset Flow via Brevo Email API",
     ["Rider", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter rider email address", False), (1, 2, "POST /forgot-password (rider email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Rider account verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (2, 1, "Display Brevo reset token dispatched alert", True)],
     "sequence_diagram_20.png"),

    ("Sequence Diagram 21: UC21 - Rider of Hour Wash Navigation Flow",
     ["Rider", "Rider Dashboard UI", "RiderDashboardController", "PickupDelivery Model", "Order Model", "MySQL pickup_delivery"],
     [(0, 1, "Click 'Rider of Hour Wash' nav", False), (1, 2, "GET /rider/dashboard", False), (2, 4, "PickupDelivery::where('rider_name', rider)", False),
      (4, 5, "SELECT * FROM pickup_delivery WHERE status='scheduled'", False), (5, 4, "Return pickup & delivery task dispatches", True),
      (2, 1, "Render dispatch task cards UI", True)],
     "sequence_diagram_21.png"),

    ("Sequence Diagram 22: UC22 - Update Pickup Logistics Status Flow & TextBee SMS Alert",
     ["Rider", "Rider Dashboard UI", "PickupDeliveryController", "PickupDelivery Model", "SmsNotificationService", "TextBee SMS Gateway"],
     [(0, 1, "Click 'Arrived & Picked Up'", False), (1, 2, "POST /rider/status/pickup (pickup_id)", False), (2, 3, "PickupDelivery::update(['status'=>'picked_up'])", False),
      (3, 4, "SmsNotificationService::sendTextBeeSms()", False), (4, 5, "POST https://api.textbee.dev/api/v1/gateway/send-sms", False),
      (5, 4, "TextBee SMS alert delivered to customer", True), (2, 1, "Task updated to Picked Up UI", True)],
     "sequence_diagram_22.png"),

    ("Sequence Diagram 23: UC23 - Update Delivery Status & Proof Photo Upload Flow",
     ["Rider", "Rider Dashboard UI", "PickupDeliveryController", "PickupDelivery Model", "OrderStatusHistory", "MySQL pickup_delivery"],
     [(0, 1, "Deliver laundry & upload proof photo", False), (1, 2, "POST /rider/status/delivery (proof_image)", False), (2, 3, "saveProofImage(file)", False),
      (3, 4, "PickupDelivery::update(['status'=>'delivered', 'proof_images'])", False), (4, 5, "UPDATE pickup_delivery & orders SET status='completed'", False),
      (2, 1, "Task completed confirmation alert", True)],
     "sequence_diagram_23.png"),

    ("Sequence Diagram 24: UC24 - Rider Home Dashboard Navigation Flow",
     ["Rider", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public landing page metadata", False), (5, 4, "Return landing page info", True), (2, 1, "Render public landing page view", True)],
     "sequence_diagram_24.png"),

    ("Sequence Diagram 25: UC25 - Rider Account Settings Navigation Flow",
     ["Rider", "Account Settings UI", "ProfileController", "RiderProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update password", False), (1, 2, "POST /rider/profile/update", False), (2, 4, "User::update(['password'])", False),
      (4, 5, "UPDATE users SET password=? WHERE id=?", False), (2, 1, "Display profile updated alert", True)],
     "sequence_diagram_25.png"),

    # --- ADMIN ROLE (UC26 - UC38) ---
    ("Sequence Diagram 26: UC26 - Administrator Login Authentication Flow",
     ["Admin", "Admin Login UI", "AuthenticatedSessionController", "AdminMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter admin credentials", False), (1, 2, "POST /login (admin credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE role='admin'", False), (5, 4, "Return Admin record & hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify AdminMiddleware permissions", True), (2, 1, "Redirect to Overall Reports & Dashboard", True)],
     "sequence_diagram_26.png"),

    ("Sequence Diagram 27: UC27 - Administrator Password Reset Flow via Brevo Email API",
     ["Admin", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter admin secure email", False), (1, 2, "POST /forgot-password (admin email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Admin account verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (2, 1, "Display Brevo reset email sent notification", True)],
     "sequence_diagram_27.png"),

    ("Sequence Diagram 28: UC28 - Overall Reports & Dashboard Navigation Flow",
     ["Admin", "Overall Reports UI", "AnalyticsController", "Order Model", "Machine Model", "MySQL DB"],
     [(0, 1, "Click 'Overall Reports & Dashboard' nav", False), (1, 2, "GET /admin/dashboard", False), (2, 4, "Order::selectRaw('SUM(total_amount), COUNT(id)')->get()", False),
      (4, 5, "SELECT sales, profit, & machine status", False), (5, 4, "Return system overview & financial metrics", True), (2, 1, "Render overall reports & metrics dashboard", True)],
     "sequence_diagram_28.png"),

    ("Sequence Diagram 29: UC29 - Manage Laundry Orders Navigation Flow",
     ["Admin", "Manage Orders UI", "LaundryController", "Order Model", "OrderStatusHistory", "MySQL orders Table"],
     [(0, 1, "Click 'Manage Laundry Orders' nav", False), (1, 2, "POST /admin/orders/update-status", False), (2, 4, "Order::update(['order_status' => new_status])", False),
      (4, 5, "UPDATE orders SET order_status=?", False), (2, 3, "OrderStatusHistory::create(['changed_by'=>admin_id])", False), (2, 1, "Refresh admin orders table UI", True)],
     "sequence_diagram_29.png"),

    ("Sequence Diagram 30: UC30 - Manage Machines Navigation Flow (Add, Edit, Remove)",
     ["Admin", "Manage Machines UI", "MachineController", "Machine Model", "MaintenanceLog", "MySQL machines Table"],
     [(0, 1, "Click 'Manage Machines' nav", False), (1, 2, "POST /admin/machines/store", False), (2, 4, "Machine::create([code, name, type])", False),
      (4, 5, "INSERT INTO machines", False), (5, 4, "Return Machine ID", True), (2, 1, "Refresh machine fleet inventory UI", True)],
     "sequence_diagram_30.png"),

    ("Sequence Diagram 31: UC31 - Services & Pricing Navigation Flow",
     ["Admin", "Services & Pricing UI", "ServiceController", "Service Model", "Database Service", "MySQL services Table"],
     [(0, 1, "Click 'Services & Pricing' nav", False), (1, 2, "POST /admin/services/update", False), (2, 4, "Service::update(['price' => new_price])", False),
      (4, 5, "UPDATE services SET price=?", False), (2, 1, "Display service rate updated alert", True)],
     "sequence_diagram_31.png"),

    ("Sequence Diagram 32: UC32 - Manage Users Navigation Flow (Stamps, Add, Edit, Remove)",
     ["Admin", "Manage Users UI", "UserController", "User Model", "LoyaltyStampService", "MySQL users Table"],
     [(0, 1, "Click 'Manage Users' nav & adjust stamps", False), (1, 2, "POST /admin/users/stamps (user_id, stamps)", False), (2, 4, "User::update(['frequent_user_card' => stamps])", False),
      (4, 5, "UPDATE users SET frequent_user_card=?", False), (2, 1, "Refresh users & stamps table UI", True)],
     "sequence_diagram_32.png"),

    ("Sequence Diagram 33: UC33 - Live SMS Outbox Navigation Flow (TextBee Logs)",
     ["Admin", "Live SMS Outbox UI", "SmsLogController", "SmsNotification Model", "TextBee Gateway", "MySQL sms_notifications"],
     [(0, 1, "Click 'Live SMS Outbox' nav", False), (1, 2, "GET /admin/sms-outbox", False), (2, 4, "SmsNotification::latest()->get()", False),
      (4, 5, "SELECT * FROM sms_notifications", False), (5, 4, "Return TextBee SMS delivery logs", True), (2, 1, "Render Live TextBee SMS outbox log table", True)],
     "sequence_diagram_33.png"),

    ("Sequence Diagram 34: UC34 - Live Email Outbox Navigation Flow (Brevo Logs)",
     ["Admin", "Live Email Outbox UI", "EmailLogController", "EmailNotification Model", "Brevo Gateway", "MySQL email_notifications"],
     [(0, 1, "Click 'Live Email Outbox' nav", False), (1, 2, "GET /admin/email-outbox", False), (2, 4, "EmailNotification::latest()->get()", False),
      (4, 5, "SELECT * FROM email_notifications", False), (5, 4, "Return Brevo email notification logs", True), (2, 1, "Render Live Brevo Email outbox table", True)],
     "sequence_diagram_34.png"),

    ("Sequence Diagram 35: UC35 - Customer Reviews Outbox Navigation Flow",
     ["Admin", "Customer Reviews UI", "CustomerFeedbackController", "CustomerFeedback Model", "User Model", "MySQL customer_feedbacks"],
     [(0, 1, "Click 'Customer Reviews Outbox' nav", False), (1, 2, "GET /admin/reviews-outbox", False), (2, 4, "CustomerFeedback::with('user')->get()", False),
      (4, 5, "SELECT * FROM customer_feedbacks", False), (5, 4, "Return ratings & feedback logs", True), (2, 1, "Render customer reviews outbox view", True)],
     "sequence_diagram_35.png"),

    ("Sequence Diagram 36: UC36 - QR Scan Logs Outbox Navigation Flow",
     ["Admin", "QR Scan Logs UI", "QrScanLogController", "QrScanLog Model", "Order Model", "MySQL qr_scan_logs"],
     [(0, 1, "Click 'QR Scan Logs Outbox' nav", False), (1, 2, "GET /admin/qr-outbox", False), (2, 4, "QrScanLog::with('order', 'scannedBy')->get()", False),
      (4, 5, "SELECT * FROM qr_scan_logs", False), (5, 4, "Return audit log of all QR scans", True), (2, 1, "Render admin QR scan log outbox UI", True)],
     "sequence_diagram_36.png"),

    ("Sequence Diagram 37: UC37 - Admin Home Dashboard Navigation Flow",
     ["Admin", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public landing page details", False), (5, 4, "Return landing page info", True), (2, 1, "Render public landing page view", True)],
     "sequence_diagram_37.png"),

    ("Sequence Diagram 38: UC38 - Admin Account Settings Navigation Flow",
     ["Admin", "Account Settings UI", "ProfileController", "AdminProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update info", False), (1, 2, "POST /admin/profile/update", False), (2, 4, "User::update(['password'])", False),
      (4, 5, "UPDATE users SET password=? WHERE id=?", False), (2, 1, "Display admin profile updated alert", True)],
     "sequence_diagram_38.png"),
]

def generate_all_sequence_diagrams():
    for title, lifelines, steps, filename in ALL_ACCURATE_SEQUENCES:
        fig, ax = plt.subplots(figsize=(12, 7.8), dpi=300)
        fig.patch.set_facecolor(PRIMARY_BG)
        ax.set_facecolor(PRIMARY_BG)
        draw_sequence_template(ax, title, lifelines, steps)
        plt.tight_layout()
        plt.savefig(f'diagrams/{filename}', dpi=300, bbox_inches='tight', facecolor='white')
        plt.close()
        print(f"Saved diagrams/{filename}")

# -------------------------------------------------------------
# 8. PACKAGE DIAGRAM
# -------------------------------------------------------------
def draw_package(ax, x, y, name, classes, w=26, h=18, bg="#F0F9FF", border="#0284C7"):
    tab = patches.Rectangle((x, y + h), 10, 2.5, fc=bg, ec=border, lw=1.3)
    ax.add_patch(tab)
    ax.text(x + 5, y + h + 1.2, "package", fontsize=7.5, color=border, ha='center', va='center', fontweight='bold')
    
    rect = patches.Rectangle((x, y), w, h, fc=bg, ec=border, lw=1.3)
    ax.add_patch(rect)
    ax.text(x + 2, y + h - 2, name, fontsize=9.5, fontweight='bold', color='#0F172A')

    c_text = "\n".join(classes)
    ax.text(x + 2, y + h - 4.5, c_text, fontsize=8, va='top', ha='left', color='#334155', fontfamily='monospace')

def generate_package_diagram():
    fig, ax = plt.subplots(figsize=(13, 8.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 96, "HourWash Subsystem Package & Module Dependency Diagram", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    rect = patches.Rectangle((4, 4), 92, 88, fc='#FAFAFA', ec='#64748B', lw=1.5, linestyle='--')
    ax.add_patch(rect)
    ax.text(6, 89.5, "App Subsystems (Laravel 11 Architecture)", fontsize=11, fontweight='bold', color='#475569')

    draw_package(ax, 8, 66, "App\\Http\\Controllers", 
                 ["+ Auth\\SessionController", "+ LaundryController", "+ MachineController", "+ ChatbotController (AI)", "+ AnalyticsController"], w=26, h=18, bg="#E0F2FE", border="#0284C7")

    draw_package(ax, 38, 66, "App\\Http\\Middleware", 
                 ["+ AdminMiddleware", "+ StaffMiddleware", "+ CustomerMiddleware", "+ RiderMiddleware", "+ SecurityHeaders"], w=26, h=18, bg="#F5F3FF", border="#7C3AED")

    draw_package(ax, 68, 66, "App\\Models", 
                 ["+ User", "+ CustomerProfile", "+ StaffProfile", "+ Order", "+ Machine", "+ PickupDelivery", "+ QrScanLog"], w=24, h=18, bg="#FEF3C7", border="#B45309")

    draw_package(ax, 8, 38, "App\\Services", 
                 ["+ SmsNotificationService (TextBee)", "+ EmailNotificationService (Brevo)", "+ LoyaltyStampService"], w=26, h=18, bg="#DCFCE7", border="#16A34A")

    draw_package(ax, 38, 38, "App\\Jobs & Mail", 
                 ["+ SendSmsJob (TextBee)", "+ OrderStatusUpdated Mail (Brevo)"], w=26, h=18, bg="#FFEDD5", border="#EA580C")

    draw_package(ax, 68, 38, "Database\\Migrations", 
                 ["+ create_users_table", "+ create_orders_table", "+ create_machines_table", "+ create_pickup_delivery_table"], w=24, h=18, bg="#F3E8FF", border="#9333EA")

    draw_package(ax, 23, 10, "Resources\\Views", 
                 ["+ auth/* (Login/Register)", "+ customer/* (Orders)", "+ staff/* (Machines)", "+ rider/* (Deliveries)"], w=26, h=18, bg="#F1F5F9", border="#475569")

    draw_package(ax, 53, 10, "App\\Providers", 
                 ["+ AppServiceProvider", "+ EventServiceProvider", "+ RouteServiceProvider"], w=24, h=18, bg="#F1F5F9", border="#475569")

    ax.annotate("", xy=(38, 75), xytext=(34, 75), arrowprops=dict(arrowstyle="->", lw=1.2, color="#64748B", linestyle=":"))
    ax.text(36, 76.5, "«use»", fontsize=8, color="#64748B", ha='center')

    ax.annotate("", xy=(68, 75), xytext=(64, 75), arrowprops=dict(arrowstyle="->", lw=1.2, color="#64748B", linestyle=":"))
    ax.text(66, 76.5, "«import»", fontsize=8, color="#64748B", ha='center')

    plt.tight_layout()
    plt.savefig('diagrams/package_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/package_diagram.png")

# -------------------------------------------------------------
# 9. DEPLOYMENT DIAGRAM
# -------------------------------------------------------------
def draw_node_3d(ax, x, y, name, details, w=24, h=16, depth=2, bg="#F8FAFC", border="#334155"):
    front = patches.Rectangle((x, y), w, h, fc=bg, ec=border, lw=1.4)
    ax.add_patch(front)

    top = patches.Polygon([[x, y + h], [x + depth, y + h + depth], [x + w + depth, y + h + depth], [x + w, y + h]], fc='#E2E8F0', ec=border, lw=1.4)
    ax.add_patch(top)

    side = patches.Polygon([[x + w, y], [x + w + depth, y + depth], [x + w + depth, y + h + depth], [x + w, y + h]], fc='#CBD5E1', ec=border, lw=1.4)
    ax.add_patch(side)

    ax.text(x + w/2, y + h - 2, f"«device / node»\n{name}", fontsize=9, fontweight='bold', ha='center', color='#0F172A')
    
    ax.plot([x, x + w], [y + h - 4.2, y + h - 4.2], color=border, lw=1)

    d_text = "\n".join(details)
    ax.text(x + 1.5, y + h - 5.5, d_text, fontsize=7.8, va='top', ha='left', color='#334155', fontfamily='sans-serif')

def generate_deployment_diagram():
    fig, ax = plt.subplots(figsize=(13, 8.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 96, "HourWash Production Infrastructure & Deployment Diagram (Actual API Nodes)", fontsize=14.5, fontweight='bold', ha='center', color=TEXT_COLOR)

    draw_node_3d(ax, 5, 55, "Client Devices Node", 
                 ["- Mobile / Desktop Web Browsers", "- Tailwind Responsive UI", "- Vite / JS Client Engine", "- Session / Cookie Storage"], w=26, h=22, bg="#E0F2FE", border="#0284C7")

    draw_node_3d(ax, 46, 55, "Web Application Server Node", 
                 ["- Nginx / Apache Web Server", "- PHP 8.5 FPM Runtime", "- Laravel 11 Framework", "- Eloquent ORM & Middleware", "- Artisan Task Worker Queue"], w=27, h=22, bg="#DCFCE7", border="#16A34A")

    draw_node_3d(ax, 46, 12, "Database Server Node", 
                 ["- MySQL Server 8.0 Engine", "- InnoDB Storage Engine", "- Encrypted User Password Hashes", "- Relational Foreign Key Integrity"], w=27, h=20, bg="#FEF3C7", border="#B45309")

    draw_node_3d(ax, 5, 12, "External Integration Gateways", 
                 ["- TextBee SMS API (api.textbee.dev)", "- Brevo Email API (api.brevo.com)", "- OpenAI Cloud & Ollama LLM", "- QRServer API (api.qrserver.com)"], w=26, h=20, bg="#FFEDD5", border="#EA580C")

    ax.plot([31, 46], [66, 66], color='#0284C7', lw=1.8)
    ax.text(38.5, 68, "HTTPS / TLS (Port 443)\n[ Session Cookies / JSON ]", fontsize=7.8, color='#0284C7', fontweight='bold', ha='center')

    ax.plot([60, 60], [55, 32], color='#B45309', lw=1.8)
    ax.text(61.5, 43, "MySQL TCP/IP (Port 3306)\n[ PDO SQL Queries ]", fontsize=7.8, color='#B45309', fontweight='bold', ha='left')

    ax.plot([46, 31], [28, 22], color='#EA580C', lw=1.8, linestyle='--')
    ax.text(36, 27, "REST HTTPS API\n[ JSON Payload ]", fontsize=7.8, color='#EA580C', fontweight='bold', ha='center')

    plt.tight_layout()
    plt.savefig('diagrams/deployment_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/deployment_diagram.png")

if __name__ == '__main__':
    generate_system_design_diagram()
    generate_use_case_diagram()
    generate_class_diagram()
    generate_all_sequence_diagrams()
    generate_package_diagram()
    generate_deployment_diagram()
    print("ALL 43 HIGH-RESOLUTION DIAGRAMS GENERATED SUCCESSFULLY!")
