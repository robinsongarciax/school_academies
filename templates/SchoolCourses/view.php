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
                    <h3 class="page-header-title">Configuraci&oacute;n de <?= __('School Courses') ?></h3>
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
    <div class="row">
        <!-- Segmento izquiero -->
        <div class="col-lg-7 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= h($schoolCourse->name) ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('Name') ?></th>
                                <td><?= h($schoolCourse->name) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Subject') ?></th>
                                <td><?= $schoolCourse->has('subject') ? $this->Html->link($schoolCourse->subject->name, ['controller' => 'Subjects', 'action' => 'view', $schoolCourse->subject->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Teacher') ?></th>
                                <td><?= $schoolCourse->has('teacher') ? $this->Html->link($schoolCourse->teacher->name, ['controller' => 'Teachers', 'action' => 'view', $schoolCourse->teacher->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Term') ?></th>
                                <td><?= $schoolCourse->has('term') ? $this->Html->link($schoolCourse->term->id, ['controller' => 'Terms', 'action' => 'view', $schoolCourse->term->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Capacity') ?></th>
                                <td><?= $this->Number->format($schoolCourse->capacity) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Left side - Horario -->
        <div class="col-lg-5 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Related Schedules') ?></h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($schoolCourse->schedules)) : ?>
                    <div class="table-responsive">
                        <table class="table" width="100%" cellspacing="0">
                            <tr>
                                <th><?= __('Day') ?></th>
                                <th><?= __('Start') ?></th>
                                <th><?= __('End') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                            
                            <?php foreach ($schoolCourse->schedules as $schedules) : ?>
                            <tr>
                                <td><?= h($schedules->day_name) ?></td>
                                <td><?= h($schedules->start) ?></td>
                                <td><?= h($schedules->end) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('View'), ['controller' => 'Schedules', 'action' => 'view', $schedules->id]) ?>
                                    <?= $this->Html->link(__('Edit'), ['controller' => 'Schedules', 'action' => 'edit', $schedules->id]) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Schedules', 'action' => 'delete', $schedules->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schedules->id)]) ?>
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

        <div class="col-lg-12 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Related Students') ?></h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($schoolCourse->students)) : ?>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <tr>
                                <th><?= __('Name') ?></th>
                                <th><?= __('Last Name') ?></th>
                                <th><?= __('Second Last Name') ?></th>
                                <th><?= __('Curp') ?></th>
                                <th><?= __('Email') ?></th>
                                <th><?= __('Level') ?></th>
                                <th><?= __('Institute') ?></th>
                                <th><?= __('Group') ?></th>
                                <th><?= __('Id Number') ?></th>
                                <th><?= __('Birth Date') ?></th>
                                <th class="actions"><?= __('Actions') ?></th>
                            </tr>
                            <?php foreach ($schoolCourse->students as $students) : ?>
                            <tr>
                                <td><?= h($students->name) ?></td>
                                <td><?= h($students->last_name) ?></td>
                                <td><?= h($students->second_last_name) ?></td>
                                <td><?= h($students->curp) ?></td>
                                <td><?= h($students->email) ?></td>
                                <td><?= h($students->level) ?></td>
                                <td><?= h($students->institute) ?></td>
                                <td><?= h($students->group) ?></td>
                                <td><?= h($students->id_number) ?></td>
                                <td><?= h($students->birth_date) ?></td>
                                <td class="actions">
                                    <?= $this->Html->link(__('View'), ['controller' => 'Students', 'action' => 'view', $students->id]) ?>
                                    <?= $this->Html->link(__('Edit'), ['controller' => 'Students', 'action' => 'edit', $students->id]) ?>
                                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Students', 'action' => 'delete', $students->id], ['confirm' => __('Are you sure you want to delete # {0}?', $students->id)]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
