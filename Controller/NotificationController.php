<?php

namespace Bkstg\NotificationBundle\Controller;

use Bkstg\CoreBundle\Controller\Controller;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Spy\Timeline\Notification\NotifierInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class NotificationController extends Controller
{
    public function markReadAction(
        $id,
        TokenStorageInterface $token_storage,
        ActionManagerInterface $action_manager,
        NotifierInterface $notifier,
        Request $request
    ) {
        $user = $token_storage->getToken()->getUser();
        $subject = $action_manager->findOrCreateComponent($user);
        $notifier->markAsReadAction($subject, $id);
        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }

    public function markAllReadAction(
        TokenStorageInterface $token_storage,
        ActionManagerInterface $action_manager,
        NotifierInterface $notifier,
        Request $request
    ) {
        $user = $token_storage->getToken()->getUser();
        $subject = $action_manager->findOrCreateComponent($user);
        $notifier->markAllAsRead($subject);
        return new RedirectResponse($request->server->get('HTTP_REFERER'));
    }

    /**
     * Renders the current user's notification stream.
     */
    public function timelineAction(
        TokenStorageInterface $token_storage,
        TimelineManagerInterface $timeline_manager,
        ActionManagerInterface $action_manager,
        Request $request
    ) {
        $user = $token_storage->getToken()->getUser();
        $subject = $action_manager->findOrCreateComponent($user);
        $timeline = $timeline_manager->getTimeline($subject, [
            'page' => $request->query->get('page') ?: 1,
            'max_per_page' => 25,
            'paginate' => true,
        ]);

        return new Response($this->templating->render(
            '@BkstgNotification/Timeline/timeline.html.twig',
            ['timeline' => $timeline]
        ));
    }
}
