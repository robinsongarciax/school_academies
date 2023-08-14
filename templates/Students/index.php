<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Student> $students
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
                    <?= $this->Html->link(__('Import File'), ['action' => 'importFile'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('New Student'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Students List')?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('curp') ?></th>
                            <th><?= $this->Paginator->sort('sex') ?></th>
                            <th><?= $this->Paginator->sort('level') ?></th>
                            <th><?= $this->Paginator->sort('school_level') ?></th>
                            <th><?= $this->Paginator->sort('school_group') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= h($student->name) ?></td>
                            <td><?= h($student->curp) ?></td>
                            <td><?= h($student->sex) ?></td>
                            <td><?= h($student->level) ?></td>
                            <td><?= h($student->school_level) ?></td>
                            <td><?= h($student->school_group) ?></td>
                            <td class="actions">
                                <?= $this->Html->link("", ['action' => 'view', $student->id], ['class'=>'fas fa-eye']) ?>
                                <?= $this->Html->link("", ['action' => 'edit', $student->id], ['class'=>'fas fa-pen']) ?>
                                <?= $this->Form->postLink("", ['action' => 'delete', $student->id], ['class'=>'fas fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $student->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
