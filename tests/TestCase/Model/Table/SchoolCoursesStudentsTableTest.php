<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolCoursesStudentsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolCoursesStudentsTable Test Case
 */
class SchoolCoursesStudentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SchoolCoursesStudentsTable
     */
    protected $SchoolCoursesStudents;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SchoolCoursesStudents',
        'app.SchoolCourses',
        'app.Students',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SchoolCoursesStudents') ? [] : ['className' => SchoolCoursesStudentsTable::class];
        $this->SchoolCoursesStudents = $this->getTableLocator()->get('SchoolCoursesStudents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SchoolCoursesStudents);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SchoolCoursesStudentsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SchoolCoursesStudentsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
