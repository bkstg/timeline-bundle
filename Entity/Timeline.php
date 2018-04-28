<?php

namespace Bkstg\TimelineBundle\Entity;

use Spy\TimelineBundle\Entity\Timeline as BaseTimeline;

class Timeline extends BaseTimeline
{
    protected $id;
    protected $action;
    protected $subject;
}
