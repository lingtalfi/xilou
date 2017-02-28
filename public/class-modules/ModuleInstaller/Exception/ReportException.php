<?php


namespace ModuleInstaller\Exception;

use Installer\Report\Report;
use Installer\Report\ReportInterface;

class ReportException extends \Exception
{

    /**
     * @var Report
     */
    private $report;

    /**
     * @return Report
     */
    public function getReport()
    {
        return $this->report;
    }

    public function setReport(ReportInterface $report)
    {
        $this->report = $report;
        return $this;
    }

}