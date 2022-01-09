<?php

namespace App\Controller;

use App\ConstantBag\NormalizerContext;
use App\Entity\IntIdentifier;
use App\Services\Stats\StatsServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(host: 'url-shorter.local')]
class StatsController extends AbstractController
{
    #[Route(path: '/stats/{identifier<\d+>}', methods: 'GET')]
    public function stats(
        StatsServiceInterface $statService,
        int $identifier
    ): Response {
        $stats = $statService->countTotalAndUniqueVisits(new IntIdentifier($identifier));

        return new JsonResponse($this->normalize($stats, NormalizerContext::SINGLE));
    }

    #[Route(path: '/stats', methods: 'GET')]
    public function index(StatsServiceInterface $statService): Response
    {
        $stats = $statService->countTotalAndUniqueVisits();

        return new JsonResponse($this->normalize($stats, NormalizerContext::ALL));
    }

    /**
     * @param array $data
     * @param string $context
     * @return array
     */
    private function normalize(array $data, string $context): array
    {
        $toReturn = [];

        if ($context === NormalizerContext::SINGLE) {
            $toReturn['link_id'] = $data[0]['link_id'];
            $toReturn['total_views'] = $data[0]['total_views'];
            $toReturn['unique_views'] = $data[1]['unique_views'];
        } elseif ($context === NormalizerContext::ALL) {
            [$totals, $uniques] = $data;
            $key = 0;
            $toReturn = array_reduce(
                $totals,
                function (array $initial, array $element) use (&$uniques, &$key) {
                    $initial[$key]['link_id'] = $element['link_id'];
                    $initial[$key]['total_views'] = $element['total_views'];

                    $uniqueKey = $this->getFirstLevelKeyForValue($uniques, $element);
                    $initial[$key]['unique_views'] = $uniques[$uniqueKey]['unique_views'];
                    unset($uniques[$uniqueKey]);
                    $key++;

                    return $initial;
                },
                $toReturn
            );
        }

        return $toReturn;
    }

    /**
     * Gets first level key of the multidimensional array for searched value.
     *
     * @param array $uniques
     * @param array $element
     * @return int
     */
    private function getFirstLevelKeyForValue(array $uniques, array $element): int
    {
        foreach ($uniques as $k => $value) {
            if ($value['link_id'] === $element['link_id']){
                $key = $k;
                break;
            }
        }

        if (!isset($key)) {
            throw new \RuntimeException('Unable to perform normalization');
        }

        return $key;
    }
}