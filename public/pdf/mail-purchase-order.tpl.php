<style>


    table.grid-table {
        padding: 4px;
    }

    table.grid-table tr th {
        background-color: #000;
        color: white;
        text-align: center;
        vertical-align: middle;
    }

    table.grid-table tr.color_line_even {
        background-color: #eee;
        font-size: 7px;
    }

    table.grid-table tr.color_line_odd {
        background-color: white;
        font-size: 7px;
    }

    table.grid-table tr.color_line_even td,
    table.grid-table tr.color_line_odd td {
        vertical-align: middle;
    }

    table.align-right tr td {
        text-align: right;
    }

    table.soft-table th {
        font-size: 8px;
        background-color: #eee;
        color: #000;
        vertical-align: middle;
        text-align: center;
        font-weight: bold;
    }

    .bold {
        font-weight: bold;
    }

    .big {
        font-size: 8px;
    }

    .bigger {
        font-size: 10px;
    }

    .biggest-font-size {
        font-size: 14px;
    }

    .total_bgcolor {
        background-color: #c00;
        color: #fff;
    }


</style>
<table>


    <!-- HEADER -->
    <tr>
        <td colspan="12">
            <table>
                <tr>
                    <td colspan="6">
                        <img src="__header_logo_img_src__" width="__header_logo_width__"/>
                    </td>
                    <td colspan="6" align="right" class="biggest-font-size">
                        <b>Purchase order</b>
                        <br><span class="grayed_out">__header_date__</span>
                        <br><span class="grayed_out">__header_order__</span>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <!-- space -->
    <tr>
        <td height="35" colspan="12"></td>
    </tr>


    <!-- PRODUCTS -->
    <tr>
        <td colspan="12">
            <table class="grid-table">


                <tr>
                    <th width="10%">__text_reference_lf__</th>
                    <th width="10%">__text_reference_pro__</th>
                    <th width="10%">__text_product__</th>
                    <th width="10%">__text_ean__</th>
                    <th width="10%">__text_packing__</th>
                    <th width="10%">__text_description__</th>
                    <th width="10%">__text_logo__</th>
                    <th width="10%">__text_unit_price__</th>
                    <th width="10%">__text_quantity__</th>
                    <th width="10%">__text_total__</th>
                </tr>


                <!-- PRODUCTS -->
                <?php
                $i = 1;
                foreach ($v->order_details as $od):
                    $sClass = (0 === ($i++ % 2)) ? 'color_line_even' : 'color_line_odd';

                    ?>
                    <tr class="<?php echo $sClass; ?>">

                        <td align="center">
                            <br>
                            <?php echo $od->reference_lf; ?>
                        </td>
                        <td align="center">
                            <?php echo $od->reference_pro; ?>
                        </td>
                        <td align="left">
                            <table>
                                <tr>
                                    <td width="30%"><img width="40" src="<?php echo $od->product_image_src; ?>">
                                    </td>
                                    <td width="70%"><?php echo $od->product_name; ?></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <?php echo $od->ean; ?>
                        </td>
                        <td align="center">
                            <?php echo $od->packing; ?>
                        </td>
                        <td>
                            <?php echo $od->description; ?>
                        </td>
                        <td><img width="30" src="<?php echo $od->logo; ?>"></td>
                        <td align="center">
                            <?php echo $od->unit_price; ?>
                        </td>
                        <td align="center">
                            <?php echo $od->quantity; ?>
                        </td>
                        <td>
                            <?php echo $od->total; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <!-- END PRODUCTS -->
            </table>
        </td>
    </tr>


    <!-- space -->
    <tr>
        <td height="10" colspan="12"></td>
    </tr>


    <!-- TVA -->
    <tr>
        <!-- Code TVA -->
        <td colspan="9">

        </td>
        <!-- Calcule TVA -->
        <td colspan="3">
            <table class="soft-table align-right" cellpadding="5">
                <tr class="bold bigger">
                    <td class="total_bgcolor" align="center">
                        __text_total__
                    </td>
                    <td class="white">
                        __total__
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
