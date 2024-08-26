<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SchoolCoursesStudentsLog Entity
 *
 * @property int $id
 * @property int $school_course_id
 * @property int $student_id
 * @property string|null $cost
 * @property string $status
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int $user_id
 *
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\User $user
 */
class SchoolCoursesStudentsLog extends Entity
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
        'cost' => true,
        'status' => true,
        'created' => true,
        'user_id' => true,
        'student' => true,
        'user' => true,
    ];
}
