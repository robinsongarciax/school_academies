<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Institute $institute
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Institute'), ['action' => 'edit', $institute->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Institute'), ['action' => 'delete', $institute->id], ['confirm' => __('Are you sure you want to delete # {0}?', $institute->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Institutes'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Institute'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="institutes view content">
            <h3><?= h($institute->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($institute->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($institute->id) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related School Levels') ?></h4>
                <?php if (!empty($institute->school_levels)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($institute->school_levels as $schoolLevels) : ?>
                        <tr>
                            <td><?= h($schoolLevels->id) ?></td>
                            <td><?= h($schoolLevels->name) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'SchoolLevels', 'action' => 'view', $schoolLevels->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'SchoolLevels', 'action' => 'edit', $schoolLevels->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolLevels', 'action' => 'delete', $schoolLevels->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolLevels->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Terms') ?></h4>
                <?php if (!empty($institute->terms)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Start') ?></th>
                            <th><?= __('End') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Institute Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($institute->terms as $terms) : ?>
                        <tr>
                            <td><?= h($terms->id) ?></td>
                            <td><?= h($terms->description) ?></td>
                            <td><?= h($terms->start) ?></td>
                            <td><?= h($terms->end) ?></td>
                            <td><?= h($terms->created) ?></td>
                            <td><?= h($terms->modified) ?></td>
                            <td><?= h($terms->institute_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Terms', 'action' => 'view', $terms->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Terms', 'action' => 'edit', $terms->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Terms', 'action' => 'delete', $terms->id], ['confirm' => __('Are you sure you want to delete # {0}?', $terms->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
