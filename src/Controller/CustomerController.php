<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use App\Form\RegistrationFormType;
use App\Form\CustomerEditForm;
use App\Form\ChangePasswordForm;
use App\Form\ForgotPasswordForm;
use App\Form\CustomerLoginForm;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\FormError;

class CustomerController extends AbstractController {

    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier) {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/customer", name="customer")
     */
    public function index(CustomerRepository $customerRepository, SessionInterface $session): Response {
        $customer = $this->getCustomer($customerRepository, $session);
        if (empty($customer)) {
            return $this->redirectToRoute('customer_login');
        }
        return $this->render('customer/index.html.twig');
    }

    /**
     * @Route("/customer/login", name="customer_login")
     */
    public function login(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SessionInterface $session): Response {
        if ($session->get('userid') > 0) {
            return $this->redirectToRoute('customer');
        }
        $form = $this->createForm(CustomerLoginForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('plainPassword')->getData();

            $customerLogin = $entityManager->getRepository(Customer::class)->findOneBy(['email' => $email, 'isVerified' => true]);
            if (!empty($customerLogin) && $customerLogin->getId() > 0 && $userPasswordHasher->isPasswordValid($customerLogin, $password)) {
                $session->set('userid', $customerLogin->getId());
                return $this->redirectToRoute('customer');
            } else {
                $this->addFlash('error', 'Invalid Email or Password');
            }
        }
        return $this->render('customer/login.html.twig', [
                    'loginForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/customer/logout", name="customer_logout")
     */
    public function logout(SessionInterface $session): Response {
        $session->remove('userid');
        return $this->redirectToRoute('customer_login');
    }

    /**
     * @Route("/customer/register", name="customer_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response {
        $user = new Customer();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                    $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                    )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            $this->sendEmailVerifier($user);
            return $this->redirectToRoute('customer');
        }

        return $this->render('customer/register.html.twig', [
                    'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("customer/verify/email", name="customer_verify_email")
     */
    public function verifyUserEmail(Request $request, CustomerRepository $customerRepository): Response {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $signature = $request->query->get('signature');
        if (!empty($signature)) {
            $customer = $customerRepository->findOneBy(['verifySignature' => $signature]);
            var_dump($signature);exit;
            if (!empty($customer)) {
                try {
                    $this->emailVerifier->handleEmailConfirmation($request, $customer);
                    return $this->redirectToRoute('customer_login');
                } catch (VerifyEmailExceptionInterface $exception) {
                    $this->addFlash('verify_email_error', $exception->getReason());
                    return $this->redirectToRoute('customer_register');
                }
            }
        }
        // validate email confirmation link, sets User::isVerified=true and persists
        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('verify_email_error', 'Invalid request');

        return $this->redirectToRoute('customer_register');
    }

    /**
     * @Route("/customer/edit/profile", name="customer_edit_profile")
     */
    public function edit(Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response {
        $customer = $this->getCustomer($customerRepository, $session);
        if (empty($customer)) {
            return $this->redirectToRoute('customer_login');
        }

        $form = $this->createForm(CustomerEditForm::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // var_dump($customer);
            $customer->setFirstName($form->get('firstName')->getData());
            $customer->setLastName($form->get('lastName')->getData());
            $customer->setAddress($form->get('address')->getData());
            $customer->setAddress2($form->get('address2')->getData());
            $customer->setPhone($form->get('phone')->getData());
            $customer->setCity($form->get('city')->getData());
            $customer->setState($form->get('state')->getData());
            $customer->setZipcode($form->get('zipcode')->getData());
            $customer->setAdditionalInfo($form->get('additionalInfo')->getData());
            //$entityManager->persist($customer);
            $entityManager->flush();
            return $this->redirectToRoute('customer_edit_profile');
        }



        return $this->render('customer/editprofile.html.twig', [
                    'customer' => $customer,
                    'editForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/customer/edit/password", name="customer_edit_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, CustomerRepository $customerRepository, EntityManagerInterface $entityManager, SessionInterface $session): Response {
        $customer = $this->getCustomer($customerRepository, $session);
        if (empty($customer)) {
            return $this->redirectToRoute('customer_login');
        }

        $form = $this->createForm(ChangePasswordForm::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $password = $form->get('oldPassword')->getData();
            $customerLogin = $entityManager->getRepository(Customer::class)->findOneBy(['email' => $email, 'isVerified' => true]);
            if ($userPasswordHasher->isPasswordValid($customerLogin, $password)) {
                $customer->setPassword(
                        $userPasswordHasher->hashPassword(
                                $customer,
                                $form->get('newPassword')->getData()
                        )
                );
                $entityManager->flush();
                $this->addFlash('success', 'Your password has been reset!');
                return $this->redirectToRoute('customer');
            } else {
                $this->addFlash('error', 'Your attempt is failed');
            }
        }
        return $this->render('customer/changepassword.html.twig', [
                    'customer' => $customer,
                    'changePasswordForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/customer/forgotpassword", name="customer_forgotpassword")
     */
    public function forgotPassword(Request $request, CustomerRepository $customerRepository, EntityManagerInterface $entityManager): Response {
        //$customer = new Customer();
        $form = $this->createForm(ForgotPasswordForm::class);
        if ($request->isMethod('POST')) {
            $form->submit($request->request->get('resetemail'));
            if ($form->isSubmitted() && $form->isValid()) { //isValid ??
                $email = $form->get('resetemail')->getData();
                $customer = $entityManager->getRepository(Customer::class)->findOneBy(['email' => $email]);
                if (!empty($customer)) {
                    return $this->redirectToRoute('customer');
                }
                $form->get('resetemail')->addError(new FormError("User cannot found"));
            }
        }

        return $this->render('customer/forgotpassword.html.twig', [
                    'forgotPasswordForm' => $form->createView()
        ]);
    }

    private function getCustomer(CustomerRepository $customerRepository, SessionInterface $session) {
        $userid = $session->get('userid');
        $customer = null;
        if (empty($userid) || ( $customer = $customerRepository->find($userid)) == null) {
            return null;
        }
        return $customer;
    }

    private function sendEmailVerifier(Customer $user) {
        // generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('customer_verify_email', $user,
                (new TemplatedEmail())
                        ->from(new Address('test@gmail.com', 'Webmaster'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('customer/confirmation_email.html.twig')
        );
        // do anything else you need here, like send an email
    }

}
