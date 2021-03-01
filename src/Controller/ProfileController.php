<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{user_id}")
     * @ParamConverter("user", options={"id" ="user_id"})
     */
    public function index(User $user): Response
    {


        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
