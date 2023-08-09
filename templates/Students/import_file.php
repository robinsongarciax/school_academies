<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 * @var \Cake\Collection\CollectionInterface|string[] $terms
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $schoolCourses
 */
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
            <?= $this->Form->create($student, ['type' => 'file']) ?>
            <fieldset>
                <?php
                    echo $this->Form->control('file', ['type' => 'file', 'accept' => ['.csv,',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,',
                        'application/vnd.ms-excel'
                    ]]);
                    echo $this->Form->control('term_id', ['options' => $terms,
                        'hidden' => true, 'label' => false
                    ]);
                ?>
                <label for = "eliminar_todo">
                    <?php echo $this->Form->checkbox('eliminar', ['hiddenField' => false, 'id'=>'eliminar_todo']); ?>
                    Eliminar todo
                </label>

            </fieldset>
            <button class="btn btn-primary" type="submit">
                <?= __('Submit') ?>
            </button>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
