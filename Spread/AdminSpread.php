<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Spread;

use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;
use Spy\Timeline\Spread\Entry\EntryUnaware;
use Spy\Timeline\Spread\SpreadInterface;

abstract class AdminSpread implements SpreadInterface
{
    /**
     * {@inheritdoc}
     *
     * @param ActionInterface $action     The action interface.
     * @param EntryCollection $collection The entry collection.
     *
     * @return void
     */
    public function process(ActionInterface $action, EntryCollection $collection): void
    {
        $collection->add(new EntryUnaware('bkstg-admin', 'admin'));
    }
}
