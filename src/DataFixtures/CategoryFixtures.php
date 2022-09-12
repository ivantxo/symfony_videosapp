<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
		$this->load_main_categories($manager);
		$this->load_electronics($manager);
		$this->load_computers($manager);
		$this->load_laptops($manager);
		$this->load_books($manager);
		$this->load_movies($manager);
		$this->load_romance($manager);
    }

	private function load_main_categories(ObjectManager $manager) {
		foreach ($this->get_main_categories_data() as [$name, $id]) {
			$category = new Category();
			$category->setName($name);
			$manager->persist($category);
		}
		$manager->flush();
	}

	private function load_electronics(ObjectManager $manager) {
		$this->load_subcategories($manager, 'electronics', 1);
	}

	private function load_computers(ObjectManager $manager) {
		$this->load_subcategories($manager, 'computers', 6);
	}

	private function load_laptops($manager)
	{
		$this->load_subcategories($manager,'Laptops',8);
	}

	private function load_books($manager)
	{
		$this->load_subcategories($manager,'Books',3);
	}

	private function load_movies($manager)
	{
		$this->load_subcategories($manager,'Movies',4);
	}

	private function load_romance($manager)
	{
		$this->load_subcategories($manager,'Romance',18);
	}

	private function load_subcategories(ObjectManager $manager, string $category, int $parent_id) {
		$parent = $manager->getRepository(Category::class)->find($parent_id);
		$method_name = "get_{$category}_data";
		foreach ($this->$method_name() as [$name, $id]) {
			$category = new Category();
			$category->setName($name);
			$category->setParent($parent);
			$manager->persist($category);
		}
		$manager->flush();
	}

	private function get_main_categories_data(): array {
		return [
			['Electronics', 1],
			['Movies', 2],
			['Books', 3],
			['Toys', 4],
		];
	}

	private function get_electronics_data(): array {
		return [
			['Cameras', 5],
			['Computers', 6],
			['Mobile Phones', 7],
		];
	}

	private function get_computers_data(): array {
		return [
			['Laptops', 8],
			['Desktops', 9],
		];
	}

	private function get_laptops_data(): array {
		return [
			['Apple',10],
			['Asus',11],
			['Dell',12],
			['Lenovo',13],
			['HP',14]
		];
	}


	private function get_books_data(): array {
		return [
			['Children\'s Books',15],
			['Kindle eBooks',16],
		];
	}


	private function get_movies_data(): array	{
		return [
			['Family',17],
			['Romance',18],
		];
	}


	private function get_romance_data(): array {
		return [
			['Romantic Comedy',19],
			['Romantic Drama',20],
		];
	}
}
