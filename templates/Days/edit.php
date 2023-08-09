<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Day $day
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $day->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $day->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Days'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="days form content">
            <?= $this->Form->create($day) ?>
            <fieldset>
                <legend><?= __('Edit Day') ?></legend>
                <?php
                    echo $this->Form->control('name');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
