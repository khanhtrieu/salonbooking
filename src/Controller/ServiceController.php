<?php

namespace App\Controller;

use App\Entity\Services;
use App\Entity\Shop;
use App\Entity\ShopService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/service", name="app_shop")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $title = 'All shops: ';
    
        $shop = $doctrine->getRepository(Shop::class)->findAll();

        if (!$shop) {
            throw $this->createNotFoundException(
                'No shop available'
            );
        }
        
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'title' => $title,
            'shop' => $shop
            //'Service: '.$service->getName()
        ]);
    }

    /**
     * @Route("/loadservice/{id}", name="load_service")
     */
    public function LoadService(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $shop = $doctrine->getRepository(ShopService::class)->LoadServices($id);
        return $response = new JsonResponse($shop);
    }
}
