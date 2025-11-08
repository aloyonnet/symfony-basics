<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {

    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadPosts($manager);
    }

    public function loadUsers(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$username, $password, $roles]) {
            $user = (new User());
            $user->setUsername($username)
                ->setPassword($this->hasher->hashPassword($user, $password))
                ->setRoles($roles);

            $manager->persist($user);

            $this->addReference($username, $user);
        }

        $manager->flush();
    }

    public function loadPosts(ObjectManager $manager): void
    {
        foreach ($this->getPostData() as [$title, $slug, $content, $author, $createdAt]) {
            $post = (new Post());
            $post->setTitle($title)
                ->setSlug($slug)
                ->setContent($content)
                ->setAuthor($author)
                ->setCreatedAt($createdAt);

            $manager->persist($post);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // [$username, $password, $role];
            ['user', 'test', ['ROLE_USER']],
            ['admin', 'test', ['ROLE_ADMIN']],
        ];
    }

    private function getPostData(): array
    {
        $admin = $this->getReference('admin', User::class);
        $date = new \DateTimeImmutable('now');

        return [
            // [$title, $slug, $content, $author, $createdAt];
            ['Article 1', 'article-1', 'Contenu de l\'article 1', $admin, $date],
            ['Article 2', 'article-2',  'Contenu de l\'article 2', $admin, $date],
            ['Article 3', 'article-3',  'Contenu de l\'article 3', $admin, $date],
            ['Test 1', 'test-1',  'Contenu de test 1', $admin, $date],
            ['Test 2', 'test-2',  'Contenu de test 2', $admin, $date],
            ['Test 3', 'test-3',  'Contenu de test 3', $admin, $date],
        ];
    }
}
