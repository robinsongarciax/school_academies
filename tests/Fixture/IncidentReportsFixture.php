<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * IncidentReportsFixture
 */
class IncidentReportsFixture extends TestFixture
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
                'subject' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet',
                'date' => '2025-07-26',
                'students_id' => 1,
                'users_id' => 1,
                'teachers_id' => 1,
                'created' => '2025-07-26 09:53:24',
                'school_courses_id' => 1,
                'modified' => '2025-07-26 09:53:24',
            ],
        ];
        parent::init();
    }
}
