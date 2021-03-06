<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Entity;

use Spy\TimelineBundle\Entity\Action as BaseAction;

class Action extends BaseAction
{
    protected $id;
    protected $actionComponents;
    protected $timelines;
}
