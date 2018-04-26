<?php

namespace Bkstg\NotificationBundle\Block;

use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Notification\NotifierInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Templating\EngineInterface;

class ProductionTimelineBlock extends AbstractBlockService
{
    private $action_manager;
    private $timeline_manager;
    private $token_storage;
    private $notifier;

    public function __construct(
        $name,
        EngineInterface $templating,
        ActionManagerInterface $action_manager,
        TimelineManagerInterface $timeline_manager,
        TokenStorageInterface $token_storage,
        NotifierInterface $notifier
    ) {
        $this->action_manager = $action_manager;
        $this->timeline_manager = $timeline_manager;
        $this->token_storage = $token_storage;
        $this->notifier = $notifier;
        parent::__construct($name, $templating);
    }


    public function execute(BlockContextInterface $context, Response $response = null)
    {
        $production = $context->getBlock()->getSetting('production');
        $production_component = $this->action_manager->findOrCreateComponent($production);
        $timeline = $this->notifier->getUnreadNotifications(
            $production_component,
            'GLOBAL',
            ['paginate' => false, 'max_per_page' => 20]
        );

        return $this->renderResponse($context->getTemplate(), [
            'block' => $context->getBlock(),
            'settings' => $context->getSettings(),
            'timeline' => $timeline,
            'count' => $this->notifier->countKeys($production_component),
        ], $response);
    }

    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template' => '@BkstgNotification/Block/production_timeline.html.twig',
        ]);
    }
}
