<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $teacher
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
                    <?= $this->Html->link(__('Edit Teacher'), ['action' => 'edit', $teacher->id], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Form->postLink(__('Delete Teacher'), ['action' => 'delete', $teacher->id], ['confirm' => __('Are you sure you want to delete {0}?', $teacher->name), 'class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('List Teachers'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('New Teacher'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-5 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Teacher Information')?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('Name') ?></th>
                                <td><?= h($teacher->name) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Email') ?></th>
                                <td><?= h($teacher->email) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Celular') ?></th>
                                <td><?= h($teacher->celular) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Nombre de usuario') ?></th>
                                <td><?= $teacher->has('user') ? $this->Html->link($teacher->user->username, ['controller' => 'Users', 'action' => 'view', $teacher->user->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Active') ?></th>
                                <td><?= $teacher->active == 1 ? 'Sí' : 'No' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Academías vinculadas')?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><?= __('Id') ?></th>
                                    <th><?= __('Name') ?></th>
                                    <th><?= __('Term Id') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($teacher->school_courses)) : ?>
                                <?php foreach ($teacher->school_courses as $schoolCourses) : ?>
                                    <tr>
                                        <td><?= h($schoolCourses->id) ?></td>
                                        <td><?= h($schoolCourses->name) ?></td>
                                        <td><?= h($schoolCourses->term_id) ?></td>
                                        <td class="actions">
                                            <?= $this->Html->link(__('View'), ['controller' => 'SchoolCourses', 'action' => 'view', $schoolCourses->id]) ?>
                                            <?= $this->Html->link(__('Edit'), ['controller' => 'SchoolCourses', 'action' => 'edit', $schoolCourses->id]) ?>
                                            <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolCourses', 'action' => 'delete', $schoolCourses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolCourses->id)]) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">Sin datos disponibles</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>