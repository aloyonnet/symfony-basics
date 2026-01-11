<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class Wrapper
{
    public string $type = 'info';
    public string $title;
    public string $controller;
    public string $template;
}