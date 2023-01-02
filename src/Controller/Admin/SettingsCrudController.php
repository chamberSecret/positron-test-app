<?php

namespace App\Controller\Admin;

use App\Entity\Settings;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SettingsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Settings::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            EmailField::new('email'),
            UrlField::new('parseUrl'),
            IntegerField::new('perPage'),
        ];
    }

}
