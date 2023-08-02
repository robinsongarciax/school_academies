<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Student'), ['action' => 'edit', $student->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Student'), ['action' => 'delete', $student->id], ['confirm' => __('Are you sure you want to delete # {0}?', $student->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Students'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Student'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="students view content">
            <h3><?= h($student->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($student->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($student->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Second Last Name') ?></th>
                    <td><?= h($student->second_last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Curp') ?></th>
                    <td><?= h($student->curp) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($student->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Institute') ?></th>
                    <td><?= h($student->institute) ?></td>
                </tr>
                <tr>
                    <th><?= __('Group') ?></th>
                    <td><?= h($student->group) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id Number') ?></th>
                    <td><?= h($student->id_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Term') ?></th>
                    <td><?= $student->has('term') ? $this->Html->link($student->term->id, ['controller' => 'Terms', 'action' => 'view', $student->term->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $student->has('user') ? $this->Html->link($student->user->name, ['controller' => 'Users', 'action' => 'view', $student->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($student->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Level') ?></th>
                    <td><?= $this->Number->format($student->level) ?></td>
                </tr>
                <tr>
                    <th><?= __('Birth Date') ?></th>
                    <td><?= h($student->birth_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($student->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($student->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related School Courses') ?></h4>
                <?php if (!empty($student->school_courses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Capacity') ?></th>
                            <th><?= __('Subjet Id') ?></th>
                            <th><?= __('Teacher Id') ?></th>
                            <th><?= __('Term Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($student->school_courses as $schoolCourses) : ?>
                        <tr>
                            <td><?= h($schoolCourses->id) ?></td>
                            <td><?= h($schoolCourses->name) ?></td>
                            <td><?= h($schoolCourses->capacity) ?></td>
                            <td><?= h($schoolCourses->subjet_id) ?></td>
                            <td><?= h($schoolCourses->teacher_id) ?></td>
                            <td><?= h($schoolCourses->term_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'SchoolCourses', 'action' => 'view', $schoolCourses->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'SchoolCourses', 'action' => 'edit', $schoolCourses->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolCourses', 'action' => 'delete', $schoolCourses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolCourses->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Courses') ?></h4>
                <?php if (!empty($student->courses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Cost') ?></th>
                            <th><?= __('Student Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($student->courses as $courses) : ?>
                        <tr>
                            <td><?= h($courses->id) ?></td>
                            <td><?= h($courses->name) ?></td>
                            <td><?= h($courses->cost) ?></td>
                            <td><?= h($courses->student_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Courses', 'action' => 'view', $courses->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Courses', 'action' => 'edit', $courses->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Courses', 'action' => 'delete', $courses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $courses->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
