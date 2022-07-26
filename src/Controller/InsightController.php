<?php

namespace App\Controller;

use App\Entity\InsightType;
use App\Repository\InsightRepository;
use App\Repository\InsightTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InsightController extends AbstractController
{
    /**
     * @Route("/insight/{id}", name="app_insight_details")
     */
    public function details(InsightTypeRepository $insightTypeRepository, InsightRepository $insightRepository, $id)
    {
        $insightType = $insightTypeRepository->find($id);
        $insights = $insightRepository->getLastByType($insightType);
        return $this->render('home/index.html.twig', [
            'insight_type' => $insightType,
            'insights' => $insights,
        ]);
    }
}
