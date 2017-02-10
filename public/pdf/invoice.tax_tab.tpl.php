<?php if ($v->tax_exempt): ?>__text_tax_exempted__<?php else: ?>
    <table id="tax-tab" width="100%">
        <thead>
        <tr>
            <th class="header small">__text_tax_detail__</th>
            <th class="header small">__text_tax_rate_label__</th>
            <th class="header small">__text_tax_base_price__</th>
            <th class="header-right small">__text_tax_total__</th>
        </tr>
        </thead>
        <tbody>

        <?php if (count($v->tax_details) > 0): ?>
            <?php foreach ($v->tax_details as $d): ?>

                <tr>
                    <td class="white">
                        <?php echo $d->label; ?>
                    </td>

                    <td class="center white">
                        <?php echo $d->tax_label; ?> %
                    </td>


                    <td class="right white">
                        <?php echo $d->base_price; ?>
                    </td>


                    <td class="right white">
                        <?php echo $d->total_taxes; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td class="white center" colspan="4">__text_no_taxes__</td>
            </tr>
        <?php endif; ?>

        </tbody>
    </table>
<?php endif; ?>
<!--  / TAX DETAILS -->
