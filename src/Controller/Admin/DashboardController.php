<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Feedback;
use App\Entity\Settings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(BookCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Backend');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Back', 'fa fa-back', '/');
        yield MenuItem::linkToCrud('Books', 'fa fa-book', Book::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-bars', Category::class);
        yield MenuItem::linkToCrud('Feedback', 'fa fa-message', Feedback::class);
        yield MenuItem::linkToCrud('Settings', 'fa fa-settings', Settings::class);
    }
}
