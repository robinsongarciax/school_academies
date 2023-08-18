<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subject $subject
 * @var \Cake\Collection\CollectionInterface|string[] $teachers
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
                    <?= $this->Html->link(__('List Subjects'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('New Subject')?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($subject) ?>
            <fieldset>
                <legend><?= __('Add Subject') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('description');
                    echo $this->Form->control('sex', 
                        ['options' => [
                            'F' => 'FEMENINO', 
                            'M' => 'MASCULINO', 
                            'X' => 'MIXTO']
                        ]);
                    echo $this->Form->control('tipo_academia', 
                        ['options' => [
                            'CULTURAL' => 'CULTURAL', 
                            'DEPORTIVA' => 'DEPORTIVA']
                        ]);
                    echo $this->Form->control('pago_obligatorio', 
                        ['options' => [
                            '1' => 'Sí', 
                            '0' => 'No',
                            ]
                        ]);
                    echo $this->Form->control('is_visible',
                        ['options' => [
                            '1' => 'Sí', 
                            '0' => 'No',
                            ],
                            'label' => 'Visible para los alumnos'
                        ]);
                    echo $this->Form->control('active', ['value' => '1', 'hidden' => true, 'label' => false]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
