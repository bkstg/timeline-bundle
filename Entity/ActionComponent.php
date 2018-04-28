<?php

namespace Bkstg\TimelineBundle\Entity;

use Spy\TimelineBundle\Entity\ActionComponent as BaseActionComponent;

class ActionComponent extends BaseActionComponent
{
    protected $id;
    protected $action;
    protected $component;
}
