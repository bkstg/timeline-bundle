<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\EventListener;

use Bkstg\TimelineBundle\Block\ProductionTimelineBlock;
use Sonata\BlockBundle\Event\BlockEvent;
use Sonata\BlockBundle\Model\Block;

class ProductionTimeline
{
    /**
     * React on block creation event.
     *
     * @param BlockEvent $event The block event.
     *
     * @return void
     */
    public function onBlock(BlockEvent $event): void
    {
        $block = new Block();
        $block->setId(uniqid());
        $block->setSettings($event->getSettings());
        $block->setType(ProductionTimelineBlock::class);

        $event->addBlock($block);
    }
}
