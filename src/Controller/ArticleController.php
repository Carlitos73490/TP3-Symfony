<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article/{post_id}")
     * @ParamConverter("post", options={"id" ="post_id"})
     */
    public function index(Post $post): Response
    {
        return $this->render('article/index.html.twig', [
            'post' => $post,
        ]);
    }
}
