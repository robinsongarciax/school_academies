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
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $num_confirmed_courses ?>
                                <?= $this->Html->link(__('Ver cursos seleccionados'), [
                                    'action' => 'courseRegistration',
                                    1
                                ], [
                                    'class' => 'h6 text-primary'
                                ])?>
                            </div>
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
    <nav class="nav nav-borders">
        <?php
        $all = '';
        if ($enrolled) {
            $enrolled = 'active';
        } else {
            $all = 'active';
        }
        echo $this->Html->link(__('Ver todos los cursos'), [
            'action' => 'courseRegistration'
        ], [
            'class' => 'nav-link primary-button ' . $all
        ]);
        echo $this->Html->link(__('Ver cursos seleccionados'), [
            'action' => 'courseRegistration',
            1
        ], [
            'class' => 'nav-link primary-button ' . $enrolled
        ]);
        ?>
    </nav>
    <hr class="mt-0 mb-4">
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
                <table class="table table-bordered" width="100%" cellspacing="0" id="students">
                    <thead>
                        <tr>
                            <th><?= __('Academy') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schoolCourses as $schoolCourse): ?>

                            <?php if (/*$schoolCourse->visible
                                || */ array_key_exists($schoolCourse->id, $arr_coursesSignedup)):?>
                                <?php 

                                $availability = $schoolCourse->capacity - $schoolCourse->occupancy;
                                $class = '';
                                if (array_key_exists($schoolCourse->id, $arr_coursesSignedup)) {
                                    $course_signedup = $arr_coursesSignedup[$schoolCourse->id];
                                    if ($course_signedup['is_confirmed'] == 1) {
                                        $class = 'class="table-success"';
                                    }
                                }
                                if (empty($class)) {
                                    if ($availability == 0) {
                                        $class = 'class="table-danger"';
                                    } else if ($term->courses_allowed - $num_confirmed_courses == 0) {
                                        $class = 'class="table-secondary"';
                                    }
                                }
                                ?>
                                <?php
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
                                <tr>
                                    <td>
                                        <?= "{$schoolCourse->name } ({$schoolCourse->tipo_academia})" ?><br/>
                                        <?= $schoolCourse->teacher->name ?><br/>
                                        Lugares disp. <?= $schoolCourse->capacity - $schoolCourse->occupancy ?><br/>
                                        Horario: <b><?= $horario ?></b>
                                    </td>
                                    <td class="mobile-td-actions">
                                        <div class="mobile-actions">
                                            <?php
                                            if (array_key_exists($schoolCourse->id, $arr_coursesSignedup)) {
                                                $course_signedup = $arr_coursesSignedup[$schoolCourse->id];
                                                $school_courses_students_id = $course_signedup['school_courses_students_id'];
                                                if ($course_signedup['is_confirmed'] === 0) {
                                                    $this->Form->setTemplates([
                                                        'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                                    ]);
                                                    echo $this->Form->postLink(
                                                        __('Confirm'), [
                                                        'controller' => 'SchoolCoursesStudents',
                                                        'action' => 'confirm',
                                                        $school_courses_students_id],
                                                        [
                                                            'confirm' => __('Are you sure you want to register for {0}?',
                                                                $schoolCourse->name) . ' en el horario ' . $horario,
                                                            'title' => __('Confirm'),
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#confirmRegistrationModal'
                                                        ]);

                                                    echo $this->Form->postLink(__('Dropout'), [
                                                        'controller' => 'SchoolCoursesStudents',
                                                        'action' => 'delete',
                                                        $school_courses_students_id]);
                                                } else {
                                                    echo $this->Form->postLink(__('Imprimir<br>Constancia'), [
                                                        'controller' => 'SchoolCoursesStudents',
                                                        'action' => 'printForm', $school_courses_students_id],
                                                        ['class'=>'btn btn-primary btn-block h-100',
                                                            'escape' => false
                                                        ]);
                                                }
                                                
                                            } else {
                                                // El modal y el mensaje debe cargarse dependiendo las opciones del curso
                                                $url = null;
                                                $data_target = '#noAvailabilityModal';
                                                $message;
                                                if ($availability == 0) {
                                                    $message = __('El curso {0} no cuenta con lugares disponibles', $schoolCourse->name);
                                                } else if ($term->courses_allowed - $num_confirmed_courses == 0) {
                                                    $message = "Ha seleccionado el número máximo de cursos permitidos por alumno y no puede seleccionar mas.
                                                        Si desea agregar una academía adicional puede enviar un correo a:
                                                        kmurillo@cumbresmerida.com ó
                                                        tjgonzalez@cumbresmerida.com";
                                                } else {
                                                    $url = [
                                                        'action' => 'signup', 
                                                        $schoolCourse->id
                                                    ];
                                                    $message = __('Are you sure you want to register for {0}?', $schoolCourse->name . ' de ' . $horario);
                                                    $data_target = '#confirmRegistrationModal';
                                                }
                                                $this->Form->setTemplates([
                                                    'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                                ]);
                                                echo $this->Form->postLink(__('Signup'), $url, [
                                                    'confirm' => $message,
                                                    'title' => __('Confirm'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => $data_target,
                                                    'class' => 'btn btn-primary btn-block h-100'
                                                ]);
                                                    
                                            }
                                            ?>
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
                <table  class="table table-bordered" width="100%" cellspacing="0" id="students">
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
                            <?php if (/*$schoolCourse->visible
                                ||*/  array_key_exists($schoolCourse->id, $arr_coursesSignedup)):?>
                                
                                <?php 

                                $availability = $schoolCourse->capacity - $schoolCourse->occupancy;
                                $class = '';
                                if (array_key_exists($schoolCourse->id, $arr_coursesSignedup)) {
                                    $course_signedup = $arr_coursesSignedup[$schoolCourse->id];
                                    if ($course_signedup['is_confirmed'] == 1) {
                                        $class = 'class="table-success"';
                                    }
                                }
                                if (empty($class)) {
                                    if ($availability == 0) {
                                        $class = 'class="table-danger"';
                                    } else if ($term->courses_allowed - $num_confirmed_courses == 0) {
                                        $class = 'class="table-secondary"';
                                    }
                                }
                                ?>
                            <tr <?= $class ?>>
                                <td><?= h($schoolCourse->name) ?></td>
                                <td><?= $schoolCourse->has('teacher') ? $schoolCourse->teacher->name : '' ?></td>
                                <td><?= $schoolCourse->tipo_academia ?></td>
                                <td><?= $availability ?></td>
                                <?php
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
                                            $monday .= $time_schedule;
                                            $horario[] = 'Lun';
                                            break;
                                        case 2:
                                            $tuesday .= $time_schedule;
                                            $horario[] = 'Mar';
                                            break;
                                        case 3:
                                            $wednesday .= $time_schedule;
                                            $horario[] = 'Miérc';
                                            break;
                                        case 4:
                                            $thursday .= $time_schedule;
                                            $horario[] = 'Juev';
                                            break;
                                        case 5:
                                            $friday.= $time_schedule;
                                            $horario[] = 'Vier';
                                            break;
                                    }
                                }
                                $horario = implode(', ', $horario);
                                $horario .= ' de ' .$time_schedule;
                                ?>

                                <td><?= $monday ?></td>
                                <td><?= $tuesday ?></td>
                                <td><?= $wednesday ?></td>
                                <td><?= $thursday ?></td>
                                <td><?= $friday ?></td>
                                <td class="actions">
                                    <?php
                                    if (array_key_exists($schoolCourse->id, $arr_coursesSignedup)) {
                                        $course_signedup = $arr_coursesSignedup[$schoolCourse->id];
                                        $school_courses_students_id = $course_signedup['school_courses_students_id'];
                                        if ($course_signedup['is_confirmed'] === 0) {
                                        	$this->Form->setTemplates([
                                        		'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                        	]);
                                            echo $this->Form->postLink(
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
                                                ]);

                                            echo $this->Form->postLink(__('Dropout'), [
                                                'controller' => 'SchoolCoursesStudents',
                                                'action' => 'delete',
                                                $school_courses_students_id]);
                                        } else {
                                            echo $this->Form->postLink(__('Imprimir<br>Constancia'), [
                                                'controller' => 'SchoolCoursesStudents',
                                                'action' => 'printForm', $school_courses_students_id],
                                                ['class'=>'btn btn-primary btn-block h-100',
                                                    'escape' => false
                                                ]);
                                        }
                                        
                                    } else {
                                        // El modal y el mensaje debe cargarse dependiendo las opciones del curso
                                        $url = null;
                                        $data_target = '#noAvailabilityModal';
                                        $message;
                                        if ($availability == 0) {
                                            $message = __('El curso {0} no cuenta con lugares disponibles', $schoolCourse->name);
                                        } else if ($term->courses_allowed - $num_confirmed_courses == 0) {
                                            $message = "Ha seleccionado el número máximo de cursos permitidos por alumno y no puede seleccionar mas.
                                                Si desea agregar una academía adicional puede enviar un correo a:
                                                kmurillo@cumbresmerida.com ó
                                                tjgonzalez@cumbresmerida.com";
                                        } else {
                                            $url = [
                                                'action' => 'signup', 
                                                $schoolCourse->id
                                            ];
                                            $message = __('Are you sure you want to register for {0}?', $schoolCourse->name . ' de ' . $horario);
                                            $data_target = '#confirmRegistrationModal';
                                        }
                                        $this->Form->setTemplates([
                                            'confirmJs' => 'addToModal("{{formName}}"); return false;'
                                        ]);
                                        echo $this->Form->postLink(__('Signup'), $url, [
                                            'confirm' => $message,
                                            'title' => __('Confirm'),
                                            'data-toggle' => 'modal',
                                            'data-target' => $data_target,
                                            'class' => 'btn btn-primary btn-block h-100'
                                        ]);
                                            
                                    }
                                    ?>
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
				<h5 class="modal-title"><?= __('Confirmación de inscripción') ?></h5>
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


<!-- School course complete modal -->
<div class="modal" id="noAvailabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Información del sistema') ?></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage2"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?= __('Accept') ?></button>
            </div>
        </div>
    </div>
</div>
