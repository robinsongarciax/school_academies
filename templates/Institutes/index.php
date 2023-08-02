<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Institute> $institutes
 */
?>
<div class="institutes index content">
    <?= $this->Html->link(__('New Institute'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Institutes') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('name') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($institutes as $institute): ?>
                <tr>
                    <td><?= $this->Number->format($institute->id) ?></td>
                    <td><?= h($institute->name) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $institute->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $institute->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $institute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $institute->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
