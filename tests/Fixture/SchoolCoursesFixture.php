<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SchoolCoursesFixture
 */
class SchoolCoursesFixture extends TestFixture
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
                'capacity' => 1,
                'subjet_id' => 1,
                'teacher_id' => 1,
                'term_id' => 1,
            ],
        ];
        parent::init();
    }
}
