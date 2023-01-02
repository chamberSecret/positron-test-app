<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Book::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            ImageField::new('image')
                ->setUploadDir('public/images')
                ->setUploadedFileNamePattern('images/[slug]'),
            SlugField::new('slug')
                ->setTargetFieldName('title'),
            IntegerField::new('pages'),
            TextareaField::new('long_description'),
            TextareaField::new('shor_description'),
            DateTimeField::new('date'),
            TextField::new('status'),
        ];
    }
}
