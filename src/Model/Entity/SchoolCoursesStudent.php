<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SchoolCoursesStudent Entity
 *
 * @property int $id
 * @property int $school_course_id
 * @property int $student_id
 *
 * @property \App\Model\Entity\SchoolCourse $school_course
 * @property \App\Model\Entity\Student $student
 */
class SchoolCoursesStudent extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'school_course_id' => true,
        'student_id' => true,
        'is_confirmed' => true,
        'school_course' => true,
        'student' => true,
    ];
}
