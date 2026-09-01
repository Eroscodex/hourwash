import os
import matplotlib.pyplot as plt
import matplotlib.patches as patches

os.makedirs('diagrams', exist_ok=True)

PRIMARY_BG = '#FFFFFF'
TEXT_COLOR = '#0F172A'

plt.rcParams['font.sans-serif'] = 'Arial'
plt.rcParams['font.family'] = 'sans-serif'

# 1. SYSTEM DESIGN DIAGRAM
def generate_system_design_diagram():
    fig, ax = plt.subplots(figsize=(12, 8), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 96, "HourWash System Design & Multi-Tier Architecture", fontsize=16, fontweight='bold', ha='center', color=TEXT_COLOR)

    # Layer 1: Client / Presentation
    rect1 = patches.FancyBboxPatch((5, 78), 90, 14, linewidth=1.5, edgecolor='#0284C7', facecolor='#F0F9FF', boxstyle="round,pad=0.3")
    ax.add_patch(rect1)
    ax.text(7, 88, "PRESENTATION LAYER (Blade / Vite / Tailwind CSS)", fontsize=11, fontweight='bold', color='#0369A1')
    ax.text(20, 82, "[ Customer Web Portal ]\nOrders, Status, QR Scan", fontsize=9, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(40, 82, "[ Staff Management UI ]\nQueue, Machines, Receipts", fontsize=9, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(60, 82, "[ Rider Mobile Dashboard ]\nLogistics, Pickup & Delivery", fontsize=9, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))
    ax.text(80, 82, "[ Admin Analytics Portal ]\nUsers, Finance, Audit Logs", fontsize=9, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#0284C7", lw=1))

    ax.annotate("", xy=(50, 62), xytext=(50, 78), arrowprops=dict(arrowstyle="->", lw=1.8, color="#334155"))
    ax.text(52, 70, "HTTP / HTTPS Requests (JSON & Form Data)", fontsize=8.5, color="#475569", fontweight='bold')

    # Layer 2: Routing & Middleware
    rect2 = patches.FancyBboxPatch((5, 52), 90, 10, linewidth=1.5, edgecolor='#7C3AED', facecolor='#F5F3FF', boxstyle="round,pad=0.3")
    ax.add_patch(rect2)
    ax.text(7, 59, "SECURITY & ROUTING MIDDLEWARE LAYER", fontsize=10, fontweight='bold', color='#6D28D9')
    ax.text(20, 55, "AdminMiddleware", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(40, 55, "StaffMiddleware", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(60, 55, "CustomerMiddleware", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))
    ax.text(80, 55, "RiderMiddleware & SecurityHeaders", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#7C3AED", lw=1))

    ax.annotate("", xy=(50, 42), xytext=(50, 52), arrowprops=dict(arrowstyle="->", lw=1.8, color="#334155"))

    # Layer 3: Application Controllers & Services
    rect3 = patches.FancyBboxPatch((5, 26), 90, 16, linewidth=1.5, edgecolor='#16A34A', facecolor='#F0FDF4', boxstyle="round,pad=0.3")
    ax.add_patch(rect3)
    ax.text(7, 39, "APPLICATION LOGIC & SERVICES LAYER (Laravel 11 / PHP 8.5)", fontsize=10, fontweight='bold', color='#15803D')
    ax.text(20, 32, "Controllers:\nLaundryController\nMachineController\nAnalyticsController", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))
    ax.text(50, 32, "Domain Services:\nSmsNotificationService\nEmailNotificationService\nQrScanLogController", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))
    ax.text(80, 32, "Asynchronous Jobs:\nSendSmsJob\nOrderStatusUpdated Mail", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#16A34A", lw=1))

    ax.annotate("", xy=(35, 18), xytext=(35, 26), arrowprops=dict(arrowstyle="->", lw=1.8, color="#334155"))
    ax.text(37, 21.5, "Eloquent ORM (SQL Queries)", fontsize=8.5, color="#475569", fontweight='bold')
    ax.annotate("", xy=(80, 18), xytext=(80, 26), arrowprops=dict(arrowstyle="->", lw=1.8, color="#EA580C"))
    ax.text(82, 21.5, "REST / SMTP APIs", fontsize=8.5, color="#EA580C", fontweight='bold')

    # Layer 4A: Persistence
    rect4a = patches.FancyBboxPatch((5, 4), 60, 14, linewidth=1.5, edgecolor='#B45309', facecolor='#FEF3C7', boxstyle="round,pad=0.3")
    ax.add_patch(rect4a)
    ax.text(7, 15, "PERSISTENCE LAYER (MySQL Database)", fontsize=10, fontweight='bold', color='#B45309')
    ax.text(35, 8.5, "Tables: users | customer_profiles | staff_profiles | orders | laundries |\nmachines | pickup_deliveries | promotions | qr_codes | qr_scan_logs", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#B45309", lw=1))

    # Layer 4B: External Gateways
    rect4b = patches.FancyBboxPatch((68, 4), 27, 14, linewidth=1.5, edgecolor='#EA580C', facecolor='#FFEDD5', boxstyle="round,pad=0.3")
    ax.add_patch(rect4b)
    ax.text(70, 15, "EXTERNAL INTEGRATION", fontsize=10, fontweight='bold', color='#C2410C')
    ax.text(81.5, 8.5, "Twilio / Semaphore SMS API\nSMTP Email Server", fontsize=8.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#EA580C", lw=1))

    plt.tight_layout()
    plt.savefig('diagrams/system_design_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/system_design_diagram.png")

# 2. USE CASE DIAGRAM
def draw_actor(ax, x, y, name):
    circle = patches.Circle((x, y + 2.5), 1.2, fc='#FFFFFF', ec='#0F172A', lw=1.5)
    ax.add_patch(circle)
    ax.plot([x, x], [y + 1.3, y - 1.5], color='#0F172A', lw=1.5)
    ax.plot([x - 1.8, x + 1.8], [y + 0.5, y + 0.5], color='#0F172A', lw=1.5)
    ax.plot([x, x - 1.5], [y - 1.5, y - 3.5], color='#0F172A', lw=1.5)
    ax.plot([x, x + 1.5], [y - 1.5, y - 3.5], color='#0F172A', lw=1.5)
    ax.text(x, y - 4.8, name, fontsize=9.5, fontweight='bold', ha='center', va='top', color='#0F172A')

def draw_usecase(ax, x, y, text, w=18, h=4.2):
    ellipse = patches.Ellipse((x, y), w, h, fc='#F0F9FF', ec='#0284C7', lw=1.4)
    ax.add_patch(ellipse)
    ax.text(x, y, text, fontsize=8, ha='center', va='center', color='#0F172A', fontweight='bold')

def generate_use_case_diagram():
    fig, ax = plt.subplots(figsize=(13, 9), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97, "HourWash Laundry Shop Management System - Use Case Diagram", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    rect = patches.Rectangle((20, 4), 60, 90, linewidth=2, edgecolor='#334155', facecolor='#FAFAFA', linestyle='--')
    ax.add_patch(rect)
    ax.text(50, 91.5, "HourWash System Boundary", fontsize=12, fontweight='bold', ha='center', color='#0369A1')

    draw_actor(ax, 9, 72, "Customer")
    draw_actor(ax, 9, 28, "Staff / Store Operator")
    draw_actor(ax, 91, 72, "Rider Logistics")
    draw_actor(ax, 91, 28, "System Administrator")

    draw_usecase(ax, 35, 84, "UC1: Register & Login")
    draw_usecase(ax, 35, 76, "UC2: Place Laundry Order\n& Select Services")
    draw_usecase(ax, 35, 68, "UC3: Request Pickup & Delivery")
    draw_usecase(ax, 35, 60, "UC4: Track Order Status")
    draw_usecase(ax, 35, 52, "UC5: Apply Promo Code")
    draw_usecase(ax, 35, 44, "UC6: Submit Feedback & Rating")

    draw_usecase(ax, 35, 34, "UC7: Weigh & Queue Laundry")
    draw_usecase(ax, 35, 26, "UC8: Assign Washing Machine\n& Dryer Fleet")
    draw_usecase(ax, 35, 18, "UC9: Apply Brownout +60m\nTime Extension")
    draw_usecase(ax, 35, 10, "UC10: Scan QR & Issue Receipt")

    draw_usecase(ax, 65, 78, "UC11: View Rider Dashboard\n& Assigned Tasks")
    draw_usecase(ax, 65, 68, "UC12: Update Pickup Status\n(En Route / Picked Up)")
    draw_usecase(ax, 65, 58, "UC13: Update Delivery Status\n(Out for Delivery / Delivered)")

    draw_usecase(ax, 65, 42, "UC14: Manage User Accounts\n& Staff/Rider Profiles")
    draw_usecase(ax, 65, 32, "UC15: Configure Services & Pricing")
    draw_usecase(ax, 65, 22, "UC16: Manage Machines & Promos")
    draw_usecase(ax, 65, 12, "UC17: View Sales & Profit Analytics\n& System Audit Logs")

    for uy in [84, 76, 68, 60, 52, 44]:
        ax.plot([11, 26], [72, uy], color='#0284C7', lw=1.2)

    for uy in [34, 26, 18, 10]:
        ax.plot([11, 26], [28, uy], color='#0284C7', lw=1.2)

    for uy in [78, 68, 58]:
        ax.plot([89, 74], [72, uy], color='#16A34A', lw=1.2)

    for uy in [42, 32, 22, 12]:
        ax.plot([89, 74], [28, uy], color='#16A34A', lw=1.2)

    plt.tight_layout()
    plt.savefig('diagrams/use_case_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/use_case_diagram.png")

# 3. CLASS DIAGRAM
def draw_class_box(ax, x, y, name, attrs, methods, w=24, h=16):
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#334155', lw=1.5)
    ax.add_patch(rect)
    h_rect = patches.Rectangle((x, y + h - 3), w, 3, fc='#E0F2FE', ec='#334155', lw=1.5)
    ax.add_patch(h_rect)
    ax.text(x + w/2, y + h - 1.5, name, fontsize=9.5, fontweight='bold', ha='center', va='center', color='#0369A1')
    
    ax.plot([x, x + w], [y + h - 3, y + h - 3], color='#334155', lw=1.2)

    attr_text = "\n".join(attrs)
    ax.text(x + 1, y + h - 4, attr_text, fontsize=7.2, va='top', ha='left', color='#0F172A', fontfamily='monospace')

    div_y = y + h - 3 - (len(attrs) * 0.95 + 0.8)
    ax.plot([x, x + w], [div_y, div_y], color='#334155', lw=1)

    meth_text = "\n".join(methods)
    ax.text(x + 1, div_y - 0.8, meth_text, fontsize=7.2, va='top', ha='left', color='#047857', fontfamily='monospace')

def generate_class_diagram():
    fig, ax = plt.subplots(figsize=(15, 10), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 97, "HourWash Laundry Shop System - Unified Class Diagram", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    draw_class_box(ax, 38, 77, "User", 
                   ["+id: bigint", "+name: string", "+email: string", "+role: enum"], 
                   ["+orders(): HasMany", "+profile(): HasOne"], w=24, h=16)

    draw_class_box(ax, 5, 77, "CustomerProfile", 
                   ["+id: bigint", "+user_id: bigint", "+phone: string", "+address: text"], 
                   ["+user(): BelongsTo"], w=24, h=16)

    draw_class_box(ax, 71, 77, "StaffProfile", 
                   ["+id: bigint", "+user_id: bigint", "+shift: string", "+code: string"], 
                   ["+user(): BelongsTo"], w=24, h=16)

    draw_class_box(ax, 38, 50, "Order", 
                   ["+id: bigint", "+user_id: bigint", "+order_number: string", "+total_amount: decimal", "+status: enum"], 
                   ["+laundries(): HasMany", "+qrCode(): HasOne", "+pickupDelivery(): HasOne"], w=24, h=18)

    draw_class_box(ax, 5, 50, "Laundry", 
                   ["+id: bigint", "+order_id: bigint", "+machine_id: bigint", "+weight_kg: decimal"], 
                   ["+order(): BelongsTo", "+machine(): BelongsTo", "+service(): BelongsTo"], w=24, h=18)

    draw_class_box(ax, 5, 23, "Machine", 
                   ["+id: bigint", "+machine_number: string", "+type: enum", "+status: enum", "+brownout: bool"], 
                   ["+assignOrder()", "+addBrownoutTime()"], w=24, h=18)

    draw_class_box(ax, 71, 50, "PickupDelivery", 
                   ["+id: bigint", "+order_id: bigint", "+rider_id: bigint", "+pickup_addr: text", "+status: enum"], 
                   ["+order(): BelongsTo", "+rider(): BelongsTo"], w=24, h=18)

    draw_class_box(ax, 38, 23, "QrCode", 
                   ["+id: bigint", "+order_id: bigint", "+qr_hash: string", "+status: enum"], 
                   ["+scanLogs(): HasMany", "+verify()"], w=24, h=18)

    draw_class_box(ax, 71, 23, "QrScanLog", 
                   ["+id: bigint", "+qr_code_id: bigint", "+scanned_by: bigint", "+scanned_at: timestamp"], 
                   ["+qrCode(): BelongsTo", "+scanner(): BelongsTo"], w=24, h=18)

    ax.plot([38, 29], [85, 85], color='#334155', lw=1.3)
    ax.text(36.5, 86, "1", fontsize=8, color='#334155')
    ax.text(29.5, 86, "0..1", fontsize=8, color='#334155')

    ax.plot([62, 71], [85, 85], color='#334155', lw=1.3)
    ax.text(63, 86, "1", fontsize=8, color='#334155')
    ax.text(69.5, 86, "0..1", fontsize=8, color='#334155')

    ax.plot([50, 50], [77, 68], color='#334155', lw=1.3)
    ax.text(51, 75.5, "1", fontsize=8, color='#334155')
    ax.text(51, 69.5, "0..*", fontsize=8, color='#334155')

    ax.plot([38, 29], [59, 59], color='#334155', lw=1.3)
    ax.text(36.5, 60, "1", fontsize=8, color='#334155')
    ax.text(29.5, 60, "1..*", fontsize=8, color='#334155')

    ax.plot([62, 71], [59, 59], color='#334155', lw=1.3)
    ax.text(63, 60, "1", fontsize=8, color='#334155')
    ax.text(69.5, 60, "0..1", fontsize=8, color='#334155')

    ax.plot([17, 17], [50, 41], color='#334155', lw=1.3)
    ax.text(18, 48.5, "0..*", fontsize=8, color='#334155')
    ax.text(18, 42.5, "0..1", fontsize=8, color='#334155')

    ax.plot([50, 50], [50, 41], color='#334155', lw=1.3)
    ax.text(51, 48.5, "1", fontsize=8, color='#334155')
    ax.text(51, 42.5, "0..1", fontsize=8, color='#334155')

    ax.plot([62, 71], [32, 32], color='#334155', lw=1.3)
    ax.text(63, 33, "1", fontsize=8, color='#334155')
    ax.text(69.5, 33, "0..*", fontsize=8, color='#334155')

    plt.tight_layout()
    plt.savefig('diagrams/class_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/class_diagram.png")

# SEQUENCE HELPER
def draw_sequence_template(ax, title, lifelines, steps):
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    ax.text(50, 96, title, fontsize=14, fontweight='bold', ha='center', color=TEXT_COLOR)

    num_l = len(lifelines)
    xs = [10 + i * (80 / (num_l - 1)) for i in range(num_l)]

    for x, name in zip(xs, lifelines):
        ax.text(x, 90, name, fontsize=9, fontweight='bold', ha='center', bbox=dict(boxstyle="round,pad=0.4", fc="#E0F2FE", ec="#0284C7", lw=1.2))
        ax.plot([x, x], [85, 8], color='#94A3B8', linestyle='--', lw=1.2)

    y_step = (85 - 12) / (len(steps) + 1)
    for idx, step in enumerate(steps):
        cur_y = 85 - (idx + 1) * y_step
        from_idx, to_idx, label, is_return = step
        fx, tx = xs[from_idx], xs[to_idx]
        ls = '--' if is_return else '-'
        col = '#0284C7' if not is_return else '#64748B'

        ax.annotate("", xy=(tx, cur_y), xytext=(fx, cur_y),
                    arrowprops=dict(arrowstyle="->" if not is_return else "->", lw=1.3, color=col, linestyle=ls))
        
        mid_x = (fx + tx) / 2
        ax.text(mid_x, cur_y + 1.2, f"{idx+1}. {label}", fontsize=7.5, ha='center', va='bottom', color='#0F172A', fontweight='bold', bbox=dict(boxstyle="square,pad=0.1", fc="#FFFFFF", ec="none"))

# 4. SEQUENCE DIAGRAM 1
def generate_sequence_diagram_1():
    fig, ax = plt.subplots(figsize=(12, 7.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Customer", "Order UI (Blade)", "OrderController", "PromotionModel", "Order & Laundry", "MySQL Database"]
    steps = [
        (0, 1, "Select services & enter load details", False),
        (1, 2, "POST /laundry/store (payload)", False),
        (2, 3, "checkAndApplyPromo(promo_code)", False),
        (3, 2, "Return discount percentage & total", True),
        (2, 4, "createOrder(user_id, status='Pending')", False),
        (4, 5, "INSERT INTO orders & laundries", False),
        (5, 4, "Return generated Order ID", True),
        (4, 2, "Order Model created instance", True),
        (2, 1, "Redirect to Order Confirmation View", True),
        (1, 0, "Display Order Success & Summary", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 1: Customer Order Placement & Scheduling", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_1.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_1.png")

# 5. SEQUENCE DIAGRAM 2
def generate_sequence_diagram_2():
    fig, ax = plt.subplots(figsize=(12, 7.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Staff Operator", "Staff Dashboard", "MachineController", "Machine Model", "OrderStatusHistory", "SmsNotificationService"]
    steps = [
        (0, 1, "Inspect load & select available machine", False),
        (1, 2, "POST /staff/machine/assign (order_id, machine_id)", False),
        (2, 3, "assignMachine(order_id, status='Washing')", False),
        (3, 2, "Machine state updated to 'Washing'", True),
        (2, 4, "recordStatusChange(order_id, 'In Wash')", False),
        (4, 2, "Status history recorded in DB", True),
        (2, 5, "dispatchSms(customer_phone, 'Wash Started')", False),
        (5, 2, "SendSmsJob enqueued & sent via API", True),
        (2, 1, "Return success response & refresh UI", True),
        (1, 0, "Display 'Machine Running - In Wash' status", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 2: Laundry Processing & Machine Allocation Management", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_2.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_2.png")

# 6. SEQUENCE DIAGRAM 3
def generate_sequence_diagram_3():
    fig, ax = plt.subplots(figsize=(12, 7.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Customer", "Rider", "Rider Dashboard", "PickupDeliveryController", "PickupDelivery Model", "MySQL Database"]
    steps = [
        (0, 3, "Request laundry pickup via portal", False),
        (1, 2, "Login & access Rider Dashboard", False),
        (2, 3, "GET /rider/deliveries/assigned", False),
        (3, 2, "Render active pickup/delivery tasks", True),
        (1, 2, "Click 'Start Pickup / En Route'", False),
        (2, 3, "POST /rider/status (id, 'En Route')", False),
        (3, 4, "update(['delivery_status' => 'En Route'])", False),
        (4, 5, "UPDATE pickup_deliveries & orders", False),
        (5, 3, "Database update success", True),
        (3, 0, "Push live delivery update notification", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 3: Pickup & Delivery Logistics Operations", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_3.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_3.png")

# 7. SEQUENCE DIAGRAM 4
def generate_sequence_diagram_4():
    fig, ax = plt.subplots(figsize=(12, 7.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)

    lifelines = ["Customer / Staff", "QR Camera UI", "QrScanLogController", "QrCode Model", "QrScanLog Model", "Digital Receipt Engine"]
    steps = [
        (0, 1, "Present/Scan Order QR Code", False),
        (1, 2, "POST /qr/verify (qr_hash)", False),
        (2, 3, "findByHash(qr_hash)", False),
        (3, 2, "Return order & payment details", True),
        (2, 4, "createAuditLog(qr_code_id, staff_id)", False),
        (4, 2, "Scan timestamp & scanner ID logged", True),
        (2, 5, "generateItemizedReceipt(order_id)", False),
        (5, 2, "PDF / Printable receipt compiled", True),
        (2, 1, "Display verified order & receipt", True),
        (1, 0, "Hand over clean laundry & receipt", True),
    ]

    draw_sequence_template(ax, "Sequence Diagram 4: QR Code Verification & Payment Processing", lifelines, steps)
    plt.tight_layout()
    plt.savefig('diagrams/sequence_diagram_4.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/sequence_diagram_4.png")

# 8. PACKAGE DIAGRAM
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

    ax.text(50, 96, "HourWash System Package & Subsystem Architecture Diagram", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    rect = patches.Rectangle((4, 4), 92, 88, fc='#FAFAFA', ec='#64748B', lw=1.5, linestyle='--')
    ax.add_patch(rect)
    ax.text(6, 89.5, "App Subsystems (Laravel 11 Architecture)", fontsize=11, fontweight='bold', color='#475569')

    draw_package(ax, 8, 66, "App\\Http\\Controllers", 
                 ["+ LaundryController", "+ MachineController", "+ ServiceController", "+ QrScanLogController", "+ AnalyticsController"], w=26, h=18, bg="#E0F2FE", border="#0284C7")

    draw_package(ax, 38, 66, "App\\Http\\Middleware", 
                 ["+ AdminMiddleware", "+ StaffMiddleware", "+ CustomerMiddleware", "+ RiderMiddleware", "+ SecurityHeaders"], w=26, h=18, bg="#F5F3FF", border="#7C3AED")

    draw_package(ax, 68, 66, "App\\Models", 
                 ["+ User", "+ Order", "+ Laundry", "+ Machine", "+ Service", "+ PickupDelivery", "+ QrCode"], w=24, h=18, bg="#FEF3C7", border="#B45309")

    draw_package(ax, 8, 38, "App\\Services", 
                 ["+ SmsNotificationService", "+ EmailNotificationService"], w=26, h=18, bg="#DCFCE7", border="#16A34A")

    draw_package(ax, 38, 38, "App\\Jobs & Mail", 
                 ["+ SendSmsJob", "+ OrderStatusUpdated"], w=26, h=18, bg="#FFEDD5", border="#EA580C")

    draw_package(ax, 68, 38, "Database\\Migrations", 
                 ["+ create_users_table", "+ create_orders_table", "+ create_machines_table", "+ create_qr_codes_table"], w=24, h=18, bg="#F3E8FF", border="#9333EA")

    draw_package(ax, 23, 10, "Resources\\Views", 
                 ["+ admin/* (Analytics)", "+ staff/* (Queue)", "+ customer/* (Order)", "+ rider/* (Logistics)"], w=26, h=18, bg="#F1F5F9", border="#475569")

    draw_package(ax, 53, 10, "App\\Providers", 
                 ["+ AppServiceProvider", "+ EventServiceProvider", "+ RouteServiceProvider"], w=24, h=18, bg="#F1F5F9", border="#475569")

    ax.annotate("", xy=(38, 75), xytext=(34, 75), arrowprops=dict(arrowstyle="->", lw=1.2, color="#64748B", linestyle=":"))
    ax.text(36, 76.5, "«use»", fontsize=8, color="#64748B", ha='center')

    ax.annotate("", xy=(68, 75), xytext=(64, 75), arrowprops=dict(arrowstyle="->", lw=1.2, color="#64748B", linestyle=":"))
    ax.text(66, 76.5, "«import»", fontsize=8, color="#64748B", ha='center')

    ax.annotate("", xy=(21, 56), xytext=(21, 66), arrowprops=dict(arrowstyle="->", lw=1.2, color="#64748B", linestyle=":"))
    ax.text(22.5, 61, "«call»", fontsize=8, color="#64748B")

    plt.tight_layout()
    plt.savefig('diagrams/package_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/package_diagram.png")

# 9. DEPLOYMENT DIAGRAM
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

    ax.text(50, 96, "HourWash System Infrastructure & Deployment Diagram", fontsize=15, fontweight='bold', ha='center', color=TEXT_COLOR)

    draw_node_3d(ax, 5, 55, "Client Devices", 
                 ["- Chrome / Safari / Edge Browsers", "- Android / iOS Mobile Browsers", "- Tailwind Responsive UI", "- JavaScript / Vite Engine"], w=26, h=22, bg="#E0F2FE", border="#0284C7")

    draw_node_3d(ax, 46, 55, "Web Application Server", 
                 ["- Nginx / Apache Web Server", "- PHP 8.5 FPM Runtime", "- Laravel 11 Application Framework", "- Eloquent ORM & Middleware", "- Artisan Task Scheduler"], w=27, h=22, bg="#DCFCE7", border="#16A34A")

    draw_node_3d(ax, 46, 12, "Database Server Node", 
                 ["- MySQL Server 8.0 Engine", "- InnoDB Storage Engine", "- Encrypted Database Connections", "- Daily Automated Backups"], w=27, h=20, bg="#FEF3C7", border="#B45309")

    draw_node_3d(ax, 5, 12, "External SMS Gateway", 
                 ["- Twilio / Semaphore Cloud REST API", "- SMS Notification Queue Engine", "- Real-Time Order Status SMS"], w=26, h=20, bg="#FFEDD5", border="#EA580C")

    ax.plot([33, 46], [66, 66], color='#0284C7', lw=1.8)
    ax.text(39.5, 68, "HTTPS / TLS (Port 443)\n[ REST / HTML ]", fontsize=7.8, color='#0284C7', fontweight='bold', ha='center')

    ax.plot([60, 60], [55, 34], color='#B45309', lw=1.8)
    ax.text(61.5, 45, "MySQL TCP/IP (Port 3306)\n[ PDO / SQL ]", fontsize=7.8, color='#B45309', fontweight='bold', ha='left')

    ax.plot([46, 33], [30, 22], color='#EA580C', lw=1.8, linestyle='--')
    ax.text(36, 28, "REST HTTPS API\n[ JSON Payload ]", fontsize=7.8, color='#EA580C', fontweight='bold', ha='center')

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
    print("ALL 9 DIAGRAMS GENERATED SUCCESSFULLY!")
