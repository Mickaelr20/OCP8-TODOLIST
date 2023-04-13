<?php

// src/Security/TaskVoter.php

namespace App\Security;

use App\Entity\User;
use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\CacheableVoterInterface;

class TaskVoter extends Voter implements CacheableVoterInterface
{
    // Actions
    public const CREATE = 'create';
    public const DELETE = 'delete';
    public const EDIT = 'edit';
    public const TOGGLE = 'toggle';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute is one we support and
        // $subject is App\Entity\Task
        return $subject instanceof Task && in_array($attribute, [self::CREATE, self::DELETE, self::EDIT, self::TOGGLE]);
    }

    protected function voteOnAttribute(string $attribute, mixed $task, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        return match ($attribute) {
            self::CREATE => $this->canCreate($user, $task),
            self::DELETE => $this->canDelete($user, $task),
            self::EDIT => $this->canEdit($user, $task),
            self::TOGGLE => $this->canToggle($user, $task),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    public function canCreate(User $user, Task $task): bool
    {
        // User connected, every user can create a task
        return true;
    }

    private function canDelete(User $user, Task $task): bool
    {
        // The author of the task can delete it, or an admin
        return $task->getUser() === $user || $user->hasRole('ROLE_ADMIN');
    }

    private function canEdit(User $user, Task $task): bool
    {
        // The author of the task can edit it or an admin
        return $task->getUser() === $user || $user->hasRole('ROLE_ADMIN');
    }

    private function canToggle(User $user, Task $task): bool
    {
        // The author of the task can toggle it state (done or not done) or an admin
        return $task->getUser() === $user || $user->hasRole('ROLE_ADMIN');
    }
}
