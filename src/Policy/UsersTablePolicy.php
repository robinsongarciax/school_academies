<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\UsersTable;
use Authorization\IdentityInterface;

/**
 * Users policy
 */
class UsersTablePolicy
{

    public function scopeIndex($user, $query) {
        return $query->where(['Users.id' => $user->id]);
    }
}
