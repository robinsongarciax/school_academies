<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolCourse $schoolCourse
 */

$this->Html->script('add-edit-modal', ['block' => true]);
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
                    <?= $this->Html->link(__('New School Course'), ['action' => 'add', $schoolCourse->tipo_academia], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
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
            'class' => 'nav-link primary-button active'
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
            'class' => 'nav-link primary-button'
        ]);
        ?>
    </nav>
    <hr class="mt-0 mb-4">
    <div class="row">
        <?= $this->Flash->render() ?>
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
                                <th><?= __('Teacher') ?></th>
                                <td><?= $schoolCourse->has('teacher') ? $this->Html->link($schoolCourse->teacher->name, ['controller' => 'Teachers', 'action' => 'view', $schoolCourse->teacher->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Term') ?></th>
                                <td><?= $schoolCourse->has('term') ? $this->Html->link($schoolCourse->term->description, ['controller' => 'Terms', 'action' => 'view', $schoolCourse->term->id]) : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Capacity') ?></th>
                                <td><?= $this->Number->format($schoolCourse->capacity) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Availability') ?></th>
                                <td><?= $this->Number->format($schoolCourse->capacity - $totalStudentsConfirmed) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Sex') ?></th>
                                <?php
                                switch ($schoolCourse->sex) {
                                    case 'F':
                                        $sex = 'FEMENINO';
                                        break;
                                    case 'M':
                                        $sex = 'MASCULINO';
                                        break;
                                    default:
                                        $sex = 'MIXTO';
                                        break;
                                }
                                ?>
                                <td><?= $sex ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Price') ?></th>
                                <td><?= $this->Number->currency($schoolCourse->price) ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Pago Obligatorio') ?></th>
                                <td><?= $schoolCourse->pago_obligatorio == 1 ? 'Sí' : 'No' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Visible') ?></th>
                                <td><?= $schoolCourse->visible == 1 ? 'Sí' : 'No' ?></td>
                            </tr>
                            <tr>
                                <th><?= $schoolCourse->criterio_academia ?></th>
                                <?php if ($schoolCourse->criterio_academia == 'GRADO ESCOLAR') : ?>
                                    <td>
                                    <?php foreach ($schoolCourse->school_levels as $school_level) {
                                        echo $school_level->name . "<br/>";
                                    }
                                    ?>
                                    </td>
                                <?php else: ?>
                                    <td>M&iacute;nimo <?= $schoolCourse->min_year_of_birth ?> <br>
                                        M&aacute;ximo <?= $schoolCourse->max_year_of_birth ?></td>
                                <?php endif; ?>
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
                    <!-- Button trigger modal -->
                    <?= $this->Html->link(__('Add Schedule'), [
                        'controller' => 'Schedules',
                        'action' => 'add',
                        $schoolCourse->id
                    ], [
                        'class' => 'btn btn-sm btn-outline-primary btn-modal',
                        'modal-title' => 'Agregar Horario',
                        'data-toggle' => 'modal',
                        'data-target' => '#addEditModal'
                    ]) ?>
                    <hr class="mt-0 mb-4">
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
                                <td><?= h($schedules->day->name) ?></td>
                                <?php date_default_timezone_set('America/Mexico_City'); ?>
                                <td><?= substr($schedules->start, 0, 5) ?></td>
                                <td><?= substr($schedules->end, 0, 5) ?></td>
                                <td class="actions">
                                    <?= $this->Form->postLink("", ['controller' => 'Schedules', 'action' => 'delete', $schedules->id], ['class'=>'fas fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $schedules->id)]) ?>
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

<!-- Modal -->
<?= $this->element('modal/add_edit') ?>