<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Institute $institute
 * @var \Cake\Collection\CollectionInterface|string[] $schoolLevels
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Institutes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="institutes form content">
            <?= $this->Form->create($institute) ?>
            <fieldset>
                <legend><?= __('Add Institute') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('school_levels._ids', ['options' => $schoolLevels]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
