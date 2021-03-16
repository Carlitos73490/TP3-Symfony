<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
