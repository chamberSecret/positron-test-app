<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(CategoryRepository $categoryRepository, BookRepository $bookRepository): Response
    {
        $categories = $categoryRepository->findBy(['parent' => null]);
        $books = $bookRepository->findAll();

        return $this->render('index/index.html.twig', [
            'categories' => $categories,
            'books' => $books,
        ]);
    }
}
