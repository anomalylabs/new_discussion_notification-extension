<?php namespace Anomaly\NewDiscussionNotificationExtension;

use Anomaly\ForumModule\Discussion\Event\DiscussionWasCreated;
use Anomaly\NewDiscussionNotificationExtension\Notification\NewDiscussion;
use Anomaly\NotificationsModule\Notification\NotificationExtension;

/**
 * Class NewDiscussionNotificationExtension
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class NewDiscussionNotificationExtension extends NotificationExtension
{

    /**
     * The notification event.
     *
     * @var string
     */
    public $event = DiscussionWasCreated::class;

    /**
     * The supported drivers.
     *
     * @var array
     */
    protected $supported = [
        'mail',
        'slack',
    ];

    /**
     * This extension provides the notification that
     * fires off when a new discussion is created.
     *
     * @var string
     */
    protected $provides = 'anomaly.module.notifications::notification.new_discussion';

    /**
     * Return a new notification.
     *
     * @param $event
     */
    public function newNotification($event)
    {
        return new NewDiscussion($event);
    }
}
