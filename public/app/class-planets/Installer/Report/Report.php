<?php


namespace Installer\Report;

use Installer\Report\ReportMessage\ReportMessage;
use Installer\Report\ReportMessage\ReportMessageInterface;

class Report implements ReportInterface
{
    private $messages;

    public function __construct()
    {
        $this->messages = [];
    }

    public function addMessage($msg)
    {
        $this->messages[] = new ReportMessage($msg);
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function hasMessages()
    {
        return (count($this->messages) > 0);
    }


}