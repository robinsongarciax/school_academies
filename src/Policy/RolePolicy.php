<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Role;
use Authorization\IdentityInterface;

/**
 * Role policy
 */
class RolePolicy
{
    private $module = 'Roles';
    /**
     * Check if $user can add Role
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Role $role
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Role $role)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit Role
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Role $role
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Role $role)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete Role
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Role $role
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Role $role)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view Role
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Role $role
     * @return bool
     */
    public function canView(IdentityInterface $user, Role $role)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
