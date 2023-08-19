<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>
<!-- Menú -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('Students') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit Student'), ['action' => 'edit', $student->id], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Form->postLink(__('Delete Student'), ['action' => 'delete', $student->id], ['confirm' => __('Are you sure you want to delete # {0}?', $student->id), 'class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('List Students'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('New Student'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Student View Container -->
<div class="container-fluid">
    <?= $this->Flash->render() ?>
    <div class="row">
        <!-- Left side: Student Information -->
        <div class="col-lg-5 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= h($student->name) ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('Curp') ?></th>
                                <td><?= h($student->curp) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Sex') ?></th>
                                <td><?= h($student->sex) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Level') ?></th>
                                <td><?= h($student->level) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('School Level') ?></th>
                                <td><?= h($student->school_level) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('School Group') ?></th>
                                <td><?= h($student->school_group) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Birth Date') ?></th>
                                <td><?= $this->Time->i18nFormat($student->birth_date, [\IntlDateFormatter::LONG, \IntlDateFormatter::NONE]) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Term') ?></th>
                                <td><?= $student->has('term') ? $student->term->description : '' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Side: Courses Information -->
        <div class="col-lg-7 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('School Courses List') ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <td><?= __('Name') ?></td>
                                    <td><?= __('Status') ?></td>
                                    <td class="actions"><?= __('Acciones') ?></td>
                                </tr>
                            </thead>
                            <?php foreach ($student->school_courses as $schoolCourses) : ?>
                                <tr>
                                    <td><?= h($schoolCourses->name) ?></td>
                                    <td><?= $schoolCourses->_joinData->is_confirmed == 1 ? "Inscrito" : "Preinscrito" ?></td>
                                    <td class="actions">
                                        <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolCoursesStudents', 'action' => 'delete', $schoolCourses->_joinData->id], ['confirm' => __('Are you sure you want to delete  {0}?', $schoolCourses->name)]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
