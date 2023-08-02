<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'username' => 'Lorem ipsum dolor sit amet',
                'password' => 'Lorem ipsum dolor sit amet',
                'name' => 'Lorem ipsum dolor sit amet',
                'celular' => 'Lorem ip',
                'active' => 1,
                'created' => '2023-07-22 17:25:58',
                'modified' => '2023-07-22 17:25:58',
                'role_id' => 1,
            ],
        ];
        parent::init();
    }
}
