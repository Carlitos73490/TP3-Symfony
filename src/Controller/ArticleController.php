<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{post_id}")
     * @ParamConverter("post", options={"id" ="post_id"})
     */
    public function index(Request $request, Post $post): Response
    {

        $this->createFormBuilder();

        $comment = new Comment();
        $comment->setContent("");
        $post->addComment($comment);


//        $form = $this->createFormBuilder($comment)
//            ->add('content', TextType::class)
//            ->add('save', SubmitType::class, ['label' => 'Save Comment'])
//            ->getForm();

        $comment = new Comment();

        //$form = $this->createForm(Comment::class, $comment);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $comment = $form->getData();

            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setIsDeleted(false);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($comment);
            $entityManager->flush();

        }

        return $this->render('article/index.html.twig', [
            'post' => $post, 'form' => $form->createView(),
        ]);

    }
}
