<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolCourse $schoolCourse
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Registration to ') . h($schoolCourse->name) ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('List School Courses'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <hr class="mt-0 mb-4">
    <div class="row">
        <!-- Segmento izquiero alumnos disponibles -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Available Students') ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th><?= __('Name') ?></th>
                                    <th><?= __('School Level') ?></th>
                                    <th><?= __('School Group') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $student->name ?></td>
                                        <td><?= $student->school_level ?></td>
                                        <td><?= $student->school_group ?></td>
                                        <td><?= $this->Form->postLink(__('Enroll'), ['action' => 'enroll', $schoolCourse->id, $student->id]) ?></td> 
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Left side - Horario -->
        <div class="col-lg-6 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Students Enrolled') ?></h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($schoolCourse->students)) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tr>
                                    <th><?= __('Name') ?></th>
                                    <th><?= __('School Level') ?></th>
                                    <th><?= __('School Group') ?></th>
                                    <th class="actions"><?= __('Actions') ?></th>
                                </tr>
                                <?php foreach ($schoolCourse->students as $students) : ?>
                                    <tr>
                                        <td><?= h($students->name) ?></td>
                                        <td><?= h($students->school_level) ?></td>
                                        <td><?= h($students->school_group) ?></td>
                                        <td class="actions">
                                            <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolCoursesStudents', 'action' => 'delete', $students->_joinData->id], ['confirm' => __('Are you sure you want to delete to {0}?', $students->name)]) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    <?php else: ?>
                        <p><?= __('No data available') ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>