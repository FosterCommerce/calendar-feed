<?php
namespace fostercommerce\calendarfeed\models;

use fostercommerce\calendarfeed\CalendarFeed;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    public ?string $googleCalendarId = null;
    public ?string $developerToken = null;
    public int $totalEvents = 10;
}
