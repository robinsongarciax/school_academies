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
                    <?php if (!in_array($this->Identity->get('role')->id, [7, 8])) : ?>
                        <?php if (in_array($this->Identity->get('username'), ['TJGONZALEZ', 'MCASTILLO'])) : ?>
                            <?= $this->Html->link(__('New Incident Report'), ['action' => 'addEspecial'], ['class' => 'btn btn-md btn-primary text-white', 'escape' => true]) ?>
                        <?php else: ?>
                            <?= $this->Html->link(__('New Incident Report'), ['action' => 'add'], ['class' => 'btn btn-md btn-primary text-white', 'escape' => true]) ?>
                        <?php endif; ?>
                    <?php endif; ?>


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
                <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
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
                            <td><?= h($incidentReport->id) ?></td>
                            <td><?= h($incidentReport->date) ?></td>
                            <td><?= h($incidentReport->subject) ?></td>
                            <td><?= $incidentReport->has('student') ? $incidentReport->student->name : '' ?></td>
                            <td><?= $incidentReport->has('user') ? $incidentReport->user->name : '' ?></td>
                            <td><?= $incidentReport->has('teacher') ? $incidentReport->teacher->name : '' ?></td>
                            <td><?= $incidentReport->has('school_course') ? $incidentReport->school_course->name : '' ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['action' => 'view', $incidentReport->id]) ?>
                                <?php
                                if (!in_array($this->Identity->get('role')->id, [7, 8])) {
                                    echo $this->Html->link(__('Edit'), ['action' => 'edit', $incidentReport->id]);
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
