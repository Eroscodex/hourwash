import re

with open('generate_diagrams.py', 'r', encoding='utf-8') as f:
    content = f.read()

# 1. System Design Diagram Font Sizes
content = content.replace("fontsize=10, fontweight='bold', color='#000000')", "fontsize=13.0, fontweight='bold', color='#000000')")
content = content.replace("fontsize=7.6, ha='center'", "fontsize=10.0, fontweight='bold', ha='center'")
content = content.replace('fontsize=8.5, color="#000000", fontweight=\'bold\'', 'fontsize=11.5, color="#000000", fontweight=\'bold\'')
content = content.replace("fontsize=8.2, ha='center'", "fontsize=10.5, fontweight='bold', ha='center'")
content = content.replace("fontsize=7.5, ha='center'", "fontsize=9.8, fontweight='bold', ha='center'")
content = content.replace('fontsize=8.2, color="#000000", fontweight=\'bold\'', 'fontsize=11.0, color="#000000", fontweight=\'bold\'')
content = content.replace("fontsize=9.5, fontweight='bold', color='#000000'", "fontsize=12.0, fontweight='bold', color='#000000'")
content = content.replace("fontsize=7.2, ha='center'", "fontsize=9.5, fontweight='bold', ha='center'")

# 2. Use Case Diagram Font & Box Sizes
content = content.replace("fontsize=9.2, fontweight='bold'", "fontsize=12.0, fontweight='bold'")
content = content.replace("def draw_usecase_bw(ax, x, y, text, w=17, h=2.3):", "def draw_usecase_bw(ax, x, y, text, w=20, h=3.2):")
content = content.replace("fontsize=6.5, ha='center'", "fontsize=9.8, ha='center'")
content = content.replace("fontsize=6.2, fontweight='bold', color='#000000', ha='center', va='center'", "fontsize=9.0, fontweight='bold', color='#000000', ha='center', va='center'")
content = content.replace("fontsize=7.2, fontweight='bold', ha='center', color='#000000'", "fontsize=10.5, fontweight='bold', ha='center', color='#000000'")

# 3. Class Diagram Font & Box Sizes
content = content.replace("fontsize=8.8, fontweight='bold'", "fontsize=11.5, fontweight='bold'")
content = content.replace("fontsize=6.2, va='top', ha='left'", "fontsize=8.8, fontweight='bold', va='top', ha='left'")
content = content.replace("(i * 1.05)", "(i * 1.30)")
content = content.replace("(j * 1.05)", "(j * 1.30)")
content = content.replace("fontsize=7.2, fontweight='bold', color='#000000', va='center'", "fontsize=9.5, fontweight='bold', color='#000000', va='center'")
content = content.replace("fontsize=6.8, fontweight='bold', color='#000000', ha='center'", "fontsize=9.0, fontweight='bold', color='#000000', ha='center'")

# 4. Sequence Diagram Template
content = content.replace("fontsize=7.8, fontweight='bold', ha='center', va='top'", "fontsize=11.5, fontweight='bold', ha='center', va='top'")
content = content.replace("fontsize=7.2, fontweight='bold', ha='center', va='center'", "fontsize=10.5, fontweight='bold', ha='center', va='center'")
content = content.replace("fontsize=8.0, fontweight='bold', color='#000000', ha='center', va='center'", "fontsize=9.5, fontweight='bold', color='#000000', ha='center', va='center'")
content = content.replace("fontsize=7.5, fontweight='bold', fontstyle='italic'", "fontsize=9.5, fontweight='bold', fontstyle='italic'")
content = content.replace("fontsize=6.8, ha='center', va='bottom', color='#000000', fontweight='bold'", "fontsize=9.5, ha='center', va='bottom', color='#000000', fontweight='bold'")

# 5. Package Diagram scale
content = content.replace("tab_fs = max(5.2, 8.5 * min(scale_w, scale_h))", "tab_fs = max(8.5, 12.0 * min(scale_w, scale_h))")
content = content.replace("lbl_fs = max(4.8, 7.2 * min(scale_w, scale_h))", "lbl_fs = max(7.5, 10.0 * min(scale_w, scale_h))")

with open('generate_diagrams.py', 'w', encoding='utf-8') as f:
    f.write(content)

print('Updated generate_diagrams.py successfully!')
