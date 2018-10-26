<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Event;

use Spy\Timeline\Model\ActionInterface;
use Symfony\Component\EventDispatcher\Event;

class TimelineLinkEvent extends Event
{
    const NAME = 'bkstg.timeline.timeline_link';

    private $link;
    private $action;

    /**
     * Create a new timeline link event.
     *
     * @param ActionInterface $action The action for this event.
     */
    public function __construct(ActionInterface $action)
    {
        $this->action = $action;
    }

    /**
     * Get the action for this event.
     *
     * @return ActionInterface
     */
    public function getAction(): ActionInterface
    {
        return $this->action;
    }

    /**
     * Get the link for this event.
     *
     * @return ?string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Set the link for this event.
     *
     * @param string $link The link.
     *
     * @return self
     */
    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
