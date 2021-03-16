<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;


class CommentVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [SELF::VIEW, SELF::EDIT])
            && $subject instanceof \App\Entity\Comment;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // you know $subject is a Comment object, thanks to `supports()`
        /** @var Comment $comment */
        $comment = $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView($comment, $user);
            case self::EDIT:
                return $this->canEdit($comment, $user);
        }
        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Comment $comment, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($comment, $user)) {
            return true;
        }

        // the comment object could have, for example, a method `isPrivate()`
        return !$comment->getIsDeleted();
    }

    private function canEdit(Comment $comment, User $user)
    {
        if(in_array('ROLE_ADMIN',$user->getRoles())){
            return true;
        }
        // this assumes that the Post object has a `getOwner()` method
        return $user === $comment->getAuthor();
    }
}
