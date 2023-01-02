<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    protected array $info;

    public function __construct()
    {
        $user = $this->getUser();

        $this->info = [
            "user" => $user
        ];
    }
}