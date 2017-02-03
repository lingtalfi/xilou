<?php


use QuickDoc\Util\TodoUtil;


$allTodos = TodoUtil::getTodos();


?>
    <div class="body-content">
        <div class="key2value-form">
            <?php foreach ($allTodos as $file => $todos): ?>
                <h4><?php echo $file ?></h4>
                <table>
                    <?php foreach ($todos as $todo): ?>
                        <tr>
                            <td><?php echo $todo; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>
        </div>
    </div>
<?php


