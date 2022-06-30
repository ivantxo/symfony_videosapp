<?php

namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;

class CategoryTreeAdminOptList extends CategoryTreeAbstract {
	public function get_category_list(array $categories_array, $repeat = 0): array {
		foreach ($categories_array as $value) {
			$this->category_list[] = ['name'=> str_repeat("-",$repeat) . $value['name'], 'id'=>$value['id']];

			if (!empty($value['children'])) {
				$repeat = $repeat + 2;
				$this->get_category_list($value['children'], $repeat);
				$repeat = $repeat - 2;
			}

		}
		return $this->category_list;
	}
}
