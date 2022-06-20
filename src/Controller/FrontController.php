<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController {
    /**
     * @Route("/", name="main_page")
     */
    public function index(): Response {
        return $this->render('front/index.html.twig');
    }

	/**
	 * @Route("/video-list", name="video_list")
	 */
	public function video_list(): Response {
		return $this->render('front/video_list.html.twig');
	}

	/**
	 * @Route("/video-details", name="video_details")
	 */
	public function video_details(): Response {
		return $this->render('front/video_details.html.twig');
	}

	/**
	 * @Route("/search-results", methods={"POST"}, name="search_results")
	 */
	public function search_results(): Response {
		return $this->render('front/search_results.html.twig');
	}
}
