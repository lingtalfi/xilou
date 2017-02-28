<?php


use AdminTable\Listable\ArrayListable;
use AdminTable\NullosAdminTable;
use AdminTable\Table\ListWidgets;
use Shared\FrontOne\ArticleCrud\ArticleCrudUtil;
use Shared\FrontOne\ArticleCrud\ArticleScannerUtil;
use Shared\FrontOne\ArticleCrud\Exception\ArticleCrudCannotDeleteProtectedException;
use Shared\FrontOne\FrontOneConfig;
use FrontOne\Util\ArticlesUtil;
use Layout\Goofy;


?>
    <div class="tac bignose install-page">
        <h3><?php echo __("Articles", LL); ?></h3>
        <p>
            <a href="<?php
            echo url(FrontOneConfig::getArticlesUri(), [
                'action' => 'edit',
            ]);
            ?>"><?php echo __("Create a new article", LL); ?></a>
        </p>
    </div>
<?php


$list = ArticleScannerUtil::getAllArticles();
$arr = ArticlesUtil::articlesListToArray($list);
$listable = ArrayListable::create()->setArray($arr)->searchColumns([
    'label',
    'content',
    'anchor',
    'position',
]);
$table = NullosAdminTable::create()
    ->setRic(['anchor'])
    ->setWidgets(ListWidgets::create()
//        ->disableMultipleActions()
    )
    ->setActionLink('delete', __("Delete", LL), function ($ric) use ($arr, $listable) {
        try {
            $anchor = $ric['anchor'];
            ArticleCrudUtil::delete($anchor);
            foreach ($arr as $k => $item) {
                if ($anchor === $item['anchor']) {
                    unset($arr[$k]);
                }
            }
            $listable->setArray($arr);
        } catch (ArticleCrudCannotDeleteProtectedException $e) {
            Goofy::alertError($e->getMessage());
        } catch (\Exception $e) {
            Logger::log($e, "frontOne.delete");
            Goofy::alertError(Helper::defaultLogMsg());
        }
    })
    ->setExtraColumn('edit', '<a class="action-link" href="' . url(FrontOneConfig::getArticlesUri(), [
            'action' => 'edit',
            'ric' => '{ric}',
        ], true, false) . '">' . __('Edit', LL) . '</a>')
    ->setTransformer('edit', function ($v, $item, $ricValue) {
        return str_replace('{ric}', $ricValue, $v);
    })
    ->setMultipleActionHandler('deleteAll', __('Delete All', LL), function (array $rics) use ($arr, $listable) {
        try {
            foreach ($rics as $ric) {
                $anchor = $ric['anchor'];
                ArticleCrudUtil::delete($anchor);
                foreach ($arr as $k => $item) {
                    if ($anchor === $item['anchor']) {
                        unset($arr[$k]);
                    }
                }
                $listable->setArray($arr);
            }
        } catch (ArticleCrudCannotDeleteProtectedException $e) {
            Goofy::alertError($e->getMessage());
        } catch (\Exception $e) {
            Logger::log($e, "frontOne.delete");
            Goofy::alertError(Helper::defaultLogMsg());
        }
    }, true)
    ->setListable($listable);
$table->setTransformer('content', function ($v, $item) {
    return substr(htmlspecialchars($v), 0, 30) . '...';
});
$table->displayTable();
