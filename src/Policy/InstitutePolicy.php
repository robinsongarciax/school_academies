<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\institute;
use Authorization\IdentityInterface;

/**
 * institute policy
 */
class institutePolicy
{
    private $module = 'Institutes';
    /**
     * Check if $user can add institute
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\institute $institute
     * @return bool
     */
    public function canAdd(IdentityInterface $user, institute $institute)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit institute
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\institute $institute
     * @return bool
     */
    public function canEdit(IdentityInterface $user, institute $institute)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete institute
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\institute $institute
     * @return bool
     */
    public function canDelete(IdentityInterface $user, institute $institute)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view institute
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\institute $institute
     * @return bool
     */
    public function canView(IdentityInterface $user, institute $institute)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
