<?php

namespace App\Controller;

use App\Dto\Stats\FollowLinkDto;
use App\Entity\StringIdentifier;
use App\Services\Link\LinkServiceInterface;
use App\Services\Stats\StatsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(host: 'url-sh.local')]
class MainController extends AbstractController
{
    #[Route(path: '/{identifier<\w{10}>}', methods: 'GET')]
    public function followLink(
        Request $request,
        LinkServiceInterface $linkService,
        StatsServiceInterface $statsService,
        string $identifier
    ): Response {
        $link = $linkService->getByShortenedUri(new StringIdentifier($identifier));
        $fullUserAgent = get_browser();

        if ($fullUserAgent) {
            $userAgent = [
                'parent' => $fullUserAgent->parent,
                'browser' => $fullUserAgent->browser,
                'platform' => $fullUserAgent->platform,
                'version' => $fullUserAgent->version,
                'device_type' => $fullUserAgent->device_type,
            ];
        }

        $followLinkDto = new FollowLinkDto(
            $link,
            $request->getClientIps()[0],
            $userAgent ?? [],
        );
        $statsService->followLink($followLinkDto);

        return new RedirectResponse($link->getOriginalUrl());
    }
}