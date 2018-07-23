<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
