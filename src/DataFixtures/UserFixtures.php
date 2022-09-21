<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
	private UserPasswordHasherInterface $password_encoder;

	public function __construct(UserPasswordHasherInterface $password_encoder)
	{
		$this->password_encoder = $password_encoder;
	}

	public function load(ObjectManager $manager): void
    {
		foreach ($this->get_user_data() as [$name, $last_name, $email, $password, $api_key, $roles]) {
			$user = new User();
			$user->setName($name);
			$user->setLastName($last_name);
			$user->setEmail($email);
			$user->setPassword($this->password_encoder->hashPassword($user, $password));
			$user->setVimeoApiKey($api_key);
			$user->setRoles($roles);
			$manager->persist($user);
		}
        $manager->flush();
    }

	private function get_user_data(): array
	{
		return [
			['Ivan', 'Ramirez', 'ivan@test.com', 'passwd', 'pass135', ['ROLE_ADMIN']],
			['Santiago', 'Ramirez', 'santi@test.com', 'passwd', 'pass135', ['ROLE_ADMIN']],
			['Sonia', 'Gerena', 'sonia@test.com', 'passwd', 'pass135', ['ROLE_ADMIN']],
			['Ronaldo', 'Nazairo Lima', 'ronaldo@test.com', 'passwd', 'pass135', ['ROLE_ADMIN']],
			['Ronaldinho', 'de Asis Moreira', 'dinho@test.com', 'passwd', 'pass135', ['ROLE_ADMIN']],
			['Lionel', 'Messi', 'messi@test.com', 'passwd', 'pass135', ['ROLE_ADMIN']],
		];
	}
}
