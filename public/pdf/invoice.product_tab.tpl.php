<table class="product" width="100%" cellpadding="4" cellspacing="0">

    <thead>
    <tr>
        <th width="15%" class="product header small">__text_reference__</th>
        <th width="35%" class="product header small">__text_product__</th>
        <th width="10%" class="product header small">__text_tax_rate__</th>
        <th width="10%" class="product header small">__text_base_price__</th>
        <th width="10%" class="product header-right small">__text_unit_price__</th>
        <th width="10%" class="product header small">__text_quantity__</th>
        <th width="10%" class="product header-right small">__text_total__</th>
    </tr>
    </thead>

    <tbody>

    <!-- PRODUCTS -->
    <?php
    $i = 0;
    foreach ($v->order_details as $od):
        $sClass = (0 === ($i++ % 2)) ? 'color_line_even' : 'color_line_odd';

        ?>
        <tr class="product <?php echo $sClass; ?>">

            <td class="product center">
                <?php echo $od->product_reference; ?>
            </td>
            <td class="product left">
                <?php if ($v->display_product_images): ?>
                    <table>
                        <tr>
                            <td width="30%"><img width="40" src="<?php echo $od->product_image_src; ?>"></td>
                            <td width="70%"><?php echo $od->product_name; ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <?php echo $od->product_name; ?>
                <?php endif; ?>

            </td>
            <td class="product center">
                <?php echo $od->tax_label; ?>
            </td>


            <td class="product center">
                <?php echo $od->base_price; ?>
            </td>

            <td class="product right">
                <?php echo $od->unit_price; ?>
            </td>
            <td class="product center">
                <?php echo $od->quantity; ?>
            </td>
            <td class="product right">
                <?php echo $od->total; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <!-- END PRODUCTS -->

    <!-- CART RULES -->
    <?php

    $colCount = 0; // don't know what colCount is exactly,
    //see original prestashop template
    $nbCartDiscounts = count($v->cart_discounts);
    if ($nbCartDiscounts > 0): ?>
        <tr class="discount">
            <th class="header" colspan="7">
                __text_discounts__
            </th>
        </tr>
        <?php foreach ($v->cart_discounts as $cd): ?>
            <tr class="discount">
                <td class="white right" colspan="6">
                    <?php echo $cd->name; ?>
                </td>
                <td class="right white">
                    <?php echo $cd->price; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
