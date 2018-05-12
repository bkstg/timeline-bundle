<?php

namespace Bkstg\TimelineBundle\Event;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryInterface;
use Symfony\Component\EventDispatcher\Event;

class NotificationEntryEvent extends Event
{
    const NAME = 'bkstg.timeline.notification_entry';

    protected $entry;
    protected $action;
    protected $notify = true;

    public function __construct(EntryInterface $entry, ActionInterface $action)
    {
        $this->entry = $entry;
        $this->action = $action;
    }

    public function getEntry()
    {
        return $this->entry;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getNotify()
    {
        return $this->notify;
    }

    public function setNotify(bool $notify)
    {
        $this->notify = $notify;
    }
}
