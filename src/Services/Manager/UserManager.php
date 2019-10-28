<?php

namespace App\Services\Manager;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class UserManager
 * @package App\Services\Manager
 */
class UserManager
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TranslatorInterface
     */
    private $trans;
    /**
     * @var Environment
     */
    private $templating;
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(
        UserRepository $repository,
        AuthorizationCheckerInterface $checker,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $trans,
        Environment $templating,
        RequestStack $requestStack,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->repository = $repository;
        $this->checker = $checker;
        $this->eventDispatcher = $eventDispatcher;
        $this->trans = $trans;
        $this->templating = $templating;
        $this->requestStack = $requestStack;
        $this->encoder = $encoder;
    }

    /**
     * @return bool
     */
    public function isLogin(): bool
    {
        return $this->checker->isGranted('IS_AUTHENTICATED_FULLY');
    }
}