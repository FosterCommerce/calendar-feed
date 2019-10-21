<?php
namespace fostercommerce\calendarfeed;

use fostercommerce\calendarfeed\variables\CalendarFeedVariable;
use fostercommerce\calendarfeed\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\twig\variables\CraftVariable;

use yii\base\Event;

class CalendarFeed extends Plugin
{
    public static $plugin;

    public $schemaVersion = '1.0.0';

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'feed' => \fostercommerce\calendarfeed\services\Feed::class,
        ]);

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('calendarFeed', CalendarFeedVariable::class);
            }
        );
    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'calendar-feed/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
