<?php


namespace Events;

use Counter\CounterModule;

class EventsServices
{
    /**
     * This event was designed for statistic tools
     */
    public static function onPageRenderedAfter()
    {
        CounterModule::onPageRenderedAfter();
    }
}