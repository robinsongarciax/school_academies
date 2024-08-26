<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\SchoolCoursesStudent;
use Authorization\IdentityInterface;

/**
 * SchoolCourse policy
 */
class SchoolCoursesStudentPolicy
{
    private $module = 'SchoolCourses';

    /**
     * Check if $user can deactive SchoolCourse
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\SchoolCoursesStudent $schoolCoursesStudent.
     * @return bool
     */
    public function canDeactive(IdentityInterface $user, SchoolCoursesStudent $schoolCoursesStudent)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

}
