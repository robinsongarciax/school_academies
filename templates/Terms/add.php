<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Term $term
 * @var \Cake\Collection\CollectionInterface|string[] $institutes
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Terms'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="terms form content">
            <?= $this->Form->create($term) ?>
            <fieldset>
                <legend><?= __('Add Term') ?></legend>
                <?php
                    echo $this->Form->control('description');
                    echo $this->Form->control('start');
                    echo $this->Form->control('end');
                    echo $this->Form->control('institute_id', ['options' => $institutes]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
