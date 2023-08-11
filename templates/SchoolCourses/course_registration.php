<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SchoolCourse> $schoolCourses
 */

$this->Html->script('confirm-bootstrap-modal', ['block' => true]);
$arr_coursesSignedup = [];
foreach ($studentCourses as $studentCourse) {
    $school_course = $studentCourse->SchoolCoursesStudents;
    $arr_coursesSignedup[$school_course['school_course_id']] = [
        'school_courses_students_id' => $school_course['id'],
        'is_confirmed' => $school_course['is_confirmed'],
    ];
}
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Course Registration') ?></h3>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="container-fluid">
    <!-- Mensajes flash -->
    <?= $this->Flash->render() ?>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('School Course List')?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table  class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name', __('Academy')) ?></th>
                            <th><?= $this->Paginator->sort('teacher_id', __('Teacher')) ?></th>
                            <th><?= __('Monday') ?></th>
                            <th><?= __('Tuesday') ?></th>
                            <th><?= __('Wednesday') ?></th>
                            <th><?= __('Thursday') ?></th>
                            <th><?= __('Friday') ?></th>
                            <th><?= __('Saturday') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>

                            <?php if ($schoolCourse->subject->is_visible
                                ||  array_key_exists($schoolCourse->id, $arr_coursesSignedup)):?>
                            <tr>
                                <td><?= h($schoolCourse->name) ?></td>
                                <td><?= $schoolCourse->has('teacher') ? $this->Html->link($schoolCourse->teacher->name, ['controller' => 'Teachers', 'action' => 'view', $schoolCourse->teacher->id]) : '' ?></td>
                                <?php
                                $monday = '';
                                $tuesday = '';
                                $wednesday = '';
                                $thursday = '';
                                $friday = '';
                                $saturday = '';
                                foreach ($schoolCourse->schedules as $schedule) {
                                    $start = $this->Time->format($schedule->start, [IntlDateFormatter::NONE, IntlDateFormatter::SHORT]);
                                    $end = $this->Time->format($schedule->end, [IntlDateFormatter::NONE, IntlDateFormatter::SHORT]);
                                    $time_schedule = $start . ' - ' . $end;
                                    switch ($schedule->day_id) {
                                        case 1:
                                            $monday .= $time_schedule;
                                            break;
                                        case 2:
                                            $tuesday .= $time_schedule;
                                            break;
                                        case 3:
                                            $wednesday .= $time_schedule;
                                            break;
                                        case 4:
                                            $thursday .= $time_schedule;
                                            break;
                                        case 5:
                                            $friday.= $time_schedule;
                                            break;
                                        case 6:
                                            $saturday .= $time_schedule;
                                            break;
                                    }
                                }
                                ?>

                                <td><?= $monday ?></td>
                                <td><?= $tuesday ?></td>
                                <td><?= $wednesday ?></td>
                                <td><?= $thursday ?></td>
                                <td><?= $friday ?></td>
                                <td><?= $saturday ?></td>
                                <td class="actions">
                                    <?php if (array_key_exists($schoolCourse->id, $arr_coursesSignedup)) :
                                        $course_signedup = $arr_coursesSignedup[$schoolCourse->id];
                                        $school_courses_students_id = $course_signedup['school_courses_students_id'];
                                        if ($course_signedup['is_confirmed'] === 0) :
                                        ?>
                                        	<?php $this->Form->setTemplates([
                                        		'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                        	]); ?>
                                            <?= $this->Form->postLink(
                                            	__('Confirm'), [
                                                'controller' => 'SchoolCoursesStudents',
                                                'action' => 'confirm', 
                                                $school_courses_students_id], 
                                                [
                                                	'confirm' => __('Are you sure you want to register for {0}?', 
                                                        $schoolCourse->name),
                                                	'title' => __('Confirm'),
                                                	'data-toggle' => 'modal',
                                                	'data-target' => '#confirmRegistrationModal'
                                                ]) ?>

                                            <?= $this->Form->postLink(__('Dropout'), [
                                                'controller' => 'SchoolCoursesStudents',
                                                'action' => 'delete', 
                                                $school_courses_students_id]) ?>
                                        <?php else: ?>
                                            <?= $this->Form->postLink(__('Print Form'), [
                                                'controller' => 'SchoolCoursesStudents',
                                                'action' => 'printForm', $school_courses_students_id]) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?= $this->Form->postLink(__('Signup'), ['action' => 'signup', $schoolCourse->id]) ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Confirm modal -->
<div class="modal" id="confirmRegistrationModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?= __('Confirm Registration') ?></h5>
				<button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p id="confirmMessage"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><?= __('Cancel') ?></button>
				<button type="button" class="btn btn-primary" id="accept"><?= __('Accept') ?></button>
			</div>
		</div>
	</div>
</div>