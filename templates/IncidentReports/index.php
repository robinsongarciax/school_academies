<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\IncidentReport> $incidentReports
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Incident Reports') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('New Incident Report'), ['action' => 'add'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <?= $this->Flash->render() ?>
    <div class="card shadow mb-4">
        
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= __('Incident Date') ?></th>
                            <th><?= __('Issue') ?></th>
                            <th><?= __('Student') ?></th>
                            <th><?= __('Reported by') ?></th>
                            <th><?= __('Teacher') ?></th>
                            <th><?= __('School Course') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incidentReports as $incidentReport): ?>
                        <tr>
                            <td><?= h($incidentReport->date) ?></td>
                            <td><?= h($incidentReport->subject) ?></td>
                            <td><?= $incidentReport->has('student') ? $this->Html->link($incidentReport->student->name, ['controller' => 'Students', 'action' => 'view', $incidentReport->student->id]) : '' ?></td>
                            <td><?= $incidentReport->has('user') ? $this->Html->link($incidentReport->user->name, ['controller' => 'Users', 'action' => 'view', $incidentReport->user->id]) : '' ?></td>
                            <td><?= $incidentReport->has('teacher') ? $incidentReport->teacher->name : '' ?></td>
                            <td><?= $incidentReport->has('school_course') ? $incidentReport->school_course->name : '' ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $incidentReport->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $incidentReport->id]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
