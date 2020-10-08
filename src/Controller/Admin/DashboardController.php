<?php

namespace App\Controller\Admin;

use App\Entity\InsightType;
use App\Entity\Journal;
use App\Entity\Source;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/", name="app_admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Publicator');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Journal', 'fa fa-newspaper-o', Journal::class);
        yield MenuItem::linkToCrud('Insight', 'fa fa-thermometer-quarter', InsightType::class);
        yield MenuItem::linkToCrud('Source', 'fa fa-globe', Source::class);
    }
}
