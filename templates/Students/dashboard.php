<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Student> $students
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Dashboard') ?></h3>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Students List')?></h6>
        </div>
        <div class="card-body">
            <?= $this->Flash->render() ?>
            
            <!-- Search bar -->
            <div class="container" id="search-bar">
                <?= $this->Form->create(null, ['url' => ['action' => 'search']]) ?>
                <div class="form-group row">
                    <label for="inputSearch" class="col-sm-1 col-form-label">B&uacute;queda</label>
                    <div class="col-sm-8">
                        <input class="form-control" id="inputSearch" name="inputSearch" placeholder="Nombre o CURP del alumno" value ="<?= $searchOptions['inputSearch'] ?? '' ?>">
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <div class="col-sm-3">
                        <label class="mr-sm-2" for="tipoAcacemia">Tipo de academia</label>
                        <select class="custom-select mr-sm-2" id="tipoAcacemia" name="tipoAcacemia">
                        <option selected>Seleccione...</option>
                        <option value="DEPORTIVA" <?= $searchOptions['tipoAcacemia'] == 'DEPORTIVA' ? 'selected' : ''?>>Deportiva</option>
                        <option value="CULTURAL" <?= $searchOptions['tipoAcacemia'] == 'CULTURAL' ? 'selected' : ''?>>Cultural</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="mr-sm-2" for="numCursos">Número de cursos</label>
                        <select class="custom-select mr-sm-2" id="numCursos" name="numCursos">
                        <option value="" selected>Seleccione...</option>
                        <option value="1" <?= $searchOptions['numCursos'] == '1' ? 'selected' : ''?>>1</option>
                        <option value="2" <?= $searchOptions['numCursos'] == '2' ? 'selected' : ''?>>2</option>
                        <option value="3" <?= $searchOptions['numCursos'] == '3' ? 'selected' : ''?>>3 o más</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="mr-sm-2" for="tipoCurso">Tipo de curso</label>
                        <select class="custom-select mr-sm-2" id="tipoCurso" name="tipoCurso">
                            <option value="" selected>Seleccione...</option>
                            <option value="1" <?= $searchOptions['tipoCurso'] == '1' ? 'selected' : ''?>>Sin costo</option>
                            <option value="2" <?= $searchOptions['tipoCurso'] == '2' ? 'selected' : ''?>>Con costo</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="col-sm-3">
                        <label class="mr-sm-2" for="academia">Academia</label>
                        <select class="custom-select mr-sm-2" id="academia" name="academia">
                            <option value="" selected>Seleccione...</option>
                            <?php foreach($schoolCourses as $schoolCourse) : ?>
                                <option value="<?= $schoolCourse->id ?>" <?= $searchOptions['academia'] == $schoolCourse->id ? 'selected' : ''?>><?= $schoolCourse->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?= $this->Form->button(__('Search'), ['id' => 'search-list']) ?>

                <?= $this->Form->button(__('Download'), ['type' => 'submit',
                                                        'formAction' => 'downloadSchoolCoursesStudents',
                                                         'class' => 'btn btn-info',
                                                         'id' => 'download-list']) ?>

                <?= $this->Form->end() ?>
            </div>
            <br>

            <div class="table-responsive">
                
                <?php
                // custom form fields
                $this->Form->setTemplates([
                    'label' => '<label{{attrs}}>{{text}}</label>',
                    // Select element,
                    'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
                ]);

                $limitControl = $this->Paginator->limitControl([10 => 10, 25 => 25, 50 => 50, 100 => 100], null, [
                    'label' => __('Show') . ' ']);
                echo str_replace('</div></form>', ' '. $this->Form->label(' ' . __('records')) . '</div></form>', $limitControl);
                ?>

                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('curp') ?></th>
                            <th><?= $this->Paginator->sort('school_level') ?></th>
                            <th><?= $this->Paginator->sort('school_group') ?></th>
                            <th><?= $this->Paginator->sort('SchoolCourses.name', __('Academy')) ?></th>
                            <th><?= $this->Paginator->sort('SchoolCoursesStudents.id', __('Folio')) ?></th>
                            <th><?= $this->Paginator->sort('SchoolCoursesStudents.cost', __('Cost')) ?></th>
                            <th><?= $this->Paginator->sort('SchoolCoursesStudents.is_pagado', __('Pagado')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <?php //pr($student);die();?>
                            <td><?= h($student->name) ?></td>
                            <td><?= h($student->curp) ?></td>
                            <td><?= h($student->school_level) ?></td>
                            <td><?= h($student->school_group) ?></td>
                            <td><?= h($student->_matchingData['SchoolCourses']->name) ?></td>
                            <td><?= '#' . h($student->_matchingData['SchoolCoursesStudents']->id) ?></td>
                            <td><?= h($student->_matchingData['SchoolCoursesStudents']->cost) ?></td>
                            <td>
                                <?php
                                if ($student->_matchingData['SchoolCoursesStudents']->is_pagado == 1)
                                    echo 'Sí';
                                else {
                                    echo 'No';
                                    echo '&nbsp';
                                    echo $this->Form->postLink('',
                                                               ['controller' => 'SchoolCoursesStudents',
                                                                'action' => 'updateIsPagado', $student->_matchingData['SchoolCoursesStudents']->id],
                                                               ['class' => 'fa fa-money-bill',
                                                                'title' => 'Marcar como pagado',
                                                                'confirm' => __('Are you sure you want mark this course as paid?')]);
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p><?= $this->Paginator->counter(__('Showing {{start}} of {{end}} out of {{count}} record(s)')) ?></p>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <?= $this->Paginator->first('<< ' . __('first')) ?>
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers(['modulus' => 4]) ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                    <?= $this->Paginator->last(__('last') . ' >>') ?>
                </ul>
            </nav>
        </div>
    </div>
</div>