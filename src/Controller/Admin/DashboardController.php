<?php

namespace App\Controller\Admin;

use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Editeur;
use App\Entity\Nationalite;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

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
            ->setTitle('BiblioApi');
    }

    public function configureMenuItems(): iterable
    {
      return [
         MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),

         MenuItem::section('Livres'),
         MenuItem::linkToCrud('Auteurs', 'fa fa-tags', Auteur::class),
         MenuItem::linkToCrud('Livres', 'fa fa-file-text', Livre::class),
         MenuItem::linkToCrud('Genres', 'fa fa-file-text', Genre::class),
         MenuItem::linkToCrud('Editeurs', 'fa fa-file-text', Editeur::class),
         MenuItem::linkToCrud('Nationalités', 'fa fa-file-text', Nationalite::class),
     ];
    }
}
