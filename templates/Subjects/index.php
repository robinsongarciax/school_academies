<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Subject> $subjects
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
                    <?= $this->Html->link(__('New Subject'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Subjects List')?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('institute') ?></th>
                            <th><?= $this->Paginator->sort('sex') ?></th>
                            <th><?= $this->Paginator->sort('tipo_academia') ?></th>
                            <th><?= $this->Paginator->sort('criterio_academia') ?></th>
                            <th><?= $this->Paginator->sort('pago_obligatorio') ?></th>

                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= h($subject->name) ?></td>
                            <td><?= h($subject->institute) ?></td>
                            <td><?= h($subject->sex) ?></td>
                            <td><?= h($subject->tipo_academia) ?></td>
                            <td><?= h($subject->criterio_academia) ?></td>
                            <td><?= $subject->pago_obligatorio === 1 ? 'Sí' : 'No' ?></td>
                            <td class="actions">
                                <?= $this->Html->link("", ['action' => 'view', $subject->id], ['class'=>'fas fa-eye']) ?>
                                <?= $this->Html->link("", ['action' => 'edit', $subject->id], ['class'=>'fas fa-pen']) ?>
                                <?= $this->Form->postLink("", ['action' => 'delete', $subject->id], ['class'=>'fas fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $subject->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
