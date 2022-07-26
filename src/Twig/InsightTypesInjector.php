<?php

namespace App\Twig;

use App\Repository\InsightTypeRepository;

class InsightTypesInjector
{
    /**
     * @var InsightTypeRepository
     */
    private $insightTypeRepository;

    /**
     * @param InsightTypeRepository $insightTypeRepository
     */
    public function __construct(InsightTypeRepository $insightTypeRepository)
    {
        $this->insightTypeRepository = $insightTypeRepository;
    }

    public function all()
    {
        return $this->insightTypeRepository->findAll();
    }
}
