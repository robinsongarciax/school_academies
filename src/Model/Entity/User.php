<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Authentication\IdentityInterface;

/**
 * User Entity
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $celular
 * @property int $active
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property int $role_id
 *
 * @property \App\Model\Entity\Role $role
 * @property \App\Model\Entity\Student[] $students
 * @property \App\Model\Entity\Teacher[] $teachers
 */
class User extends Entity implements IdentityInterface
{

    /**
     * Authentication\IdentityInterface method
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Authentication\IdentityInterface method
     */
    public function getOriginalData()
    {
        return $this;
    }

    public function isModulePermission(String $module, String $permission) : bool {
        $modules_permissions = $this->get('role')->get('modules_permissions');
        foreach ($modules_permissions as $value) {
            if ($value->module->name == $module && $value->permission->name == $permission) 
                return true;
        }
        return false;
    }
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
        'username' => true,
        'password' => true,
        'name' => true,
        'celular' => true,
        'active' => true,
        'created' => true,
        'modified' => true,
        'role_id' => true,
        'role' => true,
        'students' => true,
        'teachers' => true,
        'school_levels' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = [
        'password',
    ];

    protected function _setPassword(string $password) : ?string 
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
    }

    protected function getRole() {

    }
}
