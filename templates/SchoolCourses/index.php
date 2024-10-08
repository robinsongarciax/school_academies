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
                    <?= $this->Html->link(__('New School Course'), ['action' => 'add', $type], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <?= $this->Flash->render() ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('School Courses List')?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered" id="academiesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('id') ?></th>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th>Tipo<br/>Academia</th>
                            <th><?= $this->Paginator->sort('teacher_id') ?></th>
                            <th><?= $this->Paginator->sort('capacity') ?></th>
                            <th><?= $this->Paginator->sort('Disp.') ?></th>
                            <th><?= $this->Paginator->sort('sex') ?></th>
                            <th>Horario</th>
                            <th>Ciclo<br/>Escolar</th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>
                        <tr>
                            <td><?= h($schoolCourse->id) ?></td>
                            <td><?= h($schoolCourse->name) ?></td>
                            <td><?= $schoolCourse->tipo_academia ?></td>
                            <td><?= $schoolCourse->has('teacher') ? $this->Html->link($schoolCourse->teacher->name, ['controller' => 'Teachers', 'action' => 'view', $schoolCourse->teacher->id]) : '' ?>
                            </td>
                            <td><?= $this->Number->format($schoolCourse->capacity) ?></td>
                            <td><?= $this->Number->format($schoolCourse->capacity - $schoolCourse->occupancy) ?></td>
                            <?php
                            switch ($schoolCourse->sex) {
                                case 'F':
                                    $sex = 'FEMENINO';
                                    break;
                                case "M":
                                    $sex = 'MASCULINO';
                                    break;
                                default:
                                    $sex = 'MIXTO';
                                    break;
                            }
                            ?>
                            <td><?= $sex ?></td>
                            <?php
                            // horario
                            $monday = '';
                            $tuesday = '';
                            $wednesday = '';
                            $thursday = '';
                            $friday = '';
                            $saturday = '';
                            $horario = [];
                            $time_schedule = '';
                            foreach ($schoolCourse->schedules as $schedule) {
                                $start = substr($schedule->start, 0, 5);//$this->Time->format($schedule->start, [IntlDateFormatter::NONE, IntlDateFormatter::SHORT]);
                                $end = substr($schedule->end, 0, 5);//$this->Time->format($schedule->end, [IntlDateFormatter::NONE, IntlDateFormatter::SHORT]);
                                $time_schedule = $start . ' - ' . $end;
                                switch ($schedule->day_id) {
                                    case 1:
                                        $horario[] = 'Lun';
                                        break;
                                    case 2:
                                        $horario[] = 'Mar';
                                        break;
                                    case 3:
                                        $horario[] = 'Miérc';
                                        break;
                                    case 4:
                                        $horario[] = 'Juev';
                                        break;
                                    case 5:
                                        $horario[] = 'Vier';
                                        break;
                                }
                            }
                            $horario = implode(', ', $horario);
                            $horario .= ' de ' .$time_schedule;
                            ?>
                            <td><?= $horario ?></td>
                            
                            <td><?= $schoolCourse->has('term') ? $this->Html->link($schoolCourse->term->description, ['controller' => 'Terms', 'action' => 'view', $schoolCourse->term->id]) : '' ?></td>
                            <td class="actions" nowrap>
                                <?= $this->Html->link("", ['action' => 'view', $schoolCourse->id], ['class'=>'fas fa-eye', 'title' => 'Ver']) ?>
                                <?= $this->Html->link("", ['action' => 'edit', $schoolCourse->id], ['class'=>'fas fa-pen', 'title' => 'Editar']) ?>
                                <?= $this->Form->postLink("", ['action' => 'delete', $schoolCourse->id], ['class'=>'fas fa-trash', 'title' => 'Eliminar', 'confirm' => __('Are you sure you want to delete {0}?', $schoolCourse->name)]) ?>
                                <?= $this->Html->link("", ['action' => 'studentRegistration', $schoolCourse->id], ['class'=>'fas fa-user-plus', 'title' => 'Agregar alumnos']) ?>
                                <?= $this->Html->link("", ['action' => 'export-related-students', $schoolCourse->id], ['class'=>'fas fa-file-excel', 'title' => 'Relación de alumnos en excel']) ?>
                                <?= $this->Html->link("", ['action' => 'export-related-students', $schoolCourse->id, 'pdf'], ['class'=>'fas fa-file-pdf', 'title' => 'Relación de alumnos en pdf']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
