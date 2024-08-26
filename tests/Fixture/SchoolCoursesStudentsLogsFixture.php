<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SchoolCoursesStudentsLogsFixture
 */
class SchoolCoursesStudentsLogsFixture extends TestFixture
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
                'school_course_id' => 1,
                'student_id' => 1,
                'cost' => 1.5,
                'status' => 'Lorem ipsum d',
                'created' => '2024-08-25 02:38:50',
                'user_id' => 1,
            ],
        ];
        parent::init();
    }
}
