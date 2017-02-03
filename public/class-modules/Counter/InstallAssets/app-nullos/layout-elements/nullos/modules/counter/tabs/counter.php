<?php


use Counter\CounterPreferences;
use Counter\CounterUtil;
use Icons\Icons;
use Layout\Goofy;
use Module\DynamicPreferencesHelper;
use Stat\Analyzer\Cache\PerDayAnalyzerCache;
use Stat\Analyzer\CumulatorPerDayAnalyzer;
use Stat\Analyzer\PerDayAnalyzerUtil;
use Stat\Extractor\CounterExtractor;


?>
    <div class="tac bignose install-page">
        <h3><?php echo __("Counter", LL); ?></h3>
    </div>

<?php


$list = CounterUtil::getTargetSitesList();
$list["Admin"] = APP_ROOT_DIR . '/www';


if (array_key_exists("update", $_GET)) {
    $target = $_GET['update'];
    if (array_key_exists($target, $list)) {
        $targetPath = $list[$target];
        CounterUtil::installCaptureSystem($targetPath);
        Goofy::alertSuccess(__("The {target} target has been successfully installed", LL, [
            'target' => $target,
        ], url(null, [])));
    }
}


if (count($list) > 0) {
    $target = key($list);
    $targetPath = current($list);


    if (array_key_exists('target', $_POST)) {
        $target = $_POST['target'];
        if (array_key_exists($target, $list)) {
            $targetPath = $list[$target];
        }
    }


    $sel = 'selected="selected"';


    if (null !== $targetPath) {


        $dir = $targetPath . '/stats-counter';
        $displayStat = true;


        if (!is_dir($dir)) {
            $displayStat = false;

        } else {


            $curYear = date('Y');
            $curMonth = date('m');
            $curDay = date('d');

            $start = $curYear . '-' . $curMonth . '-01';
            $end = $curYear . '-' . $curMonth . '-' . date('t'); //

            $mode = null;
            $error = null;
            $hasData = false;
            $startYear = (int)$curYear;
            $startMonth = (int)$curMonth;
            $startDay = (int)$curDay;

            $endYear = (int)$curYear;
            $endMonth = (int)$curMonth;
            $endDay = (int)$curDay;

            $minYear = $startYear;
            $maxYear = $endYear;

            $prefs = CounterPreferences::getPreferences();
            $selectedMonth = DynamicPreferencesHelper::getP('month', $prefs, $curYear . '-' . $curMonth);


            $months = PerDayAnalyzerUtil::getAvailableMonths($dir);


            if (false !== ($range = PerDayAnalyzerUtil::getAvailableRange($dir))) {
                $hasData = true;
                list($minDay, $maxDay) = $range;
                $p = explode('-', $minDay);
                $minYear = $p[0];
                $p = explode('-', $maxDay);
                $maxYear = $p[0];
            }


            if (array_key_exists('startyear', $_POST)) {
                $mode = "period";
                $startYear = (int)$_POST['startyear'];
                $startMonth = (int)$_POST['startmonth'];
                $startDay = (int)$_POST['startday'];

                $endYear = (int)$_POST['endyear'];
                $endMonth = (int)$_POST['endmonth'];
                $endDay = (int)$_POST['endday'];

                $start = $startYear . '-' . sprintf('%02s', $startMonth) . '-' . sprintf('%02s', $startDay);
                $end = $endYear . '-' . sprintf('%02s', $endMonth) . '-' . sprintf('%02s', $endDay);

                if ($end < $start) {
                    $error = "The start date must start BEFORE the end date";
                }
            } elseif (array_key_exists('month', $_POST)) {
                $selectedMonth = $_POST['month'];
                $start = $selectedMonth . '-01';
                $end = $selectedMonth . '-31';
                CounterPreferences::setPreferences(['_month' => $selectedMonth]);
            } elseif (array_key_exists('previous', $_POST)) {
                $p = explode('-', $selectedMonth);
                $y = (int)$p[0];
                $m = (int)$p[1];
                $m--;
                if (0 === $m) {
                    $y--;
                    $m = 12;
                }
                $selectedMonth = $y . '-' . sprintf('%02s', $m);
                $start = $selectedMonth . '-01';
                $end = $selectedMonth . '-31';
                CounterPreferences::setPreferences(['_month' => $selectedMonth]);
            } elseif (array_key_exists('next', $_POST)) {
                $p = explode('-', $selectedMonth);
                $y = (int)$p[0];
                $m = (int)$p[1];
                $m++;
                if (13 === $m) {
                    $m = 1;
                    $y++;
                }
                $selectedMonth = $y . '-' . sprintf('%02s', $m);
                $start = $selectedMonth . '-01';
                $end = $selectedMonth . '-31';
                CounterPreferences::setPreferences(['_month' => $selectedMonth]);
            }


            if (null === $error) {

                $analyzer = new CumulatorPerDayAnalyzer();
                $extractor = new CounterExtractor();


                $cacheDir = $targetPath . '/stats-counter-range-cache';
                $data = $analyzer
                    ->setCache(new PerDayAnalyzerCache($cacheDir))// be sure to create the stats-cache directory first
                    ->analyze($start, $end, $dir, $extractor);


                $labels = array_keys($data);
                $values = array_values($data);


            } else {
                Goofy::alertError($error);
            }


            $pagePrev = 0;
            $pageNext = 0;
        }

        ?>


        <style>
            canvas {
                -moz-user-select: none;
                -webkit-user-select: none;
                -ms-user-select: none;
            }
        </style>

        <script src="<?php echo url("/libs/chartjs/Chart.bundle.min.js"); ?>"></script>
        <script src="<?php echo url("/libs/jquery/2.1.3/jquery.min.js"); ?>"></script>


        <style type="text/css">
            .counter-toolbar {
                display: flex;
                background: #ddd;
                align-items: center;
                padding: 0 10px;
                box-sizing: border-box;
            }

            .counter-toolbar .expander {
                flex: auto;
            }

            .counter-toolbar label {
                min-width: 40px;
                display: inline-block;
            }
        </style>

        <div class="tal pad">
            <form method="post" action="">
                <label>Target</label>
                <select id="counter-target-selector" name="target">
                    <?php foreach ($list as $moduleName => $path):
                        $s = ($moduleName === $target) ? $sel : '';

                        ?>
                        <option <?php echo $s; ?> value="<?php echo $moduleName; ?>"><?php echo $moduleName; ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <script>
            var selector = document.getElementById('counter-target-selector');
            selector.addEventListener('change', function () {
                this.parentNode.submit();
            });
        </script>


        <?php if (true === $displayStat && true === $hasData): ?>

            <div class="counter-toolbar">

                <form method="post" action="">
                    <label>From</label>
                    <select name="startyear">
                        <?php for ($i = $minYear; $i <= $maxYear; $i++):
                            $s = ($i === $startYear) ? $sel : '';
                            ?>
                            <option <?php echo $s; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="startmonth">
                        <?php for ($i = 1; $i <= 12; $i++):
                            $s = ($i === $startMonth) ? $sel : '';
                            ?>
                            <option <?php echo $s; ?>
                                    value="<?php echo $i; ?>"><?php echo vsprintf('%02s', $i); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="startday">
                        <?php for ($i = 1; $i <= 31; $i++):
                            $s = ($i === $startDay) ? $sel : '';
                            ?>
                            <option <?php echo $s; ?>
                                    value="<?php echo $i; ?>"><?php echo vsprintf('%02s', $i); ?></option>
                        <?php endfor; ?>
                    </select>
                    <br>
                    <label>To</label>
                    <select name="endyear">
                        <?php for ($i = $minYear; $i <= $maxYear; $i++):
                            $s = ($i === $endYear) ? $sel : '';
                            ?>
                            <option <?php echo $s; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="endmonth">
                        <?php for ($i = 1; $i <= 12; $i++):
                            $s = ($i === $endMonth) ? $sel : ''; ?>
                            <option <?php echo $s; ?>
                                    value="<?php echo $i; ?>"><?php echo vsprintf('%02s', $i); ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="endday">
                        <?php for ($i = 1; $i <= 31; $i++):
                            $s = ($i === $endDay) ? $sel : '';
                            ?>
                            <option <?php echo $s; ?>
                                    value="<?php echo $i; ?>"><?php echo vsprintf('%02s', $i); ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="hidden" name="target" value="<?php echo htmlspecialchars($target); ?>">
                    <input type="submit" value="Submit">
                </form>
                <div class="expander"></div>


                <form method="post" action="">
                    <label>Month</label>
                    <select id="counter-month-selector" name="month">
                        <?php foreach ($months as $month):
                            $s = ($selectedMonth === $month) ? $sel : '';
                            ?>
                            <option <?php echo $s; ?> value="<?php echo $month; ?>"><?php echo $month; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" name="target" value="<?php echo htmlspecialchars($target); ?>">
                </form>
                <script>
                    var selector = document.getElementById('counter-month-selector');
                    selector.addEventListener('change', function () {
                        this.parentNode.submit();
                    });
                </script>
                <form method="post" action="">
                    <button type="submit" class="button-icon"><?php Icons::printIcon('arrow-back', 'white'); ?></button>
                    <input type="hidden" name="previous" value="any">
                </form>

                <form method="post" action="">
                    <button class="button-icon arrowforward"><?php Icons::printIcon('arrow-forward', 'white'); ?></button>
                    <input type="hidden" name="next" value="any">
                </form>

            </div>
            <div style="width:100%;padding: 20px; box-sizing: border-box">
                <canvas id="canvas"></canvas>
            </div>
            <?php if (null === $error): ?>
                <script>

                    //------------------------------------------------------------------------------/
                    // GUI
                    //------------------------------------------------------------------------------/


                    //------------------------------------------------------------------------------/
                    // STATS GRAPHS
                    //------------------------------------------------------------------------------/
                    var config = {
                        type: 'line',
                        data: {
                            labels: <?php echo json_encode($labels); ?>,
                            datasets: [{
                                label: "My First dataset",
                                data: <?php echo json_encode($values); ?>,
                                fill: true,

//                borderDash: [5, 5],
                            }]
                        },
                        options: {
                            legend: false,
                            responsive: true,
                            title: {
                                display: false,
                                text: 'Nb pages refreshed'
                            },
                            tooltips: {
                                mode: 'label',
                                callbacks: {
                                    // beforeTitle: function() {
                                    //     return '...beforeTitle';
                                    // },
                                    // afterTitle: function() {
                                    //     return '...afterTitle';
                                    // },
                                    // beforeBody: function() {
                                    //     return '...beforeBody';
                                    // },
                                    // afterBody: function() {
                                    //     return '...afterBody';
                                    // },
                                    // beforeFooter: function() {
                                    //     return '...beforeFooter';
                                    // },
                                    // footer: function() {
                                    //     return 'Footer';
                                    // },
                                    // afterFooter: function() {
                                    //     return '...afterFooter';
                                    // },
                                }
                            },
                            hover: {
                                mode: 'dataset'
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Value'
                                    }
//                    ticks: {
//                        suggestedMin: -10,
//                        suggestedMax: 250,
//                    }
                                }]
                            }
                        }
                    };

                    $.each(config.data.datasets, function (i, dataset) {
                        dataset.borderColor = "blue";
                        dataset.backgroundColor = "rgba(0, 0, 200, 0.1)";
                        dataset.pointBorderColor = "purple";
                        dataset.pointBackgroundColor = "yellow";
                        dataset.pointBorderWidth = 1;
                    });

                    window.onload = function () {
                        var ctx = document.getElementById("canvas").getContext("2d");
                        window.myLine = new Chart(ctx, config);
                    };


                </script>
                <?php
            endif;
        else:


            if (false === $displayStat):
                ?>
                <div class="pad">
                    <p>The Counter system seems to be not ready for the <?php echo $target; ?> target.</p>
                    <p>
                        <a href="<?php echo url(null, [
                            'update' => $target,
                        ]); ?>">Click this link</a> to install it.
                    </p>
                </div>
                <?php
            elseif (false === $hasData):
                ?>
                <div class="pad">
                    <p>No data yet for this target.</p>
                </div>
                <?php
            endif;


        endif;
    }
} else {
    ?>
    <p class="pad">
        No target available.
    </p>
    <?php
}
