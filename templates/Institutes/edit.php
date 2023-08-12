<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Institute $institute
 * @var string[]|\Cake\Collection\CollectionInterface $schoolLevels
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $institute->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $institute->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Institutes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="institutes form content">
            <?= $this->Form->create($institute) ?>
            <fieldset>
                <legend><?= __('Edit Institute') ?></legend>
                <?php
                    echo $this->Form->control('name');
                    echo $this->Form->control('principal');
                    echo $this->Form->control('school_levels._ids', ['options' => $schoolLevels]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
