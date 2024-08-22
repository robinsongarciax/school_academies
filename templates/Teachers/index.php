<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Teacher> $teachers
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Teachers') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('New Teacher'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <?= $this->Flash->render() ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Teachers List')?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('email') ?></th>
                            <th><?= $this->Paginator->sort('tipo_academia') ?></th>
                            <th><?= $this->Paginator->sort('active', 'Estatus') ?></th>
                            <th><?= $this->Paginator->sort('user_id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($teachers as $teacher): ?>
                        <tr>
                            <td><?= h($teacher->name) ?></td>
                            <td><?= h($teacher->email) ?></td>
                            <td><?= h($teacher->tipo_academia) ?></td>
                            <th><?= h($teacher->active == 1 ? 'Vigente' : 'Baja') ?></th>
                            <td><?= $teacher->has('user') ? $this->Html->link($teacher->user->username, ['controller' => 'Users', 'action' => 'view', $teacher->user->id]) : '' ?></td>
                            <td class="actions">
                                <?= $this->Html->link("", ['action' => 'view', $teacher->id], ['class'=>'fas fa-eye']) ?>
                                <?= $this->Html->link("", ['action' => 'edit', $teacher->id], ['class'=>'fas fa-pen']) ?>
                                <?= $this->Form->postLink("", ['action' => 'deactive', $teacher->id], ['class'=>'fas fa-trash', 'confirm' => __('Are you sure you want to deactive {0}?', $teacher->name)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
