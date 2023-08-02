<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ModulesPermission Entity
 *
 * @property int $id
 * @property int $module_id
 * @property int $permission_id
 *
 * @property \App\Model\Entity\Module $module
 * @property \App\Model\Entity\Permission $permission
 * @property \App\Model\Entity\Role[] $roles
 */
class ModulesPermission extends Entity
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
        'module_id' => true,
        'permission_id' => true,
        'module' => true,
        'permission' => true,
        'roles' => true,
    ];
}
