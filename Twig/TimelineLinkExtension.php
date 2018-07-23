<?php

namespace Bkstg\TimelineBundle\Twig;

use Bkstg\TimelineBundle\Generator\LinkGeneratorInterface;
use Spy\Timeline\Model\ActionInterface;

class TimelineLinkExtension extends \Twig_Extension
{
    private $link_generator;

    public function __construct(LinkGeneratorInterface $link_generator)
    {
        $this->link_generator = $link_generator;
    }

    public function getFunctions()
    {
        return [
            new \Twig_Function('bkstg_timeline_link', [$this, 'getLink']),
        ];
    }

    public function getLink(ActionInterface $action)
    {
        return $this->link_generator->generateLink($action);
    }
}
