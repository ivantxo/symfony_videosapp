<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Video;
use App\Entity\Category;

class VideoFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		foreach($this->VideoData() as [$title, $path, $category_id])
		{
			$duration = random_int(10, 300);
			$category = $manager->getRepository(Category::class)->find($category_id);
			$video = new Video();
			$video->setTitle($title);
			$video->setPath('https://player.vimeo.com/video/'.$path);
			$video->setCategory($category);
			$video->setDuration($duration);
			$manager->persist($video);
		}

		$manager->flush();
	}

	private function VideoData()
	{
		return [
			['Movies 1',289729765,2],
			['Movies 2',238902809,2],
			['Movies 3',150870038,2],
			['Movies 4',219727723,2],
			['Movies 5',289879647,2],
			['Movies 6',261379936,2],
			['Movies 7',289029793,2],
			['Movies 8',60594348,2],
			['Movies 9',290253648,2],

			['Family 1',289729765,17],
			['Family 2',289729765,17],
			['Family 3',289729765,17],

			['Romantic comedy 1',289729765,19],
			['Romantic comedy 2',289729765,19],

			['Romantic drama 1',289729765,20],

			['Toys  1',289729765,4],
			['Toys  2',289729765,4],
			['Toys  3',289729765,4],
			['Toys  4',289729765,4],
			['Toys  5',289729765,4],
			['Toys  6',289729765,4]
		];
	}
}
