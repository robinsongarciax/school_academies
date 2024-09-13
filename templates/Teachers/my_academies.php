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
                            <th><?= $this->Paginator->sort('term') ?></th>
                            <th class="actions"><?= __('Listas escolares') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>
                        <tr>
                            <td><?= h($schoolCourse["name"]) ?></td>
                            <td><?= h($schoolCourse["term"]["description"]) ?></td>
                            <td class="actions">
                            <?= $this->Html->link("", ['controller' => 'SchoolCourses', 'action' => 'export-list-related-students', $schoolCourse["id"]], ['class' => 'fas fa-file-alt action-table', 'escape' => true]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
