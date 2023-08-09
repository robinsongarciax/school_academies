<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Schedule $schedule
 * @var \Cake\Collection\CollectionInterface|string[] $days
 * @var \Cake\Collection\CollectionInterface|string[] $schoolCourses
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Schedules'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="schedules form content">
            <?= $this->Form->create($schedule) ?>
            <fieldset>
                <legend><?= __('Add Schedule') ?></legend>
                <?php
                    echo $this->Form->control('start');
                    echo $this->Form->control('end');
                    echo $this->Form->control('day_id', ['options' => $days]);
                    echo $this->Form->control('school_courses._ids', ['options' => $schoolCourses]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
