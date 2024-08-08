<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SchoolCourse> $schoolCourses
 */
?>

<!-- Table for big screen devices -->
<div class="container-fluid">
    <div class="table-responsive d-none d-md-flex">
        <?php if (empty($schoolCourses) || $schoolCourses->count() == 0): ?>
            <p>No hay academias disponible para este alumno.</p>
        <?php else: ?>
            <table  class="table table-bordered" cellspacing="0" id="students" >
                <thead>
                    <tr>
                        <th><?= __('Academy') ?></th>
                        <th><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($schoolCourses as $schoolCourse): ?>
                            
                        <tr >
                            <td>
                                <?php $availability = $schoolCourse->capacity - $schoolCourse->occupancy; ?>
                                <?= "{$schoolCourse->name } ({$schoolCourse->tipo_academia})" ?><br/>
                                <?= $schoolCourse->teacher->name ?><br/>
                                Lugares disp. <?= $availability ?>
                            </td>
                            <td class="actions">
                                <?php
                                
                                // El modal y el mensaje debe cargarse dependiendo las opciones del curso
                                $url = null;
                                $data_target = '#noAvailabilityModal';
                                $message;
                                if ($availability == 0) {
                                    $message = __('El curso {0} no cuenta con lugares disponibles', $schoolCourse->name);
                                    echo $this->Form->button(__('Signup'), [
                                        'type' => 'button',
                                        'confirm' => $message,
                                        'title' => __('Confirm'),
                                        'class' => 'btn btn-primary btn-block h-100']);
                                } else {
                                    $url = [
                                        'action' => 'enrollment', 
                                        $schoolCourse->id,
                                        $student_id
                                    ];
                                    $message = __('Are you sure you want to register for {0}?', $schoolCourse->name);
                                    $data_target = '#confirmRegistrationModal';
                                    echo $this->Form->postLink(__('Signup'), $url, [
                                        'confirm' => $message,
                                        'title' => __('Confirm'),
                                        'class' => 'btn btn-primary btn-block h-100'
                                    ]);
                                }
                                        
                                
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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


<!-- School course complete modal -->
<div class="modal" id="noAvailabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= __('Confirm Registration') ?></h5>
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
