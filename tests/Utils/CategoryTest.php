<?php

namespace App\Tests\Utils;


use App\Utils\CategoryTreeFrontPage;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
	protected $mockedCategoryTreeFrontPage;

    protected function setUp(): void
	{
		$_SERVER['KERNEL_CLASS'] = 'App\Kernel';
		$kernel = self::bootKernel();
		$urlGenerator = $kernel->getContainer()->get('router');
		$this->mockedCategoryTreeFrontPage = $this->getMockBuilder('App\Utils\CategoryTreeFrontPage')
			->disableOriginalConstructor()
			->setMethods()
			->getMock();
		$this->mockedCategoryTreeFrontPage->url_generator = $urlGenerator;
	}

	/**
	 * @dataProvider dataForTestCategoryTreeFrontPage
	 */
	public function testCategoryTreeFrontPage($string, $array, $id): void
	{
		$this->mockedCategoryTreeFrontPage->categories_array_from_db = $array;
		$this->mockedCategoryTreeFrontPage->slugger = new AppExtension();
		$mainParentId = $this->mockedCategoryTreeFrontPage->get_main_parent($id)['id'];
		$array2 = $this->mockedCategoryTreeFrontPage->build_tree($mainParentId);
		$this->assertSame($string, $this->mockedCategoryTreeFrontPage->get_category_list($array2));
	}

	public function dataForTestCategoryTreeFrontPage()
	{
		yield [
			'<ul><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/hp,14">HP</a></li></ul></li></ul></li></ul>',
			[
				['name' => 'Electronics', 'id' => 1, 'parent_id' => null],
				['name' => 'Computers', 'id' => 6, 'parent_id' => 1],
				['name' => 'Laptops', 'id' => 8, 'parent_id' => 6],
				['name' => 'HP', 'id' => 14, 'parent_id' => 8],
			],
			1
		];
	}
}
