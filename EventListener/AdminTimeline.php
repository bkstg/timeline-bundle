<?php

namespace Bkstg\NotificationBundle\EventListener;

use Bkstg\NotificationBundle\Block\AdminTimelineBlock;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class AdminTimeline
{
    public function onBlock(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType(AdminTimelineBlock::class);

        $event->addBlock($block);
    }
}
