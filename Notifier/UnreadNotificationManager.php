<?php

namespace Bkstg\NotificationBundle\Notifier;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Notification\NotifierInterface;
use Spy\Timeline\Notification\Unread\UnreadNotificationManager as BaseUnreadNotificationManager;
use Spy\Timeline\Spread\Entry\EntryCollection;

class UnreadNotificationManager extends BaseUnreadNotificationManager
{
    /**
     * {@inheritdoc}
     */
    public function notify(ActionInterface $action, EntryCollection $entryCollection)
    {
        // Get the original author of this action.
        $author = $action->getComponent('subject');
        $i = 0;
        foreach ($entryCollection as $context => $entries) {
            foreach ($entries as $entry) {
                // No need to notify the author of the action.
                if ($entry->getSubject()->getId() != $author->getId()) {
                    $i++;
                    $this->timelineManager->createAndPersist($action, $entry->getSubject(), $context, 'notification');
                }
            }
        }

        if ($i > 0) {
            $this->timelineManager->flush();
        }
    }
}
