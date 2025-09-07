<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SchoolCourse Entity
 *
 * @property int $id
 * @property string $name
 * @property int $capacity
 * @property int $subjet_id
 * @property int $teacher_id
 * @property int $term_id
 *
 * @property \App\Model\Entity\Teacher $teacher
 * @property \App\Model\Entity\Term $term
 * @property \App\Model\Entity\Schedule[] $schedules
 * @property \App\Model\Entity\Student[] $students
 */
class SchoolCourse extends Entity
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
        'name' => true,
        'capacity' => true,
        'price' => true,
        'occupancy' => true,
        'criterio_academia' => true,
        'min_year_of_birth' => true,
        'max_year_of_birth' => true,
        'teacher_id' => true,
        'teaching_assistant_id' => true,
        'term_id' => true,
        'sex' => true,
        'tipo_academia' => true,
        'pago_obligatorio' => true,
        'visible' => true,
        'teacher' => true,
        'term' => true,
        'schedules' => true,
        'students' => true,
        'school_levels' => true
    ];
}
