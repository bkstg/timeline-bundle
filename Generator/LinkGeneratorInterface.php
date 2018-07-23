<?php

namespace Bkstg\TimelineBundle\Generator;

use Spy\Timeline\Model\ActionInterface;

interface LinkGeneratorInterface
{
    /**
     * Generates a link for the given action.
     *
     * @param  ActionInterface $action The action to generate the link for.
     * @return ?string
     */
    public function generateLink(ActionInterface $action): ?string;
}
