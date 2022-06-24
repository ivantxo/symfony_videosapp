<?php

namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeFrontPage extends CategoryTreeAbstract {

	public function get_category_list(array $categories_array) {
		$this->category_list .= '<ul>';
		foreach ($categories_array as $value) {
			$category_name = $value['name'];
			$url = $this->url_generator->generate(
				'video_list',
				[
					'categoryname' => $category_name,
					'id' => $value['id']
				]
			);
			$this->category_list .= '<li>' . '<a href="' . $url . '">' . $category_name . '</a></li>';
			if (!empty($value['children'])) {
				$this->get_category_list($value['children']);
			}
		}
		$this->category_list .= '</ul>';
		return $this->category_list;
	}
}
