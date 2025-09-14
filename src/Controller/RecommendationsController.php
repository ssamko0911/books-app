<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\Path;
use App\Repository\RecommendationRepository;
use App\Utils\UrlTool;

class RecommendationsController
{
    private RecommendationRepository $recommendationRepository;

    public function __construct()
    {
        $this->recommendationRepository = new RecommendationRepository();
    }

    public function index(): void
    {
        $recommendations = $this->recommendationRepository->getAll();

        UrlTool::view(Path::RECOMMENDATIONS_LIST->value, [
                'recommendations' => $recommendations,
            ]
        );
    }

    public function show(int $id): void
    {
        $recommendation = $this->recommendationRepository->findOneById($id);

        if (null === $recommendation) {
            UrlTool::abort();
        }

        UrlTool::view(Path::RECOMMENDATIONS_SHOW->value, [
            'recommendation' => $recommendation,
        ]);
    }
}
