import os
import math
import matplotlib.pyplot as plt
import matplotlib.patches as patches

os.makedirs('diagrams', exist_ok=True)

# PURE BLACK AND WHITE UML THEME
PRIMARY_BG = '#FFFFFF'
BORDER_COLOR = '#000000'
TEXT_COLOR = '#000000'

plt.rcParams['font.sans-serif'] = 'Arial'
plt.rcParams['font.family'] = 'sans-serif'

# -------------------------------------------------------------
# HELPER: ELLIPSE BOUNDARY INTERSECTION
# -------------------------------------------------------------
def get_ellipse_border_pt(cx, cy, rx, ry, target_x, target_y):
    dx = target_x - cx
    dy = target_y - cy
    if dx == 0 and dy == 0:
        return cx, cy
    angle = math.atan2(dy, dx)
    bx = cx + rx * math.cos(angle)
    by = cy + ry * math.sin(angle)
    return bx, by

# -------------------------------------------------------------
# HELPER: NO TITLE WORDS INSIDE DIAGRAM CANVAS
# -------------------------------------------------------------
def add_underlined_title(ax, text, x=2, y=98.2, fontsize=12):
    # Intentional pass to remove all title words from inside the diagram PNG image canvas
    pass

# -------------------------------------------------------------
# 1. SYSTEM DESIGN DIAGRAM (BLACK & WHITE - NO TITLE WORDS)
# -------------------------------------------------------------
def generate_system_design_diagram():
    fig, ax = plt.subplots(figsize=(15, 10.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # Layer 1: Client / Presentation
    rect1 = patches.FancyBboxPatch((3, 78), 94, 17, linewidth=1.4, edgecolor='#000000', facecolor='#FFFFFF', boxstyle="round,pad=0.3")
    ax.add_patch(rect1)
    ax.text(5, 92.5, "1. PRESENTATION LAYER (Web Portals & Responsive Sidebar Navigation UI)", fontsize=10, fontweight='bold', color='#000000')
    
    ax.text(14, 85.0, "[ Customer Portal ]\n• Customer Dashboard\n• Book New Order\n• My Order History\n• Frequent User Card\n• Home Dashboard\n• Account Settings", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(38, 85.0, "[ Staff Console ]\n• Workstation Dashboard\n• Manage Laundry Orders\n• Manage Machines\n• New Walk-in Order\n• QR Scan Logs Outbox\n• Account Settings", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(62, 85.0, "[ Rider Dashboard ]\n• Rider of Hour Wash\n  (Pickup/Delivery)\n• Home Dashboard\n• Account Settings\n• Proof Photo Upload", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(86, 85.0, "[ Admin Portal ]\n• Overall Reports\n• Manage Laundry Orders\n• Manage Machines & Users\n• Services & Pricing\n• Live SMS/Email Outbox", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))

    ax.annotate("", xy=(50, 62), xytext=(50, 78), arrowprops=dict(arrowstyle="->", lw=1.8, color="#000000"))
    ax.text(52, 70, "HTTP / HTTPS Requests (JSON / Blade Forms / REST API)", fontsize=8.5, color="#000000", fontweight='bold')

    # Layer 2: Routing & Middleware
    rect2 = patches.FancyBboxPatch((3, 50), 94, 12, linewidth=1.4, edgecolor='#000000', facecolor='#FFFFFF', boxstyle="round,pad=0.3")
    ax.add_patch(rect2)
    ax.text(5, 59, "2. SECURITY, AUTHENTICATION & ROUTING MIDDLEWARE LAYER", fontsize=10, fontweight='bold', color='#000000')
    ax.text(16, 54.5, "Breeze Auth Session\nVerification", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(38, 54.5, "CustomerMiddleware &\nStaffMiddleware", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(62, 54.5, "RiderMiddleware &\nAdminMiddleware", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(84, 54.5, "SecurityHeaders &\nCSRF Protection", fontsize=8, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.2", fc="#FFFFFF", ec="#000000", lw=1))

    ax.annotate("", xy=(50, 38), xytext=(50, 50), arrowprops=dict(arrowstyle="->", lw=1.8, color="#000000"))

    # Layer 3: Controllers & Services
    rect3 = patches.FancyBboxPatch((3, 23), 94, 15, linewidth=1.4, edgecolor='#000000', facecolor='#FFFFFF', boxstyle="round,pad=0.3")
    ax.add_patch(rect3)
    ax.text(5, 35.5, "3. APPLICATION CONTROLLERS & LOGIC LAYER (Laravel 11 / PHP 8.5)", fontsize=10, fontweight='bold', color='#000000')
    ax.text(20, 28.5, "Controllers:\nAuth Controllers | LaundryController\nMachineController | ServiceController\nChatbotController (AI Engine)\nQrScanLogController | ProfileController", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(52, 28.5, "Domain Services:\nSmsNotificationService (TextBee)\nEmailNotificationService (Brevo)\nLoyaltyStampService (12-Stamp Card)\nReceiptGeneratorEngine", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))
    ax.text(82, 28.5, "Asynchronous Jobs & AI:\nSendSmsJob Queue\nOrderStatusUpdated Mail\nOpenAI / Ollama AI Chatbot Engine", fontsize=7.5, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))

    ax.annotate("", xy=(35, 14), xytext=(35, 23), arrowprops=dict(arrowstyle="->", lw=1.8, color="#000000"))
    ax.text(36.5, 18.5, "Eloquent ORM (SQL Queries / Relational Reads & Writes)", fontsize=8.2, color="#000000", fontweight='bold')
    ax.annotate("", xy=(82, 14), xytext=(82, 23), arrowprops=dict(arrowstyle="->", lw=1.8, color="#000000"))
    ax.text(83.5, 18.5, "REST / API Integration", fontsize=8.2, color="#000000", fontweight='bold')

    # Layer 4A: Database Persistence
    rect4a = patches.FancyBboxPatch((3, 2), 60, 12, linewidth=1.4, edgecolor='#000000', facecolor='#FFFFFF', boxstyle="round,pad=0.3")
    ax.add_patch(rect4a)
    ax.text(5, 11.5, "4. PERSISTENCE LAYER (MySQL Database)", fontsize=9.5, fontweight='bold', color='#000000')
    ax.text(33, 6.0, "Tables: users (frequent_user_card) | customer_profiles | staff_profiles | services |\nmachines | orders | order_status_history | qr_codes | qr_scan_logs |\npickup_delivery | sms_notifications | email_notifications | customer_feedbacks", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))

    # Layer 4B: External APIs
    rect4b = patches.FancyBboxPatch((65, 2), 32, 12, linewidth=1.4, edgecolor='#000000', facecolor='#FFFFFF', boxstyle="round,pad=0.3")
    ax.add_patch(rect4b)
    ax.text(67, 11.5, "5. EXTERNAL API GATEWAYS", fontsize=9.5, fontweight='bold', color='#000000')
    ax.text(81, 6.0, "• TextBee SMS Gateway (api.textbee.dev)\n• Brevo Transactional Email (api.brevo.com)\n• OpenAI Cloud LLM & Ollama Local LLM\n• QRServer Engine (api.qrserver.com)", fontsize=7.2, ha='center', va='center', bbox=dict(boxstyle="round,pad=0.3", fc="#FFFFFF", ec="#000000", lw=1))

    plt.tight_layout()
    plt.savefig('diagrams/system_design_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/system_design_diagram.png")

# -------------------------------------------------------------
# 2. USE CASE DIAGRAM (BLACK & WHITE - NO TITLE WORDS)
# -------------------------------------------------------------
def draw_actor_bw(ax, x, y, name, is_left=True):
    circle = patches.Circle((x, y + 2.5), 1.3, fc='#FFFFFF', ec='#000000', lw=1.6)
    ax.add_patch(circle)
    ax.plot([x, x], [y + 1.2, y - 1.8], color='#000000', lw=1.8)
    ax.plot([x - 2.0, x + 2.0], [y + 0.3, y + 0.3], color='#000000', lw=1.8)
    ax.plot([x, x - 1.6], [y - 1.8, y - 4.0], color='#000000', lw=1.8)
    ax.plot([x, x + 1.6], [y - 1.8, y - 4.0], color='#000000', lw=1.8)
    ax.text(x, y - 5.5, name, fontsize=9.2, fontweight='bold', ha='center', va='top', color='#000000')

    if is_left:
        return (x + 2.0, y + 0.3)
    else:
        return (x - 2.0, y + 0.3)

def draw_usecase_bw(ax, x, y, text, w=17, h=2.3):
    ellipse = patches.Ellipse((x, y), w, h, fc='#FFFFFF', ec='#000000', lw=1.3)
    ax.add_patch(ellipse)
    ax.text(x, y, text, fontsize=6.5, ha='center', va='center', color='#000000', fontweight='bold')
    return x, y, w/2.0, h/2.0

def draw_dashed_relationship_border_bw(ax, src_cx, src_cy, src_rx, src_ry, tgt_cx, tgt_cy, tgt_rx, tgt_ry, label):
    x1, y1 = get_ellipse_border_pt(src_cx, src_cy, src_rx, src_ry, tgt_cx, tgt_cy)
    x2, y2 = get_ellipse_border_pt(tgt_cx, tgt_cy, tgt_rx, tgt_ry, src_cx, src_cy)

    ax.annotate("", xy=(x2, y2), xytext=(x1, y1),
                arrowprops=dict(arrowstyle="->", lw=1.2, color='#000000', linestyle='--'))
    mx, my = (x1 + x2) / 2, (y1 + y2) / 2
    ax.text(mx, my, label, fontsize=6.2, fontweight='bold', color='#000000', ha='center', va='center',
            bbox=dict(boxstyle="square,pad=0.12", fc="#FFFFFF", ec="none"))

def generate_use_case_diagram():
    fig, ax = plt.subplots(figsize=(16, 12.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # System Boundary Box
    rect = patches.FancyBboxPatch((18, 2), 64, 95, boxstyle="round,pad=0.5", ec='#000000', fc='#FFFFFF', lw=2.0)
    ax.add_patch(rect)
    ax.text(50, 95.2, "A Web-Based Laundry Service Management System for HourWash Laundry Shop in Orosite Legazpi City", fontsize=7.2, fontweight='bold', ha='center', color='#000000')

    # Actor Stick Figures
    customer_hand = draw_actor_bw(ax, 7, 75, "Customer Role\n(User / Student)", is_left=True)
    staff_hand = draw_actor_bw(ax, 7, 25, "Staff Operator\n(Store Cashier)", is_left=True)
    rider_hand = draw_actor_bw(ax, 93, 75, "Rider of HourWash", is_left=False)
    admin_hand = draw_actor_bw(ax, 93, 25, "System Administrator\n(Store Manager)", is_left=False)

    uc_dict = {}

    # --- SHARED TOP AUTHENTICATION USE CASES (SINGLE LOGIN, REGISTER, FORGOT) ---
    uc_dict['LOGIN'] = draw_usecase_bw(ax, 50, 90.0, "Login Authentication")
    uc_dict['REGISTER'] = draw_usecase_bw(ax, 33, 90.0, "User Registration")
    uc_dict['FORGOT'] = draw_usecase_bw(ax, 67, 90.0, "Forgot Password Reset")

    # --- CUSTOMER ROLE USE CASES ---
    uc_dict['UC_CUST_DASH'] = draw_usecase_bw(ax, 33, 82.0, "Customer Dashboard")
    uc_dict['UC_BOOK_ORDER'] = draw_usecase_bw(ax, 33, 77.0, "Book New Order")
    uc_dict['UC_ORDER_HIST'] = draw_usecase_bw(ax, 33, 72.0, "My Order History")
    uc_dict['UC_STAMPS'] = draw_usecase_bw(ax, 33, 67.0, "Frequent User Card (12 Stamps)")
    uc_dict['UC_CUST_SETTINGS'] = draw_usecase_bw(ax, 33, 62.0, "Customer Account Settings")

    # --- STAFF OPERATOR ROLE USE CASES ---
    uc_dict['UC_STAFF_DASH'] = draw_usecase_bw(ax, 33, 46.0, "Workstation Dashboard")
    uc_dict['UC_MANAGE_ORDERS'] = draw_usecase_bw(ax, 33, 41.0, "Manage Laundry Orders")
    uc_dict['UC_MANAGE_MACHINES'] = draw_usecase_bw(ax, 33, 36.0, "Manage Machines Fleet")
    uc_dict['UC_WALKIN_ORDER'] = draw_usecase_bw(ax, 33, 31.0, "New Walk-in Order")
    uc_dict['UC_STAFF_QR'] = draw_usecase_bw(ax, 33, 26.0, "QR Scan Logs Outbox")
    uc_dict['UC_STAFF_SETTINGS'] = draw_usecase_bw(ax, 33, 21.0, "Staff Account Settings")

    # --- RIDER LOGISTICS ROLE USE CASES ---
    uc_dict['UC_RIDER_DASH'] = draw_usecase_bw(ax, 67, 82.0, "Rider of Hour Wash")
    uc_dict['UC_PICKUP_STATUS'] = draw_usecase_bw(ax, 67, 77.0, "Update Pickup Status")
    uc_dict['UC_DELIVERY_PROOF'] = draw_usecase_bw(ax, 67, 72.0, "Update Delivery & Proof Upload")
    uc_dict['UC_RIDER_SETTINGS'] = draw_usecase_bw(ax, 67, 67.0, "Rider Account Settings")

    # --- SYSTEM ADMINISTRATOR ROLE USE CASES ---
    uc_dict['UC_ADMIN_REPORTS'] = draw_usecase_bw(ax, 67, 54.0, "Overall Reports & Dashboard")
    uc_dict['UC_PRICING'] = draw_usecase_bw(ax, 67, 48.0, "Services & Pricing")
    uc_dict['UC_MANAGE_USERS'] = draw_usecase_bw(ax, 67, 42.0, "Manage Users (Stamps)")
    uc_dict['UC_SMS_OUTBOX'] = draw_usecase_bw(ax, 67, 36.0, "Live SMS Outbox (TextBee)")
    uc_dict['UC_EMAIL_OUTBOX'] = draw_usecase_bw(ax, 67, 30.0, "Live Email Outbox (Brevo)")
    uc_dict['UC_REVIEWS'] = draw_usecase_bw(ax, 67, 24.0, "Customer Reviews Outbox")
    uc_dict['UC_ADMIN_SETTINGS'] = draw_usecase_bw(ax, 67, 18.0, "Admin Account Settings")

    # --- CENTRAL INFRASTRUCTURE USE CASES ---
    uc_dict['DB_AUTH'] = draw_usecase_bw(ax, 50, 82.0, "Database Login Auth\n(Bcrypt Session)")
    uc_dict['EMAIL_GW'] = draw_usecase_bw(ax, 50, 68.0, "Transactional Email Gateway\n(Brevo REST API)")
    uc_dict['SMS_GW'] = draw_usecase_bw(ax, 50, 53.0, "Real-time SMS Notifications\n(TextBee REST API)")
    uc_dict['QR_ENG'] = draw_usecase_bw(ax, 50, 38.0, "Digital QR Code Engine\n(api.qrserver.com)")
    uc_dict['EXT_TIME'] = draw_usecase_bw(ax, 50, 23.0, "+60m Power Outage\nTime Extension")
    uc_dict['HOME_VIEW'] = draw_usecase_bw(ax, 50, 8.0, "Public Home Dashboard\n(Landing Page View)")

    # --- CONNECT ACTORS TO USE CASES ---
    hx, hy = customer_hand
    for key in ['REGISTER', 'LOGIN', 'FORGOT', 'UC_CUST_DASH', 'UC_BOOK_ORDER', 'UC_ORDER_HIST', 'UC_STAMPS', 'UC_CUST_SETTINGS', 'HOME_VIEW']:
        cx, cy, rx, ry = uc_dict[key]
        bx, by = get_ellipse_border_pt(cx, cy, rx, ry, hx, hy)
        ax.plot([hx, bx], [hy, by], color='#000000', lw=1.1)

    hx, hy = staff_hand
    for key in ['LOGIN', 'FORGOT', 'UC_STAFF_DASH', 'UC_MANAGE_ORDERS', 'UC_MANAGE_MACHINES', 'UC_WALKIN_ORDER', 'UC_STAFF_QR', 'UC_STAFF_SETTINGS', 'HOME_VIEW']:
        cx, cy, rx, ry = uc_dict[key]
        bx, by = get_ellipse_border_pt(cx, cy, rx, ry, hx, hy)
        ax.plot([hx, bx], [hy, by], color='#000000', lw=1.1)

    hx, hy = rider_hand
    for key in ['LOGIN', 'FORGOT', 'UC_RIDER_DASH', 'UC_PICKUP_STATUS', 'UC_DELIVERY_PROOF', 'UC_RIDER_SETTINGS', 'HOME_VIEW']:
        cx, cy, rx, ry = uc_dict[key]
        bx, by = get_ellipse_border_pt(cx, cy, rx, ry, hx, hy)
        ax.plot([hx, bx], [hy, by], color='#000000', lw=1.1)

    hx, hy = admin_hand
    for key in ['LOGIN', 'FORGOT', 'UC_ADMIN_REPORTS', 'UC_PRICING', 'UC_MANAGE_USERS', 'UC_SMS_OUTBOX', 'UC_EMAIL_OUTBOX', 'UC_REVIEWS', 'UC_ADMIN_SETTINGS', 'HOME_VIEW']:
        cx, cy, rx, ry = uc_dict[key]
        bx, by = get_ellipse_border_pt(cx, cy, rx, ry, hx, hy)
        ax.plot([hx, bx], [hy, by], color='#000000', lw=1.1)

    # --- DASHED RELATIONSHIPS (<<include>> / <<extend>>) ---
    def connect_dashed(src_key, tgt_key, label):
        src_cx, src_cy, src_rx, src_ry = uc_dict[src_key]
        tgt_cx, tgt_cy, tgt_rx, tgt_ry = uc_dict[tgt_key]
        draw_dashed_relationship_border_bw(ax, src_cx, src_cy, src_rx, src_ry, tgt_cx, tgt_cy, tgt_rx, tgt_ry, label)

    connect_dashed('LOGIN', 'DB_AUTH', "<<include>>")
    connect_dashed('FORGOT', 'EMAIL_GW', "<<include>>")
    connect_dashed('UC_BOOK_ORDER', 'SMS_GW', "<<include>>")
    connect_dashed('UC_ORDER_HIST', 'QR_ENG', "<<include>>")
    connect_dashed('UC_MANAGE_MACHINES', 'EXT_TIME', "<<extend>>")
    connect_dashed('UC_PICKUP_STATUS', 'SMS_GW', "<<include>>")
    connect_dashed('UC_SMS_OUTBOX', 'SMS_GW', "<<include>>")
    connect_dashed('UC_EMAIL_OUTBOX', 'EMAIL_GW', "<<include>>")

    plt.tight_layout()
    plt.savefig('diagrams/use_case_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/use_case_diagram.png")

# -------------------------------------------------------------
# 3. CLASS DIAGRAM (BLACK & WHITE - EXACT 3-TIER, ZERO OVERLAPS, NO TITLE WORDS)
# -------------------------------------------------------------
def draw_class_box_bw(ax, x, y, name, attrs, methods, w=29, h=30):
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(rect)
    
    h_rect = patches.Rectangle((x, y + h - 3.2), w, 3.2, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(h_rect)
    ax.text(x + w/2.0, y + h - 1.6, name, fontsize=8.8, fontweight='bold', ha='center', va='center', color='#000000')
    
    ax.plot([x, x + w], [y + h - 3.2, y + h - 3.2], color='#000000', lw=1.2)

    attr_start_y = y + h - 3.8
    for i, attr in enumerate(attrs):
        ax.text(x + 0.6, attr_start_y - (i * 1.05), attr, fontsize=6.2, va='top', ha='left', color='#000000', fontfamily='sans-serif')

    div_y = attr_start_y - (len(attrs) * 1.05) - 0.2
    ax.plot([x, x + w], [div_y, div_y], color='#000000', lw=1.2)

    method_start_y = div_y - 0.4
    for j, meth in enumerate(methods):
        ax.text(x + 0.6, method_start_y - (j * 1.05), meth, fontsize=6.2, va='top', ha='left', color='#000000', fontfamily='sans-serif')

def draw_composition_diamond_bw(ax, x, y, direction='down'):
    if direction == 'down':
        pts = [[x, y], [x - 0.8, y - 1.2], [x, y - 2.4], [x + 0.8, y - 1.2]]
    elif direction == 'up':
        pts = [[x, y], [x - 0.8, y + 1.2], [x, y + 2.4], [x + 0.8, y + 1.2]]
    elif direction == 'left':
        pts = [[x, y], [x - 1.2, y + 0.8], [x - 2.4, y], [x - 1.2, y - 0.8]]
    elif direction == 'right':
        pts = [[x, y], [x + 1.2, y + 0.8], [x + 2.4, y], [x + 1.2, y - 0.8]]
    diamond = patches.Polygon(pts, fc='#000000', ec='#000000', lw=1.2, zorder=5)
    ax.add_patch(diamond)

def draw_aggregation_diamond_bw(ax, x, y, direction='down'):
    if direction == 'down':
        pts = [[x, y], [x - 0.8, y - 1.2], [x, y - 2.4], [x + 0.8, y - 1.2]]
    elif direction == 'up':
        pts = [[x, y], [x - 0.8, y + 1.2], [x, y + 2.4], [x + 0.8, y + 1.2]]
    diamond = patches.Polygon(pts, fc='#FFFFFF', ec='#000000', lw=1.3, zorder=5)
    ax.add_patch(diamond)

def draw_generalization_triangle_bw(ax, x, y, direction='up'):
    if direction == 'up':
        pts = [[x, y], [x - 1.2, y - 2.6], [x + 1.2, y - 2.6]]
    elif direction == 'down':
        pts = [[x, y], [x - 1.2, y + 2.6], [x + 1.2, y + 2.6]]
    tri = patches.Polygon(pts, fc='#FFFFFF', ec='#000000', lw=1.4, zorder=5)
    ax.add_patch(tri)

def generate_class_diagram():
    fig, ax = plt.subplots(figsize=(16, 13.0), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # 1. TOP TIER: Users Parent Base Class (Top Center, y=80.0 to y=96.5)
    draw_class_box_bw(ax, 38, 80.0, "Users", 
                      ["- Name: varchar", "- Email: varchar", "- Phone Number: varchar", "- Address: varchar", "- Password: varchar", "- Role: varchar", "- Frequent Stamps: int"], 
                      ["+login()", "+changePassword()", "+updateProfile()"], w=24, h=16.5)

    # 2. MIDDLE TIER: Subclasses Inheriting from Users (y=57.0 to y=73.0)
    draw_class_box_bw(ax, 4, 57.0, "Customer", 
                      ["- user_id: int", "- address: varchar", "- barangay: varchar", "- city: varchar"], 
                      ["+registerCustomer()", "+bookNewOrder()", "+viewMyOrderHistory()", "+viewFrequentUserCard()", "+editProfile()"], w=20, h=16.0)

    draw_class_box_bw(ax, 27.5, 57.0, "Staff", 
                      ["- user_id: int", "- employee_id: varchar", "- position: varchar", "- status: varchar"], 
                      ["+manageLaundryOrders()", "+weighScaleOrder()", "+manageMachines()", "+triggerExtension()", "+createWalkInOrder()"], w=20, h=16.0)

    draw_class_box_bw(ax, 51, 57.0, "Rider of HourWash", 
                      ["- user_id: int", "- rider_name: varchar", "- contact_number: varchar", "- status: varchar"], 
                      ["+viewRiderDispatches()", "+updatePickupStatus()", "+updateDeliveryStatus()", "+uploadProofPhoto()"], w=20, h=16.0)

    draw_class_box_bw(ax, 74.5, 57.0, "Admin", 
                      ["- user_id: int", "- admin_level: varchar", "- status: varchar"], 
                      ["+viewOverallReports()", "+manageServicesAndPricing()", "+manageUsersAndStamps()", "+viewLiveSmsOutbox()", "+viewLiveEmailOutbox()"], w=20, h=16.0)

    # Generalization Lines (Subclasses -> Users Parent)
    draw_generalization_triangle_bw(ax, 50, 80.0, direction='up')
    ax.plot([50, 50], [77.4, 76.5], color='#000000', lw=1.4)
    ax.plot([14.0, 84.5], [76.5, 76.5], color='#000000', lw=1.4)
    ax.plot([14.0, 14.0], [76.5, 73.0], color='#000000', lw=1.4)
    ax.plot([37.5, 37.5], [76.5, 73.0], color='#000000', lw=1.4)
    ax.plot([61.0, 61.0], [76.5, 73.0], color='#000000', lw=1.4)
    ax.plot([84.5, 84.5], [76.5, 73.0], color='#000000', lw=1.4)

    # 3. BOTTOM TIER: 10 Use Case Feature Classes (w=17.5, gap=2.125)
    # Row 1 (y=30.0 to 46.0, h=16.0)
    draw_class_box_bw(ax, 2.0, 30.0, "Manage Laundry Orders", 
                      ["- Order ID: int", "- Order Number: varchar", "- Customer ID: int", "- Service ID: int", "- Total Amount: float", "- Order Status: varchar", "- Weight: float"], 
                      ["+createBookOrder()", "+createWalkInOrder()", "+updateOrderStatus()", "+calculateTotalAmount()"], w=17.5, h=16.0)

    draw_class_box_bw(ax, 21.625, 30.0, "Services & Pricing", 
                      ["- Service ID: int", "- Service Name: varchar", "- Service Type: varchar", "- Rate Per Kg: float", "- Est Duration: int"], 
                      ["+getPublicServices()", "+calculateServiceRate()", "+updateTariffRates()"], w=17.5, h=16.0)

    draw_class_box_bw(ax, 41.25, 30.0, "Manage Machines", 
                      ["- Machine ID: int", "- Machine Code: varchar", "- Machine Type: varchar", "- Status: varchar", "- Remaining Min: int"], 
                      ["+assignOrderToMachine()", "+trigger60mExtension()", "+toggleMachineStatus()"], w=17.5, h=16.0)

    draw_class_box_bw(ax, 60.875, 30.0, "Pickup & Delivery", 
                      ["- Task ID: int", "- Order ID: int", "- Rider Name: varchar", "- Logistics Status: varchar", "- Proof Photo: varchar"], 
                      ["+updatePickupStatus()", "+updateDeliveryStatus()", "+uploadProofPhoto()"], w=17.5, h=16.0)

    draw_class_box_bw(ax, 80.5, 30.0, "Overall Reports", 
                      ["- Report ID: int", "- Total Revenue: float", "- Total Orders: int", "- Active Machines: int", "- Report Date: date"], 
                      ["+generateDailyReport()", "+fetchRevenueAnalytics()", "+exportSummaryPDF()"], w=17.5, h=16.0)

    # Row 2 (y=3.5 to 18.5, h=15.0)
    draw_class_box_bw(ax, 2.0, 3.5, "Live SMS Outbox", 
                      ["- Log ID: int", "- Recipient Phone: varchar", "- Message Body: text", "- Delivery Status: varchar", "- Sent Timestamp: datetime"], 
                      ["+sendTextBeeSms()", "+logSmsDispatch()", "+skipIfCanceled()"], w=17.5, h=15.0)

    draw_class_box_bw(ax, 21.625, 3.5, "Live Email Outbox", 
                      ["- Email ID: int", "- Recipient Email: varchar", "- Subject: varchar", "- Email Body: text", "- Send Status: varchar"], 
                      ["+sendBrevoEmail()", "+logEmailDispatch()", "+retryFailedEmail()"], w=17.5, h=15.0)

    draw_class_box_bw(ax, 41.25, 3.5, "QR Scan Logs", 
                      ["- Audit Log ID: int", "- Order ID: int", "- Scanned By: int", "- QR Token: varchar", "- Scan Timestamp: datetime"], 
                      ["+logQrScan()", "+verifyQrToken()", "+fetchScanAuditHistory()"], w=17.5, h=15.0)

    draw_class_box_bw(ax, 60.875, 3.5, "12-Stamp User Card", 
                      ["- Card ID: int", "- Customer ID: int", "- Total Stamps: int", "- Reward Claimed: boolean", "- Expiry Date: date"], 
                      ["+addOrderStamp()", "+redeemFreeWash()", "+getStampingStatus()"], w=17.5, h=15.0)

    draw_class_box_bw(ax, 80.5, 3.5, "Customer Reviews", 
                      ["- Review ID: int", "- Order ID: int", "- Customer ID: int", "- Rating Stars: int", "- Comments: text"], 
                      ["+submitCustomerReview()", "+getPublicReviews()", "+deleteReview()"], w=17.5, h=15.0)

    # 4. RELATIONSHIP CONNECTIONS (Exact Guide Style with Generous Channel Spacing)

    # Helper function to draw relationship from subclass bottom to lower class top
    def draw_guide_conn(x_start, target_x, target_y, is_comp=True, y_track1=50.0, x_channel=None, y_track2=None):
        # 1. Diamond at subclass bottom (y=57.0)
        if is_comp:
            draw_composition_diamond_bw(ax, x_start, 57.0, direction='down')
        else:
            draw_aggregation_diamond_bw(ax, x_start, 57.0, direction='down')
        
        # Multiplicity '1' next to diamond
        ax.text(x_start + 1.1, 54.8, "1", fontsize=7.2, fontweight='bold', color='#000000', va='center')

        # Line points
        d_bottom = 54.6
        if x_channel is None:
            # Direct path in upper gap to Row 1 box top
            ax.plot([x_start, x_start, target_x, target_x], [d_bottom, y_track1, y_track1, target_y], color='#000000', lw=1.3)
        else:
            # Multi-turn path passing through channel to Row 2 box top
            ax.plot([x_start, x_start, x_channel, x_channel, target_x, target_x], 
                    [d_bottom, y_track1, y_track1, y_track2, y_track2, target_y], color='#000000', lw=1.3)

        # Arrowhead entering target box top
        ax.annotate("", xy=(target_x, target_y), xytext=(target_x, target_y + 1.2), 
                    arrowprops=dict(arrowstyle="->", lw=1.3, color='#000000'))
        
        # Multiplicity '1..*' near arrowhead
        ax.text(target_x, target_y + 0.8, "1..*", fontsize=6.8, fontweight='bold', color='#000000', ha='center', 
                bbox=dict(boxstyle="square,pad=0.1", fc="#FFFFFF", ec="none"))

    # Channel Midpoints:
    # Ch1 (between 19.5 and 21.625): 20.56
    # Ch2 (between 39.125 and 41.25): 40.188 (use 39.8 and 40.5)
    # Ch3 (between 58.75 and 60.875): 59.81
    # Ch4 (between 78.375 and 80.5): 79.44

    # --- A) CUSTOMER CONNECTIONS (Subclass x=4..24) ---
    # 1. Customer ◆-- Manage Laundry Orders
    draw_guide_conn(x_start=6.5, target_x=6.5, target_y=46.0, is_comp=True, y_track1=50.8)

    # 2. Customer ◇-- Services & Pricing
    draw_guide_conn(x_start=11.5, target_x=26.0, target_y=46.0, is_comp=False, y_track1=52.8)

    # 3. Customer ◇-- 12-Stamp User Card
    draw_guide_conn(x_start=16.5, target_x=69.625, target_y=18.5, is_comp=False, y_track1=51.8, x_channel=59.81, y_track2=23.0)

    # 4. Customer ◇-- Customer Reviews
    draw_guide_conn(x_start=21.5, target_x=89.25, target_y=18.5, is_comp=False, y_track1=50.0, x_channel=79.44, y_track2=21.8)

    # --- B) STAFF CONNECTIONS (Subclass x=27.5..47.5) ---
    # 1. Staff ◆-- Manage Laundry Orders
    draw_guide_conn(x_start=30.0, target_x=15.0, target_y=46.0, is_comp=True, y_track1=49.6)

    # 2. Staff ◆-- Manage Machines
    draw_guide_conn(x_start=37.5, target_x=45.5, target_y=46.0, is_comp=True, y_track1=48.4)

    # 3. Staff ◇-- QR Scan Logs
    draw_guide_conn(x_start=44.0, target_x=50.0, target_y=18.5, is_comp=False, y_track1=49.0, x_channel=39.8, y_track2=24.2)

    # --- C) RIDER CONNECTIONS (Subclass x=51..71) ---
    # 1. Rider ◆-- Pickup & Delivery
    draw_guide_conn(x_start=61.0, target_x=69.625, target_y=46.0, is_comp=True, y_track1=47.5)

    # --- D) ADMIN CONNECTIONS (Subclass x=74.5..94.5) ---
    # 1. Admin ◇-- Services & Pricing
    draw_guide_conn(x_start=77.5, target_x=34.0, target_y=46.0, is_comp=False, y_track1=53.4)

    # 2. Admin ◇-- Overall Reports
    draw_guide_conn(x_start=82.0, target_x=89.25, target_y=46.0, is_comp=False, y_track1=48.2)

    # 3. Admin ◇-- Live Email Outbox
    draw_guide_conn(x_start=87.0, target_x=30.375, target_y=18.5, is_comp=False, y_track1=52.4, x_channel=40.5, y_track2=25.4)

    # 4. Admin ◇-- Live SMS Outbox
    draw_guide_conn(x_start=91.5, target_x=10.75, target_y=18.5, is_comp=False, y_track1=53.8, x_channel=20.56, y_track2=26.6)

    plt.tight_layout()
    plt.savefig('diagrams/class_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/class_diagram.png")



# -------------------------------------------------------------
# SEQUENCE DIAGRAM ENGINE (BLACK & WHITE - NO TITLE WORDS)
# -------------------------------------------------------------
def draw_sequence_template(ax, title, lifelines, steps, alt_fragment=None):
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    num_l = len(lifelines)
    xs = [10 + i * (80 / (num_l - 1)) for i in range(num_l)]

    # Draw First Participant as Black & White Stick Figure Actor
    actor_x = xs[0]
    circle = patches.Circle((actor_x, 93.5), 1.1, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(circle)
    ax.plot([actor_x, actor_x], [92.4, 90.2], color='#000000', lw=1.8)
    ax.plot([actor_x - 1.6, actor_x + 1.6], [91.7, 91.7], color='#000000', lw=1.8)
    ax.plot([actor_x, actor_x - 1.2], [90.2, 88.5], color='#000000', lw=1.8)
    ax.plot([actor_x, actor_x + 1.2], [90.2, 88.5], color='#000000', lw=1.8)
    ax.text(actor_x, 87.0, lifelines[0], fontsize=7.8, fontweight='bold', ha='center', va='top', color='#000000')

    # Draw Subsequent Participants as B&W Component Boxes
    for i in range(1, num_l):
        x = xs[i]
        box = patches.Rectangle((x - 6.5, 89.0), 13.0, 4.2, fc='#FFFFFF', ec='#000000', lw=1.3)
        ax.add_patch(box)
        ax.text(x, 91.1, lifelines[i], fontsize=7.2, fontweight='bold', ha='center', va='center', color='#000000')

    # Draw Lifeline Vertical Black Dashed Lines
    ax.plot([xs[0], xs[0]], [85.5, 7.0], color='#000000', linestyle='--', lw=1.2)
    for i in range(1, num_l):
        x = xs[i]
        ax.plot([x, x], [89.0, 7.0], color='#000000', linestyle='--', lw=1.2)

    # Calculate step Y coordinates
    num_steps = len(steps)
    y_start = 83.0
    y_end = 12.0
    y_step = (y_start - y_end) / (num_steps + 1)

    # Draw Vertical Activation Bars
    bar_w = 1.5
    half_bar = 0.75
    for i in range(num_l):
        x = xs[i]
        act_y_max = None
        act_y_min = None
        for idx, (f_idx, t_idx, _, _) in enumerate(steps):
            if f_idx == i or t_idx == i:
                cur_y = y_start - (idx + 1) * y_step
                if act_y_max is None or cur_y > act_y_max:
                    act_y_max = cur_y
                if act_y_min is None or cur_y < act_y_min:
                    act_y_min = cur_y
        if act_y_max is not None and act_y_min is not None:
            bar_top = min(act_y_max + 1.5, 85.0)
            bar_bottom = max(act_y_min - 1.5, 9.0)
            bar = patches.Rectangle((x - half_bar, bar_bottom), bar_w, bar_top - bar_bottom, fc='#FFFFFF', ec='#000000', lw=1.2, zorder=3)
            ax.add_patch(bar)

    # Render Combined Fragment Box (Alt Box) if specified
    if alt_fragment:
        alt_top, alt_mid, alt_bottom, valid_text, invalid_text = alt_fragment
        
        alt_box = patches.Rectangle((xs[0] - 3.5, alt_bottom), (xs[min(3, num_l-1)] - xs[0]) + 7.0, alt_top - alt_bottom, 
                                    fc='#FFFFFF', ec='#000000', lw=1.4, zorder=2)
        ax.add_patch(alt_box)

        tag = patches.Polygon([[xs[0] - 3.5, alt_top], [xs[0] + 3.0, alt_top], [xs[0] + 5.0, alt_top - 2.2], [xs[0] - 3.5, alt_top - 2.2]],
                              fc='#FFFFFF', ec='#000000', lw=1, zorder=4)
        ax.add_patch(tag)
        ax.text(xs[0] - 0.5, alt_top - 1.1, "Alt", fontsize=8.0, fontweight='bold', color='#000000', ha='center', va='center', zorder=5)

        ax.plot([xs[0] - 3.5, xs[min(3, num_l-1)] + 3.5], [alt_mid, alt_mid], color='#000000', linestyle='--', lw=1.2, zorder=4)

        ax.text(xs[0] - 2.5, alt_top - 3.5, f"[ {valid_text} ]", fontsize=7.5, fontweight='bold', fontstyle='italic', color='#000000', zorder=5)
        ax.text(xs[0] - 2.5, alt_mid - 2.5, f"[ {invalid_text} ]", fontsize=7.5, fontweight='bold', fontstyle='italic', color='#000000', zorder=5)

    # Render Sequence Arrow Messages
    for idx, step in enumerate(steps):
        cur_y = y_start - (idx + 1) * y_step
        from_idx, to_idx, label, is_return = step
        fx_center, tx_center = xs[from_idx], xs[to_idx]

        if fx_center < tx_center:
            start_x = fx_center + half_bar
            end_x = tx_center - half_bar
        else:
            start_x = fx_center - half_bar
            end_x = tx_center + half_bar
        
        ls = '--' if is_return else '-'
        arr_style = "->"
        
        ax.annotate("", xy=(end_x, cur_y), xytext=(start_x, cur_y),
                    arrowprops=dict(arrowstyle=arr_style, lw=1.2, color='#000000', linestyle=ls), zorder=6)
        
        mid_x = (start_x + end_x) / 2
        ax.text(mid_x, cur_y + 1.1, f"{idx+1}. {label}", fontsize=6.8, ha='center', va='bottom', color='#000000', fontweight='bold',
                bbox=dict(boxstyle="square,pad=0.1", fc="#FFFFFF", ec="none"), zorder=7)

# -------------------------------------------------------------
# 38 ACCURATE SEQUENCE DIAGRAM DEFINITIONS
# -------------------------------------------------------------
ALL_ACCURATE_SEQUENCES = [
    # --- CUSTOMER ROLE (UC1 - UC9) ---
    ("Sequence Diagram 1: UC1 - Customer Registration Flow",
     ["Customer", "Register UI", "RegisteredUserController", "CustomerProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Enter name, email, phone, address", False), (1, 2, "POST /register (payload)", False), (2, 4, "User::create([role='customer'])", False),
      (4, 5, "INSERT INTO users", False), (5, 4, "Return User ID", True), (2, 3, "CustomerProfile::create([user_id])", False),
      (3, 5, "INSERT INTO customer_profiles", False), (2, 1, "Redirect to Customer Dashboard", True), (1, 0, "Display Registration Success Alert", True)],
     (42.0, 26.0, 14.0, "Valid Data", "Duplicate Email Error"),
     "sequence_diagram_1.png"),

    ("Sequence Diagram 2: UC2 - Customer Login Authentication Flow",
     ["Customer", "Login UI", "AuthenticatedSessionController", "CustomerMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter email & password", False), (1, 2, "POST /login (credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE email=?", False), (5, 4, "Return User record & password hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify CustomerMiddleware permissions", True), (2, 1, "Redirect to Customer Dashboard UI", True)],
     (45.0, 27.0, 15.0, "Valid Credentials", "Invalid Credentials"),
     "sequence_diagram_2.png"),

    ("Sequence Diagram 3: UC3 - Customer Forgot Password Reset Flow via Brevo Email API",
     ["Customer", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter account email", False), (1, 2, "POST /forgot-password (email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Customer record verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (5, 3, "HTTP 201 Created (Brevo API Accepted)", True), (2, 1, "Display Brevo Reset Link Dispatched Alert", True)],
     (42.0, 25.0, 14.0, "Account Found", "Email Not Registered"),
     "sequence_diagram_3.png"),

    ("Sequence Diagram 4: UC4 - Customer Dashboard Navigation Flow",
     ["Customer", "Customer Dashboard UI", "CustomerDashboardController", "Order Model", "Machine Model", "MySQL DB"],
     [(0, 1, "Click 'Customer Dashboard' nav", False), (1, 2, "GET /customer/dashboard", False), (2, 4, "Order::where('customer_id', user_id)", False),
      (4, 5, "SELECT active orders & quick actions", False), (5, 4, "Return active bookings & quick links", True), (2, 1, "Render live status & quick actions UI", True)],
     None,
     "sequence_diagram_4.png"),

    ("Sequence Diagram 5: UC5 - Book New Order Navigation Flow",
     ["Customer", "Book New Order UI", "LaundryController", "Service Model", "Order Model", "MySQL orders Table"],
     [(0, 1, "Click 'Book New Order' nav", False), (1, 2, "POST /customer/orders/book", False), (2, 3, "Service::find(service_id)", False),
      (3, 2, "Return calculated rate per load/kg", True), (2, 4, "Order::create([customer_id, status='pending'])", False), (4, 5, "INSERT INTO orders", False),
      (2, 1, "Redirect to Order Booking Summary", True)],
     None,
     "sequence_diagram_5.png"),

    ("Sequence Diagram 6: UC6 - My Order History Navigation Flow & QR Rendering",
     ["Customer", "My Order History UI", "OrderHistoryController", "Order Model", "QrCode Model", "api.qrserver.com REST API"],
     [(0, 1, "Click 'My Order History' nav", False), (1, 2, "GET /customer/orders/history", False), (2, 3, "Order::with('qrCode')->get()", False),
      (3, 4, "SELECT qr_token FROM qr_codes", False), (4, 3, "Return qr_token string", True), (2, 5, "Fetch https://api.qrserver.com/v1/create-qr-code/?data=token", False),
      (5, 1, "Render digital QR code image on receipt", True)],
     None,
     "sequence_diagram_6.png"),

    ("Sequence Diagram 7: UC7 - Frequent User Card (12-Stamp Loyalty Rewards) Navigation Flow",
     ["Customer", "Frequent User Card UI", "LoyaltyCardController", "LoyaltyStampService", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Frequent User Card' nav", False), (1, 2, "GET /customer/loyalty-card", False), (2, 4, "User::find(user_id)->frequent_stamps", False),
      (4, 5, "SELECT stamp_count FROM users", False), (5, 4, "Return current stamp count (e.g. 8/12)", True), (2, 3, "LoyaltyStampService::checkRewardEligibility()", False),
      (2, 1, "Render 12-stamp card & reward claim button", True)],
     None,
     "sequence_diagram_7.png"),

    ("Sequence Diagram 8: UC8 - Home Dashboard Public Landing Page Navigation Flow",
     ["Customer", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public service rates & store hours", False), (5, 4, "Return public landing metadata", True), (2, 1, "Render public landing page view", True)],
     None,
     "sequence_diagram_8.png"),

    ("Sequence Diagram 9: UC9 - Account Settings (Profile & Security) Navigation Flow",
     ["Customer", "Account Settings UI", "ProfileController", "CustomerProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update info", False), (1, 2, "POST /user/profile/update", False), (2, 4, "User::update(['name', 'password'])", False),
      (4, 5, "UPDATE users SET name=?, password=?", False), (2, 3, "CustomerProfile::update(['address'])", False), (2, 1, "Display profile updated success alert", True)],
     None,
     "sequence_diagram_9.png"),

    # --- STAFF ROLE (UC10 - UC18) ---
    ("Sequence Diagram 10: UC10 - Staff Login Authentication Flow",
     ["Staff", "Staff Login UI", "AuthenticatedSessionController", "StaffMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter staff credentials", False), (1, 2, "POST /login (staff credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE role='staff'", False), (5, 4, "Return Staff record & password hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify StaffMiddleware permissions", True), (2, 1, "Redirect to Workstation Dashboard", True)],
     (45.0, 27.0, 15.0, "Valid Staff Credentials", "Invalid Credentials"),
     "sequence_diagram_10.png"),

    ("Sequence Diagram 11: UC11 - Staff Forgot Password Reset Flow via Brevo Email API",
     ["Staff", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter staff work email", False), (1, 2, "POST /forgot-password (staff email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Staff record verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (2, 1, "Display Brevo reset email dispatched alert", True)],
     (42.0, 25.0, 14.0, "Staff Email Verified", "Email Not Found"),
     "sequence_diagram_11.png"),

    ("Sequence Diagram 12: UC12 - Workstation Dashboard Navigation Flow",
     ["Staff", "Workstation UI", "WorkstationController", "Order Model", "Machine Model", "MySQL DB"],
     [(0, 1, "Click 'Workstation Dashboard' nav", False), (1, 2, "GET /staff/workstation", False), (2, 4, "Order::whereIn('status', ['pending', 'washing'])", False),
      (4, 5, "SELECT active queue & machine timers", False), (5, 4, "Return active cashier & machine metrics", True), (2, 1, "Render queue & cashier processing UI", True)],
     None,
     "sequence_diagram_12.png"),

    ("Sequence Diagram 13: UC13 - Manage Laundry Orders Navigation Flow",
     ["Staff", "Manage Orders UI", "Database"],
     [(0, 1, "click orders", False), (1, 2, "request pending orders from database", False),
      (2, 1, "orders retrieved from database", True), (1, 0, "orders displayed", True),
      (0, 1, "add walk-in order", False), (1, 2, "request add order", False),
      (2, 1, "add order is granted", True), (1, 0, "new order added", True),
      (0, 1, "weigh scale & edit status", False), (1, 2, "request edit status", False),
      (2, 1, "edit status is granted", True), (1, 0, "order status edited", True),
      (0, 1, "delete invalid order", False), (1, 2, "request delete order", False),
      (2, 1, "delete order is granted", True), (1, 0, "order deleted", True)],
     None,
     "sequence_diagram_13.png"),

    ("Sequence Diagram 14: UC14 - Manage Machines Navigation Flow (+60m Extension & TextBee Alert)",
     ["Staff", "Manage Machines UI", "Database"],
     [(0, 1, "click machines", False), (1, 2, "request machine status from database", False),
      (2, 1, "machines retrieved from database", True), (1, 0, "machines displayed", True),
      (0, 1, "add timer extension (+60m)", False), (1, 2, "request add extension", False),
      (2, 1, "timer extension granted", True), (1, 0, "timer extension added", True),
      (0, 1, "edit machine status (washing/drying)", False), (1, 2, "request edit status", False),
      (2, 1, "edit machine status granted", True), (1, 0, "machine status edited", True),
      (0, 1, "delete machine assignment", False), (1, 2, "request delete assignment", False),
      (2, 1, "delete assignment granted", True), (1, 0, "machine assignment deleted", True)],
     None,
     "sequence_diagram_14.png"),

    ("Sequence Diagram 15: UC15 - New Walk-in Order Navigation Flow",
     ["Staff", "New Walk-in Order UI", "WalkinOrderController", "Order Model", "User Model", "MySQL orders Table"],
     [(0, 1, "Click 'New Walk-in Order' nav", False), (1, 2, "POST /staff/orders/walk-in", False), (2, 4, "User::firstOrCreate([customer_phone])", False),
      (4, 5, "INSERT INTO users & orders", False), (5, 4, "Return generated Order ID", True), (2, 1, "Redirect to Receipt & Counter Payment UI", True)],
     None,
     "sequence_diagram_15.png"),

    ("Sequence Diagram 16: UC16 - QR Scan Logs Outbox Navigation Flow",
     ["Staff", "QR Scan Logs UI", "QrScanLogController", "QrScanLog Model", "Order Model", "MySQL qr_scan_logs"],
     [(0, 1, "Click 'QR Scan Logs Outbox' nav", False), (1, 2, "GET /staff/qr-logs", False), (2, 4, "QrScanLog::latest()->get()", False),
      (4, 5, "SELECT * FROM qr_scan_logs", False), (5, 4, "Return audit logs of all QR scans", True), (2, 1, "Render QR scan log outbox table", True)],
     None,
     "sequence_diagram_16.png"),

    ("Sequence Diagram 17: UC17 - Staff Home Dashboard Navigation Flow",
     ["Staff", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public landing page details", False), (5, 4, "Return store landing metadata", True), (2, 1, "Render public landing page view", True)],
     None,
     "sequence_diagram_17.png"),

    ("Sequence Diagram 18: UC18 - Staff Account Settings Navigation Flow",
     ["Staff", "Account Settings UI", "ProfileController", "StaffProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update password", False), (1, 2, "POST /staff/profile/update", False), (2, 4, "User::update(['password'])", False),
      (4, 5, "UPDATE users SET password=? WHERE id=?", False), (2, 1, "Display profile updated alert", True)],
     None,
     "sequence_diagram_18.png"),

    # --- RIDER ROLE (UC19 - UC25) ---
    ("Sequence Diagram 19: UC19 - Rider Login Authentication Flow",
     ["Rider", "Rider Login UI", "AuthenticatedSessionController", "RiderMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter rider credentials", False), (1, 2, "POST /login (rider credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE role='rider'", False), (5, 4, "Return Rider record & password hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify RiderMiddleware permissions", True), (2, 1, "Redirect to Rider Dashboard UI", True)],
     (45.0, 27.0, 15.0, "Valid Rider Credentials", "Invalid Credentials"),
     "sequence_diagram_19.png"),

    ("Sequence Diagram 20: UC20 - Rider Forgot Password Reset Flow via Brevo Email API",
     ["Rider", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter rider email address", False), (1, 2, "POST /forgot-password (rider email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Rider account verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (2, 1, "Display Brevo reset token dispatched alert", True)],
     (42.0, 25.0, 14.0, "Rider Account Verified", "Email Not Found"),
     "sequence_diagram_20.png"),

    ("Sequence Diagram 21: UC21 - Rider of Hour Wash Navigation Flow",
     ["Rider", "Rider Dashboard UI", "RiderDashboardController", "PickupDelivery Model", "Order Model", "MySQL pickup_delivery"],
     [(0, 1, "Click 'Rider of Hour Wash' nav", False), (1, 2, "GET /rider/dashboard", False), (2, 4, "PickupDelivery::where('rider_name', rider)", False),
      (4, 5, "SELECT * FROM pickup_delivery WHERE status='scheduled'", False), (5, 4, "Return pickup & delivery task dispatches", True),
      (2, 1, "Render dispatch task cards UI", True)],
     None,
     "sequence_diagram_21.png"),

    ("Sequence Diagram 22: UC22 - Update Pickup Logistics Status Flow & TextBee SMS Alert",
     ["Rider", "Rider Dashboard UI", "PickupDeliveryController", "PickupDelivery Model", "SmsNotificationService", "TextBee SMS Gateway"],
     [(0, 1, "Click 'Arrived & Picked Up'", False), (1, 2, "POST /rider/status/pickup (pickup_id)", False), (2, 3, "PickupDelivery::update(['status'=>'picked_up'])", False),
      (3, 4, "SmsNotificationService::sendTextBeeSms()", False), (4, 5, "POST https://api.textbee.dev/api/v1/gateway/send-sms", False),
      (5, 4, "TextBee SMS alert delivered to customer", True), (2, 1, "Task updated to Picked Up UI", True)],
     None,
     "sequence_diagram_22.png"),

    ("Sequence Diagram 23: UC23 - Update Delivery Status & Proof Photo Upload Flow",
     ["Rider", "Rider Dashboard UI", "PickupDeliveryController", "PickupDelivery Model", "OrderStatusHistory", "MySQL pickup_delivery"],
     [(0, 1, "Deliver laundry & upload proof photo", False), (1, 2, "POST /rider/status/delivery (proof_image)", False), (2, 3, "saveProofImage(file)", False),
      (3, 4, "PickupDelivery::update(['status'=>'delivered', 'proof_images'])", False), (4, 5, "UPDATE pickup_delivery & orders SET status='completed'", False),
      (2, 1, "Task completed confirmation alert", True)],
     None,
     "sequence_diagram_23.png"),

    ("Sequence Diagram 24: UC24 - Rider Home Dashboard Navigation Flow",
     ["Rider", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public landing page metadata", False), (5, 4, "Return landing page info", True), (2, 1, "Render public landing page view", True)],
     None,
     "sequence_diagram_24.png"),

    ("Sequence Diagram 25: UC25 - Rider Account Settings Navigation Flow",
     ["Rider", "Account Settings UI", "ProfileController", "RiderProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update password", False), (1, 2, "POST /rider/profile/update", False), (2, 4, "User::update(['password'])", False),
      (4, 5, "UPDATE users SET password=? WHERE id=?", False), (2, 1, "Display profile updated alert", True)],
     None,
     "sequence_diagram_25.png"),

    # --- ADMIN ROLE (UC26 - UC38) ---
    ("Sequence Diagram 26: UC26 - Administrator Login Authentication Flow",
     ["Admin", "Admin Login UI", "AuthenticatedSessionController", "AdminMiddleware", "User Model", "MySQL users Table"],
     [(0, 1, "Enter admin credentials", False), (1, 2, "POST /login (admin credentials)", False), (2, 4, "User::where('email', email)", False),
      (4, 5, "SELECT * FROM users WHERE role='admin'", False), (5, 4, "Return Admin record & hash", True), (4, 2, "Hash::check(password, hash)", True),
      (2, 3, "Verify AdminMiddleware permissions", True), (2, 1, "Redirect to Overall Reports & Dashboard", True)],
     (45.0, 27.0, 15.0, "Valid Admin Credentials", "Invalid Credentials"),
     "sequence_diagram_26.png"),

    ("Sequence Diagram 27: UC27 - Administrator Password Reset Flow via Brevo Email API",
     ["Admin", "Forgot Password UI", "PasswordResetLinkController", "EmailNotificationService", "User Model", "Brevo API Gateway"],
     [(0, 1, "Enter admin secure email", False), (1, 2, "POST /forgot-password (admin email)", False), (2, 4, "User::where('email', email)", False),
      (4, 2, "Admin account verified", True), (2, 3, "EmailNotificationService::sendBrevoPasswordReset()", False), (3, 5, "POST https://api.brevo.com/v3/smtp/email", False),
      (2, 1, "Display Brevo reset email sent notification", True)],
     (42.0, 25.0, 14.0, "Admin Account Verified", "Email Not Registered"),
     "sequence_diagram_27.png"),

    ("Sequence Diagram 28: UC28 - Overall Reports & Dashboard Navigation Flow",
     ["Admin", "Overall Reports UI", "AnalyticsController", "Order Model", "Machine Model", "MySQL DB"],
     [(0, 1, "Click 'Overall Reports & Dashboard' nav", False), (1, 2, "GET /admin/dashboard", False), (2, 4, "Order::selectRaw('SUM(total_amount), COUNT(id)')->get()", False),
      (4, 5, "SELECT sales, profit, & machine status", False), (5, 4, "Return system overview & financial metrics", True), (2, 1, "Render overall reports & metrics dashboard", True)],
     None,
     "sequence_diagram_28.png"),

    ("Sequence Diagram 29: UC29 - Manage Laundry Orders Navigation Flow",
     ["Admin", "Manage Orders UI", "Database"],
     [(0, 1, "click laundry orders", False), (1, 2, "request order table from database", False),
      (2, 1, "order list retrieved from database", True), (1, 0, "orders displayed", True),
      (0, 1, "add order account", False), (1, 2, "request add account", False),
      (2, 1, "add order is granted", True), (1, 0, "new order added", True),
      (0, 1, "edit order status", False), (1, 2, "request edit status", False),
      (2, 1, "edit order is granted", True), (1, 0, "order status edited", True),
      (0, 1, "delete order", False), (1, 2, "request delete order", False),
      (2, 1, "delete order is granted", True), (1, 0, "order deleted", True)],
     None,
     "sequence_diagram_29.png"),

    ("Sequence Diagram 30: UC30 - Manage Machines Navigation Flow (Add, Edit, Remove)",
     ["Admin", "Manage Machines UI", "Database"],
     [(0, 1, "click machines", False), (1, 2, "request machine list from database", False),
      (2, 1, "machine list retrieved from database", True), (1, 0, "machines displayed", True),
      (0, 1, "add machine account", False), (1, 2, "request add account", False),
      (2, 1, "add machine is granted", True), (1, 0, "new machine added", True),
      (0, 1, "edit machine status", False), (1, 2, "request edit account", False),
      (2, 1, "edit machine is granted", True), (1, 0, "machine edited", True),
      (0, 1, "delete machine", False), (1, 2, "request delete account", False),
      (2, 1, "delete machine is granted", True), (1, 0, "machine deleted", True)],
     None,
     "sequence_diagram_30.png"),

    ("Sequence Diagram 31: UC31 - Services & Pricing Navigation Flow",
     ["Admin", "Services & Pricing UI", "Database"],
     [(0, 1, "click services", False), (1, 2, "request service table from database", False),
      (2, 1, "services retrieved from database", True), (1, 0, "services displayed", True),
      (0, 1, "add service", False), (1, 2, "request add service", False),
      (2, 1, "add service is granted", True), (1, 0, "new service added", True),
      (0, 1, "edit service tariff rate", False), (1, 2, "request edit tariff", False),
      (2, 1, "edit tariff is granted", True), (1, 0, "service tariff edited", True),
      (0, 1, "delete service", False), (1, 2, "request delete service", False),
      (2, 1, "delete service is granted", True), (1, 0, "service deleted", True)],
     None,
     "sequence_diagram_31.png"),

    ("Sequence Diagram 32: UC32 - Manage Users Navigation Flow (Stamps, Add, Edit, Remove)",
     ["Admin", "Manage Users UI", "Database"],
     [(0, 1, "click staff / users", False), (1, 2, "request user accounts from database", False),
      (2, 1, "user accounts retrieved from database", True), (1, 0, "user accounts displayed", True),
      (0, 1, "add user account", False), (1, 2, "request add account", False),
      (2, 1, "add user is granted", True), (1, 0, "new user added", True),
      (0, 1, "edit user account / stamps", False), (1, 2, "request edit account", False),
      (2, 1, "edit user is granted", True), (1, 0, "user edited", True),
      (0, 1, "delete account", False), (1, 2, "request delete account", False),
      (2, 1, "delete user is granted", True), (1, 0, "user deleted", True)],
     None,
     "sequence_diagram_32.png"),

    ("Sequence Diagram 33: UC33 - Live SMS Outbox Navigation Flow (TextBee Logs)",
     ["Admin", "Live SMS Outbox UI", "SmsLogController", "SmsNotification Model", "TextBee Gateway", "MySQL sms_notifications"],
     [(0, 1, "Click 'Live SMS Outbox' nav", False), (1, 2, "GET /admin/sms-outbox", False), (2, 4, "SmsNotification::latest()->get()", False),
      (4, 5, "SELECT * FROM sms_notifications", False), (5, 4, "Return TextBee SMS delivery logs", True), (2, 1, "Render Live TextBee SMS outbox log table", True)],
     None,
     "sequence_diagram_33.png"),

    ("Sequence Diagram 34: UC34 - Live Email Outbox Navigation Flow (Brevo Logs)",
     ["Admin", "Live Email Outbox UI", "EmailLogController", "EmailNotification Model", "Brevo Gateway", "MySQL email_notifications"],
     [(0, 1, "Click 'Live Email Outbox' nav", False), (1, 2, "GET /admin/email-outbox", False), (2, 4, "EmailNotification::latest()->get()", False),
      (4, 5, "SELECT * FROM email_notifications", False), (5, 4, "Return Brevo email notification logs", True), (2, 1, "Render Live Brevo Email outbox table", True)],
     None,
     "sequence_diagram_34.png"),

    ("Sequence Diagram 35: UC35 - Customer Reviews Outbox Navigation Flow",
     ["Admin", "Customer Reviews UI", "Database"],
     [(0, 1, "click reviews", False), (1, 2, "request reviews table from database", False),
      (2, 1, "reviews retrieved from database", True), (1, 0, "reviews displayed", True),
      (0, 1, "add admin response", False), (1, 2, "request add response", False),
      (2, 1, "add response is granted", True), (1, 0, "response added", True),
      (0, 1, "edit review approval", False), (1, 2, "request edit approval", False),
      (2, 1, "edit approval is granted", True), (1, 0, "review edited", True),
      (0, 1, "delete review", False), (1, 2, "request delete review", False),
      (2, 1, "delete review is granted", True), (1, 0, "review deleted", True)],
     None,
     "sequence_diagram_35.png"),

    ("Sequence Diagram 36: UC36 - QR Scan Logs Outbox Navigation Flow",
     ["Admin", "QR Scan Logs UI", "QrScanLogController", "QrScanLog Model", "Order Model", "MySQL qr_scan_logs"],
     [(0, 1, "Click 'QR Scan Logs Outbox' nav", False), (1, 2, "GET /admin/qr-outbox", False), (2, 4, "QrScanLog::with('order', 'scannedBy')->get()", False),
      (4, 5, "SELECT * FROM qr_scan_logs", False), (5, 4, "Return audit log of all QR scans", True), (2, 1, "Render admin QR scan log outbox UI", True)],
     None,
     "sequence_diagram_36.png"),

    ("Sequence Diagram 37: UC37 - Admin Home Dashboard Navigation Flow",
     ["Admin", "Home Dashboard UI", "HomeController", "Service Model", "StoreInfo", "MySQL DB"],
     [(0, 1, "Click 'Home Dashboard' nav", False), (1, 2, "GET /home", False), (2, 4, "Service::where('status', 'active')->get()", False),
      (4, 5, "SELECT public landing page details", False), (5, 4, "Return landing page info", True), (2, 1, "Render public landing page view", True)],
     None,
     "sequence_diagram_37.png"),

    ("Sequence Diagram 38: UC38 - Admin Account Settings Navigation Flow",
     ["Admin", "Account Settings UI", "ProfileController", "AdminProfile", "User Model", "MySQL users Table"],
     [(0, 1, "Click 'Account Settings' nav & update info", False), (1, 2, "POST /admin/profile/update", False), (2, 4, "User::update(['password'])", False),
      (4, 5, "UPDATE users SET password=? WHERE id=?", False), (2, 1, "Display admin profile updated alert", True)],
     None,
     "sequence_diagram_38.png"),
]

def generate_all_sequence_diagrams():
    for title, lifelines, steps, alt_fragment, filename in ALL_ACCURATE_SEQUENCES:
        fig, ax = plt.subplots(figsize=(12, 7.8), dpi=300)
        fig.patch.set_facecolor(PRIMARY_BG)
        ax.set_facecolor(PRIMARY_BG)
        draw_sequence_template(ax, title, lifelines, steps, alt_fragment)
        plt.tight_layout()
        plt.savefig(f'diagrams/{filename}', dpi=300, bbox_inches='tight', facecolor='white')
        plt.close()
        print(f"Saved diagrams/{filename}")

# -------------------------------------------------------------
# 8. PACKAGE DIAGRAM OF THE SYSTEM (STRICTLY MATCHING REFERENCE IMAGE 1 LAYOUT)
# -------------------------------------------------------------
# 8. PACKAGE DIAGRAM OF THE SYSTEM (STRICTLY MATCHING REFERENCE IMAGE LAYOUT & FEATURES)
# -------------------------------------------------------------
def draw_role_package_container_reference(ax, x, y, role_name, left_folders, right_folders, bottom_folder_name='Profile', w=45.5, h=44.0):
    # Outer Role Container Box
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(rect)
    
    scale_w = w / 45.5
    scale_h = h / 44.0

    # Outer Role Top Tab
    tab_w = max(12.0 * scale_w, len(role_name) * 1.05 * scale_w)
    tab_h = min(2.4, h * 0.06)
    tab = patches.Rectangle((x, y + h), tab_w, tab_h, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(tab)
    tab_fs = max(5.2, 8.5 * min(scale_w, scale_h))
    ax.text(x + 1.2 * scale_w, y + h + tab_h/2.0, role_name, fontsize=tab_fs, fontweight='bold', color='#000000', va='center')

    num_f = len(left_folders)
    folder_w = 17.0 * scale_w
    folder_h = 4.6 * scale_h
    ptab_h = 1.2 * scale_h
    ptab_w = 7.0 * scale_w

    left_x = x + (3.0 * scale_w)
    right_x = x + (25.5 * scale_w)

    if num_f == 4:
        y_starts = [y + (35.0 * scale_h) - i * (9.5 * scale_h) for i in range(4)]
    else:
        y_starts = [y + (35.0 * scale_h) - i * (10.5 * scale_h) for i in range(3)]

    left_boxes = []
    right_boxes = []

    lbl_fs = max(4.8, 7.2 * min(scale_w, scale_h))

    # Draw Left Column Folders
    for i, fname in enumerate(left_folders):
        fy = y_starts[i]
        ptab = patches.Rectangle((left_x, fy + folder_h), ptab_w, ptab_h, fc='#FFFFFF', ec='#000000', lw=1.0)
        ax.add_patch(ptab)
        pbox = patches.Rectangle((left_x, fy), folder_w, folder_h, fc='#FFFFFF', ec='#000000', lw=1.0)
        ax.add_patch(pbox)
        ax.text(left_x + folder_w/2.0, fy + folder_h/2.0, fname, fontsize=lbl_fs, fontweight='bold', ha='center', va='center', color='#000000')
        left_boxes.append((fy, fy + folder_h + ptab_h))

    # Draw Right Column Folders
    for j, gname in enumerate(right_folders):
        fy = y_starts[j]
        ptab = patches.Rectangle((right_x, fy + folder_h), ptab_w, ptab_h, fc='#FFFFFF', ec='#000000', lw=1.0)
        ax.add_patch(ptab)
        pbox = patches.Rectangle((right_x, fy), folder_w, folder_h, fc='#FFFFFF', ec='#000000', lw=1.0)
        ax.add_patch(pbox)
        ax.text(right_x + folder_w/2.0, fy + folder_h/2.0, gname, fontsize=lbl_fs, fontweight='bold', ha='center', va='center', color='#000000')
        right_boxes.append((fy, fy + folder_h + ptab_h))

    # 1. Horizontal dashed ACCESS arrow from Top Left (Login) to Top Right (Dashboard)
    ax.annotate("", xy=(right_x, y_starts[0] + folder_h/2.0), xytext=(left_x + folder_w, y_starts[0] + folder_h/2.0),
                arrowprops=dict(arrowstyle="->", lw=1.1, color='#000000', linestyle='--'))
    ax.text((left_x + folder_w + right_x)/2.0, y_starts[0] + folder_h/2.0 + (1.0 * scale_h), "ACCESS", fontsize=max(4.2, 6.2 * min(scale_w, scale_h)), fontweight='bold', ha='center', color='#000000')

    # 2. Vertical dashed ACCESS arrow: Row 0 -> Row 1 (Left Column)
    top_y0 = left_boxes[0][0]
    bot_y1 = left_boxes[1][1]
    ax.annotate("", xy=(left_x + folder_w/2.0, bot_y1), xytext=(left_x + folder_w/2.0, top_y0),
                arrowprops=dict(arrowstyle="->", lw=1.1, color='#000000', linestyle='--'))
    ax.text(left_x + folder_w/2.0 - (2.5 * scale_w), (top_y0 + bot_y1)/2.0, "ACCESS", fontsize=max(4.0, 5.8 * min(scale_w, scale_h)), fontweight='bold', ha='right', va='center', color='#000000')

    # 3. Remaining Vertical Dashed Arrows Down Left Column
    for i in range(1, num_f - 1):
        top_y = left_boxes[i][0]
        bot_y = left_boxes[i+1][1]
        ax.annotate("", xy=(left_x + folder_w/2.0, bot_y), xytext=(left_x + folder_w/2.0, top_y),
                    arrowprops=dict(arrowstyle="->", lw=1.1, color='#000000', linestyle='--'))

    # 4. Vertical Dashed Arrows Down Right Column
    for j in range(num_f - 1):
        top_y = right_boxes[j][0]
        bot_y = right_boxes[j+1][1]
        ax.annotate("", xy=(right_x + folder_w/2.0, bot_y), xytext=(right_x + folder_w/2.0, top_y),
                    arrowprops=dict(arrowstyle="->", lw=1.1, color='#000000', linestyle='--'))

    # 5. Draw Bottom Folder (Trashbin or Profile)
    b_w = 14.5 * scale_w
    b_h = 4.0 * scale_h
    b_x = x + (15.5 * scale_w)
    b_y = y + (1.0 * scale_h)
    t_tab = patches.Rectangle((b_x, b_y + b_h), 6.0 * scale_w, 0.9 * scale_h, fc='#FFFFFF', ec='#000000', lw=1.0)
    ax.add_patch(t_tab)
    t_box = patches.Rectangle((b_x, b_y), b_w, b_h, fc='#FFFFFF', ec='#000000', lw=1.0)
    ax.add_patch(t_box)
    ax.text(b_x + b_w/2.0, b_y + b_h/2.0, bottom_folder_name, fontsize=lbl_fs, fontweight='bold', ha='center', va='center', color='#000000')

    # Arrows from left bottom and right bottom folders down to Profile
    left_bot_x = left_x + folder_w/2.0
    left_bot_y = left_boxes[num_f - 1][0]
    right_bot_x = right_x + folder_w/2.0
    right_bot_y = right_boxes[num_f - 1][0]
    prof_top_y = b_y + b_h + (0.9 * scale_h)

    # Arrow from left column down to Profile
    ax.plot([left_bot_x, left_bot_x, b_x + (3.0 * scale_w), b_x + (3.0 * scale_w)],
            [left_bot_y, prof_top_y + (0.6 * scale_h), prof_top_y + (0.6 * scale_h), prof_top_y],
            color='#000000', linestyle='--', lw=1.1)
    ax.annotate("", xy=(b_x + (3.0 * scale_w), prof_top_y), xytext=(b_x + (3.0 * scale_w), prof_top_y + 0.2),
                arrowprops=dict(arrowstyle="->", lw=1.1, color='#000000', linestyle='--'))

    # Arrow from right column down to Profile
    ax.plot([right_bot_x, right_bot_x, b_x + b_w - (3.0 * scale_w), b_x + b_w - (3.0 * scale_w)],
            [right_bot_y, prof_top_y + (0.6 * scale_h), prof_top_y + (0.6 * scale_h), prof_top_y],
            color='#000000', linestyle='--', lw=1.1)
    ax.annotate("", xy=(b_x + b_w - (3.0 * scale_w), prof_top_y), xytext=(b_x + b_w - (3.0 * scale_w), prof_top_y + 0.2),
                arrowprops=dict(arrowstyle="->", lw=1.1, color='#000000', linestyle='--'))


def generate_package_diagram():
    fig, ax = plt.subplots(figsize=(16, 12.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # Outer Main System Frame Box (Matching Reference Image 1)
    rect_sys = patches.Rectangle((1.5, 1.5), 97, 93.5, fc='#FFFFFF', ec='#000000', lw=1.6)
    ax.add_patch(rect_sys)

    # Outer Main System Title Tab at Top Left (Matching Reference Image 1)
    sys_tab = patches.Rectangle((1.5, 95.0), 84.0, 2.4, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(sys_tab)
    ax.text(3.0, 96.2, "A Web-Based Laundry Service Management System for HourWash Laundry Shop in Orosite Legazpi City", fontsize=6.8, fontweight='bold', color='#000000', va='center')

    # 1. Admin Role Package (Top Left - y=48.0 to y=92.0, tab y=92.0 to 94.4)
    admin_left = ["Login", "Manage Orders", "Services & Pricing"]
    admin_right = ["Dashboard", "User Accounts", "Manage Machines"]
    draw_role_package_container_reference(ax, 3.0, 48.0, "Admin", admin_left, admin_right, bottom_folder_name="Profile", w=45.5, h=44.0)

    # 2. Customer Role Package (Top Right - y=48.0 to y=92.0, tab y=92.0 to 94.4)
    customer_left = ["Login", "Book New Order", "My Order History"]
    customer_right = ["Dashboard", "12-Stamp Card", "Active Services"]
    draw_role_package_container_reference(ax, 51.5, 48.0, "Customer", customer_left, customer_right, bottom_folder_name="Profile", w=45.5, h=44.0)

    # 3. Staff Operator Role Package (Bottom Left - y=2.5 to y=46.5)
    staff_left = ["Login", "Workstation Queue", "Manage Laundry"]
    staff_right = ["Dashboard", "New Walk-in Order", "Manage Machines"]
    draw_role_package_container_reference(ax, 3.0, 2.5, "Staff", staff_left, staff_right, bottom_folder_name="Profile", w=45.5, h=44.0)

    # 4. Rider of HourWash Package (Bottom Right - y=2.5 to y=46.5)
    rider_left = ["Login", "Rider Dashboard", "Pickup Logistics"]
    rider_right = ["Dashboard", "Delivery Tasks", "Proof Photo Upload"]
    draw_role_package_container_reference(ax, 51.5, 2.5, "Rider of HourWash", rider_left, rider_right, bottom_folder_name="Profile", w=45.5, h=44.0)

    plt.tight_layout()
    plt.savefig('diagrams/package_diagram.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/package_diagram.png")

# -------------------------------------------------------------
# 9. SYSTEM DEPLOYMENT DIAGRAM (STRICTLY MATCHING USER REFERENCE IMAGE: 3D WEB SERVER WITH EMBEDDED PACKAGE & DATABASE + 4 CLIENT NODES)
# -------------------------------------------------------------
def generate_deployment_diagram():
    fig, ax = plt.subplots(figsize=(16, 16.0), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # 1. TOP 3D NODE CUBE: WEB SERVER (y=38.0 to 97.0)
    ws_x, ws_y, ws_w, ws_h, ws_d = 5.0, 38.0, 90.0, 59.0, 3.0
    # Front Face
    front = patches.Rectangle((ws_x, ws_y), ws_w, ws_h, fc='#FFFFFF', ec='#000000', lw=1.6)
    ax.add_patch(front)
    # Top Face
    top = patches.Polygon([[ws_x, ws_y + ws_h], [ws_x + ws_d, ws_y + ws_h + ws_d], [ws_x + ws_w + ws_d, ws_y + ws_h + ws_d], [ws_x + ws_w, ws_y + ws_h]], fc='#F8F9FA', ec='#000000', lw=1.6)
    ax.add_patch(top)
    # Side Face
    side = patches.Polygon([[ws_x + ws_w, ws_y], [ws_x + ws_w + ws_d, ws_y + ws_d], [ws_x + ws_w + ws_d, ws_y + ws_h + ws_d], [ws_x + ws_w, ws_y + ws_h]], fc='#F1F5F9', ec='#000000', lw=1.6)
    ax.add_patch(side)

    # Label on Top Left inside WEB SERVER face
    ax.text(ws_x + 3.0, ws_y + ws_h - 2.5, "WEB SERVER", fontsize=13, fontweight='bold', color='#000000')


    # INSIDE WEB SERVER:
    # A) Upper Block: Package Diagram Container (y=48.0 to 91.0)
    pkg_x, pkg_y, pkg_w, pkg_h = ws_x + 3.0, ws_y + 10.0, ws_w - 6.0, 43.0
    rect_sys = patches.Rectangle((pkg_x, pkg_y), pkg_w, pkg_h, fc='#FFFFFF', ec='#000000', lw=1.2)
    ax.add_patch(rect_sys)

    # Render 4 Role Packages inside Package Diagram Container (Admin & Customer tabs end at y=84.7, 6.3 units BELOW pkg_y + pkg_h = 91.0)
    # Admin (Top Left)
    admin_left = ["Login", "Manage Orders", "Services & Pricing"]
    admin_right = ["Dashboard", "User Accounts", "Manage Machines"]
    draw_role_package_container_reference(ax, pkg_x + 1.5, pkg_y + 19.5, "Admin", admin_left, admin_right, bottom_folder_name="Profile", w=39.5, h=16.0)

    # Customer (Top Right)
    customer_left = ["Login", "Book New Order", "My Order History"]
    customer_right = ["Dashboard", "12-Stamp Card", "Active Services"]
    draw_role_package_container_reference(ax, pkg_x + 43.0, pkg_y + 19.5, "Customer", customer_left, customer_right, bottom_folder_name="Profile", w=39.5, h=16.0)

    # Staff (Bottom Left)
    staff_left = ["Login", "Workstation Queue", "Manage Laundry"]
    staff_right = ["Dashboard", "New Walk-in Order", "Manage Machines"]
    draw_role_package_container_reference(ax, pkg_x + 1.5, pkg_y + 1.0, "Staff", staff_left, staff_right, bottom_folder_name="Profile", w=39.5, h=16.0)

    # Rider of HourWash (Bottom Right)
    rider_left = ["Login", "Rider Dashboard", "Pickup Logistics"]
    rider_right = ["Dashboard", "Delivery Tasks", "Proof Photo Upload"]
    draw_role_package_container_reference(ax, pkg_x + 43.0, pkg_y + 1.0, "Rider of HourWash", rider_left, rider_right, bottom_folder_name="Profile", w=39.5, h=16.0)


    # B) Lower Block: DATABASE Container (y=40.0 to 48.5)
    db_x, db_y, db_w, db_h = ws_x + 3.0, ws_y + 2.0, ws_w - 6.0, 8.5
    rect_db = patches.Rectangle((db_x, db_y), db_w, db_h, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(rect_db)

    # "DATABASE" Label inside DATABASE Container
    ax.text(db_x + 3.0, db_y + db_h/2.0, "DATABASE", fontsize=18, fontweight='bold', color='#000000', va='center', ha='left')

    # Folder Icon on top right inside DATABASE box
    icon_w, icon_h = 6.5, 5.2
    icon_x = db_x + db_w - icon_w - 3.0
    icon_y = db_y + (db_h - icon_h)/2.0 - 0.3
    f_tab = patches.Rectangle((icon_x, icon_y + icon_h), 3.2, 1.2, fc='#FFFFFF', ec='#000000', lw=1.0)
    ax.add_patch(f_tab)
    f_body = patches.Rectangle((icon_x, icon_y), icon_w, icon_h, fc='#FFFFFF', ec='#000000', lw=1.0)
    ax.add_patch(f_body)


    # C) Connecting Elbow Arrow inside Web Server (DATABASE -> Package Diagram)
    st_x = db_x + db_w
    st_y = db_y + db_h/2.0
    elb_x = pkg_x + pkg_w + 1.2
    tg_x = pkg_x + pkg_w
    tg_y = pkg_y + pkg_h/2.0

    ax.plot([st_x, elb_x, elb_x, tg_x],
            [st_y, st_y, tg_y, tg_y],
            color='#000000', lw=1.4)
    ax.annotate("", xy=(tg_x, tg_y), xytext=(tg_x + 0.6, tg_y),
                arrowprops=dict(arrowstyle="->", lw=1.4, color='#000000'))


    # 2. BOTTOM 3D NODE CUBES: CLIENT NODES (ADMIN, CUSTOMER, STAFF, RIDER OF HOURWASH)
    client_nodes = [
        ("ADMIN", 2.0, 4.0, 20.5),
        ("CUSTOMER", 25.5, 4.0, 20.5),
        ("STAFF", 49.0, 4.0, 20.5),
        ("RIDER OF HOURWASH", 72.5, 4.0, 24.0)
    ]

    client_top_anchors = []

    for node_name, cx, cy, cw in client_nodes:
        ch, cd = 22.0, 2.5
        # Front Face
        c_front = patches.Rectangle((cx, cy), cw, ch, fc='#FFFFFF', ec='#000000', lw=1.4)
        ax.add_patch(c_front)
        # Top Face
        c_top = patches.Polygon([[cx, cy + ch], [cx + cd, cy + ch + cd], [cx + cw + cd, cy + ch + cd], [cx + cw, cy + ch]], fc='#F8F9FA', ec='#000000', lw=1.4)
        ax.add_patch(c_top)
        # Side Face
        c_side = patches.Polygon([[cx + cw, cy], [cx + cw + cd, cy + cd], [cx + cw + cd, cy + ch + cd], [cx + cw, cy + ch]], fc='#F1F5F9', ec='#000000', lw=1.4)
        ax.add_patch(c_side)

        lbl_fs = 7.2 if "HOURWASH" in node_name else 8.5
        ax.text(cx + 1.2, cy + ch - 3.2, node_name, fontsize=lbl_fs, fontweight='bold', color='#000000')

        # Inside client node cube: Inner box centered labeled "BROWSER"
        b_box = patches.Rectangle((cx + 3.0, cy + 4.5), cw - 6.0, 10.0, fc='#FFFFFF', ec='#000000', lw=1.0)
        ax.add_patch(b_box)
        ax.text(cx + cw/2.0, cy + 9.5, "BROWSER", fontsize=8.0, fontweight='bold', ha='center', va='center', color='#000000')

        # Store top anchor point for connecting arrows
        client_top_anchors.append((cx + cw/2.0, cy + ch + cd))


    # 3. CONNECTING ARROWS FROM WEB SERVER DOWN TO CLIENT NODES WITH "http" LABELS
    ws_bottom_y = ws_y # y = 38.0
    ws_bottom_anchors = [
        ws_x + 8.0,
        ws_x + 28.0,
        ws_x + 52.0,
        ws_x + 76.0
    ]

    for i in range(4):
        src_x = ws_bottom_anchors[i]
        src_y = ws_bottom_y
        tgt_x, tgt_y = client_top_anchors[i]

        ax.annotate("", xy=(tgt_x, tgt_y), xytext=(src_x, src_y),
                    arrowprops=dict(arrowstyle="->", lw=1.3, color='#000000'))
        
        mid_x = (src_x + tgt_x) / 2.0
        mid_y = (src_y + tgt_y) / 2.0
        ax.text(mid_x, mid_y, "http", fontsize=7.5, color='#000000', ha='center', va='center',
                bbox=dict(boxstyle="square,pad=0.1", fc="#FFFFFF", ec="none"))

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
    print("ALL HIGH-RESOLUTION BLACK & WHITE DIAGRAMS GENERATED SUCCESSFULLY!")
