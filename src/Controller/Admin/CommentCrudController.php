<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextEditorField::new('content'),
            AssociationField::new('author'),
            DateTimeField::new('CreatedAt'),
            BooleanField::new('isDeleted'),

        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $comment = new Comment();
        $comment->setCreatedAt(new \DateTimeImmutable());
        return $comment;
    }

}
