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
    fig, ax = plt.subplots(figsize=(13, 9), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97, "HourWash System Architecture & End-to-End Database Flow", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    # Layer 1: Client / Presentation
    rect1 = patches.FancyBboxPatch((4, 78), 92, 15, linewidth=1.5, edgecolor='#0284C7', facecolor='#F0F9FF', boxstyle="round,pad=0.3")
    ax.add_patch(rect1)
    ax.text(6, 90, "1. PRESENTATION LAYER (Web & Mobile Blade / Tailwind Interfaces)", fontsize=10.5, fontweight='bold', color='#0369A1')
    ax.text(18, 83.5, "[ Customer Portal ]\nRegister, Login, Order,\nTrack, QR Scan, Review", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(39, 83.5, "[ Staff Console ]\nQueue, Weigh Load, Machine\nAssign, +60m Brownout, Print", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(61, 83.5, "[ Rider Dashboard ]\nLogistics Queue, Pickup/Delivery\nStatus, Proof Upload", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(82, 83.5, "[ Admin Portal ]\nUser Roles, Service Rates,\nProfit Analytics, Audit Logs", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))

    ax.annotate("", xy=(50, 63), xytext=(50, 78), arrowprops=dict(arrowstyle="->", lw=1.8, color="#334155"))
    ax.text(52, 70.5, "HTTP/HTTPS Request (Credentials / Form Data / REST API)", fontsize=8.5, color="#475569", fontweight='bold')

    # Layer 2: Routing & Middleware
    rect2 = patches.FancyBboxPatch((4, 51), 92, 12, linewidth=1.5, edgecolor='#7C3AED', facecolor='#F5F3FF', boxstyle="round,pad=0.3")
    ax.add_patch(rect2)
    ax.text(6, 59.5, "2. SECURITY, AUTHENTICATION & ROUTING MIDDLEWARE LAYER", fontsize=10, fontweight='bold', color='#6D28D9')
    ax.text(18, 55, "Breeze Auth Session\nVerification", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(38, 55, "CustomerMiddleware &\nStaffMiddleware", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(60, 55, "RiderMiddleware &\nAdminMiddleware", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(82, 55, "SecurityHeaders &\nCSRF Verification", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))

    ax.annotate("", xy=(50, 40), xytext=(50, 51), arrowprops=dict(arrowstyle="->", lw=1.8, color="#334155"))

    # Layer 3: Application Controllers & Services
    rect3 = patches.FancyBboxPatch((4, 24), 92, 16, linewidth=1.5, edgecolor='#16A34A', facecolor='#F0FDF4', boxstyle="round,pad=0.3")
    ax.add_patch(rect3)
    ax.text(6, 37, "3. APPLICATION LOGIC & SERVICES LAYER (Laravel 11 / PHP 8.5)", fontsize=10, fontweight='bold', color='#15803D')
    ax.text(20, 29.5, "Controllers:\nAuthenticatedSessionController\nLaundryController | MachineController\nAnalyticsController | QrScanLogController", fontsize=7.8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))
    ax.text(50, 29.5, "Domain Services:\nSmsNotificationService\nEmailNotificationService\nReceiptGeneratorEngine", fontsize=7.8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))
    ax.text(80, 29.5, "Asynchronous Jobs:\nSendSmsJob Queue\nOrderStatusUpdated Mail", fontsize=7.8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))

    ax.annotate("", xy=(35, 16), xytext=(35, 24), arrowprops=dict(arrowstyle="->", lw=1.8, color="#334155"))
    ax.text(36.5, 19.5, "Eloquent ORM (SQL Queries / Relational Reads & Writes)", fontsize=8, color="#475569", fontweight='bold')
    ax.annotate("", xy=(80, 16), xytext=(80, 24), arrowprops=dict(arrowstyle="->", lw=1.8, color="#EA580C"))
    ax.text(81.5, 19.5, "REST / SMTP APIs", fontsize=8, color="#EA580C", fontweight='bold')

    # Layer 4A: Database Persistence
    rect4a = patches.FancyBboxPatch((4, 3), 64, 13, linewidth=1.5, edgecolor='#B45309', facecolor='#FEF3C7', boxstyle="round,pad=0.3")
    ax.add_patch(rect4a)
    ax.text(6, 13, "4. PERSISTENCE LAYER (MySQL Database Schema)", fontsize=10, fontweight='bold', color='#B45309')
    ax.text(36, 7, "Tables: users | customer_profiles | staff_profiles | services | machines |\norders | order_status_history | qr_codes | qr_scan_logs |\npickup_delivery | sms_notifications | customer_feedbacks", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#B45309", lw=1))

    # Layer 4B: External Integration
    rect4b = patches.FancyBboxPatch((71, 3), 25, 13, linewidth=1.5, edgecolor='#EA580C', facecolor='#FFEDD5', boxstyle="round,pad=0.3")
    ax.add_patch(rect4b)
    ax.text(73, 13, "EXTERNAL APIS", fontsize=9.5, fontweight='bold', color='#C2410C')
    ax.text(83.5, 7, "Twilio / Semaphore SMS\nSMTP Email Gateway", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#EA580C", lw=1))

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

def draw_usecase(ax, x, y, text, w=18, h=4.0):
    ellipse = patches.Ellipse((x, y), w, h, fc='#F0F9FF', ec='#0284C7', lw=1.4)
    ax.add_patch(ellipse)
    ax.text(x, y, text, fontsize=7.5, ha='center', va='center', color='#0F172A', fontweight='bold')

def generate_use_case_diagram():
    fig, ax = plt.subplots(figsize=(13, 9.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97.5, "HourWash System Complete Use Case Diagram & Actor Interactions", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    rect = patches.Rectangle((20, 3), 60, 91, linewidth=2, edgecolor='#334155', facecolor='#FAFAFA', linestyle='--')
    ax.add_patch(rect)
    ax.text(50, 92, "HourWash System Boundary", fontsize=12, fontweight='bold', ha='center', color='#0369A1')

    draw_actor(ax, 9, 72, "Customer")
    draw_actor(ax, 9, 26, "Staff / Operator")
    draw_actor(ax, 91, 72, "Rider Logistics")
    draw_actor(ax, 91, 26, "System Administrator")

    # Customer UCs
    draw_usecase(ax, 35, 85, "UC1: Register & Login Auth")
    draw_usecase(ax, 35, 77, "UC2: Place Laundry Order\n& Select Services/Weight")
    draw_usecase(ax, 35, 69, "UC3: Request Pickup & Delivery")
    draw_usecase(ax, 35, 61, "UC4: Track Real-Time Status")
    draw_usecase(ax, 35, 53, "UC5: Apply Promo Coupon Code")
    draw_usecase(ax, 35, 45, "UC6: Submit Rating & Review")

    # Staff UCs
    draw_usecase(ax, 35, 35, "UC7: Weigh & Queue Load")
    draw_usecase(ax, 35, 27, "UC8: Assign Washer / Dryer")
    draw_usecase(ax, 35, 19, "UC9: Apply +60m Brownout")
    draw_usecase(ax, 35, 11, "UC10: Scan QR & Issue Receipt")

    # Rider UCs
    draw_usecase(ax, 65, 80, "UC11: View Assigned Tasks")
    draw_usecase(ax, 65, 70, "UC12: Update Pickup Status\n(On the Way / Picked Up)")
    draw_usecase(ax, 65, 60, "UC13: Update Delivery Status\n(Delivering / Delivered)")

    # Admin UCs
    draw_usecase(ax, 65, 43, "UC14: Manage User Accounts")
    draw_usecase(ax, 65, 33, "UC15: Configure Services & Rates")
    draw_usecase(ax, 65, 23, "UC16: Manage Machines & Promos")
    draw_usecase(ax, 65, 13, "UC17: Sales & Profit Analytics\n& QR Audit Logs")

    for uy in [85, 77, 69, 61, 53, 45]:
        ax.plot([11, 26], [72, uy], color='#0284C7', lw=1.2)

    for uy in [35, 27, 19, 11]:
        ax.plot([11, 26], [26, uy], color='#0284C7', lw=1.2)

    for uy in [80, 70, 60]:
        ax.plot([89, 74], [72, uy], color='#16A34A', lw=1.2)

    for uy in [43, 33, 23, 13]:
        ax.plot([89, 74], [26, uy], color='#16A34A', lw=1.2)

    plt.tight_layout()
    plt.savefig('diagrams/use_case_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/use_case_diagram.png")

# -------------------------------------------------------------
# 3. CLASS DIAGRAM
# -------------------------------------------------------------
def draw_class_box(ax, x, y, name, attrs, methods, w=24, h=16):
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#334155', lw=1.4)
    ax.add_patch(rect)
    h_rect = patches.Rectangle((x, y + h - 3), w, 3, fc='#E0F2FE', ec='#334155', lw=1.4)
    ax.add_patch(h_rect)
    ax.text(x + w/2, y + h - 1.5, name, fontsize=9, fontweight='bold', ha='center', va='center', color='#0369A1')
    
    ax.plot([x, x + w], [y + h - 3, y + h - 3], color='#334155', lw=1)

    attr_text = "\n".join(attrs)
    ax.text(x + 0.8, y + h - 3.8, attr_text, fontsize=6.8, va='top', ha='left', color='#0F172A', fontfamily='monospace')

    div_y = y + h - 3 - (len(attrs) * 0.9 + 0.6)
    ax.plot([x, x + w], [div_y, div_y], color='#334155', lw=0.9)

    meth_text = "\n".join(methods)
    ax.text(x + 0.8, div_y - 0.7, meth_text, fontsize=6.8, va='top', ha='left', color='#047857', fontfamily='monospace')

def generate_class_diagram():
    fig, ax = plt.subplots(figsize=(15, 10.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97.5, "HourWash System Complete Domain Class Diagram & Relational Schema", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    # Row 1: Users & Profiles
    draw_class_box(ax, 38, 77, "User", 
                   ["+id: PK bigint", "+name: string", "+email: string", "+password: hash", "+role: enum"], 
                   ["+authenticate()", "+orders(): HasMany"], w=24, h=17)

    draw_class_box(ax, 5, 77, "CustomerProfile", 
                   ["+id: PK bigint", "+user_id: FK bigint", "+address: text", "+barangay: string", "+city: string"], 
                   ["+user(): BelongsTo"], w=24, h=17)

    draw_class_box(ax, 71, 77, "StaffProfile", 
                   ["+id: PK bigint", "+user_id: FK bigint", "+employee_id: string", "+position: string", "+status: enum"], 
                   ["+user(): BelongsTo"], w=24, h=17)

    # Row 2: Order & Core Operations
    draw_class_box(ax, 38, 48, "Order", 
                   ["+id: PK bigint", "+order_number: string", "+customer_id: FK bigint", "+service_id: FK bigint", "+total_amount: decimal", "+order_status: enum"], 
                   ["+statusHistory(): HasMany", "+pickupDelivery(): HasOne"], w=24, h=20)

    draw_class_box(ax, 5, 48, "Service", 
                   ["+id: PK bigint", "+name: string", "+service_type: string", "+price: decimal", "+estimated_minutes: int"], 
                   ["+orders(): HasMany"], w=24, h=20)

    draw_class_box(ax, 71, 48, "Machine", 
                   ["+id: PK bigint", "+machine_code: string", "+machine_type: enum", "+status: enum", "+current_order_id: FK"], 
                   ["+assignOrder()", "+addBrownoutTime()"], w=24, h=20)

    # Row 3: Logistics, QR & Communications
    draw_class_box(ax, 5, 18, "PickupDelivery", 
                   ["+id: PK bigint", "+order_id: FK bigint", "+rider_name: string", "+type: enum", "+status: enum", "+proof_images: json"], 
                   ["+updateStatus()"], w=24, h=21)

    draw_class_box(ax, 38, 18, "QrCode & ScanLog", 
                   ["+id: PK bigint", "+order_id: FK bigint", "+qr_token: string", "+scanned_by: FK bigint", "+scan_type: enum"], 
                   ["+verifyToken()", "+logScan()"], w=24, h=21)

    draw_class_box(ax, 71, 18, "SmsNotification", 
                   ["+id: PK bigint", "+order_id: FK bigint", "+phone: string", "+message: text", "+status: enum"], 
                   ["+dispatchViaApi()"], w=24, h=21)

    # Multiplicities
    ax.plot([38, 29], [85, 85], color='#334155', lw=1.3)
    ax.text(36.5, 86, "1", fontsize=8, color='#334155')
    ax.text(29.5, 86, "0..1", fontsize=8, color='#334155')

    ax.plot([62, 71], [85, 85], color='#334155', lw=1.3)
    ax.text(63, 86, "1", fontsize=8, color='#334155')
    ax.text(69.5, 86, "0..1", fontsize=8, color='#334155')

    ax.plot([50, 50], [77, 68], color='#334155', lw=1.3)
    ax.text(51, 75.5, "1", fontsize=8, color='#334155')
    ax.text(51, 69.5, "0..*", fontsize=8, color='#334155')

    ax.plot([38, 29], [58, 58], color='#334155', lw=1.3)
    ax.text(36.5, 59, "1", fontsize=8, color='#334155')
    ax.text(29.5, 59, "0..*", fontsize=8, color='#334155')

    ax.plot([62, 71], [58, 58], color='#334155', lw=1.3)
    ax.text(63, 59, "1", fontsize=8, color='#334155')
    ax.text(69.5, 59, "0..1", fontsize=8, color='#334155')

    ax.plot([29, 38], [28, 28], color='#334155', lw=1.3)
    ax.text(30, 29, "0..1", fontsize=8, color='#334155')
    ax.text(36.5, 29, "1", fontsize=8, color='#334155')

    ax.plot([62, 71], [28, 28], color='#334155', lw=1.3)
    ax.text(63, 29, "1", fontsize=8, color='#334155')
    ax.text(69.5, 29, "0..*", fontsize=8, color='#334155')

    plt.tight_layout()
    plt.savefig('diagrams/class_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/class_diagram.png")

# SEQUENCE HELPER
def draw_sequence_template(ax, title, lifelines, steps):
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 96.5, title, fontsize=13.5, fontweight='bold', ha='center', color=TEXT_COLOR)

    num_l = len(lifelines)
    xs = [9 + i * (82 / (num_l - 1)) for i in range(num_l)]

    for x, name in zip(xs, lifelines):
        ax.text(x, 90, name, fontsize=8.5, fontweight='bold', ha='center', bbox=dict(boxstyle="round,pad=0.4", fc="#E0F2FE", ec="#0284C7", lw=1.2))
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
        ax.text(mid_x, cur_y + 1.2, f"{idx+1}. {label}", fontsize=7.2, ha='center', va='bottom', color='#0F172A', fontweight='bold', bbox=dict(boxstyle="square,pad=0.1", fc="#FFFFFF", ec="none"))

# -------------------------------------------------------------
# 4. SEQUENCE DIAGRAM 1: AUTHENTICATION & LOGIN
# -------------------------------------------------------------
def generate_sequence_diagram_1():
    fig, ax = plt.subplots(figsize=(12, 7.8), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["User / Actor", "Login View (Blade)", "AuthenticatedSessionController", "Role Middleware", "User Model", "MySQL users Table"]
    steps = [
        (0, 1, "Enter email & password credentials", False),
        (1, 2, "POST /login (email, password)", False),
        (2, 4, "User::where('email', email)->first()", False),
        (4, 5, "SELECT * FROM users WHERE email=?", False),
        (5, 4, "Return User record & password hash", True),
        (4, 2, "Hash::check(password, user.password)", True),
        (2, 3, "Auth::login(user) & generate session", False),
        (3, 2, "Verify role (customer/staff/rider/admin)", True),
        (2, 1, "Redirect to role dashboard view", True),
        (1, 0, "Display authenticated role home page", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 1: Step-by-Step User Authentication & Database Login Flow", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_1.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_1.png")

# -------------------------------------------------------------
# 5. SEQUENCE DIAGRAM 2: ORDER PLACEMENT
# -------------------------------------------------------------
def generate_sequence_diagram_2():
    fig, ax = plt.subplots(figsize=(12, 7.8), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Customer", "Order Form (Blade)", "LaundryController", "Service & Promo Model", "Order & QrCode Model", "MySQL DB (orders, qr_codes)"]
    steps = [
        (0, 1, "Select service, weight & promo code", False),
        (1, 2, "POST /laundry/store (payload)", False),
        (2, 3, "Service::find(id) & calculate total", False),
        (3, 2, "Return price & discount total", True),
        (2, 4, "Order::create([customer_id, status='pending'])", False),
        (4, 5, "INSERT INTO orders & generate order_number", False),
        (5, 4, "Return saved Order ID", True),
        (2, 4, "QrCode::create([order_id, qr_token])", False),
        (4, 5, "INSERT INTO qr_codes", False),
        (2, 1, "Redirect to Order Confirmation with QR Code", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 2: Customer Laundry Order Placement & Database Persistence Flow", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_2.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_2.png")

# -------------------------------------------------------------
# 6. SEQUENCE DIAGRAM 3: MACHINE ALLOCATION & BROWNOUT
# -------------------------------------------------------------
def generate_sequence_diagram_3():
    fig, ax = plt.subplots(figsize=(12, 7.8), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Staff Operator", "Staff Dashboard", "MachineController", "Machine Model", "OrderStatusHistory", "SmsNotificationService"]
    steps = [
        (0, 1, "Weigh load & select idle machine", False),
        (1, 2, "POST /staff/machine/assign (order_id, machine_id)", False),
        (2, 3, "Machine::update(['status'=>'washing', 'current_order_id'])", False),
        (3, 2, "Machine state updated in MySQL", True),
        (2, 4, "OrderStatusHistory::create(['status'=>'washing'])", False),
        (4, 2, "Status history recorded", True),
        (1, 2, "Click 'Trigger Brownout Extension (+60 mins)'", False),
        (2, 3, "addBrownoutTime(60 mins)", False),
        (2, 5, "dispatchSms(customer_phone, 'Wash Started / Delay Alert')", False),
        (5, 1, "Twilio/Semaphore API sends SMS to customer", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 3: Machine Allocation, Brownout Extension & SMS Alert Flow", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_3.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_3.png")

# -------------------------------------------------------------
# 7. SEQUENCE DIAGRAM 4: QR CODE & RIDER LOGISTICS
# -------------------------------------------------------------
def generate_sequence_diagram_4():
    fig, ax = plt.subplots(figsize=(12, 7.8), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Rider / Staff", "QR Scanner / Dashboard", "QrScanLogController", "PickupDelivery Model", "QrScanLog Model", "Digital Receipt Engine"]
    steps = [
        (0, 1, "Scan Order QR Code token", False),
        (1, 2, "POST /qr/verify (qr_token)", False),
        (2, 3, "PickupDelivery::update(['status'=>'picked_up'])", False),
        (3, 2, "Logistics status updated to 'Picked Up'", True),
        (2, 4, "QrScanLog::create(['scanned_by', 'scan_type'])", False),
        (4, 2, "Audit scan log saved in MySQL", True),
        (2, 5, "generateReceipt(order_id)", False),
        (5, 2, "Printable receipt & payment verification compiled", True),
        (2, 1, "Render verified receipt & delivery status", True),
        (1, 0, "Hand over clean laundry & updated receipt", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 4: QR Scan Audit Verification, Logistics & Receipt Flow", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_4.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_4.png")

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
                 ["+ Auth\\SessionController", "+ LaundryController", "+ MachineController", "+ RiderDashboardController", "+ AnalyticsController"], w=26, h=18, bg="#E0F2FE", border="#0284C7")

    draw_package(ax, 38, 66, "App\\Http\\Middleware", 
                 ["+ AdminMiddleware", "+ StaffMiddleware", "+ CustomerMiddleware", "+ RiderMiddleware", "+ SecurityHeaders"], w=26, h=18, bg="#F5F3FF", border="#7C3AED")

    draw_package(ax, 68, 66, "App\\Models", 
                 ["+ User", "+ CustomerProfile", "+ StaffProfile", "+ Order", "+ Machine", "+ PickupDelivery", "+ QrScanLog"], w=24, h=18, bg="#FEF3C7", border="#B45309")

    draw_package(ax, 8, 38, "App\\Services", 
                 ["+ SmsNotificationService", "+ EmailNotificationService"], w=26, h=18, bg="#DCFCE7", border="#16A34A")

    draw_package(ax, 38, 38, "App\\Jobs & Mail", 
                 ["+ SendSmsJob", "+ OrderStatusUpdated Mail"], w=26, h=18, bg="#FFEDD5", border="#EA580C")

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

    ax.text(50, 96, "HourWash System Production Infrastructure & Deployment Diagram", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    draw_node_3d(ax, 5, 55, "Client Devices", 
                 ["- Mobile / Desktop Web Browsers", "- Tailwind Responsive UI", "- Vite / JS Client Engine", "- Session / Cookie Storage"], w=26, h=22, bg="#E0F2FE", border="#0284C7")

    draw_node_3d(ax, 46, 55, "Web Application Server", 
                 ["- Nginx / Apache Web Server", "- PHP 8.5 FPM Runtime", "- Laravel 11 Framework", "- Eloquent ORM & Middleware", "- Artisan Task Worker Queue"], w=27, h=22, bg="#DCFCE7", border="#16A34A")

    draw_node_3d(ax, 46, 12, "Database Server Node", 
                 ["- MySQL Server 8.0 Engine", "- InnoDB Storage Engine", "- Encrypted User Password Hashes", "- Relational Foreign Key Integrity"], w=27, h=20, bg="#FEF3C7", border="#B45309")

    draw_node_3d(ax, 5, 12, "External SMS Gateway", 
                 ["- Twilio / Semaphore REST API", "- SMS Dispatch Worker Queue", "- Customer Order SMS Alerts"], w=26, h=20, bg="#FFEDD5", border="#EA580C")

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
    generate_sequence_diagram_1()
    generate_sequence_diagram_2()
    generate_sequence_diagram_3()
    generate_sequence_diagram_4()
    generate_package_diagram()
    generate_deployment_diagram()
    print("ALL 9 DETAILED DIAGRAMS GENERATED SUCCESSFULLY!")
