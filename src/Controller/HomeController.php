<?php

namespace App\Controller;

use App\Repository\InsightRepository;
use App\Repository\InsightTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(InsightRepository $insightRepository, InsightTypeRepository $insightTypeRepository)
    {
        $insightImpact = $insightTypeRepository->findOneBy(['name'=>'Impact factor']);
        return $this->redirectToRoute('app_insight_details', ['id' => $insightImpact->getId()]);
    }
}
