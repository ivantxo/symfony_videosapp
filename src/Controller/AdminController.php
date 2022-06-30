<?php

namespace App\Controller;

use App\Entity\Category;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController {

	private ManagerRegistry $doctrine;

	public function __construct(ManagerRegistry $doctrine) {
		$this->doctrine = $doctrine;
	}

	/**
     * @Route("/", name="admin_main_page")
     */
    public function index(): Response {
        return $this->render('admin/my_profile.html.twig');
    }

	/**
	 * @Route("/categories", name="categories")
	 */
	public function categories(CategoryTreeAdminList $categories): Response {
		$categories->get_category_list($categories->build_tree());
		return $this->render('admin/categories.html.twig', [
			'categories' => $categories->category_list
		]);
	}

	/**
	 * @Route("/delete_category/{id}", name="delete_category")
	 */
	public function delete_category(Category $category): Response {
		$entity_manager = $this->doctrine->getManager();
		$entity_manager->remove($category);
		$entity_manager->flush();
		return $this->redirectToRoute('categories');
	}

	/**
	 * @Route("/videos", name="videos")
	 */
	public function videos(): Response {
		return $this->render('admin/videos.html.twig');
	}

	/**
	 * @Route("/upload_video", name="upload_video")
	 */
	public function upload_video(): Response {
		return $this->render('admin/upload_video.html.twig');
	}

	/**
	 * @Route("/users", name="users")
	 */
	public function users(): Response {
		return $this->render('admin/users.html.twig');
	}

	/**
	 * @Route("/edit_category/{id}", name="edit_category")
	 */
	public function edit_category(): Response {
		return $this->render('admin/edit_category.html.twig');
	}

	public function get_all_categories(CategoryTreeAdminOptList $categories) {
		$categories->get_category_list($categories->build_tree());
		return $this->render(
			'admin/_all_categories.html.twig',
			[
				'categories' => $categories
			]
		);
	}
}
