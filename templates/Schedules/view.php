<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Schedule $schedule
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Schedule'), ['action' => 'edit', $schedule->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Schedule'), ['action' => 'delete', $schedule->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schedule->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Schedules'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Schedule'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="schedules view content">
            <h3><?= h($schedule->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Day') ?></th>
                    <td><?= $schedule->has('day') ? $this->Html->link($schedule->day->name, ['controller' => 'Days', 'action' => 'view', $schedule->day->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($schedule->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Start') ?></th>
                    <td><?= h($schedule->start) ?></td>
                </tr>
                <tr>
                    <th><?= __('End') ?></th>
                    <td><?= h($schedule->end) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related School Courses') ?></h4>
                <?php if (!empty($schedule->school_courses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Capacity') ?></th>
                            <th><?= __('Price') ?></th>
                            <th><?= __('Subjet Id') ?></th>
                            <th><?= __('Teacher Id') ?></th>
                            <th><?= __('Term Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($schedule->school_courses as $schoolCourses) : ?>
                        <tr>
                            <td><?= h($schoolCourses->id) ?></td>
                            <td><?= h($schoolCourses->name) ?></td>
                            <td><?= h($schoolCourses->capacity) ?></td>
                            <td><?= h($schoolCourses->price) ?></td>
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
        </div>
    </div>
</div>
