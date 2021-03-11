<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            TextField::new('email'),
            TextEditorField::new('BioContent'),
            TextField::new('password'),
            BooleanField::new('isActive'),
            BooleanField::new('isBlocked'),
            //AssociationField::new('comments'),
            //AssociationField::new('posts')
        ];
    }

}
