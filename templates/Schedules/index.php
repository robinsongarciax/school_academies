<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Schedule> $schedules
 */
?>
<div class="schedules index content">
    <?= $this->Html->link(__('New Schedule'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Schedules') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('start') ?></th>
                    <th><?= $this->Paginator->sort('end') ?></th>
                    <th><?= $this->Paginator->sort('day_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr>
                    <td><?= $this->Number->format($schedule->id) ?></td>
                    <td><?= h($schedule->start) ?></td>
                    <td><?= h($schedule->end) ?></td>
                    <td><?= $schedule->has('day') ? $this->Html->link($schedule->day->name, ['controller' => 'Days', 'action' => 'view', $schedule->day->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $schedule->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $schedule->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $schedule->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schedule->id)]) ?>
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
