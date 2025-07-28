<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\IncidentReport $incidentReport
 */
?>
<!-- Menú -->
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('View Incident Report') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Edit Incident Report'), ['action' => 'edit', $incidentReport->id], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('List Incident Reports'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                    <?= $this->Html->link(__('New Incident Report'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
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
        <div class="col-lg-12 d-none d-lg-block">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Incident Report: {0}', $incidentReport->subject) ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><?= __('Incident Report #') ?></th>
                                <td><?= $incidentReport->id ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Student') ?></th>
                                <td><?= $incidentReport->has('student') ? $incidentReport->student->name : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Description') ?></th>
                                <td><?= h($incidentReport->description) ?></td>
                            </tr>
                            
                            <tr>
                                <th><?= __('Reported by') ?></th>
                                <td><?= $incidentReport->has('user') ? $incidentReport->user->name : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Teacher') ?></th>
                                <td><?= $incidentReport->has('teacher') ? $incidentReport->teacher->name : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('School Course') ?></th>
                                <td><?= $incidentReport->has('school_course') ? $incidentReport->school_course->name : '' ?></td>
                            </tr>
                            <tr>
                                <th><?= __('Incident Date') ?></th>
                                <td><?= h($incidentReport->date) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
