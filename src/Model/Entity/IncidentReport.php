<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * IncidentReport Entity
 *
 * @property int $id
 * @property string $subject
 * @property string $description
 * @property \Cake\I18n\FrozenDate $date
 * @property int $students_id
 * @property int $users_id
 * @property int $teachers_id
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int $school_courses_id
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\Student $student
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Teacher $teacher
 * @property \App\Model\Entity\SchoolCourse $school_course
 */
class IncidentReport extends Entity
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
        'subject' => true,
        'description' => true,
        'date' => true,
        'students_id' => true,
        'users_id' => true,
        'teachers_id' => true,
        'created' => true,
        'school_courses_id' => true,
        'modified' => true,
        'student' => true,
        'user' => true,
        'teacher' => true,
        'school_course' => true,
    ];
}
