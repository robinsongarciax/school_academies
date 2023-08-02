<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Subject;
use Authorization\IdentityInterface;

/**
 * Subject policy
 */
class SubjectPolicy
{
    private $module = 'Subjects';
    /**
     * Check if $user can add Subject
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Subject $subject
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Subject $subject)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit Subject
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Subject $subject
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Subject $subject)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete Subject
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Subject $subject
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Subject $subject)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view Subject
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Subject $subject
     * @return bool
     */
    public function canView(IdentityInterface $user, Subject $subject)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
