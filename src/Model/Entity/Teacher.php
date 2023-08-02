<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Teacher Entity
 *
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property string $second_last_name
 * @property string|null $email
 * @property string|null $celular
 * @property int $active
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $user_id
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\SchoolCourse[] $school_courses
 * @property \App\Model\Entity\Subject[] $subjects
 */
class Teacher extends Entity
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
        'email' => true,
        'celular' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true,
        'user' => true,
        'school_courses' => true,
        'subjects' => true,
    ];
}
