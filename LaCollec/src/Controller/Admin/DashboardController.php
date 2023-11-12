<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Selection;
use App\Entity\Videotheque;
use App\Entity\Video;
use App\Entity\Member;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(MemberCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('LaCollec');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Member', 'fas fa-list', Member::class);
        yield MenuItem::linkToCrud('Videotheque', 'fas fa-list', Videotheque::class);
        yield MenuItem::linkToCrud('Video', 'fas fa-list', Video::class);
        yield MenuItem::linkToCrud('Selection', 'fas fa-list', Selection::class);
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
    }
}
