<?php

namespace App\Utils\AbstractClasses;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class CategoryTreeAbstract {

	public $categories_array_from_db;

	protected static $db_connection;

	private EntityManagerInterface $entity_manager;

	private UrlGeneratorInterface $url_generator;

	public function __construct(EntityManagerInterface $entity_manager, UrlGeneratorInterface $url_generator) {
		$this->entity_manager = $entity_manager;
		$this->url_generator = $url_generator;
		$this->categories_array_from_db = $this->get_categories();
	}

	abstract public function get_category_list(array $categories_array);

	private function get_categories(): array {
		if (self::$db_connection) {
			return self::$db_connection;
		}
		else {
			$conn = $this->entity_manager->getConnection();
			$sql = 'SELECT * FROM categories';
			$stmt = $conn->prepare($sql);
			$stmt->executeQuery();
			return self::$db_connection = $stmt->fetchAll();
		}
	}
}
