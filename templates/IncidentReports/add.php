<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\IncidentReport $incidentReport
 * @var \Cake\Collection\CollectionInterface|string[] $students
 * @var \Cake\Collection\CollectionInterface|string[] $users
 * @var \Cake\Collection\CollectionInterface|string[] $teachers
 * @var \Cake\Collection\CollectionInterface|string[] $schoolCourses
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

$('#teachers-id').on('change', function() {
    let selected = $(this).find("option:selected");
    let teacherId = selected.val();
    $.ajax({
        url: '<?= $this->Url->build(['controller' => 'IncidentReports', 'action' => 'search-school-courses']) ?>',
        method: 'GET',
        data: {
            teacherId: teacherId
        },
        success: function(response) {
            console.log(response);
            var coursesSelect = $('#school-courses-id');
            coursesSelect.empty().append('<option value=""><?=__('Select School Course')?></option>');
            $.each(response.schoolCourses, function(id, name) {
                coursesSelect.append($('<option></option>').val(id).text(name));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});

$('#school-courses-id').on('change', function() {
    let selected = $(this).find("option:selected");
    let schoolCourseId = selected.val();
    $.ajax({
        url: '<?= $this->Url->build(['controller' => 'IncidentReports', 'action' => 'search-students']) ?>',
        method: 'GET',
        data: {
            schoolCourseId: schoolCourseId
        },
        success: function(response) {
            console.log(response);
            var studentsSelect = $('#students-id');
            studentsSelect.empty().append('<option value=""><?=__('Select Student')?></option>');
            $.each(response.students, function(id, name) {
                studentsSelect.append($('<option></option>').val(id).text(name));
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
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
                    <?= $this->Html->link(__('List Incident Reports'), ['action' => 'index'], ['class' => 'btn btn-sm btn-light text-primary', 'escape' => true]) ?>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Add Incident Report') ?></h6>
        </div>
        <div class="card-body">
        <?= $this->Form->create($incidentReport, ['type' => 'file']) ?>
        <fieldset>
            <?php
                echo $this->Form->control('subject', ['label' => __('Issue')]);
                echo $this->Form->control('date', ['label' => __('Incident Date'), 'max' => date('Y-m-d')]);
                echo $this->Form->control('teachers_id', ['options' => $teachers, 'label' => __('Teacher'), 'empty' => __('Select Teacher')]);
                echo $this->Form->control('school_courses_id', ['options' => [], 'Label' => __('School Course'), 'empty' => __('Select Teacher First')]);
                echo $this->Form->control('students_id', ['options' => [], 'label' => __('Student'), 'empty' => __('Select School Course First')]);;
                echo $this->Form->control('description', ['type' => 'textarea']);
                echo $this->Form->control('attachment', ['type' => 'file', 'label' => 'Reporte físico', 'required' => false, 'accept' => 'image/*']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
    </div>
</div>
