<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SchoolLevel $schoolLevel
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $schoolLevel->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $schoolLevel->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List School Levels'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="schoolLevels form content">
            <?= $this->Form->create($schoolLevel) ?>
            <fieldset>
                <legend><?= __('Edit School Level') ?></legend>
                <?php
                    echo $this->Form->control('name');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
