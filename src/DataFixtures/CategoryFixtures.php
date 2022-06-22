<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture {
    public function load(ObjectManager $manager): void {
		$this->load_main_categories($manager);
    }

	private function load_main_categories(ObjectManager $manager) {
		foreach ($this->get_main_categories_data() as [$name, $id]) {
			$category = new Category();
			$category->setName($name);
			$manager->persist($category);
		}
		$manager->flush();
	}

	private function get_main_categories_data(): array {
		return [
			['Electronics', 1],
			['Books', 2],
			['Movies', 3],
			['Funny', 4],
			['For kids', 5],
			['For adults', 6],
			['Scary', 7],
			['Inspirational', 8],
			['Motivating', 9],
			['Surprising', 10],
		];
	}
}
