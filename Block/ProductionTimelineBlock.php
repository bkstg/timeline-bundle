<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Templating\TwigEngine;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProductionTimelineBlock extends AbstractBlockService
{
    private $action_manager;
    private $timeline_manager;
    private $token_storage;
    private $request_stack;

    public function __construct(
        $name,
        TwigEngine $templating,
        ActionManagerInterface $action_manager,
        TimelineManagerInterface $timeline_manager,
        TokenStorageInterface $token_storage,
        RequestStack $request_stack
    ) {
        $this->action_manager = $action_manager;
        $this->timeline_manager = $timeline_manager;
        $this->token_storage = $token_storage;
        $this->request_stack = $request_stack;
        parent::__construct($name, $templating);
    }

    public function execute(BlockContextInterface $context, Response $response = null)
    {
        $request = $this->request_stack->getCurrentRequest();
        $production = $context->getBlock()->getSetting('production');
        $production_component = $this->action_manager->findOrCreateComponent($production);
        $timeline = $this->timeline_manager->getTimeline(
            $production_component,
            [
                'page' => $request->query->getInt('page', 1),
                'paginate' => true,
                'max_per_page' => 10,
            ]
        );

        return $this->renderResponse($context->getTemplate(), [
            'block' => $context->getBlock(),
            'settings' => $context->getSettings(),
            'timeline' => $timeline,
            'count' => $this->timeline_manager->countKeys($production_component),
        ], $response);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'production' => null,
            'template' => '@BkstgTimeline/Block/_production_timeline.html.twig',
        ]);
    }
}
