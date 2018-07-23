<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Generator;

use Spy\Timeline\Model\ActionInterface;

interface LinkGeneratorInterface
{
    /**
     * Generates a link for the given action.
     *
     * @param ActionInterface $action The action to generate the link for.
     *
     * @return ?string
     */
    public function generateLink(ActionInterface $action): ?string;
}
