<?php


use Article\Article;
use Crud\CrudModule;

$form = CrudModule::getForm("zilu.article", ['id']);


$form->labels = [
    "id" => "id",
    "reference_lf" => "reference lf",
    "reference_hldp" => "reference hldp",
    "descr_fr" => "descr fr",
    "descr_en" => "descr en",
    "ean" => "ean",
    "photo" => "photo",
    "logo" => "logo",
    "long_desc_en" => "long desc en",
];


$form->title = "Article";


$form->addControl("reference_lf")->type("text")
    ->addConstraint("required");
$form->addControl("reference_hldp")->type("text");
$form->addControl("descr_fr")->type("message");
$form->addControl("descr_en")->type("message");
$form->addControl("ean")->type("text");
$form->addControl("photo")->type("text");


$logoList = Article::getLogoList();
$logoOptionsList = $logoList;
array_walk($logoOptionsList, function (&$v) {
    $v = [
        "data-src" => $v,
    ];
});




$form->addControl("logo")->type("select", $logoList, [], $logoOptionsList);
$form->addControl("long_desc_en")->type("message");


$form->display();

?>
<script>
    $(document).ready(function () {
        var jSelect = $("select[name='logo']");


        $.widget("custom.iconselectmenu", $.ui.selectmenu, {
            _renderItem: function (ul, item) {
                var li = $("<li style='display: flex; justify-content: space-between'><img  src='"+ item.element.attr("data-src") +"'><span>"+ item.label +"</span></li>");
                return li.appendTo(ul);
            }
        });

        jSelect
            .iconselectmenu({
                width: 500
            })
            .iconselectmenu("menuWidget")
            .addClass("ui-menu-icons avatar");


    });
</script>
