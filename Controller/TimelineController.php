<?php

declare(strict_types=1);

/*
 * This file is part of the BkstgTimelineBundle package.
 * (c) Luke Bainbridge <http://www.lukebainbridge.ca/>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bkstg\TimelineBundle\Controller;

use Bkstg\CoreBundle\Controller\Controller;
use Bkstg\TimelineBundle\Entity\Action;
use Spy\Timeline\Driver\ActionManagerInterface;
use Spy\Timeline\Driver\TimelineManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TimelineController extends Controller
{
    /**
     * Renders the current user's timeline stream.
     *
     * @param TokenStorageInterface    $token_storage    The token storage service.
     * @param TimelineManagerInterface $timeline_manager The Timeline manager service.
     * @param ActionManagerInterface   $action_manager   The action manager service.
     * @param Request                  $request          The incoming request.
     *
     * @return Response
     */
    public function timelineAction(
        TokenStorageInterface $token_storage,
        TimelineManagerInterface $timeline_manager,
        ActionManagerInterface $action_manager,
        Request $request
    ): Response {
        $user = $token_storage->getToken()->getUser();
        $subject = $action_manager->findOrCreateComponent($user);
        $timeline = $timeline_manager->getTimeline($subject, [
            'page' => $request->query->get('page') ?: 1,
            'max_per_page' => 25,
            'paginate' => true,
        ]);

        return new Response($this->templating->render(
            '@BkstgTimeline/Timeline/timeline.html.twig',
            ['timeline' => $timeline]
        ));
    }
}
