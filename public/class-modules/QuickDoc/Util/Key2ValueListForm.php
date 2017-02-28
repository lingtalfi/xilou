<?php


namespace QuickDoc\Util;


/**
 * This helper uses Key2ValueForm's style to display QuickDoc lists for links|images.
 */
class Key2ValueListForm
{


    private $_mode;
    private $_alpha;
    private $_groupByFiles;
    private $_titles;
    /**
     * array:
     *      - 0: foundList
     *      - 1: unfoundList
     */
    private $_mappings;
    private $onPostAfterMsg;


    private $key;
    private $displayTopSubmitBtn;

    private function __construct()
    {
        $this->_mode = 'unresolved';
        $this->_alpha = true;
        $this->_groupByFiles = false;
        $this->onPostAfterMsg = null;
        $this->key = 'tag';
        $this->displayTopSubmitBtn = false;
    }

    public static function create()
    {
        return new self();
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

    public function mappings(array $mappings)
    {
        $this->_mappings = $mappings;
        return $this;
    }

    public function titles($unresolved, $resolved, $all)
    {
        $this->_titles = [
            'unresolved' => $unresolved,
            'resolved' => $resolved,
            'all' => $all,
        ];
        return $this;
    }

    //--------------------------------------------
    //
    //--------------------------------------------
    public function onSubmit($func)
    {
        if (array_key_exists($this->key, $_POST)) {
            $all = $_POST[$this->key];
            $foundList = [];
            $unfoundList = [];
            foreach ($all as $file => $items) {
                foreach ($items as $capture => $value) {
                    if ('' === trim($value)) {
                        $unfoundList[$file][] = [$capture];
                    } else {
                        $foundList[$file][] = [$capture, $value];
                    }
                }
            }
            $this->onPostAfterMsg = call_user_func($func, $foundList, $unfoundList);
        }
    }


    public function onPreferencesChange($func)
    {
        if (array_key_exists("mode", $_GET)) {
            $mode = (array_key_exists('mode', $_GET)) ? $_GET['mode'] : $this->_mode;
            $alpha = (array_key_exists('alpha', $_GET)) ? (bool)$_GET['alpha'] : $this->_alpha;
            $group = (array_key_exists('group', $_GET)) ? (bool)$_GET['group'] : $this->_groupByFiles;
            $newPrefs = [
                'mode' => $mode,
                'alpha' => $alpha,
                'group' => $group,
            ];
            call_user_func($func, $newPrefs);
        }
    }

    public function display()
    {

        $lists = $this->_mappings;


        $mode = (array_key_exists('mode', $_GET)) ? $_GET['mode'] : $this->_mode;
        $alpha = (array_key_exists('alpha', $_GET)) ? (bool)$_GET['alpha'] : $this->_alpha;
        $group = (array_key_exists('group', $_GET)) ? (bool)$_GET['group'] : $this->_groupByFiles;

        $cssId = 'quickdoc_k2v_' . rand(0, 10000);
        $modeId = $cssId . '_s';
        $alphaId = $cssId . '_a';
        $groupId = $cssId . '_f';
        $alphaInputId = $cssId . '_i';
        $groupInputId = $cssId . '_j';
        $blackBoxId = $cssId . '_h';

        $sel = 'selected="selected"';
        $checked = 'checked="checked"';


        // empty?
        $isEmpty = true;
        $countUnfound = count($lists['unfound']);
        $countFound = count($lists['found']);
        $countAll = $countFound + $countUnfound;
        if (
            ('unresolved' === $mode && $countUnfound > 0) ||
            ('resolved' === $mode && $countFound > 0) ||
            ('all' === $mode && $countAll > 0)
        ) {
            $isEmpty = false;
        }

        ?>
        <div class="body-top">
            <form action="" method="get" id="<?php echo $cssId; ?>">
                <div class="box">
                    <select name="mode" id="<?php echo $modeId; ?>">
                        <option <?php echo ('unresolved' === $mode) ? $sel : ''; ?> value="unresolved">Show unresolved
                            only
                        </option>
                        <option <?php echo ('resolved' === $mode) ? $sel : ''; ?> value="resolved">Show resolved only
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
                    $getLeftOver = array_diff(array_keys($_GET), ['mode', 'alpha', 'group']);
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
            var modeSelector = document.getElementById("<?php echo $modeId; ?>");
            var alphaCheckbox = document.getElementById("<?php echo $alphaId; ?>");
            var groupCheckbox = document.getElementById("<?php echo $groupId; ?>");
            var alphaInput = document.getElementById("<?php echo $alphaInputId; ?>");
            var groupInput = document.getElementById("<?php echo $groupInputId; ?>");


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
            $this->displayList($lists, $mode, $alpha, $group);
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
    private function displayList(array $lists, $mode, $alpha, $groupByFiles)
    {
        $foundList = $lists['found'];
        $unfoundList = $lists['unfound'];
        $theList = $this->getTheList($foundList, $unfoundList, $mode, $groupByFiles, $alpha);

        if ('all' === $mode) {
            $selected = $theList;
        } else {
            $foundList = $theList[0];
            $unfoundList = $theList[1];
            $selected = ('unresolved' === $mode) ? $unfoundList : $foundList;
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
                    <td><?php echo $item[1]; ?></td>
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

    private function displayHeaderRow()
    {
        ?>
        <tr>
            <th>Keys</th>
            <th>Values</th>
        </tr>
        <?php
    }


    private function getTheList($foundList, $unfoundList, $mode, $groupByFiles, $alpha)
    {
        /**
         * Nomenclature
         * ---------------
         *
         * item: <foundItem>|<unfoundItem>
         * foundItem: array containing 5 entries;
         *      - 0: file path
         *      - 1: capture
         *      - 2: value
         *
         * unfoundItem: array containing 3 entries;
         *      - 0: file path
         *      - 1: capture
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
         *              - one array containing 2 entries (found and unfound):
         *                   0: <item>s
         *                   1: <item>s
         *      - if files=true
         *              - one array containing 2 entries (found and unfound):
         *                   0: array of file => <item>s
         *                   1: array of file => <item>s
         *
         *
         *
         *
         * alpha sorting operates only within the smallest container array (either a file, or the
         * unfound/found category).
         *
         *
         */
        $theList = [];
        foreach ($foundList as $file => &$_items) {
            foreach ($_items as &$_item) {
                array_unshift($_item, $file);
            }
        }
        foreach ($unfoundList as $file => &$_items2) {
            foreach ($_items2 as &$_item2) {
                array_unshift($_item2, $file);
            }
        }
        $itemSort = function ($item1, $item2) {
            return ($item1[1] > $item2[1]);
        };

        if ('all' === $mode) {
            if (true === $groupByFiles) {
                $theList = array_merge_recursive($foundList, $unfoundList);
                if (true === $alpha) {
                    foreach ($theList as $file => &$items) {
                        usort($items, $itemSort);
                    }
                }
            } else {
                foreach ($foundList as $items) {
                    $theList = array_merge($theList, $items);
                }
                foreach ($unfoundList as $items) {
                    $theList = array_merge($theList, $items);
                }
                if (true === $alpha) {
                    usort($theList, $itemSort);
                }
            }
        } else {
            $theFound = [];
            $theUnfound = [];
            if (true === $groupByFiles) {
                $theFound = $foundList;
                $theUnfound = $unfoundList;

                if (true === $alpha) {
                    foreach ($theFound as $file => &$items) {
                        usort($items, $itemSort);
                    }
                    foreach ($theUnfound as $file => &$items2) {
                        usort($items2, $itemSort);
                    }
                }
            } else {
                foreach ($foundList as $items) {
                    $theFound = array_merge($theFound, $items);
                }
                foreach ($unfoundList as $items) {
                    $theUnfound = array_merge($theUnfound, $items);
                }
                if (true === $alpha) {
                    usort($theFound, $itemSort);
                    usort($theUnfound, $itemSort);
                }
            }
            $theList = [
                $theFound,
                $theUnfound,
            ];

        }
        return $theList;
    }

}