<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Student $student
 * @var string[]|\Cake\Collection\CollectionInterface $terms
 * @var string[]|\Cake\Collection\CollectionInterface $users
 * @var string[]|\Cake\Collection\CollectionInterface $schoolCourses
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $student->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $student->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Students'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="students form content">
            <?= $this->Form->create($student) ?>
            <fieldset>
                <legend><?= __('Edit Student') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('last_name');
                    echo $this->Form->control('second_last_name');
                    echo $this->Form->control('curp');
                    echo $this->Form->control('email');
                    echo $this->Form->control('level');
                    echo $this->Form->control('institute');
                    echo $this->Form->control('group');
                    echo $this->Form->control('id_number');
                    echo $this->Form->control('birth_date');
                    echo $this->Form->control('term_id', ['options' => $terms]);
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('school_courses._ids', ['options' => $schoolCourses]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
