<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Term $term
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Term'), ['action' => 'edit', $term->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Term'), ['action' => 'delete', $term->id], ['confirm' => __('Are you sure you want to delete # {0}?', $term->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Terms'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Term'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="terms view content">
            <h3><?= h($term->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Description') ?></th>
                    <td><?= h($term->description) ?></td>
                </tr>
                <tr>
                    <th><?= __('Institute') ?></th>
                    <td><?= $term->has('institute') ? $this->Html->link($term->institute->name, ['controller' => 'Institutes', 'action' => 'view', $term->institute->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($term->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Start') ?></th>
                    <td><?= h($term->start) ?></td>
                </tr>
                <tr>
                    <th><?= __('End') ?></th>
                    <td><?= h($term->end) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($term->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($term->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Students') ?></h4>
                <?php if (!empty($term->students)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Last Name') ?></th>
                            <th><?= __('Second Last Name') ?></th>
                            <th><?= __('Curp') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Level') ?></th>
                            <th><?= __('Institute') ?></th>
                            <th><?= __('Group') ?></th>
                            <th><?= __('Id Number') ?></th>
                            <th><?= __('Birth Date') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Term Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($term->students as $students) : ?>
                        <tr>
                            <td><?= h($students->id) ?></td>
                            <td><?= h($students->name) ?></td>
                            <td><?= h($students->last_name) ?></td>
                            <td><?= h($students->second_last_name) ?></td>
                            <td><?= h($students->curp) ?></td>
                            <td><?= h($students->email) ?></td>
                            <td><?= h($students->level) ?></td>
                            <td><?= h($students->institute) ?></td>
                            <td><?= h($students->group) ?></td>
                            <td><?= h($students->id_number) ?></td>
                            <td><?= h($students->birth_date) ?></td>
                            <td><?= h($students->created) ?></td>
                            <td><?= h($students->modified) ?></td>
                            <td><?= h($students->term_id) ?></td>
                            <td><?= h($students->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Students', 'action' => 'view', $students->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Students', 'action' => 'edit', $students->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Students', 'action' => 'delete', $students->id], ['confirm' => __('Are you sure you want to delete # {0}?', $students->id)]) ?>
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
