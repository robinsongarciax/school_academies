<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Controller\SchoolCoursesController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\SchoolCoursesController Test Case
 *
 * @uses \App\Controller\SchoolCoursesController
 */
class SchoolCoursesControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
        'app.SchoolCoursesSchedules',
        'app.SchoolCoursesStudents',
    ];

    /**
     * Test index method
     *
     * @return void
     * @uses \App\Controller\SchoolCoursesController::index()
     */
    public function testIndex(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test view method
     *
     * @return void
     * @uses \App\Controller\SchoolCoursesController::view()
     */
    public function testView(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test add method
     *
     * @return void
     * @uses \App\Controller\SchoolCoursesController::add()
     */
    public function testAdd(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     * @uses \App\Controller\SchoolCoursesController::edit()
     */
    public function testEdit(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     * @uses \App\Controller\SchoolCoursesController::delete()
     */
    public function testDelete(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
