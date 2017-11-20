<?php

namespace Bkstg\NotificationBundle\Entity;

use Spy\TimelineBundle\Entity\Action as BaseAction;

class Action extends BaseAction
{
    protected $id;
    protected $actionComponents;
    protected $timelines;
    protected $link;

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Action
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
}
