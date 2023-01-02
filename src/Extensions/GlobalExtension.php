<?php

namespace App\Extensions;

use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GlobalExtension extends AbstractExtension
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getUser', [$this, 'getUser']),
        ];
    }

    public function getUser(): ?string
    {
        $user = $this->security->getUser();
        return $user?->getUserIdentifier();
    }
}