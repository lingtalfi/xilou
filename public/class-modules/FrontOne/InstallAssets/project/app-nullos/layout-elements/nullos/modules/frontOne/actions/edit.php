<?php

use Shared\FrontOne\Article;
use Shared\FrontOne\ArticleCrud\ArticleCrudUtil;
use Shared\FrontOne\ArticleCrud\ArticleScannerUtil;
use Shared\FrontOne\ArticleCrud\Exception\ArticleCrudException;
use Shared\FrontOne\FrontOneConfig;
use QuickForm\ControlFactory\InertControlFactory;


?>
<div class="tac bignose install-page">
    <h3><?php echo __("Articles", LL); ?></h3>
    <p>
        <a href="<?php
        echo url(FrontOneConfig::getArticlesUri(), [
            'action' => 'list',
        ]);
        ?>"><?php echo __("List of articles", LL); ?></a>
    </p>
</div>
<?php


$defaultValues = [
    'position' => 0,
    'active' => 1,
    'protected' => 0,
];
$ric = null;
if (array_key_exists('ric', $_GET)) {
    $ric = $_GET['ric'];
    $list = ArticleScannerUtil::getAllArticles();
    $article = $list->getArticle($ric);
    if (null !== $article) {
        $defaultValues = [
            'anchor' => $article->getAnchor(),
            'label' => $article->getLabel(),
            'content' => $article->getContent(),
            'position' => $article->getPosition(),
            'active' => (int)$article->isActive(),
            'protected' => (int)$article->isProtected(),
        ];
    }
}


$models = ArticleScannerUtil::getModels();
array_unshift($models, __("Choose a model to inject...", LL));


$form = \QuickFormZ::create();
$form->addControlFactory(InertControlFactory::create());

$form->title = __("Article form", LL);
$form->labels = [
    'anchor' => __("Anchor", LL),
    'label' => __("Label", LL),
    'content' => __("Content", LL),
    'position' => __("Position", LL),
    'active' => __("Active", LL),
    'protected' => __("Protected", LL),
];

$form->defaultValues = $defaultValues;

$id = 'fo_model_' . rand(0, 10000);
$targetId = $id . '-t';
$form->addControl('anchor')->type('text', ['focus' => true])
    ->addConstraint('required')
    ->addConstraint('regex', '![^a-zA-Z0-9-_]!', __("The anchor can only contain alphanumeric chars, dashes and underscores", LL));
$form->addControl('label')->type('text');
$form->addControl('content')->type('message', [
    'id' => $targetId,
]);
$form->addControl('any')->type('inertSelect', $models, [
    'id' => $id,
])->label("");
$form->addControl('position')->type('text');
$form->addControl('active')->type('checkbox', "active");
$form->addControl('protected')->type('checkbox', "protected");


$form->formTreatmentFunc = function (array $formattedValues, &$msg) use ($ric) {

    $article = new Article();
    $article->setAnchor($formattedValues['anchor']);
    $article->setLabel($formattedValues['label']);
    $article->setContent($formattedValues['content']);
    $article->setPosition($formattedValues['position']);
    $article->setIsActive((bool)$formattedValues['active']);
    $article->setIsProtected((bool)$formattedValues['protected']);

    try {
        if (null === $ric) {
            ArticleCrudUtil::create($article);
        } else {
            ArticleCrudUtil::replace($ric, $article);
        }


    } catch (ArticleCrudException $e) {
        $msg = $e->getMessage();
        return false;
    } catch (\Exception $e) {
        $msg = Helper::defaultLogMsg();
        Logger::log($e, "frontOne.createArticle");
        return false;
    }

    $msg = __("The article was created successfully", LL);
    return true;
};

$form->play();


?>
<script>
    //------------------------------------------------------------------------------/
    // INJECT MODEL INTO TEXTAREA
    //------------------------------------------------------------------------------/
    var target = document.getElementById('<?php echo $targetId; ?>');
    var select = document.getElementById('<?php echo $id; ?>');
    var url = "/services/modules/frontOne/get-article-model.php";
    select.addEventListener('change', function () {
        var value = this.value;
        var data = {
            model: value
        };
        var success = function (text) {
            target.value = text;
        };
        z.ajaxPost(url, data, success);
    });
</script>
