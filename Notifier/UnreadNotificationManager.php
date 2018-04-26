<?php

namespace Bkstg\NotificationBundle\Notifier;

use Bkstg\CoreBundle\User\UserInterface;
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
        // Get the original subject of this action.
        $orig_subject = $action->getComponent('subject')->getData();

        // Iterate over entry collections for each context.
        foreach ($entryCollection as $context => $entries) {
            // Iterate over entries in each collection.
            foreach ($entries as $entry) {
                // Get the subject data for the entry..
                $subject_model = $entry->getSubjectModel();
                $subject_id = $entry->getSubjectId();

                // Only notify UserInterface subjects that are not the original
                // subject of the action.
                if (class_exists($subject_model)
                    && in_array(UserInterface::class, class_implements($subject_model))
                    && ($subject_model != get_class($orig_subject) || $subject_id != $orig_subject->getId())
                ) {
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
