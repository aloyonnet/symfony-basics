<?php

namespace App\Twig\Components;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('post_search', template: 'site/components/_post_search.html.twig')]
class PostSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = null;

    public function __construct(private PostRepository $repository)
    {
    }

    public function getPosts(): array
    {
        return $this->repository->findBySearchQuery($this->query);
    }
}
