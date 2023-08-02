<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StudentsFixture
 */
class StudentsFixture extends TestFixture
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
                'curp' => 'Lorem ipsum dolo',
                'email' => 'Lorem ipsum dolor sit amet',
                'level' => 1,
                'institute' => 'Lorem ipsum dolor sit amet',
                'group' => 'Lorem ip',
                'id_number' => 'Lorem ipsum dolor sit amet',
                'birth_date' => '2023-07-22',
                'created' => '2023-07-22 17:19:18',
                'modified' => '2023-07-22 17:19:18',
                'term_id' => 1,
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
