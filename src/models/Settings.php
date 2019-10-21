<?php
namespace fostercommerce\calendarfeed\models;

use fostercommerce\calendarfeed\CalendarFeed;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public $googleCalendarId = null;
    public $developerToken = null;
    public $totalEvents = 10;
}
