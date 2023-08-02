<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Term> $terms
 */
?>
<div class="terms index content">
    <?= $this->Html->link(__('New Term'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Terms') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('description') ?></th>
                    <th><?= $this->Paginator->sort('start') ?></th>
                    <th><?= $this->Paginator->sort('end') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th><?= $this->Paginator->sort('institute_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($terms as $term): ?>
                <tr>
                    <td><?= $this->Number->format($term->id) ?></td>
                    <td><?= h($term->description) ?></td>
                    <td><?= h($term->start) ?></td>
                    <td><?= h($term->end) ?></td>
                    <td><?= h($term->created) ?></td>
                    <td><?= h($term->modified) ?></td>
                    <td><?= $term->has('institute') ? $this->Html->link($term->institute->name, ['controller' => 'Institutes', 'action' => 'view', $term->institute->id]) : '' ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $term->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $term->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $term->id], ['confirm' => __('Are you sure you want to delete # {0}?', $term->id)]) ?>
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
