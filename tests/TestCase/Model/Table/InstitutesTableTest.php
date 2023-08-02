<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InstitutesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InstitutesTable Test Case
 */
class InstitutesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InstitutesTable
     */
    protected $Institutes;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Institutes',
        'app.Terms',
        'app.SchoolLevels',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Institutes') ? [] : ['className' => InstitutesTable::class];
        $this->Institutes = $this->getTableLocator()->get('Institutes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Institutes);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\InstitutesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
