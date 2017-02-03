<?php


use Shared\FrontOne\FrontOneUtil;

$ll = 'modules/frontOne/frontOne';
Spirit::set('ll', $ll); // for linkt
define('LL', $ll); // translation context


?>
<div class="tac bignose install-page">
    <h3><?php echo __("Theme configuration", LL); ?></h3>
    <p>
        <?php echo __("Use this page to configure your theme.", LL); ?>
    </p>
    <p>
        <a href="<?php echo doclink('modules/frontone-module/theme-page.md'); ?>"><?php echo __("Need help?", LL); ?></a>
    </p>
    <div>
        <?php


        $theme = FrontOneUtil::getTheme();

        $form = \QuickFormZ::create();

        $form->title = __("Theme form", LL);
        $form->labels = [
            'icon' => __("Icon", LL),
            // seo
            'seoTitle' => __("Seo title", LL),

            // texts
            'title' => __("Title", LL),
            'headerParagraph' => __("Header paragraph", LL),
            'footerParagraph' => __("Footer paragraph", LL),
        ];

        $form->addFieldset(__('Seo', LL), [
            'seoTitle',
        ]);
        $form->addFieldset(__('Theme texts', LL), [
            'title',
            'headerParagraph',
            'footerParagraph',
        ]);
        $form->defaultValues = [
            'seoTitle' => $theme['seoTitle'],
            'title' => $theme['seoTitle'],
            'headerParagraph' => $theme['headerParagraph'],
            'footerParagraph' => $theme['footerParagraph'],
            'icon' => $theme['icon'],
        ];


        $form->addControl('icon')->type('text')->hint("http://fontawesome.io/icons/");
        $form->addControl('seoTitle')->type('text');
        $form->addControl('title')->type('text');
        $form->addControl('headerParagraph')->type('message');
        $form->addControl('footerParagraph')->type('message');


        $form->formTreatmentFunc = function (array $formattedValues, &$msg) {
            $theme = $formattedValues;
            FrontOneUtil::setTheme($theme);
            return true;
        };

        $form->play();

        ?>
    </div>
</div>
