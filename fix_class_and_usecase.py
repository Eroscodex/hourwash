with open('generate_diagrams.py', 'r', encoding='utf-8') as f:
    code = f.read()

# Fix draw_class_box_bw
old_box_fn = """def draw_class_box_bw(ax, x, y, name, attrs, methods, w=29, h=30):
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(rect)
    
    h_rect = patches.Rectangle((x, y + h - 3.2), w, 3.2, fc='#FFFFFF', ec='#000000', lw=1.4)
    ax.add_patch(h_rect)
    ax.text(x + w/2.0, y + h - 1.6, name, fontsize=11.5, fontweight='bold', ha='center', va='center', color='#000000')
    
    ax.plot([x, x + w], [y + h - 3.2, y + h - 3.2], color='#000000', lw=1.2)

    attr_start_y = y + h - 3.8
    for i, attr in enumerate(attrs):
        ax.text(x + 0.6, attr_start_y - (i * 1.30), attr, fontsize=8.8, fontweight='bold', va='top', ha='left', color='#000000', fontfamily='sans-serif')

    div_y = attr_start_y - (len(attrs) * 1.05) - 0.2
    ax.plot([x, x + w], [div_y, div_y], color='#000000', lw=1.2)

    method_start_y = div_y - 0.4
    for j, meth in enumerate(methods):
        ax.text(x + 0.6, method_start_y - (j * 1.30), meth, fontsize=8.8, fontweight='bold', va='top', ha='left', color='#000000', fontfamily='sans-serif')"""

new_box_fn = """def draw_class_box_bw(ax, x, y, name, attrs, methods, w=29, h=30):
    rect = patches.Rectangle((x, y), w, h, fc='#FFFFFF', ec='#000000', lw=1.6)
    ax.add_patch(rect)
    
    h_rect = patches.Rectangle((x, y + h - 3.2), w, 3.2, fc='#FFFFFF', ec='#000000', lw=1.6)
    ax.add_patch(h_rect)
    ax.text(x + w/2.0, y + h - 1.6, name, fontsize=11.5, fontweight='bold', ha='center', va='center', color='#000000')
    
    ax.plot([x, x + w], [y + h - 3.2, y + h - 3.2], color='#000000', lw=1.4)

    attr_start_y = y + h - 3.8
    for i, attr in enumerate(attrs):
        ax.text(x + 0.6, attr_start_y - (i * 1.20), attr, fontsize=8.2, fontweight='bold', va='top', ha='left', color='#000000', fontfamily='sans-serif')

    div_y = attr_start_y - (len(attrs) * 1.20) - 0.3
    ax.plot([x, x + w], [div_y, div_y], color='#000000', lw=1.4)

    method_start_y = div_y - 0.4
    for j, meth in enumerate(methods):
        ax.text(x + 0.6, method_start_y - (j * 1.20), meth, fontsize=8.2, fontweight='bold', va='top', ha='left', color='#000000', fontfamily='sans-serif')"""

code = code.replace(old_box_fn, new_box_fn)

# Increase class box heights
code = code.replace("w=24, h=16.5)", "w=25.0, h=18.0)")
code = code.replace("w=24.0, h=16.0)", "w=25.0, h=17.5)")
code = code.replace("w=21.5, h=16.0)", "w=21.5, h=19.5)")
code = code.replace("w=17.5, h=15.0)", "w=17.5, h=16.5)")

with open('generate_diagrams.py', 'w', encoding='utf-8') as f:
    f.write(code)

print('Updated draw_class_box_bw and box heights successfully!')
