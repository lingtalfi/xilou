<?php

use Layout\Goofy;
use QuickDoc\QuickDocUtil;


$success = false;
if (array_key_exists('rescan', $_POST)) {
    QuickDocUtil::scanDoc();
    $success = true;
}

$success2 = false;
if (array_key_exists('regenerate', $_POST)) {
    QuickDocUtil::copyDoc();
    $success2 = true;
}


?>
<div class="tac quickdoc">
    <section class="boxy">
        <?php
        if (true === $success) {
            Goofy::alertSuccess(__("The dictionary has been successfully rescanned", LL), false, false);
        } elseif (true === $success2) {
            Goofy::alertSuccess(__("The doc has been successfully regenerated", LL), false, false);
        }
        ?>
        <?php echo __("Click the button below to rescan the dictionary", LL); ?>
        <br>
        <form action="" method="post">
            <button type="submit" class="big" name="rescan"><?php echo __("Rescan", LL); ?></button>
        </form>
    </section>
    <section class="boxy">
        <?php echo __("Click the button below to generate the doc", LL); ?>
        <br>
        <form action="" method="post">
            <button type="submit" class="big" name="regenerate"><?php echo __("Regenerate", LL); ?></button>
        </form>
    </section>
</div>