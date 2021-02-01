<?php
namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Place;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('BeerTime');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Evénément');
        yield MenuItem::linkToCrud('Lieux', 'fa fa-map-marker', Place::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class);

    }
}
