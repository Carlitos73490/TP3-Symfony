<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'home_page')]
    public function index(): Response
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();
        $user = $this->getUser();
        $currentUserName = isset($user) ? $this->getUser()->getUsername() : "Anonyme";
        return $this->render('home_page/index.html.twig', [
            'posts' => $posts, 'currentUserName' => $currentUserName ,
        ]);
    }
    #[Route('/addPost', name: 'home_page_edit')]
    public function addPost(Request $request) : Response
    {
        $this->denyAccessUnlessGranted('ROLE_AUTHOR');
        $post = new Post();
        $post->setAuthor($this->getUser());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
            return $this->redirectToRoute("home_page");
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/deletePost/{post_id}")
     * @ParamConverter("post", options={"id"="post_id"})
     */
    public function deletePost(Request $request, Post $post): Response {
        $this->denyAccessUnlessGranted('edit', $post);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        return $this->redirectToRoute("home_page");
    }

}
