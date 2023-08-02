<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Student;
use Authorization\IdentityInterface;

/**
 * Student policy
 */
class StudentPolicy
{
    private $module = 'Students';
    /**
     * Check if $user can add Student
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Student $student
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Student $student)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit Student
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Student $student
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Student $student)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete Student
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Student $student
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Student $student)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view Student
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Student $student
     * @return bool
     */
    public function canView(IdentityInterface $user, Student $student)
    {
        return $user->isModulePermission($this->module, 'View');
    }

    /**
     * Check if $user can import Students from file
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Student $student
     * @return bool
     */
    public function canImportFile(IdentityInterface $user, Student $student)
    {
        return $user->isModulePermission($this->module, 'Add');
    }
}
