<style>
    table, th, td {
        margin: 0 !important;
        padding: 0 !important;
        vertical-align: middle;
        font-size: __theme_font_size_text__;
        white-space: nowrap;
    }

    table.product {
        border: 1px solid __theme_color_border__;
        border-collapse: collapse;
    }

    table#addresses-tab tr td {
        font-size: large;
    }

    table#summary-tab {
        padding: __theme_table_padding__;
        border: 1pt solid __theme_color_border__;
    }

    table#total-tab {
        padding: __theme_table_padding__;
        border: 1pt solid __theme_color_border__;
    }

    table#tax-tab {
        padding: __theme_table_padding__;
        border: 1pt solid __theme_color_border__;
    }

    table#payment-tab {
        padding: __theme_table_padding__;
        border: 1px solid __theme_color_border__;
    }

    th.product {
        border-bottom: 1px solid __theme_color_border__;
    }

    tr.discount th.header {
        border-top: 1px solid __theme_color_border__;
    }

    tr.product td {
        border-bottom: 1px solid __theme_color_border_lighter__;
    }

    tr.color_line_even {
        background-color: __theme_color_line_even__;
    }

    tr.color_line_odd {
        background-color: __theme_color_line_odd__;
    }

    tr.customization_data td {
    }

    td.product {
        vertical-align: middle;
        font-size: __theme_font_size_product__;
    }

    th.header {
        font-size: __theme_font_size_header__;
        height: __theme_height_header__;
        background-color: __theme_color_header__;
        vertical-align: middle;
        text-align: center;
        font-weight: bold;
    }

    th.header-right {
        font-size: __theme_font_size_header__;
        height: __theme_height_header__;
        background-color: __theme_color_header__;
        vertical-align: middle;
        text-align: right;
        font-weight: bold;
    }

    th.payment {
        background-color: __theme_color_header__;
        vertical-align: middle;
        font-weight: bold;
    }

    th.tva {
        background-color: __theme_color_header__;
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
        background-color: __theme_color_header__;

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

            {invoice.addresses_tab}

        </td>
    </tr>

    <tr>
        <td colspan="12" height="30">&nbsp;</td>
    </tr>

    <!-- TVA Info -->
    <tr>
        <td colspan="12">

            {invoice.summary_tab}

        </td>
    </tr>

    <tr>
        <td colspan="12" height="20">&nbsp;</td>
    </tr>

    <!-- Product -->
    <tr>
        <td colspan="12">

            {invoice.product_tab}

        </td>
    </tr>

    <tr>
        <td colspan="12" height="10">&nbsp;</td>
    </tr>

    <!-- TVA -->
    <tr>
        <!-- Code TVA -->
        <td colspan="6" class="left">{invoice.tax_tab}</td>
        <td colspan="1">&nbsp;</td>
        <!-- Calcule TVA -->
        <td colspan="5" rowspan="5" class="right">

            { invoice.total_tab}

        </td>
    </tr>

    <tr>
        <td colspan="12" height="10">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="6" class="left">

            { invoice.payment_tab}

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
