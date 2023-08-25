<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolCourse $schoolCourse
 * @var \Cake\Collection\CollectionInterface|string[] $subjects
 * @var \Cake\Collection\CollectionInterface|string[] $teachers
 * @var \Cake\Collection\CollectionInterface|string[] $terms
 * @var \Cake\Collection\CollectionInterface|string[] $schedules
 * @var \Cake\Collection\CollectionInterface|string[] $students
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('School Courses') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('List School Courses'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Add School Course') ?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($schoolCourse, [
                'templates' => 'academies_form'
            ]) ?>
            <fieldset>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <?= $this->Form->control('name', [
                            'label' => ['text' => 'Nombre de la academia']
                        ]) ?>
                    </div>
                    <div class="form-group col-md-2">
                        <?= $this->Form->control('capacity') ?>
                    </div>

                    <div class="form-group col-md-2">
                        <?= $this->Form->control('price') ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <?= $this->Form->control('sex', 
                        ['options' => [
                            'F' => 'FEMENINO', 
                            'M' => 'MASCULINO', 
                            'X' => 'MIXTO']
                        ]);
                        ?>
                    </div>
                    <div class="form-group col-md-3">
                        <?= $this->Form->control('tipo_academia', [
                            'options' => [
                                'CULTURAL' => 'CULTURAL', 
                                'DEPORTIVA' => 'DEPORTIVA'
                            ],
                            'value' => $type
                        ]);
                        ?>
                    </div>
                    <div class="form-group col-md-2">
                        <?= $this->Form->control('pago_obligatorio', 
                        ['options' => [
                            '1' => 'Sí', 
                            '0' => 'No',
                            ],
                            'default' => 0
                        ]);
                        ?>
                    </div>
                    <div class="form-group col-md-2">
                        <?= $this->Form->control('visible',
                        ['options' => [
                            '1' => 'Sí', 
                            '0' => 'No',
                            ],
                            'label' => 'Visible para los alumnos'
                        ]);
                        ?>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-10">
                        <?= $this->Form->control('teacher_id', ['options' => $teachers]); ?>
                    </div>
                </div>
                <?php
                echo $this->Form->control('criterio_academia', [
                    'type' => 'radio',
                    'options' => [
                        'AÑO DE NACIMIENTO' => 'AÑO DE NACIMIENTO', 
                        'GRADO ESCOLAR' => 'GRADO ESCOLAR'],
                    'default' => 'AÑO DE NACIMIENTO',
                    'id' => 'criterio_academia'
                ]);
                ?>
                <div id="collapseBirthDate" class="collapse show" aria-labelledby="headingBirthDate">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <?= $this->Form->control('min_year_of_birth',
                                    [
                                        'type' => 'year',
                                        'min' => 2005,
                                        'max' => date('Y', strtotime('Y' .  " -4 years")),
                                        'label' => 'Año de nacimiento mínimo',
                                    ]) ?>
                            </div>
                            <div class="form-group col-md-5">
                                <?= $this->Form->control('max_year_of_birth',
                                    [
                                        'type' => 'year',
                                        'min' => 2005,
                                        'max' => date('Y', strtotime('Y' .  " -4 years")),
                                        'label' => ['text' => 'Año de nacimiento máximo']
                                    ]) ?>
                            </div>
                        </div>
                            
                    </div>
                </div>
                <div id="collapseSchoolGrade" class="collapse" aria-labelledby="headingSchoolGrade">
                    <div class="bg-white py-2 collapse-inner rounded" style="padding-left:.75rem;">
                        <?= $this->Form->control('school_levels._ids', [
                            'templates' => 'multichecked_form',
                            'options' => $schoolLevels,
                            'multiple' => 'checkbox',
                            'label' => false
                        ]) ?>
                    </div>
                </div>
                <?= $this->Form->control('term_id', ['options' => $terms, 'hidden' => true, 'label' => false]); ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
