<table id="addresses-tab" cellspacing="0" cellpadding="0">
    <tr>
        <td><span class="bold"> </span>
            <br/>
            <br/><?php echo $v->shop_address; ?>
        </td>
        <td>
            <?php if ($v->delivery_address): ?>
                <span class="bold"><?php echo $v->text_delivery_address; ?></span>
                <br>
                <br><?php echo $v->delivery_address; ?>
            <?php endif; ?>
        </td>
        <td><span class="bold"><?php echo $v->text_billing_address; ?></span>
            <br>
            <br><?php echo $v->billing_address; ?>
        </td>
    </tr>
</table>
