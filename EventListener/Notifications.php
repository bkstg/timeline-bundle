<?php

namespace Bkstg\NotificationBundle\EventListener;

use Bkstg\NotificationBundle\Block\NotificationsBlock;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class Notifications
{
    public function onBlock(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType(NotificationsBlock::class);

        $event->addBlock($block);
    }
}
