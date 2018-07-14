<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\EventListener;

use Bkstg\TimelineBundle\Block\NotificationsBlock;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class Notifications
{
    public function onBlock(BlockEvent $event): void
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType(NotificationsBlock::class);

        $event->addBlock($block);
    }
}
