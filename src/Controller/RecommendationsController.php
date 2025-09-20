<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\Path;
use App\Repository\RecommendationRepository;
use App\Utils\UrlTool;

final class RecommendationsController extends BaseController
{
    public function __construct(
        private RecommendationRepository $recommendationRepository
    )
    {
    }

    public function index(): void
    {
        $recommendations = $this->recommendationRepository->getAll();

        $this->render(Path::RECOMMENDATIONS_LIST->value, [
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

        $this->render(Path::RECOMMENDATIONS_SHOW->value, [
                'recommendation' => $recommendation,
            ]
        );
    }
}
