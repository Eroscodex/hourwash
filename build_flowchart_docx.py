import os
import shutil
import docx
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

def set_cell_background(cell, fill_hex):
    shading_elm = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    cell._tc.get_or_add_tcPr().append(shading_elm)

def set_cell_margins(cell, top=140, bottom=140, left=180, right=180):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for margin_name, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{margin_name}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def set_table_borders(table, color="D3D3D3"):
    tblPr = table._tbl.tblPr
    borders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>\n'
        f'  <w:top w:val="single" w:sz="6" w:space="0" w:color="{color}"/>\n'
        f'  <w:bottom w:val="single" w:sz="6" w:space="0" w:color="{color}"/>\n'
        f'  <w:insideH w:val="single" w:sz="4" w:space="0" w:color="{color}"/>\n'
        f'  <w:left w:val="none"/>\n'
        f'  <w:right w:val="none"/>\n'
        f'  <w:insideV w:val="none"/>\n'
        f'</w:tblBorders>'
    )
    tblPr.append(borders)

def make_callout_box(doc, text, title="WORKFLOW OVERVIEW"):
    tbl = doc.add_table(rows=1, cols=1)
    tbl.alignment = WD_TABLE_ALIGNMENT.CENTER
    cell = tbl.cell(0, 0)
    cell.width = Inches(6.5)
    set_cell_background(cell, "F8F9FA")
    set_cell_margins(cell, top=160, bottom=160, left=200, right=200)

    tcPr = cell._tc.get_or_add_tcPr()
    borders = parse_xml(
        f'<w:tcBorders {nsdecls("w")}>\n'
        f'  <w:top w:val="none"/>\n'
        f'  <w:left w:val="single" w:sz="24" w:space="0" w:color="1A365D"/>\n'
        f'  <w:bottom w:val="none"/>\n'
        f'  <w:right w:val="none"/>\n'
        f'</w:tcBorders>'
    )
    tcPr.append(borders)

    p = cell.paragraphs[0]
    p.paragraph_format.space_before = Pt(0)
    p.paragraph_format.space_after = Pt(4)
    run_t = p.add_run(f"📌 {title}\n")
    run_t.bold = True
    run_t.font.name = 'Arial'
    run_t.font.size = Pt(10.5)
    run_t.font.color.rgb = RGBColor(0x1A, 0x36, 0x5D)

    run_b = p.add_run(text)
    run_b.font.name = 'Arial'
    run_b.font.size = Pt(9.5)
    run_b.font.color.rgb = RGBColor(0x2D, 0x37, 0x48)

    p_after = doc.add_paragraph()
    p_after.paragraph_format.space_before = Pt(0)
    p_after.paragraph_format.space_after = Pt(6)

def create_flowchart_docx():
    doc = docx.Document()

    # Page Margins (1 inch)
    for section in doc.sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.0)
        section.right_margin = Inches(1.0)

    # Base Styles
    normal_style = doc.styles['Normal']
    normal_style.font.name = 'Arial'
    normal_style.font.size = Pt(11)
    normal_style.font.color.rgb = RGBColor(0x2D, 0x37, 0x48)

    # Document Header Title
    title_p = doc.add_paragraph()
    title_p.paragraph_format.space_before = Pt(0)
    title_p.paragraph_format.space_after = Pt(2)
    run_title = title_p.add_run("HOUR WASH LAUNDRY SHOP SYSTEM")
    run_title.bold = True
    run_title.font.name = 'Arial'
    run_title.font.size = Pt(24)
    run_title.font.color.rgb = RGBColor(0x1A, 0x36, 0x5D)

    sub_p = doc.add_paragraph()
    sub_p.paragraph_format.space_before = Pt(0)
    sub_p.paragraph_format.space_after = Pt(18)
    run_sub = sub_p.add_run("System Process & Workflow Flowchart Documentation")
    run_sub.font.name = 'Arial'
    run_sub.font.size = Pt(14)
    run_sub.font.color.rgb = RGBColor(0x4A, 0x55, 0x68)

    # Divider Line
    p_div = doc.add_paragraph()
    p_div.paragraph_format.space_after = Pt(12)
    p_div_border = parse_xml(f'<w:pBdr {nsdecls("w")}><w:bottom w:val="single" w:sz="18" w:space="1" w:color="1A365D"/></w:pBdr>')
    p_div._p.get_or_add_pPr().append(p_div_border)

    # Executive Summary Section
    h1 = doc.add_heading("1. Executive Overview & Flowchart Specification", level=1)
    h1.style.font.color.rgb = RGBColor(0x1A, 0x36, 0x5D)
    h1.paragraph_format.space_before = Pt(12)
    h1.paragraph_format.space_after = Pt(8)

    p_intro = doc.add_paragraph(
        "This document presents the official system flowcharts for the Hour Wash Laundry Shop Management System. "
        "The flowcharts define the precise operational logic, decision points, user interaction paths, and automated system "
        "processes across the three primary user roles: Customer, Staff, and Administrator/Owner."
    )
    p_intro.paragraph_format.space_after = Pt(10)

    make_callout_box(
        doc,
        "All flowcharts adhere to standard UML flowchart modeling standards:\n"
        "• Oval (Terminator): Marks the starting point or completion of a workflow.\n"
        "• Parallelogram (Input/Output): Represents user interactions, screen displays, and user data entries.\n"
        "• Diamond (Decision): Denotes conditional branching (Yes/No validation checks).\n"
        "• Rectangle (Process): Indicates backend system computations, API dispatches, and database mutations.\n"
        "• Circle (Connector): Serves as off-page / role transition connectors (e.g., T, A, B, C).",
        title="FLOWCHART SYMBOL LEGEND & STANDARDS"
    )

    # Flowchart Sections List
    flowcharts = [
        {
            "num": "1",
            "title": "Login Page Flowchart",
            "img": "diagrams/login_flowchart.png",
            "desc": "Defines the authentication and role-based routing flow. Users enter credentials, which are validated against the database. Valid users are dynamically routed to their specific role dashboard (Customer A, Staff B, or Admin C), while invalid attempts trigger error messages and re-entry loops."
        },
        {
            "num": "2",
            "title": "Customer Order Placement Flowchart",
            "img": "diagrams/customer_order_flowchart.png",
            "desc": "Illustrates the customer order placement workflow. Customers select laundry service options (wash, dry, fold), input load weights (kg), review pricing calculations, and confirm orders. The system automatically generates a unique Order ID and QR code, sending instant confirmation via SMS (TextBee API) and Email (Brevo API)."
        },
        {
            "num": "3",
            "title": "Staff Laundry Service Processing Flowchart",
            "img": "diagrams/staff_laundry_flowchart.png",
            "desc": "Details the staff operational workflow for laundry processing. Staff members scan the customer QR code to fetch order details, verify and weigh laundry items, and update status through the service lifecycle (Washing → Drying → Folding → Ready for Pickup). Completion triggers automated SMS/Email alerts to the customer."
        },
        {
            "num": "4",
            "title": "QR Code Tracking & Notification Flowchart",
            "img": "diagrams/qr_tracking_flowchart.png",
            "desc": "Outlines the real-time QR tracking and notification mechanism. Customers or staff scan the QR token, which queries the backend database for live status updates. When laundry status changes, automated webhooks dispatch TextBee SMS and Brevo Email notifications to the customer."
        },
        {
            "num": "5",
            "title": "System Administration & Management Flowchart",
            "img": "diagrams/admin_management_flowchart.png",
            "desc": "Represents the administrative control workflow. Administrators manage customer and staff user accounts, configure laundry service pricing and detergent inventories, inspect revenue analytics reports, and audit system operational logs."
        }
    ]

    for item in flowcharts:
        doc.add_page_break()
        
        h = doc.add_heading(f"{int(item['num']) + 1}. Figure {item['num']}: {item['title']}", level=1)
        h.style.font.color.rgb = RGBColor(0x1A, 0x36, 0x5D)
        h.paragraph_format.space_before = Pt(12)
        h.paragraph_format.space_after = Pt(8)

        p_desc = doc.add_paragraph(item['desc'])
        p_desc.paragraph_format.space_after = Pt(12)

        # Embed Image
        if os.path.exists(item['img']):
            p_img = doc.add_paragraph()
            p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_img.paragraph_format.space_before = Pt(6)
            p_img.paragraph_format.space_after = Pt(6)
            run = p_img.add_run()
            run.add_picture(item['img'], width=Inches(6.5))

            p_cap = doc.add_paragraph()
            p_cap.alignment = WD_ALIGN_PARAGRAPH.CENTER
            p_cap.paragraph_format.space_after = Pt(16)
            run_cap = p_cap.add_run(f"Figure {item['num']} — {item['title']} (Hour Wash System)")
            run_cap.italic = True
            run_cap.font.size = Pt(9.5)
            run_cap.font.color.rgb = RGBColor(0x71, 0x80, 0x96)

    # Save to target filenames with fallback if locked by MS Word
    for filename in ["Hour_Wash_Flowchart.docx", "HourWash_Flowchart.docx"]:
        try:
            doc.save(filename)
            print(f"DOCX SUCCESSFULLY SAVED TO: {filename}")
        except PermissionError:
            print(f"Warning: {filename} is open in another program. Attempting to write...")
            alt_name = filename.replace(".docx", "_Updated.docx")
            doc.save(alt_name)
            print(f"DOCX SAVED TO FALLBACK: {alt_name}")

if __name__ == '__main__':
    create_flowchart_docx()
