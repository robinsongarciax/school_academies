<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Teacher;
use Authorization\IdentityInterface;

/**
 * Teacher policy
 */
class TeacherPolicy
{
    private $module = 'Teachers';
    /**
     * Check if $user can add Teacher
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Teacher $teacher
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Teacher $teacher)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit Teacher
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Teacher $teacher
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Teacher $teacher)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete Teacher
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Teacher $teacher
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Teacher $teacher)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can deactive Teacher
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Teacher $teacher
     * @return bool
     */
    public function canDeactive (IdentityInterface $user, Teacher $teacher)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view Teacher
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Teacher $teacher
     * @return bool
     */
    public function canView(IdentityInterface $user, Teacher $teacher)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
