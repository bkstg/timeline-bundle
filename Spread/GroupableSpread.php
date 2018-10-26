<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Spread;

use Bkstg\CoreBundle\User\MembershipProviderInterface;
use Doctrine\Common\Proxy\Proxy;
use Doctrine\ORM\EntityManagerInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;
use Spy\Timeline\Spread\Entry\EntryUnaware;
use Spy\Timeline\Spread\SpreadInterface;

abstract class GroupableSpread implements SpreadInterface
{
    protected $membership_provider;
    private $em;

    /**
     * Create a new groupable spread.
     *
     * @param MembershipProviderInterface $membership_provider The membership provider service.
     * @param EntityManagerInterface      $em                  The entity manager service.
     */
    public function __construct(
        MembershipProviderInterface $membership_provider,
        EntityManagerInterface $em
    ) {
        $this->membership_provider = $membership_provider;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     *
     * @param ActionInterface $action     The action.
     * @param EntryCollection $collection The entry collection.
     *
     * @return void
     */
    public function process(ActionInterface $action, EntryCollection $collection): void
    {
        // Get the groupable object and iterate over groups.
        $groupable = $action->getComponent('directComplement')->getData();
        foreach ($groupable->getGroups() as $group) {
            // Create a a timeline entry for the group.
            $collection->add(new EntryUnaware($this->resolveClass($group), $group->getId()));

            // Iterate over members and spread to all active members.
            foreach ($this->membership_provider->loadActiveMembershipsByProduction($group) as $membership) {
                $user = $membership->getMember();
                $collection->add(new EntryUnaware($this->resolveClass($user), $user->getId()));
            }
        }
    }

    /**
     * Resolve a class.
     *
     * @param mixed $object The object to resolve.
     *
     * @return string
     */
    private function resolveClass($object)
    {
        if (!$object instanceof Proxy) {
            return get_class($object);
        }

        // If this is a proxy resolve using class metadata.
        return $this
            ->em
            ->getClassMetadata(get_class($object))
            ->getReflectionClass()
            ->getName();
    }
}
