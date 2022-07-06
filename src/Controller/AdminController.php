<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Utils\CategoryTreeAdminList;
use App\Utils\CategoryTreeAdminOptList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
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
	 * @Route("/categories", name="categories", methods={"GET", "POST"})
	 */
	public function categories(CategoryTreeAdminList $categories, Request $request): Response {
		$categories->get_category_list($categories->build_tree());

		$category = new Category();
		$form = $this->createForm(CategoryType::class, $category);
		$form->handleRequest($request);
		$is_invalid = null;

		if ($this->save_category($category, $form, $request)) {
			return $this->redirectToRoute('categories');
		}
		else if ($request->isMethod('post')) {
			$is_invalid = ' is-invalid';
		}

		return $this->render('admin/categories.html.twig', [
			'categories' => $categories->category_list,
			'form' => $form->createView(),
			'is_invalid' => $is_invalid,
		]);
	}

	/**
	 * @Route("/edit_category/{id}", name="edit_category", methods={"GET", "POST"})
	 */
	public function edit_category(Category $category, Request $request): Response {
		$form = $this->createForm(CategoryType::class, $category);
		$is_invalid = null;
		if ($this->save_category($category, $form, $request)) {
			return $this->redirectToRoute('categories');
		}
		else if ($request->isMethod('post')) {
			$is_invalid = ' is-invalid';
		}
		return $this->render(
			'admin/edit_category.html.twig',
			[
				'category' => $category,
				'form' => $form->createView(),
				'is_invalid' => $is_invalid,
			]
		);
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

	public function get_all_categories(CategoryTreeAdminOptList $categories, $edited_category = null) {
		$categories->get_category_list($categories->build_tree());
		return $this->render(
			'admin/_all_categories.html.twig',
			[
				'categories' => $categories,
				'edited_category' => $edited_category,
			]
		);
	}

	private function save_category(Category $category, FormInterface $form, Request $request): bool {
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$category->setName($request->request->get('category')['name']);

			$repository = $this->doctrine->getRepository(Category::class);
			$parent = $repository->find($request->request->get('category')['parent']);
			$category->setParent($parent);

			$entity_manager = $this->doctrine->getManager();
			$entity_manager->persist($category);
			$entity_manager->flush();
			return true;
		}
		return false;
	}
}
