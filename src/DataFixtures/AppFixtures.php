<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {

    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$username, $password, $roles]) {
        $user = (new User());
        $user->setUsername($username)
            ->setPassword($this->hasher->hashPassword($user, $password))
            ->setRoles($roles);

            $manager->persist($user);
        }

        $manager->flush();
    }

    /**
     * @return array|array[]
     */
    private function getUserData(): array
    {
        return [
            // [$username, $password, $role];
            ['user', 'test', ['ROLE_USER']],
            ['admin', 'test', ['ROLE_ADMIN']],
        ];
    }
}
