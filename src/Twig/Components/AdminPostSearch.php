<?php

namespace App\Twig\Components;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent('admin_post_search', template: 'admin/components/_post_search.html.twig')]
class AdminPostSearch
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
