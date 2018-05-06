<?php

namespace Bkstg\TimelineBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

class ProductionTimelineBlock extends AbstractBlockService
{
    private $action_manager;
    private $timeline_manager;
    private $token_storage;

    public function __construct(
        $name,
        EngineInterface $templating,
        ActionManagerInterface $action_manager,
        TimelineManagerInterface $timeline_manager,
        TokenStorageInterface $token_storage
    ) {
        $this->action_manager = $action_manager;
        $this->timeline_manager = $timeline_manager;
        $this->token_storage = $token_storage;
        parent::__construct($name, $templating);
    }


    public function execute(BlockContextInterface $context, Response $response = null)
    {
        $production = $context->getBlock()->getSetting('production');
        $production_component = $this->action_manager->findOrCreateComponent($production);
        $timeline = $this->timeline_manager->getTimeline(
            $production_component,
            ['paginate' => false, 'max_per_page' => 20]
        );

        return $this->renderResponse($context->getTemplate(), [
            'block' => $context->getBlock(),
            'settings' => $context->getSettings(),
            'timeline' => $timeline,
            'count' => $this->timeline_manager->countKeys($production_component),
        ], $response);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'production' => null,
            'template' => '@BkstgTimeline/Block/production_timeline.html.twig',
        ]);
    }
}
