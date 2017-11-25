<?php

namespace Bkstg\NotificationBundle\EventListener;

use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class Notifications
{
    public function onBlock(BlockEvent $event)
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType('Bkstg\\NotificationBundle\\Block\\NotificationsBlock');

        $event->addBlock($block);
    }
}
