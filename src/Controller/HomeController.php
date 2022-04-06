<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Services;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function index(ManagerRegistry $doctrine, SessionInterface $session): Response {
        $userid = $session->get('userid');
        $loggedIn = false;
        if ($userid != null) {
            $loggedIn = true;
        }
        $services = $doctrine->getRepository(Services::class)->getListHomepageServiceActivated();
        return $this->render('home/index.html.twig', [
                    'controller_name' => 'HomeController',
                    'services' => $services,
                    'loggedIn' => $loggedIn
        ]);
    }

}
