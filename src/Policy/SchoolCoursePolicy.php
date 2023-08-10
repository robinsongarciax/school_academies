<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\SchoolCourse;
use Authorization\IdentityInterface;

/**
 * SchoolCourse policy
 */
class SchoolCoursePolicy
{
    private $module = 'SchoolCourses';
    /**
     * Check if $user can add SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCourse $schoolCourse
     * @return bool
     */
    public function canAdd(IdentityInterface $user, SchoolCourse $schoolCourse)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCourse $schoolCourse
     * @return bool
     */
    public function canEdit(IdentityInterface $user, SchoolCourse $schoolCourse)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCourse $schoolCourse
     * @return bool
     */
    public function canDelete(IdentityInterface $user, SchoolCourse $schoolCourse)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCourse $schoolCourse
     * @return bool
     */
    public function canView(IdentityInterface $user, SchoolCourse $schoolCourse)
    {
        return $user->isModulePermission($this->module, 'View');
    }

    /**
     * Check if $user can sign-up SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCourse $schoolCourse
     * @return bool
     */
    public function canSignup(IdentityInterface $user, SchoolCourse $schoolCourse)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can enroll students for SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCourse $schoolCourse
     * @return bool
     */
    public function canEnroll(IdentityInterface $user, SchoolCourse $schoolCourse)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }
}
