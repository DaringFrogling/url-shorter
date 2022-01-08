<?php

namespace App\Controller;

use App\Dto\Link\LinkCreateDto;
use App\Dto\Link\LinkUpdateDto;
use App\Entity\Link\LinkInterface;
use App\Entity\StringIdentifier;
use App\Repository\Link\LinkRepository;
use App\Services\Link\LinkServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class LinkController extends AbstractController
{
    #[Route(path: '/links', methods: 'POST')]
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

    #[Route(path: '/links/{identifier<\w{10}>}', methods: 'PATCH')]
    public function update(
        Request $request,
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $request = $request->request->all();
        // где-то тут должна быть валидация ))
        $updateDto = new LinkUpdateDto(
            new StringIdentifier($identifier),
            $request['long_url'],
            $request['title'],
            $request['tags'] ?? [],
        );
        $linkService->update($updateDto);

        return new JsonResponse(['status' => 1]);
    }

    #[Route(path: '/links/{identifier<\w{10}>}', methods: 'DELETE')]
    public function delete(
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $linkService->delete(new StringIdentifier($identifier));

        return new JsonResponse(['status' => 1]);
    }

    #[Route(path: '/links/{identifier<\w{10}>}', methods: 'GET')]
    public function link(
        LinkServiceInterface $linkService,
        string $identifier
    ): Response {
        $link = $linkService->getByShortenedUri(new StringIdentifier($identifier));

        return new JsonResponse($this->normalize($link));
    }

    #[Route(path: '/links', methods: 'GET')]
    public function index(
        Request $request,
        LinkRepository $linkRepository,
    ): Response {
        $query = $request->query->all();

        // Тут можно было сделать query dto объект для более удобного поиска
        $title = $query['query']['title'] ?? null;
        $tags = isset($query['query']['tags']) && !empty($query['query']['tags'])
            ? explode(',', $query['query']['tags'])
            : [];
        // Где-то тут должна быть валидация для query dto
        // Обращение к репозиторию нужно заменить на сервис с аргументом в сигнатуре с query dto объектом
        if ($title || !empty($tags)) {
            $links = $linkRepository->findBy([
                'title' => $title,
                'tags' => $tags,
            ]);
        } else {
            $links = $linkRepository->findAll();
        }

        return new JsonResponse($this->normalize($links));
    }

    /**
     * @param LinkInterface[]|LinkInterface $data
     * @return array
     */
    private function normalize(array|LinkInterface $data): array
    {
        $toReturn = [];

        if (is_array($data)) {
            /** @var LinkInterface $element */
            foreach ($data as $element) {
                $toReturn[] = [
                    'identifier' => $element->getIdentifier()->getValue(),
                    'shortened_uri' => $element->getShortenedUri()->getValue(),
                    'title' => $element->getTitle(),
                    'tags' => $element->getTags()
                ];
            }
        } elseif ($data instanceof LinkInterface) {
            $toReturn = [
                'identifier' => $data->getIdentifier()->getValue(),
                'shortened_uri' => $data->getShortenedUri()->getValue(),
                'title' => $data->getTitle(),
                'tags' => $data->getTags()
            ];
        }

        return $toReturn;
    }
}
