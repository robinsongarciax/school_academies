<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolCoursesStudentsLogsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolCoursesStudentsLogsTable Test Case
 */
class SchoolCoursesStudentsLogsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SchoolCoursesStudentsLogsTable
     */
    protected $SchoolCoursesStudentsLogs;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SchoolCoursesStudentsLogs',
        'app.Students',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SchoolCoursesStudentsLogs') ? [] : ['className' => SchoolCoursesStudentsLogsTable::class];
        $this->SchoolCoursesStudentsLogs = $this->getTableLocator()->get('SchoolCoursesStudentsLogs', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SchoolCoursesStudentsLogs);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SchoolCoursesStudentsLogsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\SchoolCoursesStudentsLogsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
