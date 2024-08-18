<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Student> $students
 */
?>
<header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
    <div class="container-fluid px-4">
        <div class="page-header-content">
            <div class="row align-items-center justify-content-between pt-3">
                <div class="col-auto mb-3">
                    <h3 class="page-header-title"><?= __('Dashboard') ?></h3>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary-cm"><?= __('Students List')?></h6>
        </div>
        <div class="card-body">
            <?= $this->Flash->render() ?>
            <div class="table-responsive">
                
                <?php
                // custom form fields
                $this->Form->setTemplates([
                    'label' => '<label{{attrs}}>{{text}}</label>',
                    // Select element,
                    'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
                ]);

                $limitControl = $this->Paginator->limitControl([10 => 10, 25 => 25, 50 => 50, 100 => 100], null, [
                    'label' => __('Show') . ' ']);
                echo str_replace('</div></form>', ' '. $this->Form->label(' ' . __('records')) . '</div></form>', $limitControl);
                ?>

                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?= $this->Paginator->sort('name') ?></th>
                            <th><?= $this->Paginator->sort('curp') ?></th>
                            <th><?= $this->Paginator->sort('school_level') ?></th>
                            <th><?= $this->Paginator->sort('school_group') ?></th>
                            <th><?= $this->Paginator->sort('SchoolCourses.name', __('Academy')) ?></th>
                            <th><?= $this->Paginator->sort('SchoolCoursesStudents.cost', __('Cost')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <?php //pr($student);die();?>
                            <td><?= h($student->name) ?></td>
                            <td><?= h($student->curp) ?></td>
                            <td><?= h($student->school_level) ?></td>
                            <td><?= h($student->school_group) ?></td>
                            <td><?= h($student->_matchingData['SchoolCourses']->name) ?></td>
                            <td><?= h($student->_matchingData['SchoolCoursesStudents']->cost) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p><?= $this->Paginator->counter(__('Showing {{start}} of {{end}} out of {{count}} record(s)')) ?></p>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    <?= $this->Paginator->first('<< ' . __('first')) ?>
                    <?= $this->Paginator->prev('< ' . __('previous')) ?>
                    <?= $this->Paginator->numbers(['modulus' => 4]) ?>
                    <?= $this->Paginator->next(__('next') . ' >') ?>
                    <?= $this->Paginator->last(__('last') . ' >>') ?>
                </ul>
            </nav>
        </div>
    </div>
</div>