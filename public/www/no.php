<?php


use Mail\OrderConfMail;
use Mail\OrderProviderConfMail;

require_once __DIR__ . "/../init.php";


//------------------------------------------------------------------------------/
// EMBED A FILE
//------------------------------------------------------------------------------/


/**
 * <td height=43 class=xl71 width=53 style='height:32.0pt;width:40pt'>ref</td>
 * <td class=xl71 width=0 style='border-left:none'>&nbsp;</td>
 * <td class=xl95 width=188 style='border-left:none;width:141pt'>FRENCH PRODUCT
 * NAME</td>
 * <td class=xl73 width=159 style='border-left:none;width:119pt'>PRODUCT</td>
 * <td class=xl71 width=221 style='border-left:none;width:166pt'>PHOTOS</td>
 * <td class=xl73 width=324 style='border-left:none;width:243pt'>FEATURES</td>
 * <td class=xl72 width=243 style='border-left:none;width:182pt'>LOGO &amp; LOGO
 * SIZE</td>
 * <td class=xl83 width=156 style='border-left:none;width:117pt'>PACKING</td>
 * <td class=xl97 width=93 style='border-left:none;width:70pt'>FRENCH REFERENCE</td>
 * <td class=xl103 width=161 style='border-left:none;width:121pt'>EAN</td>
 * </tr>
 * <tr height=132 style='mso-height-source:userset;height:99.0pt'>
 * <td height=132 class=xl69 style='height:99.0pt;border-top:none'>1491</td>
 * <td class=xl69 style='border-top:none;border-left:none'>&nbsp;</td>
 * <td class=xl96 width=188 style='border-top:none;border-left:none;width:141pt'>1
 * - CORDE DE TIRAGE</td>
 * <td class=xl77 width=159 style='border-top:none;border-left:none;width:119pt'>TRICEPS
 * ROPE WITH RUBBER ENDS</td>
 * ...
 *
 * <x:CF>Bitmap</x:CF>
 * <x:AutoPict/>
 * </x:ClientData>
 * </v:shape><![endif]--><![if !vml]><span style='mso-ignore:vglayout;
 * position:absolute;z-index:108;margin-left:48px;margin-top:4px;width:104px;
 * height:120px'><img width=104 height=120 src="Products-list.fld/image002.png"
 * alt="Capture dÕŽcran 2015-10-05 ˆ 09.29.23.png" v:shapes="Image_x0020_205"></span><![endif]><span
 * style='mso-ignore:vglayout2'>
 * <table cellpadding=0 cellspacing=0>
 * <tr>
 * <td height=132 class=xl71 width=221 style='height:99.0pt;border-top:none;
 * border-left:none;width:166pt'>&nbsp;</td>
 * </tr>
 * </table>
 * </span></td>
 * <td class=xl73 width=324 style='border-top:none;border-left:none;width:243pt'>Dimensions:
 * 70 cm</td>
 * <td class=xl72 width=243 style='border-top:none;border-left:none;width:182pt'>&nbsp;</td>
 * <td class=xl83 width=156 style='border-top:none;border-left:none;width:117pt'>0,85
 * kgs</td>
 * <td class=xl97 width=93 style='border-top:none;border-left:none;width:70pt'>1491</td>
 * <td class=xl103 style='border-top:none;border-left:none'>3760223791419</td>
 * </tr>
 */


/**
 * tr td.xl69:first ref
 * tr td.xl96 french product name
 * xl177: product
 * xl73: features
 * xl72: logo
 * xl83: packing
 * xl103: ean
 *
 */