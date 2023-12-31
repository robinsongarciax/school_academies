<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ModulesPermissionsFixture
 */
class ModulesPermissionsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'module_id' => 1,
                'permission_id' => 1,
            ],
        ];
        parent::init();
    }
}
