<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Term Entity
 *
 * @property int $id
 * @property string $description
 * @property \Cake\I18n\FrozenDate $start
 * @property \Cake\I18n\FrozenDate $end
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $institute_id
 *
 * @property \App\Model\Entity\Institute $institute
 * @property \App\Model\Entity\Class[] $classes
 * @property \App\Model\Entity\Student[] $students
 */
class Term extends Entity
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
        'description' => true,
        'start' => true,
        'end' => true,
        'active' => true,
        'courses_allowed' => true,
        'created' => true,
        'modified' => true,
        'institute_id' => true,
        'institute' => true,
        'classes' => true,
        'students' => true,
    ];
}
