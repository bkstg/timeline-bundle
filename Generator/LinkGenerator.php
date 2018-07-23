<?php

namespace Bkstg\TimelineBundle\Generator;

use Bkstg\TimelineBundle\Event\TimelineLinkEvent;
use Spy\Timeline\Model\ActionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LinkGenerator implements LinkGeneratorInterface
{
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function generateLink(ActionInterface $action): ?string
    {
        $event = new TimelineLinkEvent($action);
        $this->dispatcher->dispatch(TimelineLinkEvent::NAME, $event);
        return $event->getLink();
    }
}
