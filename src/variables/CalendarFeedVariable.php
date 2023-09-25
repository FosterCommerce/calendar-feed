<?php
namespace fostercommerce\calendarfeed\variables;

use fostercommerce\calendarfeed\CalendarFeed;

use Craft;

class CalendarFeedVariable
{
    public function feed() : String
    {
        $html = CalendarFeed::getInstance()->feed->getFeed();
        return $html;
    }
}
