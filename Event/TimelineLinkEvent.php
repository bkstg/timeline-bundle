<?php

namespace Bkstg\TimelineBundle\Event;

use Spy\Timeline\Model\ActionInterface;
use Symfony\Component\EventDispatcher\Event;

class TimelineLinkEvent extends Event
{
    const NAME = 'bkstg.timeline.timeline_link';

    private $link;
    private $action;

    public function __construct(ActionInterface $action)
    {
        $this->action = $action;
    }

    public function getAction(): ActionInterface
    {
        return $this->action;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }
}
