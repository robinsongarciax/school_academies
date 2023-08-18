<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Subject Entity
 *
 * @property int $id
 * @property string|null $id_number
 * @property string $name
 * @property int|null $capacity
 * @property string|null $institute
 * @property string $sex
 * @property string $tipo_academia
 * @property string $criterio_academia
 * @property string|null $grade_level
 * @property int $anio_nacimiento_minimo
 * @property int $anio_nacimiento_maximo
 * @property int $grado_minimo
 * @property int $grado_maximo
 * @property float|null $costo_normal
 * @property float|null $costo_material
 * @property float|null $costo_cumbres
 * @property float|null $costo_segundo_semestre
 * @property float|null $costo_externos
 * @property string $description
 * @property int|null $active
 * @property int|null $seleccionado
 * @property int|null $pago_obligatorio
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Teacher[] $teachers
 */
class Subject extends Entity
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
        'id_number' => true,
        'name' => true,
        'capacity' => true,
        'institute' => true,
        'sex' => true,
        'tipo_academia' => true,
        'criterio_academia' => true,
        'grade_level' => true,
        'costo_normal' => true,
        'costo_material' => true,
        'costo_cumbres' => true,
        'costo_segundo_semestre' => true,
        'costo_externos' => true,
        'description' => true,
        'active' => true,
        'seleccionado' => true,
        'pago_obligatorio' => true,
        'is_visible' => true,
        'created' => true,
        'modified' => true,
        'teachers' => true
    ];
}
