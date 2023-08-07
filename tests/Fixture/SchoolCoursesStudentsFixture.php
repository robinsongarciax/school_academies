<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SchoolCoursesStudentsFixture
 */
class SchoolCoursesStudentsFixture extends TestFixture
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
            ],
        ];
        parent::init();
    }
}
