<?php

namespace App\Utils;

use App\Twig\AppExtension;
use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeFrontPage extends CategoryTreeAbstract {
	public $html_1 = '<ul>';
	public $html_2 = '<li>';
	public $html_3 = '<a href="';
	public $html_4 = '">';
	public $html_5 = '</a>';
	public $html_6 = '</li>';
	public $html_7 = '</ul>';

	public function get_category_list_and_parent(int $id): string {
		// Twig extension to slugify url's for categories
		$this->slugger = new AppExtension();
		$parent_data = $this->get_main_parent($id); // main parent of sub-category
		$this->main_parent_name = $parent_data['name']; // for accessing in the view
		$this->main_parent_id = $parent_data['id']; // for accessing in the view
		$key = array_search($id, array_column($this->categories_array_from_db, 'id'));
		$this->current_category_name = $this->categories_array_from_db[$key]['name']; // for accessing in the view
		$categories_array = $this->build_tree($parent_data['id']); // builds array for generating nested html list
		return $this->get_category_list($categories_array);
	}

	public function get_category_list(array $categories_array) {
		$this->category_list .= $this->html_1;
		foreach ($categories_array as $value) {
			$category_name_slugified = $this->slugger->slugify($value['name']);
			$url = $this->url_generator->generate(
				'video_list',
				[
					'categoryname' => $category_name_slugified,
					'id' => $value['id']
				]
			);
			$category_name = $value['name'];
			$this->category_list .= $this->html_2 . $this->html_3 . $url . $this->html_4 . $category_name . $this->html_5;
			if (!empty($value['children'])) {
				$this->get_category_list($value['children']);
			}
			$this->category_list .= $this->html_6;
		}
		$this->category_list .= $this->html_7;
		return $this->category_list;
	}

	public function get_main_parent(int $id): array {
		$key = array_search($id, array_column($this->categories_array_from_db, 'id'));
		if ($this->categories_array_from_db[$key]['parent_id'] != null) {
			return $this->get_main_parent($this->categories_array_from_db[$key]['parent_id']);
		}
		else {
			return [
				'id' => $this->categories_array_from_db[$key]['id'],
				'name' => $this->categories_array_from_db[$key]['name'],
			];
		}
	}
}
