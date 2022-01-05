<?php

namespace App\Controller;

use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\Link\Link;
use App\Entity\Link\LinkInterface;
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
        // где-то тут должна быть валидация ))
        $createDto = new LinkCreateDto(
            $request['long_url'] ?? null,
            $request['title'] ?? null,
            $request['tags'] ?? [],
        );
        $linkService->create($createDto);

        return new JsonResponse(['status' => 1], Response::HTTP_CREATED);
    }

    #[Route('/links/{identifier}', methods: 'PATCH')]
    public function update(
        Request $request,
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $request = $request->request->all();
        // где-то тут должна быть валидация ))
        $updateDto = new LinkUpdateDto(
            new LinkIdentifier($identifier),
            $request['long_url'],
            $request['title'],
            $request['tags'] ?? [],
        );
        $linkService->update($updateDto);

        return new JsonResponse(['status' => 1]);
    }

    #[Route('/links/{identifier}', methods: 'DELETE')]
    public function delete(
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $linkService->delete(new LinkIdentifier($identifier));

        return new JsonResponse(['status' => 1]);
    }

    #[Route('/links/{identifier}', methods: 'GET')]
    public function link(
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $link = $linkService->getByIdentifier(new LinkIdentifier($identifier));

        return new JsonResponse($this->normalize($link));
    }

    #[Route('/links', methods: 'GET')]
    public function index(
        Request $request,
        LinkRepository $linkRepository,
    ): Response {
        $title = $request->query->get('title');
        $tag = $request->query->get('tag');

        if ($title || $tag) {
            $links = $linkRepository->findBy(Link::class, [
                'title' => $title,
                'tags' => [$tag],
            ], null, null);
        } else {
            $links = $linkRepository->findAll(Link::class);
        }

        return new JsonResponse($this->normalize($links));
    }

    private function normalize(array|LinkInterface $data): array
    {
        $toReturn = [];

        if (is_array($data)) {
            /** @var LinkInterface $element */
            foreach ($data as $element) {
                $toReturn[] = [
                    'identifier' => $element->getId(),
                    'title' => $element->getTitle(),
                    'tags' => $element->getTags()
                ];
            }
        } elseif ($data instanceof LinkInterface) {
            $toReturn = [
                'shortened_link' => $data->getId(),
                'title' => $data->getTitle(),
                'tags' => $data->getTags()
            ];
        }

        return $toReturn;
    }
}
