<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <title>Message from {shop_name}</title>


    <style>    @media only screen and (max-width: 300px) {
            body {
                width: 218px !important;
                margin: auto !important;
            }

            thead, tbody {
                width: 100%
            }

            .table {
                width: 195px !important;
                margin: auto !important;
            }

            .logo, .titleblock, .linkbelow, .box, .footer, .space_footer {
                width: auto !important;
                display: block !important;
            }

            span.title {
                font-size: 20px !important;
                line-height: 23px !important
            }

            span.subtitle {
                font-size: 14px !important;
                line-height: 18px !important;
                padding-top: 10px !important;
                display: block !important;
            }

            td.box p {
                font-size: 12px !important;
                font-weight: bold !important;
            }

            .table-recap table, .table-recap thead, .table-recap tbody, .table-recap th, .table-recap td, .table-recap tr {
                display: block !important;
            }

            .table-recap {
                width: 200px !important;
            }

            .table-recap tr td, .conf_body td {
                text-align: center !important;
            }

            .address {
                display: block !important;
                margin-bottom: 10px !important;
            }

            .space_address {
                display: none !important;
            }
        }

        @media only screen and (min-width: 301px) and (max-width: 500px) {
            body {
                width: 425px !important;
                margin: auto !important;
            }

            thead, tbody {
                width: 100%
            }

            .table {
                margin: auto !important;
            }

            .logo, .titleblock, .linkbelow, .box, .footer, .space_footer {
                width: auto !important;
                display: block !important;
            }

            .table-recap {
                width: 295px !important;
            }

            .table-recap tr td, .conf_body td {
                text-align: center !important;
            }

            .table-recap tr th {
                font-size: 10px !important
            }

        }

        @media only screen and (min-width: 501px) and (max-width: 768px) {
            body {
                width: 478px !important;
                margin: auto !important;
            }

            thead, tbody {
                width: 100%
            }

            .table {
                margin: auto !important;
            }

            .logo, .titleblock, .linkbelow, .box, .footer, .space_footer {
                width: auto !important;
                display: block !important;
            }
        }

        @media only screen and (max-device-width: 480px) {
            body {
                width: 340px !important;
                margin: auto !important;
            }

            thead, tbody {
                width: 100%
            }

            .table {
                margin: auto !important;
            }

            .logo, .titleblock, .linkbelow, .box, .footer, .space_footer {
                width: auto !important;
                display: block !important;
            }

            .table-recap {
                width: 295px !important;
            }

            .table-recap tr td, .conf_body td {
                text-align: center !important;
            }

            .address {
                display: block !important;
                margin-bottom: 10px !important;
            }

            .space_address {
                display: none !important;
            }
        }
    </style>

</head>
<body style="-webkit-text-size-adjust:none;background-color:#fff;width:650px;font-family:Open-sans, sans-serif;color:#555454;font-size:13px;line-height:18px;margin:auto">
<table class="table table-mail"
       style="width:100%;margin-top:10px;-moz-box-shadow:0 0 5px #afafaf;-webkit-box-shadow:0 0 5px #afafaf;-o-box-shadow:0 0 5px #afafaf;box-shadow:0 0 5px #afafaf;filter:progid:DXImageTransform.Microsoft.Shadow(color=#afafaf,Direction=134,Strength=5)">
    <tr>
        <td class="space" style="width:20px;padding:7px 0">&nbsp;</td>
        <td align="center" style="padding:7px 0">
            <table class="table" bgcolor="#ffffff" style="width:100%">
                <tr>
                    <td align="center" class="logo" style="border-bottom:4px solid #333333;padding:7px 0">
                        <a title="{shop_name}" href="{shop_url}" style="color:#337ff1">
                            <img src="{shop_logo}" alt="{shop_name}"
                                 style="max-width:100%; max-height:100px; height:auto; width:auto;"/>
                        </a>
                    </td>
                </tr>

                <tr>
                    <td align="left" class="titleblock" style="padding:7px 0">
                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                            <span class="title">Bonjour Didier,</span><br/>
                            <span class="subtitle">
                                Ci-dessous la commande {order_number} en attente de validation.<br>
                                Peux-tu y jeter un coup d'oeil et me donner ton feu vert, afin que je puisse faire les demandes de devis aux fournisseurs concernés.<br>
                                <br>
                                Merci d'avance.<br>
                                Cordialement, Zilu.
                            </span>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td class="space_footer" style="padding:0!important">&nbsp;</td>
                </tr>
                <tr>
                    <td class="box" style="border:1px solid #D6D4D4;background-color:#f8f8f8;padding:7px 0">
                        <table class="table" style="width:100%">
                            <tr>
                                <td width="10" style="padding:7px 0">&nbsp;</td>
                                <td style="padding:7px 0">
                                    <font size="2" face="Open-sans, sans-serif" color="#555454">
                                        <p data-html-only="1"
                                           style="border-bottom:1px solid #D6D4D4;margin:3px 0 7px;text-transform:uppercase;font-weight:500;font-size:18px;padding-bottom:10px">
                                            Détails de la commande </p>
                                        <span style="color:#777">
							                <span style="color:#333"><strong>Commande:</strong></span> {order_number}<br/><br/>
							                <span style="color:#333"><strong>Date de livraison estimée:</strong></span> {order_estimated_date}<br/><br/>
						                </span>
                                    </font>
                                </td>
                                <td width="10" style="padding:7px 0">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:7px 0">
                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                            <table class="table table-recap" bgcolor="#ffffff"
                                   style="width:100%;border-collapse:collapse"><!-- Title -->
                                <tr>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;">
                                        Référence Leaderfit
                                    </th>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;">
                                        Référence Fournisseur
                                    </th>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;">
                                        Fournisseur
                                    </th>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;"
                                        width="30%">
                                        Produit
                                    </th>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;"
                                        width="17%"
                                    >Prix unitaire
                                    </th>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;">
                                        Quantité
                                    </th>
                                    <th bgcolor="#f8f8f8"
                                        style="border:1px solid #D6D4D4;background-color: #fbfbfb;color: #333;font-family: Arial;font-size: 13px;padding: 10px;"
                                        width="17%">Prix total
                                    </th>
                                </tr>
                                <?php foreach ($v->order_details as $od): ?>
                                    <tr>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td>
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <?php echo $od->reference; ?>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td>
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <?php echo $od->provider_reference; ?>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td>
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <?php echo $od->fournisseur; ?>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td>
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <img style="max-width:30%; max-height:40px; vertical-align: middle; height:auto; width:auto;"
                                                                 src="<?php echo $od->img_src; ?>">
                                                            <strong><?php echo $od->name; ?></strong>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td align="right">
                                                        <font size="2" style="white-space: nowrap"
                                                              face="Open-sans, sans-serif" color="#555454">
                                                            <?php echo $od->unit_price; ?>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td align="right">
                                                        <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                            <?php echo $od->quantity; ?>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td style="border:1px solid #D6D4D4;">
                                            <table class="table">
                                                <tr>
                                                    <td width="10">&nbsp;</td>
                                                    <td align="right">
                                                        <font size="2" style="white-space: nowrap"
                                                              face="Open-sans, sans-serif" color="#555454">
                                                            <?php echo $od->price; ?>
                                                        </font>
                                                    </td>
                                                    <td width="10">&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <tr class="conf_body">
                                    <td bgcolor="#f8f8f8" colspan="4"
                                        style="border:1px solid #D6D4D4;color:#333;padding:7px 0">
                                        <table class="table" style="width:100%;border-collapse:collapse">
                                            <tr>
                                                <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                <td align="right" style="color:#333;padding:0">
                                                    <font size="2" face="Open-sans, sans-serif" color="#555454">
                                                        <strong>Total</strong>
                                                    </font>
                                                </td>
                                                <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td bgcolor="#f8f8f8" colspan="4"
                                        style="border:1px solid #D6D4D4;color:#333;padding:7px 0">
                                        <table class="table" style="width:100%;border-collapse:collapse">
                                            <tr>
                                                <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                                <td align="right" style="color:#333;padding:0">
                                                    <font size="4" face="Open-sans, sans-serif" color="#555454">
                                                        {total_paid}
                                                    </font>
                                                </td>
                                                <td width="10" style="color:#333;padding:0">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </font>
                    </td>
                </tr>
                <tr>
                    <td class="space_footer" style="padding:0!important">&nbsp;</td>
                </tr>
                <tr>
                    <td class="footer" style="border-top:4px solid #333333;padding:7px 0">
                        <span><a href="{shop_url}" style="color:#337ff1">{shop_name}</a></span>
                    </td>
                </tr>
            </table>
        </td>
        <td class="space" style="width:20px;padding:7px 0">&nbsp;</td>
    </tr>
</table>
</body>
</html>