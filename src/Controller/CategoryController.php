<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{slug}', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, $slug): Response
    {
        $category = $categoryRepository->findOneBy(["slug" => $slug]);
        $subCategories = $categoryRepository->findBy(['parent' => $category->getId()]);
        $books = $category->getBooks();

        return $this->render('index/index.html.twig', [
            'categories' => $subCategories,
            'books' => $books,
        ]);
    }
}
