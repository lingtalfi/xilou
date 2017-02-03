<?php

use Icons\Icons;
use IconsViewer\IconsViewerUtil;

$icons = IconsViewerUtil::getIconsList();
$ll = "modules/iconsViewer/iconsViewer";
?>
<div class="pad">
    <table class="bigtable withlines padded lastcolumnbigger">
        <tr>
            <th><?php echo __("Identifier", $ll); ?></th>
            <th><?php echo __("Icon", $ll); ?></th>
        </tr>
        <?php foreach ($icons as $name): ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td><?php Icons::printIcon($name); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>