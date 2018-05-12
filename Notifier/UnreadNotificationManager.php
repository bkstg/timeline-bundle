<?php

namespace Bkstg\TimelineBundle\Notifier;

use Bkstg\CoreBundle\User\UserInterface;
use Bkstg\TimelineBundle\Event\NotificationEntryEvent;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Notification\NotifierInterface;
use Spy\Timeline\Notification\Unread\UnreadNotificationManager as BaseUnreadNotificationManager;
use Spy\Timeline\Spread\Entry\EntryCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UnreadNotificationManager extends BaseUnreadNotificationManager
{
    protected $dispatcher;
    protected $timeline_manager;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        TimelineManagerInterface $timeline_manager
    ) {
        $this->dispatcher = $dispatcher;
        parent::__construct($timeline_manager);
    }

    /**
     * {@inheritdoc}
     */
    public function notify(ActionInterface $action, EntryCollection $entry_collection)
    {
        // Iterate over entry collections for each context.
        foreach ($entry_collection as $context => $entries) {
            // Iterate over entries in each collection.
            foreach ($entries as $entry) {
                // Allow listeners to determine whether or not to notify.
                $entry_event = new NotificationEntryEvent($entry, $action);
                $this->dispatcher->dispatch(NotificationEntryEvent::NAME, $entry_event);

                if ($entry_event->getNotify()) {
                    // Create a new timeline action for this notification.
                    $this->timelineManager->createAndPersist(
                        $action,
                        $entry->getSubject(),
                        $context,
                        'notification'
                    );
                }
            }
        }

        // Flush the timeline maanager to persist notifications.
        $this->timelineManager->flush();
    }
}
