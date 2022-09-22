<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Video;
use App\Utils\CategoryTreeFrontPage;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\UserType;

class FrontController extends AbstractController {
	private ManagerRegistry $doctrine;

	public function __construct(ManagerRegistry $doctrine) {
		$this->doctrine = $doctrine;
	}

    /**
     * @Route("/", name="main_page")
     */
    public function index(): Response {
        return $this->render('front/index.html.twig');
    }

	/**
	 * @Route("/video-list/category/{categoryname},{id}/{page}", defaults={"page": "1"}, name="video_list")
	 */
	public function video_list(
		$id,
		$page,
		CategoryTreeFrontPage $categories,
		Request $request
	): Response {
		$ids = $categories->getChildIds($id);
		$ids[] = $id;
		$videos = $this->doctrine
			->getRepository(Video::class)
			->findByChildIds($ids, $page, $request->get('sortby'));
		$categories->get_category_list_and_parent($id);
		return $this->render('front/video_list.html.twig',
			[
				'sub_categories' => $categories,
				'videos' => $videos,
			]
		);
	}

	/**
	 * @Route("/video-details", name="video_details")
	 */
	public function video_details(): Response {
		return $this->render('front/video_details.html.twig');
	}

	/**
	 * @Route("/search-results/{page}", methods={"GET"}, defaults={"page": "1"}, name="search_results")
	 */
	public function search_results($page, Request $request): Response {
		$videos = null;
		if ($query = $request->get('query')) {
			$videos = $this->doctrine
				->getRepository(Video::class)
				->findByTitle($query, $page, $request->get('sortby'));
			if (!$videos->getItems()) $videos = null;
		}
		return $this->render(
			'front/search_results.html.twig',
			[
				'videos' => $videos,
				'query' => $query,
			]
		);
	}

	/**
	 * @Route("/pricing", name="pricing")
	 */
	public function pricing(): Response {
		return $this->render('front/pricing.html.twig');
	}

	/**
	 * @Route("/register", name="register")
	 */
	public function register(Request $request): Response {
		$user = new User();
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			dd('Register new user');
		}
		return $this->render('front/register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @Route("/login", name="login")
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response {
		return $this->render('front/login.html.twig', [
			'error' => $authenticationUtils->getLastAuthenticationError()
		]);
	}

	/**
	 * @Route("/logout", name="logout")
	 */
	public function logout(): void
	{
		throw new \Exception('This should never be reached!');
	}

	/**
	 * @Route("/payment", name="payment")
	 */
	public function payment(): Response {
		return $this->render('front/payment.html.twig');
	}

	public function main_categories(): Response {
		$categories = $this->doctrine
			->getRepository(Category::class)
			->findBy(['parent' => null], ['name' => 'ASC']);
		return $this->render(
			'front/_main_categories.html.twig',
			[
				'categories' => $categories
			]
		);
	}
}
