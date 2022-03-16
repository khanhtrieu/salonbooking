<?php

namespace App\Controller;

use App\Entity\Services;
use App\Entity\Shop;
use App\Entity\ShopService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController {

    /**
     * @Route("/service", name="app_shop")
     */
    public function index(ManagerRegistry $doctrine): Response {
        $title = 'All shops: ';

        $shops = $doctrine->getRepository(Shop::class)->findBy(['Active' => 1]);

        if (!$shops) {
            throw $this->createNotFoundException(
                    'No shop available'
            );
        }

        return $this->render('service/index.html.twig', [
                    'shops' => $shops
                        //'Service: '.$service->getName()
        ]);
    }

    /**
     * @Route("/loadservice/{id}", name="load_service")
     */
    public function LoadService(ManagerRegistry $doctrine, int $id): JsonResponse {
        $shop = $doctrine->getRepository(ShopService::class)->LoadServices($id);
        $url = $this->generateUrl('load_availabletime', array(
            'id_shop' => $id
            )
        );
        return $response = new JsonResponse(['shop'=> $shop,'url' => $url]);
    }

    /**
     * @Route("/loadavaitime/", name="load_availabletime")
     */
    public function LoadAvailableTime(ManagerRegistry $doctrine, Request $request): JsonResponse {
        $id_shop = $request->query->get('id_shop');
        $id_service = $request->query->get('id_service');
        //$shop = $doctrine->getRepository(ShopService::class)->LoadAvaiTime($id);
        return $response = new JsonResponse([]);
    }

}
