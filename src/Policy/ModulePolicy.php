<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Module;
use Authorization\IdentityInterface;

/**
 * Module policy
 */
class ModulePolicy
{

    private $module = "Modules";
    /**
     * Check if $user can add Module
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Module $module
     * @return bool
     */
    public function canAdd(IdentityInterface $user, Module $module)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit Module
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Module $module
     * @return bool
     */
    public function canEdit(IdentityInterface $user, Module $module)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete Module
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Module $module
     * @return bool
     */
    public function canDelete(IdentityInterface $user, Module $module)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view Module
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\Module $module
     * @return bool
     */
    public function canView(IdentityInterface $user, Module $module)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
