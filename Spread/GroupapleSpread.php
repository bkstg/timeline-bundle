<?php

namespace Bkstg\NotificationBundle\Spread;

use Bkstg\CoreBundle\User\MembershipProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use MidnightLuke\GroupSecurityBundle\Model\GroupableInterface;
use Spy\Timeline\Model\ActionInterface;
use Spy\Timeline\Spread\Entry\EntryCollection;
use Spy\Timeline\Spread\Entry\EntryUnaware;
use Spy\Timeline\Spread\SpreadInterface;

class GroupapleSpread implements SpreadInterface
{
    private $em;
    private $membership_provider;

    public function __construct(
        EntityManagerInterface $em,
        MembershipProviderInterface $membership_provider
    ) {
        $this->em = $em;
        $this->membership_provider = $membership_provider;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ActionInterface $action)
    {
        $object = $action->getComponent('directComplement')->getData();

        if (!$object instanceof GroupableInterface) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ActionInterface $action, EntryCollection $collection)
    {
        $object = $action->getComponent('directComplement')->getData();

        foreach ($object->getGroups() as $group) {
            foreach ($this->membership_provider->loadMembershipsByGroup($group) as $membership) {
                if ($membership->isActive()
                && !$membership->isExpired()) {
                    $user = $membership->getMember();
                    $collection->add(new EntryUnaware(get_class($user), ['username' => $user->getUsername()]));
                }
            }
        }
    }
}
