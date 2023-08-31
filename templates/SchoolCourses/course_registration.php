<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SchoolCourse> $schoolCourses
 */

$this->Html->script('confirm-bootstrap-modal', ['block' => true]);
$arr_coursesSignedup = [];
$num_confirmed_courses = 0;
$total_a_pagar = 0;
foreach ($studentCourses as $studentCourse) {
    $school_course = $studentCourse->SchoolCoursesStudents;
    $arr_coursesSignedup[$school_course['school_course_id']] = [
        'school_courses_students_id' => $school_course['id'],
        'is_confirmed' => $school_course['is_confirmed'],
    ];
    if ($school_course['is_confirmed'] == 1) {
        $num_confirmed_courses++;
        $total_a_pagar += $school_course['cost'];
    }
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
    <!-- Content Row -->
    <!-- Info for mobile device -->
    <div class="row d-flex d-lg-none">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Límite de cursos por estudiante:
                            <span class="h5 mb-0 font-weight-bold text-gray-800"><?= $term->courses_allowed ?></span>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Núm. Cursos seleccionados:
                            <span class="h5 mb-0 font-weight-bold text-gray-800"><?= $num_confirmed_courses ?></span>
                        </div>
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total a pagar:
                            <span class="h5 mb-0 font-weight-bold text-gray-800"><?= $this->Number->currency($total_a_pagar) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Info for big screen device -->
    <div class="row d-none d-md-flex">
        <!-- Maximo Num Cursos Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Límite de cursos por estudiante
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $term->courses_allowed ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Num Cursos Seleccionados Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Núm. Cursos seleccionados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $num_confirmed_courses ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total a pagar por Cursos Seleccionados Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total a pagar
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $this->Number->currency($total_a_pagar) ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('School Course List')?></h6>
        </div>
        <div class="card-body">
            <hr class="mt-0 mb-4">
            <!-- Mensajes flash -->
            <?= $this->Flash->render() ?>
            <!-- Table for mobile devices -->
            <div class="table-responsive d-flex d-lg-none">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= __('Academy') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>

                            <?php if ($schoolCourse->visible
                                ||  array_key_exists($schoolCourse->id, $arr_coursesSignedup)):?>
                            <tr>
                                <td>
                                    <?= "{$schoolCourse->name } ({$schoolCourse->tipo_academia})" ?><br/>
                                    <?= $schoolCourse->teacher->name ?>
                                </td>
                                <td class="mobile-td-actions">
                                    <div class="mobile-actions">
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
                                                <?= $this->Form->postLink("", [
                                                    'controller' => 'SchoolCoursesStudents',
                                                    'action' => 'printForm', $school_courses_students_id],
                                                    ['class'=>'fas fa-print']) ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php $this->Form->setTemplates([
                                                'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                            ]); ?>
                                            <?= $this->Form->postLink(__('Signup'), [
                                                'action' => 'signup', 
                                                $schoolCourse->id], [
                                                    'confirm' => __('Are you sure you want to register for {0}?', $schoolCourse->name),
                                                    'title' => __('Confirm'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#confirmRegistrationModal',
                                                    'class' => 'btn btn-primary btn-block h-100'
                                                ]) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Table for big screen devices -->
            <div class="table-responsive d-none d-md-flex">
                <table  class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= __('Academy') ?></th>
                            <th><?= __('Teacher') ?></th>
                            <th>Tipo<br>Academia</th>
                            <th>Lugares<br>Disp.</th>
                            <th><?= __('Mon.') ?></th>
                            <th><?= __('Tues.') ?></th>
                            <th><?= __('Wed.') ?></th>
                            <th><?= __('Thurs.') ?></th>
                            <th><?= __('Fri.') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>

                            <?php if ($schoolCourse->visible
                                ||  array_key_exists($schoolCourse->id, $arr_coursesSignedup)):?>
                            <tr>
                                <td><?= h($schoolCourse->name) ?></td>
                                <td><?= $schoolCourse->has('teacher') ? $schoolCourse->teacher->name : '' ?></td>
                                <td><?= $schoolCourse->tipo_academia ?></td>
                                <td><?= $schoolCourse->capacity - $schoolCourse->occupancy ?></td>
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
                                    }
                                }
                                ?>

                                <td><?= $monday ?></td>
                                <td><?= $tuesday ?></td>
                                <td><?= $wednesday ?></td>
                                <td><?= $thursday ?></td>
                                <td><?= $friday ?></td>
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
                                            <?= $this->Form->postLink("", [
                                                'controller' => 'SchoolCoursesStudents',
                                                'action' => 'printForm', $school_courses_students_id],
                                                ['class'=>'fas fa-print']) ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php $this->Form->setTemplates([
                                            'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                        ]); ?>
                                        <?= $this->Form->postLink(__('Signup'), [
                                            'action' => 'signup', 
                                            $schoolCourse->id], [
                                                'confirm' => __('Are you sure you want to register for {0}?', $schoolCourse->name),
                                                'title' => __('Confirm'),
                                                'data-toggle' => 'modal',
                                                'data-target' => '#confirmRegistrationModal'
                                            ]) ?>
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
