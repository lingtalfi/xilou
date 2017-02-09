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
        font-size: __theme_td_font_size__;
    }
</style>

<table id="invoice_products_table">
    <thead>
    <tr>
        <th>Reference</th>
        <th>Product</th>
        <th>Tax Rate</th>
        <th>Base price</th>
        <th>Unit Price</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?php echo $p->reference; ?></td>
            <td><?php echo $p->name; ?></td>
            <td><?php echo $p->taxRate; ?></td>
            <td><?php echo $p->basePrice; ?></td>
            <td><?php echo $p->unitPrice; ?></td>
            <td><?php echo $p->quantity; ?></td>
            <td><?php echo $p->total; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>