<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 * @var \Cake\Collection\CollectionInterface|string[] $terms
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $schoolCourses
 */
// $this->Html->script('load-school-levels', ['block' => true]);
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Students') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('List Students'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Add Student') ?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($student) ?>
            <fieldset>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('curp', [
                        'label' => 'CURP'
                    ]);
                    echo $this->Form->control('sex', [
                        'options' => ['F' => 'FEMENINO', 'M' => 'MASCULINO']
                    ]);
                    echo $this->Form->control('institute', [
                        'label' => 'Colegio'
                    ]);
                    echo $this->Form->control('level', [
                        'label' => 'Sección',
                        'options' => [
                            '' => 'Seleccione una opción',
                            'Preescolar' => 'Preescolar',
                            'Primaria' => 'Primaria',
                            'Secundaria' => 'Secundaria'
                        ],
                        'id' => 'student-level'
                    ]);
                    echo $this->Form->control('school_level', [
                        'label' => 'Grado',
                        'options' => [
                            '' => '---',
                            'Bambolino 3' => 'Bambolino 3',
                            'Kinder 1' => 'Kinder 1',
                            'Kinder 2' => 'Kinder 2',
                            'Kinder 3' => 'Kinder 3',
                            '1o. de Primaria' => '1o. de Primaria',
                            '2o. de Primaria' => '2o. de Primaria',
                            '3o. de Primaria' => '3o. de Primaria',
                            '4o. de Primaria' => '4o. de Primaria',
                            '5o. de Primaria' => '5o. de Primaria',
                            '6o. de Primaria' => '6o. de Primaria',
                            '1o. de Secundaria' => '1o. de Secundaria',
                            '2o. de Secundaria' => '2o. de Secundaria',
                            '3o. de Secundaria' => '2o. de Secundaria'
                        ],
                        'id' => 'school-level'
                    ]);
                    echo $this->Form->control('school_group', [
                        'label' => 'Grupo'
                    ]);
                    echo $this->Form->control('birth_date', [
                        'label' => 'Fecha de Nacimiento'
                    ]);
                    echo $this->Form->control('term_id', ['options' => $terms,
                        'hidden' => true, 'label' => false
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
