<?php

namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract {

	public $categories_array_from_db;

	protected static $db_connection;

	public function __construct(EntityManagerInterface $entity_manager, UrlGeneratorInterface $url_generator) {
		$this->entity_manager = $entity_manager;
		$this->url_generator = $url_generator;
		$this->categories_array_from_db = $this->get_categories();
	}

	abstract public function get_category_list(array $categories_array);

	public function build_tree(int $parent_id = null): array {
		$sub_category = [];
		foreach ($this->categories_array_from_db as $category) {
			if ($category['parent_id'] == $parent_id) {
				$children = $this->build_tree($category['id']);
				if ($children) {
					$category['children'] = $children;
				}
				$sub_category[] = $category;
			}
		}
		return $sub_category;
	}

	private function get_categories(): array {
		if (self::$db_connection) {
			return self::$db_connection;
		}
		else {
			$conn = $this->entity_manager->getConnection();
			$sql = 'SELECT * FROM categories';
			$stmt = $conn->prepare($sql);
			$result_set = $stmt->executeQuery();
			return self::$db_connection = $result_set->fetchAllAssociative();
		}
	}
}
