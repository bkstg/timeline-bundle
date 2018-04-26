<?php

namespace Bkstg\NotificationBundle\EventListener;

use Bkstg\NotificationBundle\Block\ProductionTimelineBlock;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class ProductionTimeline
{
    public function onBlock(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType(ProductionTimelineBlock::class);

        $event->addBlock($block);
    }
}
