<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SchoolCourse> $schoolCourses
 */

$arr_coursesSignedup = [];
// pr($studentCourses);
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
                                        <?= $this->Form->postLink(__('Confirm'), [
                                            'controller' => 'SchoolCoursesStudents',
                                            'action' => 'confirm', $school_courses_students_id]) ?>

                                        <?= $this->Form->postLink(__('Dropout'), [
                                            'controller' => 'SchoolCoursesStudents',
                                            'action' => 'delete', $school_courses_students_id]) ?>
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
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
