<?php


use Crud\ArrayDataTable;
use Icons\Icons;
use Layout\Goofy;
use LogSlicer\LogSlicer;
use LogWatcher\LogWatcherPreferences;
use LogWatcher\LogWatcherServices;
use LogWatcher\LogWatcherUtil;
use Module\DynamicPreferencesHelper;
use ModuleInstaller\ModuleInstallerUtil;
use PublicException\PublicException;


?>
    <div class="tac bignose install-page">
        <h3><?php echo __("Logs", LL); ?></h3>
    </div>
<?php

$prefs = LogWatcherPreferences::getPreferences();

$changedLog = (array_key_exists('log', $_GET));
$logLabel = DynamicPreferencesHelper::get('log', $prefs);


$logList = LogWatcherUtil::getLogList();

if (null === $logLabel && count($logList) > 0) {
    $logLabel = key($logList);
}

if (null !== $logLabel) {

    $nbLinesPerPage = (int)DynamicPreferencesHelper::get('nlpp', $prefs, 100);
    $nppList = $prefs['nbLinesPerPageList'];
    $logFile = $logList[$logLabel];
    $slicer = LogSlicer::create()
        ->file($logFile)
        ->nbLinesPerPage($nbLinesPerPage);
    $nbPages = (int)$slicer->getNbPages();


    if (array_key_exists('page', $_GET) || false === $changedLog) {
        $page = DynamicPreferencesHelper::get('page', $prefs, $nbPages);
    } else {
        $page = $nbPages;
    }


    if ($page < 1) {
        $page = 1;
    } elseif ($page > $nbPages) {
        $page = $nbPages;
    }
    $pagePrev = $page + 1;
    $pageNext = $page - 1;
    if ($pagePrev > $nbPages) {
        $pagePrev = $nbPages;
    }
    if ($pageNext < 1) {
        $pageNext = 1;
    }

    $lines = $slicer->getPage($page);
    $sel = 'selected="selected"';

    LogWatcherPreferences::setPreferences([
        '_page' => $page,
        '_nlpp' => $nbLinesPerPage,
        '_log' => $logLabel,
    ]);


    ?>
    <style>

        .logcontainer-wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .logcontainer-wrapper .toolbar {
            box-sizing: border-box;
            padding: 5px;
            background: #ddd;
            display: flex;
            justify-content: space-between;
        }

        .logcontainer-wrapper .pagination-container {
            height: 70px;
            background: gray;
            box-sizing: border-box;
            padding: 5px;
            display: flex;
            align-items: center;
        }

        .logcontainer-wrapper .pagination-container .arrowforward {
            margin: 0 10px;
        }

        .logcontainer-wrapper .pagination-container a {
            color: white;
        }

        .logcontainer-wrapper .pagination {
            overflow: auto;
            flex: auto;
            margin: 0 10px;
            display: flex;
            align-items: center;
            height: 70px;
        }

        .logcontainer-wrapper .pagination ul {
            display: flex;
            list-style-type: none;
        }

        .logcontainer-wrapper .pagination a {
            color: #ddd;
            margin: 0 10px;
            text-decoration: none;
            display: flex;
            height: 30px;
            width: 30px;
            align-items: center;
            justify-content: center;
            background: #8F8F8F;

        }

        .logcontainer-wrapper .pagination a:hover,
        .logcontainer-wrapper .pagination a.active {
            background: #aFaFaF;
        }

        .logcontainer {
            overflow: scroll;
            box-sizing: border-box;
            padding: 10px;
            background: black;
            color: white;
            max-height: 600px;
        }
    </style>
    <div class="logcontainer-wrapper">
        <div class="toolbar">
            <form method="get" action="">
                <select id="logwatcher_log_selector" name="log">
                    <option value="0"><?php echo __("Choose a log to watch...", LL); ?></option>
                    <?php foreach ($logList as $label => $file):
                        $s = ($logLabel === $label) ? $sel : '';
                        ?>
                        <option <?php echo $s; ?>
                                value="<?php echo htmlspecialchars($label); ?>"><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <form method="get" action="">
                <label><?php echo __("Nb lines per page", LL); ?></label>
                <select id="logwatcher_nlpp_selector" name="nlpp">
                    <?php foreach ($nppList as $n):
                        $s = ((int)$n === (int)$nbLinesPerPage) ? $sel : '';
                        ?>
                        <option <?php echo $s; ?> value="<?php echo $n; ?>"><?php echo $n; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="hidden" name="page" value="<?php echo $page; ?>">
            </form>
            <script>
                var logSelector = document.getElementById('logwatcher_log_selector');
                var nlppSelector = document.getElementById('logwatcher_nlpp_selector');

                logSelector.addEventListener('change', function () {
                    if ("0" !== this.value) {
                        this.parentNode.submit();
                    }
                });
                nlppSelector.addEventListener('change', function () {
                    this.parentNode.submit();
                });


            </script>
        </div>
        <div class="pagination-container">
            <a href="<?php
            echo url(null, [
                'page' => $pagePrev,
            ], true);
            ?>"><?php Icons::printIcon('arrow-back', 'white'); ?></a>
            <a class="arrowforward" href="<?php
            echo url(null, [
                'page' => $pageNext,
            ], true);
            ?>"><?php Icons::printIcon('arrow-forward', 'white'); ?></a>
            <section class="pagination">
                <ul>
                    <?php for ($i = $nbPages; $i > 0; $i--):
                        $link = url(null, [
                            'page' => $i,
                        ], true);
                        $s = '';
                        if ((int)$page === (int)$i) {
                            $s = ' class="active"';
                        }

                        ?>
                        <li><a<?php echo $s; ?> href="<?php echo $link; ?>"><?php echo $i ?></a></li>
                    <?php endfor; ?>
                </ul>
            </section>
        </div>
        <div class="logcontainer">

            <?php
            echo '<pre>';
            echo implode(PHP_EOL, $lines);
            echo '</pre>';


            //    echo implode('<br>', $lines);
            ?>
        </div>
    </div>
    <?php

} else {
    ?>
    <div class="pad">
        <p>
            <?php echo __("No log file to display. Check the Help tab.", LL); ?>
        </p>
    </div>
    <?php
}