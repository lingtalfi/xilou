<?php


namespace Linguist\Util;


/**
 * This helper uses Key2ValueForm's style to display Linguist translate lists
 */
class LinguistKey2ValueListForm
{


    private $_lang;
    private $_mode;
    private $_alpha;
    private $_groupByFiles;
    private $_titles;
    /**
     * array of file => items
     */
    private $_definitionItems;
    private $onPostAfterMsg;


    private $key;
    private $displayTopSubmitBtn;

    private function __construct()
    {
        $this->_lang = 'en';
        $this->_mode = 'modified'; // modified|unmodified|all
        $this->_alpha = true;
        $this->_groupByFiles = false;
        $this->onPostAfterMsg = null;
        $this->key = 'tag';
        $this->displayTopSubmitBtn = false;
        $this->_definitionItems = null;
    }

    public static function create()
    {
        return new self();
    }

    public function lang($lang)
    {
        $this->_lang = $lang;
        return $this;
    }

    public function mode($mode)
    {
        $this->_mode = $mode;
        return $this;
    }

    public function alpha($alpha)
    {
        $this->_alpha = (bool)$alpha;
        return $this;
    }

    public function groupByFiles($groupByFiles)
    {
        $this->_groupByFiles = (bool)$groupByFiles;
        return $this;
    }

    /**
     * func takes the curLang as parameter
     * and returns the definitionItems array.
     */
    public function definitionItems($func)
    {
        $this->_definitionItems = $func;
        return $this;
    }

    public function titles($modified, $unmodified, $all)
    {
        $this->_titles = [
            'modified' => $modified,
            'unmodified' => $unmodified,
            'all' => $all,
        ];
        return $this;
    }


    public function getLang()
    {
        $lang = (array_key_exists('curlang', $_GET)) ? $_GET['curlang'] : $this->_lang;
        return $lang;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    public function onSubmit($func)
    {
        if (array_key_exists($this->key, $_POST)) {
            $all = $_POST[$this->key];
            $lang = $this->getLang();
            $this->onPostAfterMsg = call_user_func($func, $lang, $all);
        }
    }


    public function onPreferencesChange($func)
    {
        if (array_key_exists("mode", $_GET)) {
            $lang = $this->getLang();
            $mode = (array_key_exists('mode', $_GET)) ? $_GET['mode'] : $this->_mode;
            $alpha = (array_key_exists('alpha', $_GET)) ? (bool)$_GET['alpha'] : $this->_alpha;
            $group = (array_key_exists('group', $_GET)) ? (bool)$_GET['group'] : $this->_groupByFiles;
            $newPrefs = [
                'mode' => $mode,
                'alpha' => $alpha,
                'group' => $group,
            ];
            call_user_func($func, $lang, $newPrefs);
        }
    }


    public function displayHead()
    {

        $lang = $this->getLang();
        $mode = (array_key_exists('mode', $_GET)) ? $_GET['mode'] : $this->_mode;
        $alpha = (array_key_exists('alpha', $_GET)) ? (bool)$_GET['alpha'] : $this->_alpha;
        $group = (array_key_exists('group', $_GET)) ? (bool)$_GET['group'] : $this->_groupByFiles;


        $cssId = 'quickdoc_k2v_' . rand(0, 10000);
        $langId = $cssId . '_k';
        $modeId = $cssId . '_s';
        $alphaId = $cssId . '_a';
        $groupId = $cssId . '_f';
        $alphaInputId = $cssId . '_i';
        $groupInputId = $cssId . '_j';
        $blackBoxId = $cssId . '_h';

        $sel = 'selected="selected"';
        $checked = 'checked="checked"';


        $langs = LinguistScanner::getLangNames();

        ?>
        <div class="body-top">
            <form action="" method="get" id="<?php echo $cssId; ?>">
                <div class="box">
                    <select name="curlang" id="<?php echo $langId; ?>">
                        <?php foreach ($langs as $name): ?>
                            <option <?php echo ($lang === $name) ? $sel : ''; ?>
                                value="<?php echo htmlspecialchars($name); ?>"><?php echo $name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="box">
                    <select name="mode" id="<?php echo $modeId; ?>">
                        <option <?php echo ('unmodified' === $mode) ? $sel : ''; ?> value="unmodified">Show unmodified
                            only
                        </option>
                        <option <?php echo ('modified' === $mode) ? $sel : ''; ?> value="modified">Show modified only
                        </option>
                        <option <?php echo ('all' === $mode) ? $sel : ''; ?> value="all">Show all</option>
                    </select>
                </div>
                <div class="box">
                    <label>
                        <input <?php echo (true === $group) ? $checked : ''; ?> type="checkbox"
                                                                                id="<?php echo $groupId; ?>">
                        <input type="hidden" name="group" value="<?php echo (int)$group; ?>"
                               id="<?php echo $groupInputId; ?>">
                        <span>grouped by files</span>
                    </label>
                </div>
                <div class="box">
                    <label>
                        <input <?php echo (true === $alpha) ? $checked : ''; ?> type="checkbox"
                                                                                id="<?php echo $alphaId; ?>">
                        <input type="hidden" name="alpha" value="<?php echo (int)$alpha; ?>"
                               id="<?php echo $alphaInputId; ?>">
                        <span>alphabetical order</span>
                    </label>
                </div>
                <div id="<?php echo $blackBoxId; ?>" style="display: none">
                    <?php
                    $getLeftOver = array_diff(array_keys($_GET), ['curlang', 'mode', 'alpha', 'group']);
                    foreach ($getLeftOver as $name) {
                        $value = $_GET[$name];
                        ?>
                        <input type="hidden"
                               name="<?php echo htmlspecialchars($name); ?>"
                               value="<?php echo htmlspecialchars($value); ?>"
                            />
                    <?php
                    }
                    ?>
                </div>
            </form>
        </div>
        <script>
            var form = document.getElementById("<?php echo $cssId; ?>");
            var langSelector = document.getElementById("<?php echo $langId; ?>");
            var modeSelector = document.getElementById("<?php echo $modeId; ?>");
            var alphaCheckbox = document.getElementById("<?php echo $alphaId; ?>");
            var groupCheckbox = document.getElementById("<?php echo $groupId; ?>");
            var alphaInput = document.getElementById("<?php echo $alphaInputId; ?>");
            var groupInput = document.getElementById("<?php echo $groupInputId; ?>");


            langSelector.addEventListener('change', function () {
                form.submit();
            });

            modeSelector.addEventListener('change', function () {
                form.submit();
            });
            alphaCheckbox.addEventListener('change', function () {
                var isNowChecked = alphaCheckbox.checked;
                if (false === isNowChecked) {
                    alphaInput.value = "0";
                }
                else {
                    alphaInput.value = "1";
                }
                form.submit();
            });
            groupCheckbox.addEventListener('change', function () {
                var isNowChecked = groupCheckbox.checked;
                if (false === isNowChecked) {
                    groupInput.value = "0";
                }
                else {
                    groupInput.value = "1";
                }
                form.submit();
            });


        </script>
    <?php
    }

    public function display()
    {

        $lang = $this->getLang();
        $mode = (array_key_exists('mode', $_GET)) ? $_GET['mode'] : $this->_mode;
        $alpha = (array_key_exists('alpha', $_GET)) ? (bool)$_GET['alpha'] : $this->_alpha;
        $group = (array_key_exists('group', $_GET)) ? (bool)$_GET['group'] : $this->_groupByFiles;


        $definitionItems = call_user_func($this->_definitionItems, $lang);

        // empty?
        $isEmpty = true;
        foreach ($definitionItems as $items) {
            if (count($items) > 0) {
                $isEmpty = false;
                break;
            }
        }
        ?>
        <div class="body-content">
            <?php
            if (null !== $this->onPostAfterMsg) {
                echo $this->onPostAfterMsg;
            }
            ?>
            <h3><?php echo $this->_titles[$mode]; ?></h3>


            <?php
            // cache this to know whether or not to display the top submit button
            ob_start();
            $this->displayList($definitionItems, $mode, $alpha, $group);
            $listHtml = ob_get_clean();
            ?>



            <?php if (false === $isEmpty): ?>
                <form action="" method="post" class="key2value-form">
                    <?php if (true === $this->displayTopSubmitBtn): ?>
                        <input type="submit" value="Submit">
                    <?php endif; ?>
                    <?php echo $listHtml; ?>
                    <input type="submit" value="Submit">
                </form>
            <?php else: ?>
                <p>no results</p>
            <?php endif; ?>
        </div>
    <?php
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    private function displayList(array $definitionItems, $mode, $alpha, $groupByFiles)
    {
        $theList = $this->getTheList($definitionItems, $mode, $groupByFiles, $alpha);

        if ('all' === $mode) {
            $selected = $theList;
        } else {
            $modifiedList = $theList[0];
            $unmodifiedList = $theList[1];
            $selected = ('unmodified' === $mode) ? $unmodifiedList : $modifiedList;
        }


        // display top submitBtn?
        $n = count($selected);
        if (
            (true === $groupByFiles && $n > 5) ||
            (false === $groupByFiles && $n > 30)
        ) {
            $this->displayTopSubmitBtn = true;
        }


        if (false === $groupByFiles) {
            $this->displayItemsTable($selected);
        } else {
            $this->displayFile2ItemsTable($selected);
        }
    }


    private function displayItemsTable(array $items)
    {

        $hasPost = (array_key_exists($this->key, $_POST));
        ?>
        <table>
            <?php //$this->displayHeaderRow();
            ?>
            <?php
            foreach ($items as $item):
                $file = $this->protect($item[0]);
                $capture = $this->protect($item[1]);
                $name = $this->key . '[' . $file . '][' . $capture . ']';

                $value = '';
                if (true === $hasPost) {
                    $value = $_POST[$this->key][$file][$capture];
                } else {
                    $value = (array_key_exists(2, $item)) ? $item[2] : '';
                }


                ?>
                <tr>
                    <td><?php echo htmlspecialchars($item[1]); ?></td>
                    <td>
                        <input type="text" name="<?php echo htmlspecialchars($name); ?>"
                               value="<?php echo htmlspecialchars($value); ?>">
                    </td>
                </tr>
            <?php
            endforeach;
            ?>
        </table>
    <?php
    }

    private function displayFile2ItemsTable(array $file2items)
    {
        foreach ($file2items as $file => $items) {
            ?>
            <h4><?php echo $file; ?></h4>
            <?php
            $this->displayItemsTable($items);
        }
    }

    private function protect($m)
    {
        return $m;
    }


    private function getTheList($definitionItems, $mode, $groupByFiles, $alpha)
    {
        /**
         * Nomenclature
         * ---------------
         *
         * item: array containing 4 entries;
         *      - 0: file path
         *      - 1: key
         *      - 2: value
         *      - 3: isSameAsRefLang
         *
         *
         *
         * Results
         * --------------
         * At the end of this big mess,
         * theList will be like this:
         *
         * - if mode = all
         *      - if files=false
         *              - one big array of <item>s
         *      - if files=true
         *              - an array of file => <item>s
         *
         * - if mode != all
         *      - if files=false
         *              - one array containing 2 entries (modified and unmodified):
         *                   0: <item>s
         *                   1: <item>s
         *      - if files=true
         *              - one array containing 2 entries (modified and unmodified):
         *                   0: array of file => <item>s
         *                   1: array of file => <item>s
         *
         *
         *
         *
         * alpha sorting operates only within the smallest container array (either a file, or the
         * unmodified/modified category).
         *
         *
         */
        $theList = [];

        $itemSort = function ($item1, $item2) {
            return ($item1[1] > $item2[1]);
        };

        if ('all' === $mode) {
            if (true === $groupByFiles) {
                $theList = $definitionItems;
                if (true === $alpha) {
                    foreach ($theList as $file => &$items) {
                        usort($items, $itemSort);
                    }
                }
            } else {
                foreach ($definitionItems as $items) {
                    $theList = array_merge($theList, $items);
                }
                if (true === $alpha) {
                    usort($theList, $itemSort);
                }
            }
        } else {
            $theModified = [];
            $theUnmodified = [];

            if (true === $groupByFiles) {


                foreach ($definitionItems as $file => $items) {
                    foreach ($items as $item) {
                        if (true === $item[3]) {
                            $theUnmodified[$file][] = $item;
                        } else {
                            $theModified[$file][] = $item;
                        }
                    }
                }


                if (true === $alpha) {
                    foreach ($theModified as $file => &$items) {
                        usort($items, $itemSort);
                    }
                    foreach ($theUnmodified as $file => &$items2) {
                        usort($items2, $itemSort);
                    }
                }
            } else {
                foreach ($definitionItems as $items) {
                    foreach ($items as $item) {
                        if (true === $item[3]) {
                            $theUnmodified[] = $item;
                        } else {
                            $theModified[] = $item;
                        }
                    }
                }

                if (true === $alpha) {
                    usort($theModified, $itemSort);
                    usort($theUnmodified, $itemSort);
                }
            }
            $theList = [
                $theModified,
                $theUnmodified,
            ];

        }
        return $theList;
    }


}