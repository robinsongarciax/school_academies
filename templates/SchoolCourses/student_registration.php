<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolCourse $schoolCourse
 */
$availability = $schoolCourse->capacity - $totalStudentsConfirmed;
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Course Information about ') . h($schoolCourse->name) ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit School Course'), ['action' => 'edit', $schoolCourse->id], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Form->postLink(__('Delete School Course'), ['action' => 'delete', $schoolCourse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolCourse->id), 'class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('List School Courses'), ['action' => 'index', $schoolCourse->tipo_academia], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('New School Course'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <nav class="nav nav-borders">
        <?php
        echo $this->Html->link(__('Course Information'), [
            'action' => 'view',
            $schoolCourse->id
        ], [
            'class' => 'nav-link primary-button'
        ]);
        echo $this->Html->link(__('Confirmed Students'), [
            'action' => 'confirmedStudents',
            $schoolCourse->id
        ], [
            'class' => 'nav-link primary-button'
        ]);
        echo $this->Html->link(__('Pre-Enrolled Students'), [
            'action' => 'studentRegistration',
            $schoolCourse->id
        ], [
            'class' => 'nav-link primary-button active'
        ]);
        ?>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Pre-Enrolled Students') . ' (' . __('Availability') . ' = ' . $availability . ')' ?></h6>
        </div>
        <div class="card-body">
            <!-- Flash Message -->
            <?= $this->Flash->render() ?>

            <!-- Button trigger students registration modal -->
            <?= $this->Form->button(__('Add Students'), [
                'type' => 'button',
                'class' => 'btn btn-outline-primary btn-sm',
                'data-toggle' => 'modal',
                'data-target' => '#studentsRegModal'
            ]) ?>

            <?= $this->Form->postLink(__('Confirm All Students'), [
                'controller' => 'SchoolCoursesStudents',
                'action' => 'confirmAllStudents',
                $schoolCourse->id
            ], [
                'class' => 'btn btn-outline-primary btn-sm',
                'confirm' => __('Are you sure you want confirm the student list?')
            ]) ?>
            <hr class="mt-0 mb-2">

            <?php if (!empty($schoolCourse->students)) : ?>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?= __('Name') ?></th>
                                <th><?= __('School Level') ?></th>
                                <th><?= __('School Group') ?></th>
                                <th><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schoolCourse->students as $schoolCourseStudent) : ?>
                            <tr>
                                <td><?= h($schoolCourseStudent->name) ?></td>
                                <td><?= h($schoolCourseStudent->school_level) ?></td>
                                <td><?= h($schoolCourseStudent->school_group) ?></td>
                                <td class="actions">
                                    <?php
                                    if ($schoolCourse->capacity > $totalStudentsConfirmed) {
                                        echo $this->Form->postLink("", [
                                            'controller' => 'SchoolCoursesStudents',
                                            'action' => 'confirm',
                                            $schoolCourseStudent->_joinData->id],
                                            ['class'=>'fas fa-user-check',]
                                        );
                                    } ?>

                                    <?= $this->Form->postLink("", [
                                        'controller' => 'SchoolCoursesStudents',
                                        'action' => 'delete',
                                        $schoolCourseStudent->_joinData->id],
                                        ['class'=>'fas fa-user-times']
                                    ) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p><?= __('No data available') ?></p>
            <?php endif; ?>

        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="studentsRegModal" tabindex="-1" role="dialog" aria-labelledby="studentsRegModalModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="studentsRegModalModalLabel">Alumnos disponibles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <?= $this->Form->create(null, ['url' => ['action' => 'preEnroll', $schoolCourse->id]]) ?>
          <div class="table-responsive">
            <table class="table table-bordered" id="modalDataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= __('Name') ?></th>
                        <th><?= __('School Level') ?></th>
                        <th><?= __('School Group') ?></th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= $this->Form->checkbox('ids[]', [
                                'value' => $student->id
                                ]) ?></td>
                            <td><?= $student->name ?></td>
                            <td><?= $student->school_level ?></td>
                            <td><?= $student->school_group ?></td>
                            <td><?= $this->Form->postLink(__('Pre-Enroll'), ['action' => 'preEnroll', $schoolCourse->id, $student->id],
                                ['block' => true]
                                ) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
                <?= $this->Form->button(__('Preinscribir'), [
                    'id' => 'preinscripcionBtn'
                ]) ?>
            </div>
            <?= $this->Form->end() ?>
            <?= $this->fetch('postLink') ?>
      </div>
    </div>
  </div>
</div>
