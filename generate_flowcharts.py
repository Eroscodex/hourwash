import os
import matplotlib.pyplot as plt
import matplotlib.patches as patches

os.makedirs('diagrams', exist_ok=True)
PRIMARY_BG = '#FFFFFF'

plt.rcParams['font.sans-serif'] = 'Arial'
plt.rcParams['font.family'] = 'sans-serif'

def draw_oval(ax, x, y, w, h, text, fontsize=14.0):
    patch = patches.FancyBboxPatch((x, y), w, h, boxstyle='round,pad=0.2,rounding_size=2.0',
                                  fc='#FFFFFF', ec='#000000', lw=2.0, zorder=4)
    ax.add_patch(patch)
    ax.text(x + w/2.0, y + h/2.0, text, fontsize=fontsize, fontweight='bold', ha='center', va='center', color='#000000', zorder=5)

def draw_parallelogram(ax, x, y, w, h, text, fontsize=13.5, slant=2.2):
    pts = [[x + slant, y], [x + w + slant, y], [x + w, y + h], [x, y + h]]
    patch = patches.Polygon(pts, fc='#FFFFFF', ec='#000000', lw=2.0, zorder=4)
    ax.add_patch(patch)
    ax.text(x + w/2.0 + slant/2.0, y + h/2.0, text, fontsize=fontsize, fontweight='bold', ha='center', va='center', color='#000000', zorder=5)

def draw_diamond(ax, x, y, w, h, text, fontsize=12.5):
    pts = [[x + w/2.0, y + h], [x + w, y + h/2.0], [x + w/2.0, y], [x, y + h/2.0]]
    patch = patches.Polygon(pts, fc='#FFFFFF', ec='#000000', lw=2.0, zorder=4)
    ax.add_patch(patch)
    ax.text(x + w/2.0, y + h/2.0, text, fontsize=fontsize, fontweight='bold', ha='center', va='center', color='#000000', zorder=5)

def draw_rectangle(ax, x, y, w, h, text, fontsize=13.5):
    patch = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#000000', lw=2.0, zorder=4)
    ax.add_patch(patch)
    ax.text(x + w/2.0, y + h/2.0, text, fontsize=fontsize, fontweight='bold', ha='center', va='center', color='#000000', zorder=5)

def draw_connector(ax, cx, cy, r, label, fontsize=14.0):
    circle = patches.Circle((cx, cy), r, fc='#FFFFFF', ec='#000000', lw=2.0, zorder=4)
    ax.add_patch(circle)
    ax.text(cx, cy, label, fontsize=fontsize, fontweight='bold', ha='center', va='center', color='#000000', zorder=5)

# -------------------------------------------------------------
# 1. LOGIN PAGE FLOWCHART
# -------------------------------------------------------------
def generate_login_flowchart():
    fig, ax = plt.subplots(figsize=(14, 16.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(-12, 100)
    ax.axis('off')

    ax.text(5, 96, 'Figure 1', fontsize=16, fontweight='normal', color='#000000')
    ax.text(5, 93, 'Login Page Flowchart', fontsize=18, fontweight='bold', color='#000000')
    ax.plot([5, 30], [91.5, 91.5], color='#000000', lw=2.0)

    # 1. Shapes
    draw_oval(ax, 23.0, 84.0, 14.0, 4.5, 'start')
    draw_connector(ax, 8.0, 75.5, 2.5, 'T')
    draw_parallelogram(ax, 19.0, 73.0, 22.0, 5.5, 'display login page')
    draw_parallelogram(ax, 18.0, 62.0, 24.0, 5.5, 'input username\nand password')
    draw_diamond(ax, 20.0, 47.0, 20.0, 9.5, 'credentials are\nvalid?')
    draw_parallelogram(ax, 44.0, 49.0, 20.0, 5.5, 'display error')
    draw_parallelogram(ax, 20.0, 37.0, 20.0, 5.5, 'logged in')
    draw_diamond(ax, 46.0, 35.0, 18.0, 9.5, 'user role is\ncustomer')
    draw_parallelogram(ax, 68.0, 37.0, 20.0, 5.5, 'display customer\ndashboard')
    draw_connector(ax, 94.5, 39.75, 2.5, 'A')
    draw_diamond(ax, 46.0, 18.0, 18.0, 9.5, 'user role is\nstaff')
    draw_parallelogram(ax, 68.0, 20.0, 20.0, 5.5, 'display staff\ndashboard')
    draw_connector(ax, 94.5, 22.75, 2.5, 'B')
    draw_diamond(ax, 46.0, 1.0, 18.0, 9.5, 'user role is\nadmin')
    draw_parallelogram(ax, 68.0, 3.0, 20.0, 5.5, 'display admin\ndashboard')
    draw_connector(ax, 94.5, 5.75, 2.5, 'C')

    # 2. Connectors & Arrows (Exact Boundary Points, zorder=10)
    # Start -> Display Login Page
    ax.annotate('', xy=(30.0, 78.5), xytext=(30.0, 84.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Connector T -> Display Login Page
    ax.annotate('', xy=(20.0, 75.5), xytext=(10.5, 75.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Display Login Page -> Input Username & Password
    ax.annotate('', xy=(30.0, 67.5), xytext=(30.0, 73.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Input Username & Password -> Credentials are valid?
    ax.annotate('', xy=(30.0, 56.5), xytext=(30.0, 62.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Credentials valid? No -> Display Error
    ax.annotate('', xy=(44.0, 51.75), xytext=(40.0, 51.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(42.0, 52.8, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Display Error -> Loopback to Display Login Page
    ax.plot([54.0, 54.0], [54.5, 75.5], color='#000000', lw=2.0, zorder=10)
    ax.annotate('', xy=(42.2, 75.5), xytext=(54.0, 75.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Credentials valid? Yes -> Logged In
    ax.annotate('', xy=(30.0, 42.5), xytext=(30.0, 47.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 44.5, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Logged In -> User role is customer
    ax.annotate('', xy=(46.0, 39.75), xytext=(41.1, 39.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Customer Yes -> Display customer dashboard
    ax.annotate('', xy=(69.1, 39.75), xytext=(64.0, 39.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(66.5, 41.0, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Display customer dashboard -> Connector A
    ax.annotate('', xy=(92.0, 39.75), xytext=(89.1, 39.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Customer No -> User role is staff
    ax.annotate('', xy=(55.0, 27.5), xytext=(55.0, 35.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(56.5, 31.0, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Staff Yes -> Display staff dashboard
    ax.annotate('', xy=(69.1, 22.75), xytext=(64.0, 22.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(66.5, 24.0, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Display staff dashboard -> Connector B
    ax.annotate('', xy=(92.0, 22.75), xytext=(89.1, 22.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Staff No -> User role is admin
    ax.annotate('', xy=(55.0, 10.5), xytext=(55.0, 18.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(56.5, 14.0, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Admin Yes -> Display admin dashboard
    ax.annotate('', xy=(69.1, 5.75), xytext=(64.0, 5.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(66.5, 7.0, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Display admin dashboard -> Connector C
    ax.annotate('', xy=(92.0, 5.75), xytext=(89.1, 5.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Admin No -> Loop back to Input Username & Password
    ax.plot([55.0, 55.0, 15.0, 15.0], [1.0, -3.0, -3.0, 64.75], color='#000000', lw=2.0, zorder=10)
    ax.annotate('', xy=(19.1, 64.75), xytext=(15.0, 64.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(35.0, -2.2, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    plt.tight_layout()
    plt.savefig('diagrams/login_flowchart.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/login_flowchart.png")

# -------------------------------------------------------------
# 2. CUSTOMER ORDER PLACEMENT FLOWCHART
# -------------------------------------------------------------
def generate_customer_order_flowchart():
    fig, ax = plt.subplots(figsize=(14, 16.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(-12, 100)
    ax.axis('off')

    ax.text(5, 96, 'Figure 2', fontsize=16, fontweight='normal', color='#000000')
    ax.text(5, 93, 'Customer Order Placement Flowchart', fontsize=18, fontweight='bold', color='#000000')
    ax.plot([5, 48], [91.5, 91.5], color='#000000', lw=2.0)

    # 1. Shapes
    draw_connector(ax, 30.0, 87.0, 2.5, 'A')
    draw_parallelogram(ax, 18.0, 74.5, 24.0, 5.5, 'display service options\n(wash, dry, fold)')
    draw_parallelogram(ax, 16.0, 63.5, 28.0, 5.5, 'select service type &\ninput load weight (kg)')
    draw_rectangle(ax, 20.0, 52.5, 20.0, 5.5, 'calculate total\norder price')
    draw_diamond(ax, 20.0, 37.5, 20.0, 9.5, 'confirm order\ndetails?')
    draw_parallelogram(ax, 44.0, 39.5, 20.0, 5.5, 'order cancelled')
    draw_oval(ax, 68.0, 40.0, 14.0, 4.5, 'end')
    draw_rectangle(ax, 18.0, 27.5, 24.0, 5.5, 'generate order ID &\nunique QR code')
    draw_rectangle(ax, 17.0, 16.5, 26.0, 5.5, 'send confirmation SMS &\nemail via API')
    draw_parallelogram(ax, 17.0, 5.5, 26.0, 5.5, 'display digital receipt &\nQR tracking code')
    draw_oval(ax, 23.0, -3.5, 14.0, 4.5, 'end')

    # 2. Connectors & Arrows (Exact Boundary Points, zorder=10)
    # Connector A -> Display Services
    ax.annotate('', xy=(30.0, 80.0), xytext=(30.0, 84.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Display Services -> Select Service Type
    ax.annotate('', xy=(30.0, 69.0), xytext=(30.0, 74.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Select Service Type -> Calculate Total Price
    ax.annotate('', xy=(30.0, 58.0), xytext=(30.0, 63.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Calculate Total Price -> Confirm Order?
    ax.annotate('', xy=(30.0, 47.0), xytext=(30.0, 52.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Confirm Order No -> Order Cancelled
    ax.annotate('', xy=(45.1, 42.25), xytext=(40.0, 42.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(42.5, 43.5, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Order Cancelled -> End
    ax.annotate('', xy=(68.0, 42.25), xytext=(65.1, 42.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Confirm Order Yes -> Generate Order ID
    ax.annotate('', xy=(30.0, 33.0), xytext=(30.0, 37.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 35.0, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Generate Order ID -> Send Notification
    ax.annotate('', xy=(30.0, 22.0), xytext=(30.0, 27.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Send Notification -> Display Digital Receipt
    ax.annotate('', xy=(30.0, 11.0), xytext=(30.0, 16.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Display Digital Receipt -> End
    ax.annotate('', xy=(30.0, 1.0), xytext=(30.0, 5.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    plt.tight_layout()
    plt.savefig('diagrams/customer_order_flowchart.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/customer_order_flowchart.png")

# -------------------------------------------------------------
# 3. STAFF LAUNDRY SERVICE PROCESSING FLOWCHART
# -------------------------------------------------------------
def generate_staff_laundry_flowchart():
    fig, ax = plt.subplots(figsize=(14, 16.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(-12, 100)
    ax.axis('off')

    ax.text(5, 96, 'Figure 3', fontsize=16, fontweight='normal', color='#000000')
    ax.text(5, 93, 'Staff Laundry Processing Flowchart', fontsize=18, fontweight='bold', color='#000000')
    ax.plot([5, 48], [91.5, 91.5], color='#000000', lw=2.0)

    # 1. Shapes
    draw_connector(ax, 30.0, 87.0, 2.5, 'B')
    draw_parallelogram(ax, 17.0, 74.5, 26.0, 5.5, 'scan customer QR code /\nsearch order ID')
    draw_diamond(ax, 20.0, 59.5, 20.0, 9.5, 'order details\nfound?')
    draw_parallelogram(ax, 44.0, 61.5, 20.0, 5.5, 'display error / not found')
    draw_rectangle(ax, 18.0, 49.5, 24.0, 5.5, 'receive & weigh laundry;\nverify items')
    draw_rectangle(ax, 16.0, 38.5, 28.0, 5.5, 'update status: Washing ->\nDrying -> Folding -> Ready')
    draw_diamond(ax, 20.0, 23.5, 20.0, 9.5, 'laundry processing\ncompleted?')
    draw_rectangle(ax, 44.0, 25.5, 22.0, 5.5, 'continue processing &\nupdate progress')
    draw_rectangle(ax, 16.0, 13.5, 28.0, 5.5, 'send SMS/Email alert:\n"Order Ready for Pickup"')
    draw_parallelogram(ax, 17.0, 2.5, 26.0, 5.5, 'confirm payment & release\nlaundry load to customer')
    draw_oval(ax, 23.0, -6.5, 14.0, 4.5, 'end')

    # 2. Connectors & Arrows (Exact Boundary Points, zorder=10)
    # Connector B -> Scan QR Code
    ax.annotate('', xy=(30.0, 80.0), xytext=(30.0, 84.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Scan QR Code -> Order Details Found?
    ax.annotate('', xy=(30.0, 69.0), xytext=(30.0, 74.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Order Details Found? No -> Display Error
    ax.annotate('', xy=(44.0, 64.25), xytext=(40.0, 64.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(42.0, 65.5, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Display Error -> Loopback to Scan QR Code
    ax.plot([54.0, 54.0], [67.0, 77.25], color='#000000', lw=2.0, zorder=10)
    ax.annotate('', xy=(44.1, 77.25), xytext=(54.0, 77.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Order Details Found? Yes -> Receive & Weigh
    ax.annotate('', xy=(30.0, 55.0), xytext=(30.0, 59.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 57.0, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Receive & Weigh -> Update Status
    ax.annotate('', xy=(30.0, 44.0), xytext=(30.0, 49.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Update Status -> Laundry Completed?
    ax.annotate('', xy=(30.0, 33.0), xytext=(30.0, 38.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Laundry Completed? No -> Continue Processing
    ax.annotate('', xy=(44.0, 28.25), xytext=(40.0, 28.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(42.0, 29.5, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Continue Processing -> Loopback to Update Status
    ax.plot([55.0, 55.0], [31.0, 41.25], color='#000000', lw=2.0, zorder=10)
    ax.annotate('', xy=(44.0, 41.25), xytext=(55.0, 41.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Laundry Completed? Yes -> Send Alert
    ax.annotate('', xy=(30.0, 19.0), xytext=(30.0, 23.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 21.0, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Send Alert -> Confirm Payment
    ax.annotate('', xy=(30.0, 8.0), xytext=(30.0, 13.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Confirm Payment -> End
    ax.annotate('', xy=(30.0, -2.0), xytext=(30.0, 2.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    plt.tight_layout()
    plt.savefig('diagrams/staff_laundry_flowchart.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/staff_laundry_flowchart.png")

# -------------------------------------------------------------
# 4. QR CODE TRACKING & NOTIFICATION FLOWCHART
# -------------------------------------------------------------
def generate_qr_tracking_flowchart():
    fig, ax = plt.subplots(figsize=(14, 16.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(-12, 100)
    ax.axis('off')

    ax.text(5, 96, 'Figure 4', fontsize=16, fontweight='normal', color='#000000')
    ax.text(5, 93, 'QR Code Tracking & Notification Flowchart', fontsize=18, fontweight='bold', color='#000000')
    ax.plot([5, 58], [91.5, 91.5], color='#000000', lw=2.0)

    # 1. Shapes
    draw_oval(ax, 23.0, 84.0, 14.0, 4.5, 'start')
    draw_parallelogram(ax, 17.0, 73.0, 26.0, 5.5, 'scan QR code using\nmobile / camera')
    draw_rectangle(ax, 19.0, 62.0, 22.0, 5.5, 'decode QR payload &\nquery Database')
    draw_diamond(ax, 20.0, 47.0, 20.0, 9.5, 'QR token valid &\norder exists?')
    draw_parallelogram(ax, 44.0, 49.0, 20.0, 5.5, 'display invalid QR\nerror message')
    draw_oval(ax, 68.0, 49.5, 14.0, 4.5, 'end')
    draw_parallelogram(ax, 16.0, 37.0, 28.0, 5.5, 'display real-time status:\n(Wash, Dry, Fold, Ready)')
    draw_diamond(ax, 20.0, 22.0, 20.0, 9.5, 'status updated\nby staff?')
    draw_rectangle(ax, 44.0, 24.0, 20.0, 5.5, 'display active status\nview')
    draw_rectangle(ax, 16.0, 12.0, 28.0, 5.5, 'trigger TextBee SMS API &\nBrevo Email API dispatch')
    draw_parallelogram(ax, 17.0, 1.0, 26.0, 5.5, 'deliver instant status alert\nto customer phone')
    draw_oval(ax, 23.0, -8.0, 14.0, 4.5, 'end')

    # 2. Connectors & Arrows (Exact Boundary Points, zorder=10)
    # Start -> Scan QR Code
    ax.annotate('', xy=(30.0, 78.5), xytext=(30.0, 84.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Scan QR Code -> Decode QR Payload
    ax.annotate('', xy=(30.0, 67.5), xytext=(30.0, 73.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Decode QR Payload -> QR Token Valid?
    ax.annotate('', xy=(30.0, 56.5), xytext=(30.0, 62.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # QR Token Valid? No -> Display Invalid QR Error
    ax.annotate('', xy=(44.0, 51.75), xytext=(40.0, 51.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(42.0, 52.8, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Display Invalid QR Error -> End
    ax.annotate('', xy=(68.0, 51.75), xytext=(65.1, 51.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # QR Token Valid? Yes -> Display Real-Time Status
    ax.annotate('', xy=(30.0, 42.5), xytext=(30.0, 47.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 44.5, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Display Real-Time Status -> Status Updated?
    ax.annotate('', xy=(30.0, 31.5), xytext=(30.0, 37.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Status Updated? No -> Display Active Status View
    ax.annotate('', xy=(44.0, 26.75), xytext=(40.0, 26.75),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(42.0, 27.8, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Status Updated? Yes -> Trigger TextBee SMS
    ax.annotate('', xy=(30.0, 17.5), xytext=(30.0, 22.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 19.5, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Trigger TextBee SMS -> Deliver Instant Status Alert
    ax.annotate('', xy=(30.0, 6.5), xytext=(30.0, 12.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Deliver Instant Status Alert -> End
    ax.annotate('', xy=(30.0, -3.5), xytext=(30.0, 1.0),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    plt.tight_layout()
    plt.savefig('diagrams/qr_tracking_flowchart.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/qr_tracking_flowchart.png")

# -------------------------------------------------------------
# 5. SYSTEM ADMINISTRATION FLOWCHART
# -------------------------------------------------------------
def generate_admin_management_flowchart():
    fig, ax = plt.subplots(figsize=(14, 16.5), dpi=300)
    fig.patch.set_facecolor(PRIMARY_BG)
    ax.set_facecolor(PRIMARY_BG)
    ax.set_xlim(0, 100)
    ax.set_ylim(-12, 100)
    ax.axis('off')

    ax.text(5, 96, 'Figure 5', fontsize=16, fontweight='normal', color='#000000')
    ax.text(5, 93, 'System Administration & Management Flowchart', fontsize=18, fontweight='bold', color='#000000')
    ax.plot([5, 68], [91.5, 91.5], color='#000000', lw=2.0)

    # 1. Shapes
    draw_connector(ax, 30.0, 87.0, 2.5, 'C')
    draw_parallelogram(ax, 17.0, 74.5, 26.0, 5.5, 'display admin management\ndashboard')
    draw_diamond(ax, 20.0, 59.5, 20.0, 9.5, 'select administrative\ntask?')
    draw_rectangle(ax, 2.0, 47.5, 20.0, 5.5, 'manage staff/customer\nuser roles & permissions')
    draw_rectangle(ax, 20.0, 47.5, 20.0, 5.5, 'update laundry services,\ndetergents & pricing')
    draw_rectangle(ax, 38.0, 47.5, 20.0, 5.5, 'generate daily & monthly\nrevenue analytics')
    draw_rectangle(ax, 19.0, 30.5, 22.0, 5.5, 'commit configuration &\nsave audit log')
    draw_parallelogram(ax, 18.0, 19.5, 24.0, 5.5, 'display success message &\nupdated metrics')
    draw_diamond(ax, 20.0, 4.5, 20.0, 9.5, 'continue admin\nsession?')
    draw_oval(ax, 23.0, -5.5, 14.0, 4.5, 'end')

    # 2. Connectors & Arrows (Exact Boundary Points, zorder=10)
    # Connector C -> Display Admin Panel
    ax.annotate('', xy=(30.0, 80.0), xytext=(30.0, 84.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Display Admin Panel -> Select Action
    ax.annotate('', xy=(30.0, 69.0), xytext=(30.0, 74.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Option 1: Manage Accounts
    ax.annotate('', xy=(12.0, 53.0), xytext=(20.0, 64.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(14.0, 60.0, 'accounts', fontsize=11.5, fontweight='bold', color='#000000', ha='right', va='center')

    # Option 2: Inventory & Pricing
    ax.annotate('', xy=(30.0, 53.0), xytext=(30.0, 59.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 57.0, 'inventory', fontsize=11.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Option 3: Sales Reports & Analytics
    ax.annotate('', xy=(48.0, 53.0), xytext=(40.0, 64.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(46.0, 60.0, 'reports', fontsize=11.5, fontweight='bold', color='#000000', ha='left', va='center')

    # Rejoin lines -> Commit Configuration
    ax.plot([12.0, 12.0, 48.0, 48.0], [47.5, 41.5, 41.5, 47.5], color='#000000', lw=2.0, zorder=10)
    ax.plot([30.0, 30.0], [47.5, 41.5], color='#000000', lw=2.0, zorder=10)
    ax.annotate('', xy=(30.0, 36.0), xytext=(30.0, 41.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Commit Configuration -> Display Success Message
    ax.annotate('', xy=(30.0, 25.0), xytext=(30.0, 30.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Display Success Message -> Continue Admin Session?
    ax.annotate('', xy=(30.0, 14.0), xytext=(30.0, 19.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)

    # Continue Admin Session Yes -> Loop back to Admin Panel
    ax.plot([40.0, 66.0, 66.0], [9.25, 9.25, 77.25], color='#000000', lw=2.0, zorder=10)
    ax.annotate('', xy=(44.1, 77.25), xytext=(66.0, 77.25),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(41.5, 10.5, 'yes', fontsize=12.5, fontweight='bold', color='#000000', ha='center', va='bottom')

    # Continue Admin Session No -> Logout & End
    ax.annotate('', xy=(30.0, -1.0), xytext=(30.0, 4.5),
                arrowprops=dict(arrowstyle='->', lw=2.0, color='#000000', shrinkA=0, shrinkB=0), zorder=10)
    ax.text(31.5, 2.0, 'no', fontsize=12.5, fontweight='bold', color='#000000', ha='left', va='center')

    plt.tight_layout()
    plt.savefig('diagrams/admin_management_flowchart.png', dpi=300, bbox_inches='tight', facecolor='white')
    plt.close()
    print("Saved diagrams/admin_management_flowchart.png")

if __name__ == '__main__':
    generate_login_flowchart()
    generate_customer_order_flowchart()
    generate_staff_laundry_flowchart()
    generate_qr_tracking_flowchart()
    generate_admin_management_flowchart()
    print("ALL 5 SYSTEM FLOWCHARTS GENERATED SUCCESSFULLY!")
