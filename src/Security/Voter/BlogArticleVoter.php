<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class BlogArticleVoter extends Voter
{

    const VIEW = 'viewPost';
    const EDIT = 'editPost';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute,[SELF::VIEW, SELF::EDIT])
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, Create a fake User
        if (!$user instanceof UserInterface) {
            $user = new User();
           $user->getRoles();
       }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Post $post */
        $post = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($post,$user);
            case self::VIEW:
                return $this->canView($post,$user);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Post $post,User $user)
    {

      if ($this->canEdit($post, $user)) {
            return true;
        } else {
            return $post->getIsPublished();
        }
    }

    private function canEdit(Post $post, User $user)
    {
        if(in_array('ROLE_ADMIN',$user->getRoles())){
            return true;
       } else {
           return $user === $post->getAuthor();
        }
        //return true;


    }
}
