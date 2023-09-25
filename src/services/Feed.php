<?php
namespace fostercommerce\calendarfeed\services;

use Craft;
use craft\base\Component;
use craft\web\View;
use craft\helpers\Template;
use fostercommerce\calendarfeed\CalendarFeed;

class Feed extends Component
{
    private $settings = null;

    public function __construct()
    {
        $this->settings = CalendarFeed::getInstance()->settings;
    }

    public function getFeed() : ?String
    {
        $client = new \Google_Client();
		$client->setDeveloperKey($this->settings->developerToken); // TODO Get from settings
		$calendar = new \Google_Service_Calendar($client);

        $params = array(
			'singleEvents' => true,
			'orderBy' => 'startTime',
			'timeMin' => date(\DateTime::ATOM),
		);

		$events = $calendar->events->listEvents($this->settings->googleCalendarId, $params);

		$items_to_show = $this->settings->totalEvents;

		$lastDate = false;

        $events = array_slice($events->getItems(), 0, $items_to_show);

        $data = [
            'dates' => [],
        ];

        foreach ($events as $event) {
            $eventDateStr = $event->start->dateTime;

            if(empty($eventDateStr)) {
                $eventDateStr = $event->start->date;
            }

            $endDateStr = $event->end->dateTime;

            if(empty($endDateStr)) {
                $endDateStr = $event->end->date;
            }

            $temp_timezone = $event->start->timeZone;

            $eventdate = new \DateTime($eventDateStr);
            $endDate = new \DateTime($endDateStr);

            if (!empty($temp_timezone)) {
                $eventdate->setTimeZone(new \DateTimeZone($temp_timezone));
                $endDate->setTimeZone(new \DateTimeZone($temp_timezone));
            } else {
                $eventdate->setTimeZone(new \DateTimeZone("America/New_York"));
                $endDate->setTimeZone(new \DateTimeZone("America/New_York"));
            }

            $dateFull = $eventdate->format("Y-M-j");
            $dateLegible = $eventdate->format("l, M j");
            $year = $eventdate->format("Y");
            $day = $eventdate->format('l');
            $dayAb = $eventdate->format('D');
            $month = $eventdate->format("M");
            $date = $eventdate->format("j");
            $hour = $eventdate->format("g:ia");
            $endDateHour = $endDate->format('g:ia');

            if ($dateFull != $lastDate) {
                $eventData = [
                    'dateFull' => $dateFull,
                    'dateLegible' => $dateLegible,
                    'year' => $year,
                    'day' => $day,
                    'dayAb' => $dayAb,
                    'month' => $month,
                    'date' => $date,
                    'events' => [],
                ];

                $data['dates'][$dateFull] = $eventData;
            }

            $data['dates'][$dateFull]['events'][] = [
                'eventDateStr' => $eventDateStr,
                'endDateHour' => $endDateHour,
                'hour' => $hour,
                'endDateStr' => $endDateStr,
                'event' => $event,
                'summary' => $event->summary,
                'link' => $event->htmlLink,
            ];

            $lastDate = $dateFull;
		}


        $oldMode = Craft::$app->view->getTemplateMode();
        Craft::$app->view->setTemplateMode(View::TEMPLATE_MODE_CP);
        $html = Craft::$app->view->renderTemplate('calendar-feed/feed', $data);
        Craft::$app->view->setTemplateMode($oldMode);
        return Template::raw($html);
    }
}

