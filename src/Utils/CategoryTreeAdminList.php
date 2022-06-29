<?php

namespace App\Utils;

use App\Utils\AbstractClasses\CategoryTreeAbstract;
use Doctrine\Persistence\ManagerRegistry;

class CategoryTreeAdminList extends CategoryTreeAbstract {

	private ManagerRegistry $doctrine;

	public $html_1 = '<ul class="fa-ul text-left">';
	public $html_2 = '<li><i class="fa-li fa fa-arrow-right"></i>  ';
	public $html_3 = '<a href="';
	public $html_4 = '">';
	public $html_5 = '</a> <a onclick="return confirm(\'Are you sure?\');" href="';
	public $html_6 = '">';
	public $html_7 = '</a>';
	public $html_8 = '</li>';
	public $html_9 = '</ul>';

	public function get_category_list(array $categories_array) {
		$this->category_list .= $this->html_1;
		foreach ($categories_array as $value) {
			$url_edit = $this->url_generator->generate('edit_category', ['id' => $value['id']]);
			$url_delete = $this->url_generator->generate('delete_category', ['id' => $value['id']]);
			$this->category_list .= $this->html_2 . $value['name'] . $this->html_3 . $url_edit . $this->html_4 .
				' Edit' . $this->html_5 . $url_delete . $this->html_6 . 'Delete' . $this->html_7;
			if (!empty($value['children'])) {
				$this->get_category_list($value['children']);
			}
			$this->category_list .= $this->html_8;
		}
		$this->category_list .= $this->html_9;
		return $this->category_list;
	}
}
