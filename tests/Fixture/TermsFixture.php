<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TermsFixture
 */
class TermsFixture extends TestFixture
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
                'description' => 'Lorem ipsum dolor sit amet',
                'start' => '2023-07-22',
                'end' => '2023-07-22',
                'created' => '2023-07-22 17:40:00',
                'modified' => '2023-07-22 17:40:00',
                'institute_id' => 1,
            ],
        ];
        parent::init();
    }
}
