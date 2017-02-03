<?php

use Bat\UriTool;
use Privilege\Privilege;
use QuickPdo\QuickPdo;
use SqlTools\SqlToolsConfig;
use SqlTools\SqlToolsModule;


define('LL', 'modules/sqltools/sqltools');

function processSql($sql, &$ret, &$msg)
{
    // pdo is executed in error=exception mode in nullos...
    try {
        SqlToolsModule::executeSqlStatements($sql);
        $ret = true;
        $msg = __("The statements have been successfully executed", LL);

    } catch (\PDOException $e) {
        $msg = $e->getMessage();
        Logger::log($e, "sqltools.pdoException");
        $ret = false;
    } catch (\Exception $e) {
        Logger::log($e, "sqltools.page");
        $ret = false;
        $msg = __("An error occurred, please checked the nullos logs", LL);
    }
}

if (Privilege::has('sqlTools.access')):
    ?>
    <section class="freepage tac">
        <h3><?php echo __("Execute SQL page", LL); ?></h3>
        <p><?php echo __("Use this page to execute sql from a file, an url or directly from code", LL); ?></p>
        <p>
            <a target="_blank"
               href="<?php echo doclink('modules/sqltools-module/execute-sql-page.md'); ?>"><?php echo __("Need help?", LL); ?></a>
        </p>
        <div>
            <?php if (true === QuickPdo::hasConnection()): ?>

                <?php

                //--------------------------------------------
                // FILE FROM USER MACHINE
                //--------------------------------------------
                $form = QuickFormZ::create();
                $form->title = __("From file", LL);
                $form->multipart = true;
                $form->labels = [
                    'file' => __('Choose a file', LL),
                ];

                $form->formTreatmentFunc = function (array $formattedValues, &$msg) {
                    $ret = true;
                    $phpItem = $formattedValues['file'][0];
                    // you get error 4 if the file is not uploaded (user didn't set the file)
                    if (0 === $phpItem['error']) {
                        $content = file_get_contents($phpItem['tmp_name']);
                        processSql($content, $ret, $msg);
                    }
                    return $ret;
                };

                $form->addControl('file')->type('file', [
                    'accept' => '.sql,.txt',
                ]);
                $form->play();


                //--------------------------------------------
                // FILE FROM FAVORITES
                //--------------------------------------------
                $form2 = QuickFormZ::create();
                $form2->title = __("From favorites", LL);
                $form2->labels = [
                    'file2' => __('Choose a file', LL),
                ];

                $form2->formTreatmentFunc = function (array $formattedValues, &$msg) {
                    $ret = true;
                    $file = $formattedValues['file2'];
                    if (file_exists($file)) {
                        $content = file_get_contents($file);
                        processSql($content, $ret, $msg);
                    } else {
                        $ret = false;
                        $msg = __("The file {file} does not exist", LL, ['file' => $file]);
                    }
                    return $ret;
                };

                $form2->addControl('file2')->type('select', SqlToolsModule::getFavoriteFiles(), ['size' => 5]);
                $form2->play();


                //--------------------------------------------
                // FROM URL
                //--------------------------------------------
                $form3 = QuickFormZ::create();
                $form3->title = __("From url", LL);
                $form3->labels = [
                    'file3' => __('url', LL),
                ];

                $form3->formTreatmentFunc = function (array $formattedValues, &$msg) {
                    $ret = true;
                    $url = $formattedValues['file3'];
                    if (false !== ($content = UriTool::fileGetContents($url))) {
                        processSql($content, $ret, $msg);
                    } else {
                        $ret = false;
                        $msg = __("Cannot access external url. To fix the problem, you can either enable the php allow_url_fopen directive, or install curl.", LL);
                    }
                    return $ret;
                };

                $form3->addControl('file3')->type('text');
                $form3->play();


                //--------------------------------------------
                // PASTE SQL DIRECTLY
                //--------------------------------------------
                $form4 = QuickFormZ::create();
                $form4->title = __("From code", LL);
                $form4->labels = [
                    'file4' => __("Type your sql directly", LL),
                ];

                $form4->formTreatmentFunc = function (array $formattedValues, &$msg) {
                    $content = $formattedValues['file4'];
                    processSql($content, $ret, $msg);
                    return $ret;
                };

                $form4->addControl('file4')->type('message');
                $form4->play();


                ?>
            <?php else: ?>
                <div class="alert alert-error">
                    <?php echo __("Your application is currently not configured to work with a database.", LL); ?>
                    <br>
                    <?php echo __("Please configure your application first.", LL); ?>
                    <br>
                    <?php echo __("You could use the \"Configure\" item on the left menu in the Quickstart section.", LL); ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php

endif;