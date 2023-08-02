<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TeachersFixture
 */
class TeachersFixture extends TestFixture
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
                'name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'second_last_name' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'celular' => 'Lorem ipsum dolor sit amet',
                'active' => 1,
                'created' => '2023-07-29 22:18:10',
                'modified' => '2023-07-29 22:18:10',
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
