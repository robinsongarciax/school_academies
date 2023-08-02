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
            <?= $this->Html->link(__('Edit School Level'), ['action' => 'edit', $schoolLevel->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete School Level'), ['action' => 'delete', $schoolLevel->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolLevel->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List School Levels'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New School Level'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="schoolLevels view content">
            <h3><?= h($schoolLevel->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($schoolLevel->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($schoolLevel->id) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
