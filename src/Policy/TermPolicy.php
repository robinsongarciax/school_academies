<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\term;
use Authorization\IdentityInterface;

/**
 * term policy
 */
class termPolicy
{
    private $module = 'Terms';
    /**
     * Check if $user can add term
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\term $term
     * @return bool
     */
    public function canAdd(IdentityInterface $user, term $term)
    {
        return $user->isModulePermission($this->module, 'Add');
    }

    /**
     * Check if $user can edit term
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\term $term
     * @return bool
     */
    public function canEdit(IdentityInterface $user, term $term)
    {
        return $user->isModulePermission($this->module, 'Edit');
    }

    /**
     * Check if $user can delete term
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\term $term
     * @return bool
     */
    public function canDelete(IdentityInterface $user, term $term)
    {
        return $user->isModulePermission($this->module, 'Delete');
    }

    /**
     * Check if $user can view term
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \App\Model\Entity\term $term
     * @return bool
     */
    public function canView(IdentityInterface $user, term $term)
    {
        return $user->isModulePermission($this->module, 'View');
    }
}
