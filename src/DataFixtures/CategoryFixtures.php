<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
		$this->load_main_categories($manager);
		$this->load_subcategories($manager, 'electronics', 1);
    }

	private function load_main_categories(ObjectManager $manager) {
		foreach ($this->get_main_categories_data() as [$name, $id]) {
			$category = new Category();
			$category->setName($name);
			$manager->persist($category);
		}
		$manager->flush();
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
			['Books', 2],
			['Movies', 3],
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
}
