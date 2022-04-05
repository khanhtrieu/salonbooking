<?php

namespace App\Controller;

use App\Entity\Services;
use App\Entity\Shop;
use App\Entity\ShopService;
use App\Entity\SpecialDate;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
        $dateNotAvailable = $doctrine->getRepository(SpecialDate::class)->getShopDateUnAvailable($id);
        $skipDates = [];
        if (count($dateNotAvailable) > 0) {
            foreach ($dateNotAvailable as $date) {
                $skipDates[] = $date['date']->format('Y-m-d');
            }
        }
        $url = $this->generateUrl('load_availabletime', array(
            'id_shop' => $id,
            'skipdate' => $skipDates
                )
        );
        return $response = new JsonResponse(['shop' => $shop, 'url' => $url]);
    }
    /**
     * @Route("/service/confirm", name="booking_confirm")
     */
    public function Confirm(ManagerRegistry $doctrine, CustomerRepository $customerRepository, SessionInterface $session): Response {
        $shopName = $session->get('shopName');
        $bookingDate = $session->get('bookingDate');
        $bookingTime = $session->get('bookingTime');
        $customerInfo = null;
        if ($shopName == null || $bookingDate == null || $bookingTime == null){
            $this->addFlash('error', 'Your appointment was not booked properly!');
        }
        // else{
        //     $userid = $session->get('userid');
        //     if ($userid <= 0) {
        //         return $this->redirectToRoute('customer_login');
        //     }
        //     $customerInfo = $doctrine->getRepository(Customer::class)->findBy(['id' => $userid]);
        // }
        $userid = $session->get('userid');
            if ($userid <= 0) {
                return $this->redirectToRoute('customer_login');
            }
            $customerInfo = $doctrine->getRepository(Customer::class)->findBy(['id' => $userid]);
        
        //var_dump($customerInfo);
        return $this->render('service/confirm.html.twig', [
             'customer' => $customerInfo
                //'Service: '.$service->getName()
        ]);
    }

    /**
     * @Route("/loadavaitime/", name="load_availabletime")
     */
    public function LoadAvailableTime(ManagerRegistry $doctrine, Request $request): JsonResponse {
        $id_shop = $request->query->get('id_shop');
        $id_service = $request->query->get('id_service');
        $booking_date = $request->query->get('booking_date');
        //$shop = $doctrine->getRepository(ShopService::class)->LoadAvaiTime($id);
        return $response = new JsonResponse([]);
    }
    // private function getCustomer(CustomerRepository $customerRepository, SessionInterface $session) {
    //     $userid = $session->get('userid');
    //     $customer = null;
    //     if (empty($userid) || ( $customer = $customerRepository->find($userid)) == null) {
    //         return null;
    //     }
    //     return $customer;
    // }

}
