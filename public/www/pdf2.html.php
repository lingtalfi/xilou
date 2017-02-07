<style>
    table, th, td {
        margin: 0 !important;
        padding: 0 !important;
        vertical-align: middle;
        font-size: 9pt;
        white-space: nowrap;
    }

    table.product {
        border: 1px solid #000000;
        border-collapse: collapse;
    }

    table#addresses-tab tr td {
        font-size: large;
    }

    table#summary-tab {
        padding: 4px;
        border: 1pt solid #000000;
    }

    table#total-tab {
        padding: 4px;
        border: 1pt solid #000000;
    }

    table#tax-tab {
        padding: 4px;
        border: 1pt solid #000000;
    }

    table#payment-tab {
        padding: 4px;
        border: 1px solid #000000;
    }

    th.product {
        border-bottom: 1px solid #000000;
    }

    tr.discount th.header {
        border-top: 1px solid #000000;
    }

    tr.product td {
        border-bottom: 1px solid #CCCCCC;
    }

    tr.color_line_even {
        background-color: #FFFFFF;
    }

    tr.color_line_odd {
        background-color: #F9F9F9;
    }

    tr.customization_data td {
    }

    td.product {
        vertical-align: middle;
        font-size: 9pt;
    }

    th.header {
        font-size: 9pt;
        height: 20px;
        background-color: #F0F0F0;
        vertical-align: middle;
        text-align: center;
        font-weight: bold;
    }

    th.header-right {
        font-size: 9pt;
        height: 20px;
        background-color: #F0F0F0;
        vertical-align: middle;
        text-align: right;
        font-weight: bold;
    }

    th.payment {
        background-color: #F0F0F0;
        vertical-align: middle;
        font-weight: bold;
    }

    th.tva {
        background-color: #F0F0F0;
        vertical-align: middle;
        font-weight: bold;
    }

    tr.separator td {
        border-top: 1px solid #000000;
    }

    .left {
        text-align: left;
    }

    .fright {
        float: right;
    }

    .right {
        text-align: right;
    }

    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    .border {
        border: 1px solid black;
    }

    .no_top_border {
        border-top: hidden;
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .grey {
        background-color: #F0F0F0;
    }

    /* This is used for the border size */
    .white {
        background-color: #FFFFFF;
    }

    .big,
    tr.big td {
        font-size: 110%;
    }

    .small, table.small th, table.small td {
        font-size: small;
    }
</style>

<table width="100%" id="body" border="0" cellpadding="0" cellspacing="0" style="margin:0;">
    <!-- Invoicing -->
    <tr>
        <td colspan="12">

            <table id="addresses-tab" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="33%"><span class="bold"> </span><br/><br/>
                        6 rue port feu hugon 37000 TOURS
                    </td>
                    <td width="33%"><span class="bold">Adresse de livraison</span><br/><br/>
                        6 rue port feu hugon 37000 TOURS
                    </td>
                    <td width="33%"><span class="bold">Adresse de facturation</span><br/><br/>
                        6 rue port feu hugon 37000 TOURS
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="12" height="30">&nbsp;</td>
    </tr>

    <!-- TVA Info -->
    <tr>
        <td colspan="12">

            <table id="summary-tab" width="100%">
                <tr>
                    <th class="header small" valign="middle">Numéro de facture</th>
                    <th class="header small" valign="middle">Date de la facture</th>
                    <th class="header small" valign="middle">Référence de la commande</th>
                    <th class="header small" valign="middle">Date de la commande</th>
                </tr>
                <tr>
                    <td class="center small white">F00463X90</td>
                    <td class="center small white">2017-06-12</td>
                    <td class="center small white">C_OO3949FK</td>
                    <td class="center small white">2016-12-31</td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td colspan="12" height="20">&nbsp;</td>
    </tr>

    <!-- Product -->
    <tr>
        <td colspan="12">

            <table class="product" width="100%" cellpadding="4" cellspacing="0">

                <thead>
                <tr>
                    <th class="product header small" width="16.6%">Référence</th>
                    <th class="product header small" width="16.6%">Produit</th>
                    <th class="product header small" width="16.6%">Taux de TVA</th>


                    <th class="product header-right small" width="16.6%">Prix unitaire <br/> Tax excl.</th>
                    <th class="product header small" width="16.6%">Qty</th>
                    <th class="product header-right small" width="16.6%">Total <br/> Tax excl.</th>
                </tr>
                </thead>

                <tbody>

                <!-- PRODUCTS -->
                <?php for ($i = 0; $i < 3; $i++):
                    $class = (0 == $i % 2) ? 'color_line_even' : 'color_line_odd';
                    ?>
                    <tr class="product <?php echo $class; ?>">

                        <td class="product center">
                            1438
                        </td>
                        <td class="product left">
                            Ballon bleu

                        </td>
                        <td class="product center">
                            TVA FR
                        </td>


                        <td class="product right">
                            64.57€
                        </td>
                        <td class="product center">
                            6
                        </td>
                        <td class="product right">
                            369.98€
                        </td>
                    </tr>
                <?php endfor; ?>
                <!-- END PRODUCTS -->

                <!-- CART RULES -->
                <?php for ($i = 0; $i < 2; $i++): ?>
                    <tr class="discount">
                        <td class="white right" colspan="{$layout._colCount - 1}">
                            Réduction panier ABC offerte
                        </td>
                        <td class="right white">
                            - 40 €
                        </td>
                    </tr>
                <?php endfor; ?>

                </tbody>

            </table>

        </td>
    </tr>

    <tr>
        <td colspan="12" height="10">&nbsp;</td>
    </tr>

    <!-- TVA -->
    <tr>
        <!-- Code TVA -->
        <td colspan="6" class="left">

            Exempt of VAT according to section 259B of the General Tax Code.

        </td>
        <td colspan="1">&nbsp;</td>
        <!-- Calcule TVA -->
        <td colspan="5" rowspan="5" class="right">

            <table id="total-tab" width="100%">

                <tr>
                    <td class="grey" width="70%">
                        Total Products
                    </td>
                    <td class="white" width="30%">
                        560€
                    </td>
                </tr>

                <tr class="bold">
                    <td class="grey">
                        Total (Tax excl.)
                    </td>
                    <td class="white">
                        485.90€
                    </td>
                </tr>

                <tr class="bold">
                    <td class="grey">
                        Total Tax
                    </td>
                    <td class="white">
                        399.8€
                    </td>
                </tr>

                <tr class="bold big">
                    <td class="grey">
                        Total
                    </td>
                    <td class="white">
                        1978.54€
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td colspan="12" height="10">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="6" class="left">

            <table id="payment-tab" width="100%">
                <tr>
                    <td class="payment center small grey bold" width="44%">Payment Method</td>
                    <td class="payment left white" width="56%">
                        <table width="100%" border="0">
                            <tr>
                                <td class="right small">Virement par chèque</td>
                                <td class="right small">388.09€</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
        <td colspan="1">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="12" height="10">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="7" class="left small">

            <table>
                <tr>
                    <td>
                        <p>Legal free text</p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
