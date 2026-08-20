import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT

doc = docx.Document()

# Set standard 1-inch margins
for section in doc.sections:
    section.top_margin = Inches(1)
    section.bottom_margin = Inches(1)
    section.left_margin = Inches(1)
    section.right_margin = Inches(1)

# Title
title = doc.add_heading('ASSIGNMENT SUBMISSION: QUANTITATIVE METHODS & SUPPLY CHAIN MANAGEMENT (SCM) ANALYSIS', level=0)
title.alignment = WD_ALIGN_PARAGRAPH.CENTER

subtitle = doc.add_paragraph('System: Hour Wash Laundry Management System (hourwashweb)\nDate: August 20, 2026\n')
subtitle.alignment = WD_ALIGN_PARAGRAPH.CENTER

# Section 1
doc.add_heading('SECTION 1: QUANTITATIVE METHODS CLASSIFICATION', level=1)

p = doc.add_paragraph()
p.add_run('The Hour Wash Laundry Management System is classified as a ').font.color.rgb = RGBColor(51, 51, 51)
r_bold = p.add_run('Hybrid Quantitative Decision Support System')
r_bold.bold = True
p.add_run('. It incorporates all three core quantitative analytics paradigms: Descriptive, Predictive, and Prescriptive Analytics.')

# 1. Descriptive
doc.add_heading('1. Descriptive Analytics ("What has happened & what is currently happening?")', level=2)
doc.add_paragraph('• Definition: Analyzes historical and current transactional data to answer "What has happened?" and "What is currently happening in the store?"')
doc.add_paragraph('• System Location: Admin & Owner Dashboard (/admin/dashboard -> AnalyticsController.php), Machine Monitor (/admin/machines), AI Chatbot Metrics Engine (ChatbotController.php).')
doc.add_paragraph('• Quantitative Formulas & Variables:')
doc.add_paragraph('   - Total Store Revenue: Total Revenue = ∑ (total_amount | payment_status = "paid")')
doc.add_paragraph('   - Machine Fleet Utilization Rate: Fleet Utilization % = (Active Machines / Total Machines) × 100%')
doc.add_paragraph('   - Order Queue Status Breakdown: Counts of orders in stages (N_pending, N_washing, N_drying, N_finish, N_completed).')

# 2. Predictive
doc.add_heading('2. Predictive Analytics ("What is expected to happen?")', level=2)
doc.add_paragraph('• Definition: Uses service parameters and current queue workload to forecast "What is expected to happen?"')
doc.add_paragraph('• System Location: Order Booking & ETA Estimation Engine (app/Models/Service.php, LaundryController.php), Live QR Code Customer Tracker (/laundry/track/{order}).')
doc.add_paragraph('• Quantitative Formulas & Variables:')
doc.add_paragraph('   - Estimated Time of Arrival / Completion (ETA):')
doc.add_paragraph('     ETA = T_creation + t_service + (N_queue × t_avg_cycle)')
doc.add_paragraph('     Durations: Wash Only ~35 mins | Dry Only ~40 mins | Self-Service ~75 mins | Full-Service ~90 mins.')

# 3. Prescriptive
doc.add_heading('3. Prescriptive Analytics ("What automated action should be taken?")', level=2)
doc.add_paragraph('• Definition: Evaluates business constraints and policies to determine "What automated action should be taken?"')
doc.add_paragraph('• System Location: Automated Loyalty Stamp Card Engine (app/Models/User.php -> addStamp()), 4:30 PM Cut-Off Policy Engine (welcome.blade.php, LaundryController.php), Automated Machine Dispatcher (AdminLaundryController.php).')
doc.add_paragraph('• Quantitative Formulas & Variables:')
doc.add_paragraph('   - 12-Stamp Loyalty Card Auto-Reset Logic:')
doc.add_paragraph('     Stamps_new = stamps_count + 1')
doc.add_paragraph('     IF Stamps_new ≥ 12 ⟹ [completed_cards = completed_cards + 1, discount_rewards = discount_rewards + 1 (₱50.00 OFF Token), stamps_count = 0 (Reset)]')
doc.add_paragraph('   - Automated 4:30 PM Same-Day Cut-Off Rule:')
doc.add_paragraph('     IF T_booking > 16:30 ⟹ Enforce "Next-Day Processing Guarantee"')

# Section 2
doc.add_heading('SECTION 2: SUPPLY CHAIN MANAGEMENT (SCM) ANALYSIS', level=1)

p_f1 = doc.add_paragraph()
r1 = p_f1.add_run('Functional Area:\n')
r1.bold = True
p_f1.add_run('- Property, Facility & Equipment Operations\n\n')
r2 = p_f1.add_run('Business Function:\n')
r2.bold = True
p_f1.add_run('- Machine Maintenance & Capacity Management\n\n')
r3 = p_f1.add_run('Business Process:\n')
r3.bold = True
p_f1.add_run('- Managing physical store equipment, washers, dryers, and laundry working spaces.\n- Inspecting machine operational statuses (idle, washing, drying, maintenance, offline).\n- Monitoring machine fleet occupancy, availability, and utilization rate.')

p_f2 = doc.add_paragraph()
r4 = p_f2.add_run('Functional Area:\n')
r4.bold = True
p_f2.add_run('- Finance & Accounting\n\n')
r5 = p_f2.add_run('Business Function:\n')
r5.bold = True
p_f2.add_run('- Order Billing & Payment Reconciliation\n\n')
r6 = p_f2.add_run('Business Process:\n')
r6.bold = True
p_f2.add_run('- Calculating order costs based on laundry weight (7kg max load limit per cycle) and service type.\n- Managing unpaid/paid order transactions, cash payments, and digital receipts.\n- Monitoring total store revenue, daily sales performance, and customer transaction balances.')

p_f3 = doc.add_paragraph()
r7 = p_f3.add_run('Functional Area:\n')
r7.bold = True
p_f3.add_run('- Marketing & Customer Relations (CRM)\n\n')
r8 = p_f3.add_run('Business Function:\n')
r8.bold = True
p_f3.add_run('- Marketing, Promotions & Loyalty Engagement\n\n')
r9 = p_f3.add_run('Business Process:\n')
r9.bold = True
p_f3.add_run('- Attracting potential customers through the online web landing page.\n- Advertising laundry packages (Wash Only, Dry Only, Wash & Dry Combo, Self-Service, Full-Service).\n- Managing promotions, supply choice discounts (own detergent/softener), and 12-stamp loyalty card rewards (₱50.00 OFF).\n- Collecting customer feedback, 1–5 star ratings, and online reviews.')

p_f4 = doc.add_paragraph()
r10 = p_f4.add_run('Functional Area:\n')
r10.bold = True
p_f4.add_run('- Customer & Rider Services\n\n')
r11 = p_f4.add_run('Business Function:\n')
r11.bold = True
p_f4.add_run('- Order Booking & Dispatch Management\n\n')
r12 = p_f4.add_run('Business Process:\n')
r12.bold = True
p_f4.add_run('- Receiving and responding to customer questions about laundry services via the AI Chatbot.\n- Verifying customer/walk-in information (name, address, barangay, phone number).\n- Approving and processing order bookings (enforcing the 4:30 PM cut-off rule and store status).\n- Sending automated booking confirmations, dynamic QR tracking tags, and SMS/Email notifications.')

# Section 3 Matrix
doc.add_heading('SECTION 3: SCM BUSINESS PROCESS & ROLE MATRIX', level=1)

table = doc.add_table(rows=1, cols=4)
table.alignment = WD_TABLE_ALIGNMENT.CENTER
hdr_cells = table.rows[0].cells
hdr_titles = ['Functional Area', 'Business Function', 'Input Variables / System Process', 'Business Outputs & KPIs']
for i, title in enumerate(hdr_titles):
    hdr_cells[i].text = title
    hdr_cells[i].paragraphs[0].runs[0].font.bold = True

rows_data = [
    ('Facility & Equipment Operations', 'Machine Maintenance & Capacity Management', 'Inputs: Service Type, Machine Availability\nLogic: Assigns idle machine with lowest workload, triggers timer.', 'Outputs: Active Machine Cycle, Live Status\nKPIs: Fleet Utilization %, Turnaround Time'),
    ('Finance & Accounting', 'Order Billing & Payment Reconciliation', 'Inputs: Weight (kg), Service Price, Supplies Discount\nLogic: Subtotal = Price * Load, applies ₱50 OFF token.', 'Outputs: Digital Invoice Receipt, Payment Status\nKPIs: Total Revenue, Payment Accuracy %'),
    ('Marketing & CRM', 'Promotions & Loyalty Engagement', 'Inputs: Completed Order Event\nLogic: Stamps + 1. At 12 stamps, resets to 0 and issues ₱50 OFF token.', 'Outputs: Updated Stamp Card (X/12), ₱50 Token\nKPIs: Customer Repeat Rate %, Reward Redemption %'),
    ('Customer & Rider Services', 'Order Booking & Dispatch Management', 'Inputs: Customer Address, Weight, 4:30 PM Time\nLogic: Validates 7kg load, checks 4:30 PM cutoff, dispatches Rider.', 'Outputs: Order Record (#HW-0001), QR Tag, SMS/Email\nKPIs: Daily Order Volume, On-Time Delivery %')
]

for area, func, proc, out in rows_data:
    row_cells = table.add_row().cells
    row_cells[0].text = area
    row_cells[1].text = func
    row_cells[2].text = proc
    row_cells[3].text = out

output_path = 'Hour_Wash_Quantitative_and_SCM_Assignment_Submission.docx'
doc.save(output_path)
print(f'Successfully generated {output_path}')
