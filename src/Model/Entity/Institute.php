<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Institute Entity
 *
 * @property int $id
 * @property string $name
 *
 * @property \App\Model\Entity\Term[] $terms
 * @property \App\Model\Entity\SchoolLevel[] $school_levels
 */
class Institute extends Entity
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
        'terms' => true,
        'school_levels' => true,
    ];
}
