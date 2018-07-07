<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgCoreBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Spread;

use Bkstg\CoreBundle\User\MembershipProviderInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;
use Spy\Timeline\Spread\Entry\EntryUnaware;
use Spy\Timeline\Spread\SpreadInterface;

abstract class GroupSpread implements SpreadInterface
{
    private $membership_provider;

    public function __construct(
        MembershipProviderInterface $membership_provider
    ) {
        $this->membership_provider = $membership_provider;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ActionInterface $action)
    {
        $object = $action->getComponent('directComplement')->getData();

        if (!$object instanceof GroupInterface) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ActionInterface $action, EntryCollection $collection): void
    {
        // Get the group for this action.
        $group = $action->getComponent('directComplement')->getData();

        // Create a a timeline entry for the group.
        $collection->add(new EntryUnaware(get_class($group), $group->getId()));

        // Iterate over memberships and spread to all active users.
        foreach ($this->membership_provider->loadMembershipsByGroup($group) as $membership) {
            if ($membership->isActive()
            && !$membership->isExpired()) {
                $user = $membership->getMember();
                $collection->add(new EntryUnaware(get_class($user), $user->getId()));
            }
        }
    }
}
