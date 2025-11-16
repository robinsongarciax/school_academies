<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\IncidentReport $incidentReport
 * @var string[]|\Cake\Collection\CollectionInterface $students
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $teachers
 * @var string[]|\Cake\Collection\CollectionInterface $schoolCourses
 */
?>
<style type="text/css">
    #description {
        resize: none;
    }
</style>
<?php $this->Html->scriptStart(['block' => true]); ?>
let allowSubmit = false;

$(window).bind('beforeunload', function (event) {
    if (!allowSubmit) {
        event.preventDefault();
        event.returnValue = '<?= __('Are you sure you want to leave?') ?>';
    }
});

$('form').on('submit', function () {
    allowSubmit = true;
});
<?php $this->Html->scriptEnd(); ?>

<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Incident Reports') ?></h3>
                </div>
                <div class="col-12 col-xl-auto mb-3">
                    <?= $this->Html->link(__('Incident Reports'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Edit Incident Report') ?></h6>
        </div>
        <div class="card-body">
            <?= $this->Form->create($incidentReport, ['type' => 'file']) ?>
            <fieldset>
                <?php
                    echo $this->Form->control('subject', ['label' => __('Issue')]);
                    echo $this->Form->control('date', ['label' => __('Incident Date')]);
                    echo $this->Form->control('teacher', ['value' => $incidentReport->teacher->name, 'disabled' => true]);
                    echo $this->Form->control('school_course', ['value' => $incidentReport->school_course->name, 'disabled' => true]);
                    echo $this->Form->control('student', ['value' => $incidentReport->student->name, 'disabled' => true]);
                    echo $this->Form->control('description', ['type' => 'textarea']);
                    echo $this->Form->control('attachment', ['type' => 'file', 'label' => 'Reporte físico', 'required' => false, 'accept' => 'image/*']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>