<?php


use Shared\FrontOne\FrontOneUtil;

$ll = 'modules/frontOne/frontOne';
Spirit::set('ll', $ll); // for linkt
define('LL', $ll); // translation context


?>
<div class="tac bignose install-page">
    <h3><?php echo __("Social links configuration", LL); ?></h3>
    <p>
        <?php echo __("Use this page to configure your social links.", LL); ?>
    </p>
    <p>
        <a href="<?php echo doclink('modules/frontone-module/social-page.md'); ?>"><?php echo __("Need help?", LL); ?></a>
    </p>
    <div>
        <?php


        $values = FrontOneUtil::getSocialLinks();

        $form = \QuickFormZ::create();

        $form->title = __("Social links form", LL);
        $form->labels = [
            'icon' => __("Icon", LL),
            // seo
            'seoTitle' => __("Seo title", LL),

            // texts
            'title' => __("Title", LL),
            'headerParagraph' => __("Header paragraph", LL),
            'footerParagraph' => __("Footer paragraph", LL),
        ];

        $form->defaultValues = [
            'facebook' => $values['facebook'],
            'github' => $values['github'],
            'instagram' => $values['instagram'],
            'twitter' => $values['twitter'],
        ];


        $form->addControl('facebook')->type('text');
        $form->addControl('github')->type('text');
        $form->addControl('instagram')->type('text');
        $form->addControl('twitter')->type('text');


        $form->formTreatmentFunc = function (array $formattedValues, &$msg) {
            FrontOneUtil::setSocialLinks($formattedValues);
            return true;
        };

        $form->play();

        ?>
    </div>
</div>
