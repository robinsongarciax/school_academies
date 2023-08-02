<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SubjectsFixture
 */
class SubjectsFixture extends TestFixture
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
                'id_number' => 'Lorem ipsum dolor ',
                'name' => 'Lorem ipsum dolor sit amet',
                'capacity' => 1,
                'institute' => 'Lorem ipsum dolor sit amet',
                'genero' => 'Lorem ipsum dolor sit amet',
                'tipo_academia' => 'Lorem ipsum dolor sit amet',
                'criterio_academia' => 'Lorem ipsum dolor sit amet',
                'grade_level' => 'Lorem ipsum dolor sit amet',
                'anio_nacimiento_minimo' => 1,
                'anio_nacimiento_maximo' => 1,
                'grado_minimo' => 1,
                'grado_maximo' => 1,
                'costo_normal' => 1,
                'costo_material' => 1,
                'costo_cumbres' => 1,
                'costo_segundo_semestre' => 1,
                'costo_externos' => 1,
                'descripcion' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'active' => 1,
                'seleccionado' => 1,
                'pago_obligatorio' => 1,
                'created' => '2023-07-22 17:20:10',
                'modified' => '2023-07-22 17:20:10',
            ],
        ];
        parent::init();
    }
}
