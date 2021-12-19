<?php

namespace App\Controller;

use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\LinkIdentifier;
use App\Repository\Link\LinkRepository;
use App\Services\Link\LinkServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends AbstractController
{
    #[Route('/links', methods: 'POST')]
    public function create(Request $request, LinkServiceInterface $linkService): Response
    {
        $request = $request->request->all();
        $createDto = new LinkCreateDto(
            $request['long_url'],
            $request['title'],
            $request['tags'],
        );
        $linkService->create($createDto);

        return new JsonResponse();
    }

    #[Route('/links/{identifier}', methods: 'PATCH')]
    public function update(
        Request $request,
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $request = $request->request->all();
        $updateDto = new LinkUpdateDto(
            $identifier,
            $request['long_url'],
            $request['title'],
            $request['tags'],
        );
        $linkService->update($updateDto);

        return new JsonResponse();
    }

    #[Route('/links/{identifier}', methods: 'DELETE')]
    public function delete(
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $linkService->delete(new LinkIdentifier($identifier));

        return new JsonResponse();
    }

    #[Route('/links/{identifier}', methods: 'GET')]
    public function link(
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $link = $linkService->getByIdentifier(new LinkIdentifier($identifier));

        return new JsonResponse($link);
    }

    #[Route('/links', methods: 'GET')]
    public function index(
        Request $request,
        LinkRepository $linkRepository,
    ): Response {
        $title = $request->query->get('title');
        $tag = $request->query->get('tag');

        if ($title || $tag) {
            $links = $linkRepository->findBy([
                'title' => $title,
                'tags' => $tag
            ]);
        } else {
            $links = $linkRepository->findAll();
        }

        return new JsonResponse($links);
    }
}
