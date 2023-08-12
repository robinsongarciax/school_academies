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
                    <h3 class="page-header-title"><?= __('Course Information about ') . h($schoolCourse->name) ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit School Course'), ['action' => 'edit', $schoolCourse->id], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Form->postLink(__('Delete School Course'), ['action' => 'delete', $schoolCourse->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolCourse->id), 'class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('List School Courses'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
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
            'class' => 'nav-link primary-button active'
        ]);
        echo $this->Html->link(__('Pre-Enrolled Students'), [
            'action' => 'studentRegistration',
            $schoolCourse->id
        ], [
            'class' => 'nav-link primary-button'
        ]);
        ?>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Confirmed Students') ?></h6>
        </div>
        <div class="card-body">
            <?= $this->Html->link(__('Exportar alumnos'), ['action' => 'export-related-students', $schoolCourse->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => true]) ?>
            <?= $this->Html->link(__('Exportar lista'), ['action' => 'export-list-related-students', $schoolCourse->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => true]) ?>
            <?= $this->Html->link(__('Constancias de estudios'), ['action' => 'constancias-estudios', $schoolCourse->id], ['class' => 'btn btn-sm btn-outline-primary', 'escape' => true]) ?>
            <hr class="mt-0 mb-4">
            <?php if (!empty($schoolCourse->students)) : ?>
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" id="dataTable" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Curp') ?></th>
                            <th><?= __('School Level') ?></th>
                            <th><?= __('School Group') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourse->students as $students) : ?>
                        <tr>
                            <td><?= h($students->name) ?></td>
                            <td><?= h($students->curp) ?></td>
                            <td><?= h($students->school_level) ?></td>
                            <td><?= h($students->school_group) ?></td>
                            <td class="actions">
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolCoursesStudents', 'action' => 'delete', $students->_joinData->id], ['confirm' => __('Are you sure you want to delete to {0}?', $students->name)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?= $this->Form->create($schedule, ['action'=>'/school_academies/schedules/add']) ?>
            <fieldset>
                <legend><?= __('Add Schedule') ?></legend>
                <?php
                    echo $this->Form->control('day_id', ['options' => $days]);
                    echo $this->Form->control('start');
                    echo $this->Form->control('end');
                    echo $this->Form->control('id', ['value' => $schoolCourse->id]);
                ?>
            </fieldset>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <?= $this->Form->end() ?>
      </div>
    </div>
  </div>
</div>
