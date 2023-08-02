<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolCoursesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolCoursesTable Test Case
 */
class SchoolCoursesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SchoolCoursesTable
     */
    protected $SchoolCourses;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SchoolCourses',
        'app.Subjects',
        'app.Teachers',
        'app.Terms',
        'app.Schedules',
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
        $config = $this->getTableLocator()->exists('SchoolCourses') ? [] : ['className' => SchoolCoursesTable::class];
        $this->SchoolCourses = $this->getTableLocator()->get('SchoolCourses', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SchoolCourses);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SchoolCoursesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SchoolCoursesTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
