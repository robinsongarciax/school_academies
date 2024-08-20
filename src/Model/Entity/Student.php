<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Student Entity
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $second_last_name
 * @property string $curp
 * @property string|null $email
 * @property int $level
 * @property string $institute
 * @property string $group
 * @property string|null $id_number
 * @property \Cake\I18n\FrozenDate $birth_date
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $term_id
 * @property int $user_id
 *
 * @property \App\Model\Entity\Term $term
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Course[] $courses
 * @property \App\Model\Entity\SchoolCourse[] $school_courses
 */
class Student extends Entity
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
        'last_name' => true,
        'second_last_name' => true,
        'curp' => true,
        'sex' => true,
        'email' => true,
        'level' => true,
        'institute' => true,
        'school_level' => true,
        'school_group' => true,
        'id_number' => true,
        'birth_date' => true,
        'created' => true,
        'modified' => true,
        'term_id' => true,
        'user_id' => true,
        'term' => true,
        'user' => true,
        'courses' => true,
        'school_courses' => true,
        'externo' => true,
        'mother_name' => true,
        'mother_phone' => true,
        'mother_email' => true,
        'father_name' => true,
        'father_phone' => true,
        'father_email' => true
    ];
}
