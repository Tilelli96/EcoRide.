<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Repository\CovoiturageRepository;
use App\Entity\Covoiturage;
use App\Entity\User;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
#[isGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private CovoiturageRepository $CovoiturageRepository
    ) {
    }

    public function index(): Response
    {
        $chartCovoiturages = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chartGains = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $covoiturages = $this->CovoiturageRepository->countCovoituragesPerDay();
        $gains = $this->CovoiturageRepository->getGainsParJour();
        $total = $this->CovoiturageRepository->getTotal();

        $labelCovoiturages = [];
        $dataCovoiturages= [];

        $labelGains = [];
        $dataGains= [];

        foreach($covoiturages as $covoiturage){
            $labelCovoiturages[] = $covoiturage['jour'];
            $dataCovoiturages[] = $covoiturage['nombre_covoiturages'];
        }

        foreach($gains as $gain){
            $labelGains[] = $gain['jour'];
            $dataGains[] = $gain['credits_gagnes'];
        }
        
        $chartCovoiturages->setData([
            'labels' => $labelCovoiturages,
            'datasets' => [
                [
                    'label' => 'Nombre De Covoiturage Par Jour',
                    'backgroundColor' => 'rgb(128, 145, 125)',
                    'borderColor' => 'rgb(128, 145, 125)',
                    'data' => $dataCovoiturages
                ],
            ],
        ]);

        $chartGains->setData([
            'labels' => $labelGains,
            'datasets' => [
                [
                    'label' => 'Gains De La Plateforme Par Jour',
                    'backgroundColor' => 'rgb(202, 118, 59)',
                    'borderColor' => 'rgb(202, 118, 59)',
                    'data' => $dataGains
                ],
            ],
        ]);

        $chartCovoiturages->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        $chartGains->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('admin/dashboard.html.twig', [
            'chartCovoiturages' => $chartCovoiturages,
            'chartGains' => $chartGains,
            'total' =>$total
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EcoRide - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Accueil', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-exit');
    }
}
