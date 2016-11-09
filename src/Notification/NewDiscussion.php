<?php namespace Anomaly\NewDiscussionNotificationExtension\Notification;

use Anomaly\ForumModule\Discussion\Event\DiscussionWasCreated;
use Anomaly\NotificationsModule\Channel\Traits\SendsViaChannel;
use Anomaly\NotificationsModule\Subscription\Contract\SubscriptionInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

/**
 * Class NewDiscussion
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class NewDiscussion extends Notification implements ShouldQueue
{

    use Queueable;
    use SendsViaChannel;

    /**
     * The event instance.
     *
     * @var DiscussionWasCreated
     */
    protected $event;

    /**
     * Create a new NewDiscussion instance.
     *
     * @param NewDiscussion $event
     */
    public function __construct(DiscussionWasCreated $event)
    {
        $this->event = $event;
    }

    /**
     * Return the mail notification.
     *
     * @param SubscriptionInterface $notifiable
     * @return MailMessage
     */
    public function toMail(SubscriptionInterface $notifiable)
    {
        $discussion = $this->event->getDiscussion();

        return $notifiable->format(
            (new MailMessage())
                ->subject('New Forum Discussion')
                ->line($discussion->getTitle())
                ->action(
                    'View Discussion',
                    url($discussion->route('view'))
                )
        );
    }

    /**
     * Return the slack notification.
     *
     * @param SubscriptionInterface $notifiable
     * @return SlackMessage
     */
    public function toSlack(SubscriptionInterface $notifiable)
    {
        $discussion = $this->event->getDiscussion();

        return $notifiable->format(
            (new SlackMessage())
                ->content('A new forum discussion has been posted!')
                ->attachment(
                    function (SlackAttachment $attachment) use ($discussion) {
                        $attachment->title($discussion->getTitle(), url($discussion->route('view')));
                    }
                )
        );
    }
}
