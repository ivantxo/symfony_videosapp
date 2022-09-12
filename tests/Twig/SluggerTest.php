<?php

namespace App\Tests\Twig;

use PHPUnit\Framework\TestCase;
use App\Twig\AppExtension;

class SluggerTest extends TestCase
{
	/**
	 * @dataProvider getSlugs
	 */
    public function testSlugify(string $string, string $slug): void
    {
		$slugger = new AppExtension();
        $this->assertSame($slug, $slugger->slugify($string));
    }

	public function getSlugs(): array
	{
		return [
			['Lorem Ipsum', 'lorem-ipsum'],
			[' Lorem Ipsum', 'lorem-ipsum'],
			[' lOrEm  iPsUm  ', 'lorem-ipsum'],
			['!Lorem Ipsum!', 'lorem-ipsum'],
			['lorem-ipsum', 'lorem-ipsum'],
			['Children\'s books', 'childrens-books'],
			['Five star movies', 'five-star-movies'],
			['Adults 60+', 'adults-60'],
		];
	}
}
