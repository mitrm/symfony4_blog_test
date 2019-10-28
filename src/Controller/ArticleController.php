<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use App\Services\Manager\ArticleManager;
use App\Services\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class ArticleController
 * @package App\Controller
 */
class ArticleController extends AbstractController
{

    /**
     * @brief
     * @var ArticleManager
     */
    private $articleManager;

    /**
     * ArticleController constructor.
     * @param ArticleManager $manager
     */
    public function __construct(ArticleManager $manager)
    {
        $this->articleManager = $manager;
    }

    /**
     * @Route("/", name="main", methods={"GET"})
     * @Cache(smaxage="5")
     * @param Paginator $paginator
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(Paginator $paginator, ArticleRepository $articleRepository): Response
    {
        $page = $paginator->getPage();
        $articles = $paginator->getItemList($articleRepository, $page);
        $nbPages = $paginator->countPage($articles);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'nbPages' => $nbPages,
            'page' => $page,
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Route("article/new", name="article_new", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleManager->create($article);
            return $this->redirectToRoute('main');
        }
        return $this->render('article/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Security("article.isOwner(user)")
     * @Route("article/{slug}/edit", name="article_edit", methods={"GET", "POST"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->articleManager->edit($article);
            return $this->redirectToRoute('article_view', ['slug' => $article->getSlug()]);
        }
        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("article/{slug}/view", name="article_view", methods={"GET", "POST"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function view(Request $request, Article $article): Response
    {
        return $this->render('article/view.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_REMEMBERED")
     * @Security("article.isOwner(user)")
     * @Route("article/{slug}/delete", name="article_delete", methods={"GET", "POST"})
     * @param Request $request
     * @param Article $article
     * @return Response
     */
    public function delete(Request $request, Article $article): Response
    {
        $this->articleManager->remove($article);
        return $this->redirectToRoute('main');
    }
}
