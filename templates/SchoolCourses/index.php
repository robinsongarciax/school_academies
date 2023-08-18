<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SchoolCourse> $schoolCourses
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
                    <?= $this->Html->link(__('New School Course'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('School Courses List')?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('capacity') ?></th>
                            <th><?= $this->Paginator->sort('subject_id') ?></th>
                            <th><?= $this->Paginator->sort('teacher_id') ?></th>
                            <th><?= $this->Paginator->sort('term_id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>
                        <tr>
                            <td><?= h($schoolCourse->name) ?></td>
                            <td><?= $this->Number->format($schoolCourse->capacity) ?></td>
                            <td><?= $schoolCourse->has('subject') ? $this->Html->link($schoolCourse->subject->name, ['controller' => 'Subjects', 'action' => 'view', $schoolCourse->subject->id]) : '' ?></td>
                            <td><?= $schoolCourse->has('teacher') ? $this->Html->link($schoolCourse->teacher->name, ['controller' => 'Teachers', 'action' => 'view', $schoolCourse->teacher->id]) : '' ?></td>
                            <td><?= $schoolCourse->has('term') ? $this->Html->link($schoolCourse->term->description, ['controller' => 'Terms', 'action' => 'view', $schoolCourse->term->id]) : '' ?></td>
                            <td class="actions">
                                <?= $this->Html->link("", ['action' => 'view', $schoolCourse->id], ['class'=>'fas fa-eye']) ?>
                                <?= $this->Html->link("", ['action' => 'edit', $schoolCourse->id], ['class'=>'fas fa-pen']) ?>
                                <?= $this->Form->postLink("", ['action' => 'delete', $schoolCourse->id], ['class'=>'fas fa-trash', 'confirm' => __('Are you sure you want to delete {0}?', $schoolCourse->name)]) ?>
                                <?= $this->Html->link("", ['action' => 'studentRegistration', $schoolCourse->id], ['class'=>'fas fa-user-plus']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
