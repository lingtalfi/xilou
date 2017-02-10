<style>
    table#invoice_products_table {
        border: 1px solid __theme_table_border_color__;
    }

    table#invoice_products_table tr th {
        text-align: center;
        font-weight: bold;
        font-size: __theme_th_font_size__;
        background-color: __theme_th_bg_color__;
    }

    table#invoice_products_table tr td {
        text-align: center;
        font-size: __theme_td_font_size__;
    }
</style>

<table id="invoice_products_table" cellpadding="__theme_cellpadding__">
    <thead>
    <tr>
        <th>__text_reference__</th>
        <th>__text_product__</th>
        <th>__text_tax_rate__</th>
        <th>__text_unit_price__</th>
        <th>__text_qty__</th>
        <th>__text_total__</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($v->products as $p): ?>
        <tr>
            <td><?php echo $p->reference; ?></td>
            <td><?php echo $p->name; ?></td>
            <td><?php echo $p->tax_rate; ?></td>
            <td><?php echo $p->unit_price; ?></td>
            <td><?php echo $p->quantity; ?></td>
            <td><?php echo $p->total; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>