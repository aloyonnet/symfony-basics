<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PostRepository;
use App\Entity\Post;

final class SiteController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('site/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/posts', name: 'post_index', methods: ['GET'])]
    public function post_index(PostRepository $postRepository): Response
    {
        return $this->render('site/post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    #[Route('/post/{id}', name: 'post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('site/post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
