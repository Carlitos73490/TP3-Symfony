<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
             TextEditorField::new('content'),
            BooleanField::new('isPublished'),
            BooleanField::new('isDeleted'),
            AssociationField::new('author')
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $post = new Post();
        $post->setCreatedAt(new \DateTimeImmutable());
        return $post;
    }


}
