<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subject $subject
 * @var string[]|\Cake\Collection\CollectionInterface $teachers
 */
?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Subjects') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Form->postLink(
                        __('Delete'),
                        ['action' => 'delete', $subject->id],
                        ['confirm' => __('Are you sure you want to delete # {0}?', $subject->id), 'class' => 'btn btn-sm btn-light text-primary']
                    ) ?>
                    <?= $this->Html->link(__('List Subjects'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Edit Subject') ?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($subject) ?>
            <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('institute');
                    echo $this->Form->control('sex', 
                        ['options' => [
                            'FEMENINO' => 'FEMENINO', 
                            'MASCULINO' => 'MASCULINO', 
                            'MIXTO' => 'MIXTO']
                        ]);
                    echo $this->Form->control('tipo_academia', 
                        ['options' => [
                            'CULTURAL' => 'CULTURAL', 
                            'DEPORTIVA' => 'DEPORTIVA']
                        ]);
                    echo $this->Form->control('criterio_academia', 
                        [
                            'type' => 'radio',
                            'options' => [
                                'AÑO DE NACIMIENTO' => 'AÑO DE NACIMIENTO', 
                                'GRADO ESCOLAR' => 'GRADO ESCOLAR'],
                            'default' => 'AÑO DE NACIMIENTO',
                            'id' => 'criterio_academia'
                        ]);
                    
                    ?>
                    <!-- Option for año de nacimiento -->
                    <div id="collapseBirthDate" class="collapse show" aria-labelledby="headingBirthDate">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <?= $this->Form->control('anio_nacimiento_minimo',
                                ['label' => 'Año de nacimiento minimo']) ?>
                            <?= $this->Form->control('anio_nacimiento_maximo',
                                ['label' => 'Año de nacimiento maximo']) ?>
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
                    <?php

                    echo $this->Form->control('teachers._ids', ['options' => $teachers, 'class' => 'form-select']);
                    echo $this->Form->control('active', ['value' => '1', 'hidden' => true, 'label' => false]);
                ?>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
