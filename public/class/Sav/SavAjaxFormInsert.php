<?php


namespace Sav;


class SavAjaxFormInsert
{


    public static function printForm($ricString)
    {
        ?>
        <form id="sav-ajax-form-insert-form" action="">
            <input type="hidden" name="ric" value="<?php echo $ricString; ?>">
            <table>
                <tr>
                    <td>Nombre de produits défectueux</td>
                    <td>
                        <input type="text" id="nb_produits_defec" name="nb_produits_defec" value="">
                    </td>
                </tr>
                <tr>
                    <td>Date notif</td>
                    <td>
                        <input type="text" id="date_notif" name="date_notif" value="">
                    </td>
                </tr>
                <tr>
                    <td>Demande de remboursement</td>
                    <td>
                        <input type="text" id="demande_remboursement" name="demande_remboursement" value="">
                    </td>
                </tr>
                <tr>
                    <td>Montant remboursé</td>
                    <td>
                        <input type="text" id="montant_rembourse" name="montant_rembourse" value="">
                    </td>
                </tr>
                <tr>
                    <td>Pourcentage remboursé</td>
                    <td>
                        <input type="text" id="pourcentage_rembourse" name="pourcentage_rembourse" value="">
                    </td>
                </tr>
                <tr>
                    <td>Date remboursement</td>
                    <td>
                        <input type="text" id="date_remboursement" name="date_remboursement" value="">
                    </td>
                </tr>
                <tr>
                    <td>Forme</td>
                    <td>
                        <input type="text" id="forme" name="forme" value="">
                    </td>
                </tr>
                <tr>
                    <td>Statut</td>
                    <td>
                        <input type="text" id="statut" name="statut" value="">
                    </td>
                </tr>
                <tr>
                    <td>Photo</td>
                    <td>
                        <input id="photo" type="file" name="photo">
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div id="progress" class="progress">
                            <div class="progress-bar progress-bar-success"></div>
                        </div>
                        <!-- The container for the uploaded files -->
                        <div id="files" class="files"></div>
                    </td>
                </tr>
                <tr>
                    <td>Avancement</td>
                    <td>
                        <textarea id="avancement" name="avancement"></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <button id="sav-ajax-form-submit-btn" type="submit">Ajouter</button>
                    </td>
                </tr>
            </table>
            <script>
                $(function () {
                    $("#date_notif").datepicker({
                        dateFormat: "yy-mm-dd"
                    });
                    $("#date_remboursement").datepicker({
                        dateFormat: "yy-mm-dd"
                    });
                });
            </script>
            <script src="/libs/jquery-file-upload/js/jquery.iframe-transport.js"></script>
            <script src="/libs/jquery-file-upload/js/jquery.fileupload.js"></script>
            <script>
                /*jslint unparam: true */
                /*global window, $ */
                $(function () {
                    'use strict';
                    // Change this to the location of your server-side upload handler:
                    var url = '/libs/jquery-file-upload/server/php/upload.php';
                    $('#photo').fileupload({
                        url: url,
                        dataType: 'json',
                        done: function (e, data) {
                            $.each(data.result.photo, function (index, file) {
//                                $('<p/>').text(file.name).appendTo('#files');
                                $('<p/>').html('<img src="' + file.thumbnailUrl + '">').appendTo('#files');
                                $('<p/>').html('<input type="hidden" name="photo" value="' + file.thumbnailUrl + '">').appendTo('#files');
                            });
                        },
                        progressall: function (e, data) {
                            var progress = parseInt(data.loaded / data.total * 100, 10);
                            $('#progress .progress-bar').css(
                                'width',
                                progress + '%'
                            );
                        }
                    }).prop('disabled', !$.support.fileInput)
                        .parent().addClass($.support.fileInput ? undefined : 'disabled');


                    $("#sav-ajax-form-submit-btn").on('click', function (e) {
                        e.preventDefault();
                        var jForm = $("#sav-ajax-form-insert-form");
                        $.getJSON('/services/zilu.php?action=sav-transform-insert&' + jForm.serialize(), function (data) {
                            if ('ok' === data) {
                                $("#sav-transform-dialog").dialog('close');
                                window.location.reload(true);
                            }
                        });
                    });

                });
            </script>
        </form>
        <?php
    }

}