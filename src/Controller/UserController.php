<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginFormType;
use App\Form\UserRegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Services\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;


/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * @brief
     * @var UserManager
     */
    private $userManager;

    public function __construct(UserManager $manager)
    {
        $this->userManager = $manager;
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authUtils)
    {
        if ($this->userManager->isLogin()) {
            return $this->redirectToRoute('main');
        }
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
        $loginForm = $this->createForm(UserLoginFormType::class);
        return $this->render('user/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'loginForm' => $loginForm->createView(),
            'bodyClass' => 'login',
        ]);
    }

    /**
     * @Route("/reg", name="reg")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardAuthenticatorHandler
     * @param LoginFormAuthenticator $formAuthenticator
     * @return Response|null
     */
    public function register(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardAuthenticatorHandler, LoginFormAuthenticator $formAuthenticator)
    {
        if ($this->userManager->isLogin()) {
            return $this->redirectToRoute('main');
        }
        $registrationForm = $this->createForm(UserRegistrationFormType::class);
        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            /** @var User $user */
            $user = $registrationForm->getData();
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $registrationForm['plainPassword']->getData()
            ));
            $em->persist($user);
            $em->flush();
            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }
        return $this->render('user/reg.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @param Request $request
     */
    public function logout(Request $request, LogoutHandlerInterface $logout)
    {

    }
}
