<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
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

        //$this->createFormBuilder();

        //$comment = new Comment();
        //$comment->setContent("");

//        $form = $this->createFormBuilder($comment)
//            ->add('content', TextType::class)
//            ->add('save', SubmitType::class, ['label' => 'Save Comment'])
//            ->getForm();
        //$form = $this->createForm(Comment::class, $comment);
        //$comment = new Comment();

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $comment = $form->getData();

            $comment->setAuthor($this->getUser());
            $comment->setIsDeleted(false);
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $entityManager = $this->getDoctrine()->getManager();

            $post->addComment($comment);
            $entityManager->persist($post);
            //$entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->render('article/index.html.twig', [
            'post' => $post, 'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/article/{post_id}/editComment/{comment_id}")
     * @ParamConverter("post", options={"id"="post_id"})
     * @ParamConverter("comment", options={"id"="comment_id"})
     */
    public function editComment (Request $request, Comment $comment): Response {
      $this->denyAccessUnlessGranted('edit', $comment);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirect("/article/" . $comment->getPost()->getId());
        }
        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
   }

    /**
     * @Route("/article/{post_id}/deleteComment/{comment_id}")
     * @ParamConverter("post", options={"id"="post_id"})
     * @ParamConverter("comment", options={"id"="comment_id"})
     */
    public function deleteComment (Request $request, Comment $comment,Post $post): Response {
        $this->denyAccessUnlessGranted('edit', $comment);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
            return $this->redirect("/article/" . $post->getId());
    }


    /**
     * @Route("/article/edit/{post_id}")
     * @ParamConverter("post", options={"id"="post_id"})
     */
    public function edit(Request $request, Post $post): Response {
        $this->denyAccessUnlessGranted('edit', $post);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirect("/article/" . $post->getId());
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/delete/{post_id}")
     * @ParamConverter("post", options={"id"="post_id"})
     */
    public function delete(Request $request, Post $post): Response {
        $this->denyAccessUnlessGranted('edit', $post);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirect("/article/" . $post->getId());
    }


}
