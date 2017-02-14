<?php


namespace Sav;


use Fournisseur\FournisseurUtil;

class SavAjaxFormInsert
{


    public static function printForm()
    {

        $fournisseurs = FournisseurUtil::getId2Labels();

        ?>
        <table>
            <tr>
                <td>Fournisseur</td>
                <td>
                    <select name="fournisseur">
                        <?php foreach ($fournisseurs as $id => $label): ?>
                            <option value="<?php echo $id; ?>"><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

}