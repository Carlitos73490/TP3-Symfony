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
        // if the user is anonymous, do not grant access
//        if (!$user instanceof UserInterface) {
//            return $user = new User();
//        }

        // you know $subject is a Post object, thanks to `supports()`
        /** @var Post $post */
        $post = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($post);
            case self::VIEW:
                return $this->canView($post);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Post $post)
    {

//        if ($this->canEdit($post, $user)) {
//            return false;
//        } else {
            return $post->getIsPublished();
        //}
    }

    private function canEdit(Post $post)
    {
//        if(in_array('ROLE_ADMIN',$user->getRoles())){
//            return true;
//        } else {
            return true;$user === $post->getAuthor();
        //}
        //return true;


    }
}
