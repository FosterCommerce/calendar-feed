<?php
namespace fostercommerce\calendarfeed\variables;

use fostercommerce\calendarfeed\CalendarFeed;

use Craft;

class CalendarFeedVariable
{
    public function feed()
    {
        $html = CalendarFeed::getInstance()->feed->getFeed();
        return $html;
    }
}
