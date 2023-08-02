<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SchoolLevelsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SchoolLevelsTable Test Case
 */
class SchoolLevelsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SchoolLevelsTable
     */
    protected $SchoolLevels;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.SchoolLevels',
        'app.Institutes',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('SchoolLevels') ? [] : ['className' => SchoolLevelsTable::class];
        $this->SchoolLevels = $this->getTableLocator()->get('SchoolLevels', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->SchoolLevels);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\SchoolLevelsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
