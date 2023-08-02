<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Subject $subject
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Subject'), ['action' => 'edit', $subject->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Subject'), ['action' => 'delete', $subject->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subject->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Subjects'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Subject'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="subjects view content">
            <h3><?= h($subject->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Id Number') ?></th>
                    <td><?= h($subject->id_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($subject->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Institute') ?></th>
                    <td><?= h($subject->institute) ?></td>
                </tr>
                <tr>
                    <th><?= __('Sex') ?></th>
                    <td><?= h($subject->sex) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tipo Academia') ?></th>
                    <td><?= h($subject->tipo_academia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Criterio Academia') ?></th>
                    <td><?= h($subject->criterio_academia) ?></td>
                </tr>
                <tr>
                    <th><?= __('Grade Level') ?></th>
                    <td><?= h($subject->grade_level) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($subject->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Capacity') ?></th>
                    <td><?= $subject->capacity === null ? '' : $this->Number->format($subject->capacity) ?></td>
                </tr>
                <tr>
                    <th><?= __('Anio Nacimiento Minimo') ?></th>
                    <td><?= $this->Number->format($subject->anio_nacimiento_minimo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Anio Nacimiento Maximo') ?></th>
                    <td><?= $this->Number->format($subject->anio_nacimiento_maximo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Grado Minimo') ?></th>
                    <td><?= $this->Number->format($subject->grado_minimo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Grado Maximo') ?></th>
                    <td><?= $this->Number->format($subject->grado_maximo) ?></td>
                </tr>
                <tr>
                    <th><?= __('Costo Normal') ?></th>
                    <td><?= $subject->costo_normal === null ? '' : $this->Number->format($subject->costo_normal) ?></td>
                </tr>
                <tr>
                    <th><?= __('Costo Material') ?></th>
                    <td><?= $subject->costo_material === null ? '' : $this->Number->format($subject->costo_material) ?></td>
                </tr>
                <tr>
                    <th><?= __('Costo Cumbres') ?></th>
                    <td><?= $subject->costo_cumbres === null ? '' : $this->Number->format($subject->costo_cumbres) ?></td>
                </tr>
                <tr>
                    <th><?= __('Costo Segundo Semestre') ?></th>
                    <td><?= $subject->costo_segundo_semestre === null ? '' : $this->Number->format($subject->costo_segundo_semestre) ?></td>
                </tr>
                <tr>
                    <th><?= __('Costo Externos') ?></th>
                    <td><?= $subject->costo_externos === null ? '' : $this->Number->format($subject->costo_externos) ?></td>
                </tr>
                <tr>
                    <th><?= __('Active') ?></th>
                    <td><?= $subject->active === null ? '' : $this->Number->format($subject->active) ?></td>
                </tr>
                <tr>
                    <th><?= __('Seleccionado') ?></th>
                    <td><?= $subject->seleccionado === null ? '' : $this->Number->format($subject->seleccionado) ?></td>
                </tr>
                <tr>
                    <th><?= __('Pago Obligatorio') ?></th>
                    <td><?= $subject->pago_obligatorio === null ? '' : $this->Number->format($subject->pago_obligatorio) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($subject->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($subject->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Description') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($subject->description)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Teachers') ?></h4>
                <?php if (!empty($subject->teachers)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Last Name') ?></th>
                            <th><?= __('Second Last Name') ?></th>
                            <th><?= __('Email') ?></th>
                            <th><?= __('Celular') ?></th>
                            <th><?= __('Active') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('Modules Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($subject->teachers as $teachers) : ?>
                        <tr>
                            <td><?= h($teachers->id) ?></td>
                            <td><?= h($teachers->name) ?></td>
                            <td><?= h($teachers->last_name) ?></td>
                            <td><?= h($teachers->second_last_name) ?></td>
                            <td><?= h($teachers->email) ?></td>
                            <td><?= h($teachers->celular) ?></td>
                            <td><?= h($teachers->active) ?></td>
                            <td><?= h($teachers->created) ?></td>
                            <td><?= h($teachers->modified) ?></td>
                            <td><?= h($teachers->modules_id) ?></td>
                            <td><?= h($teachers->user_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Teachers', 'action' => 'view', $teachers->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Teachers', 'action' => 'edit', $teachers->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Teachers', 'action' => 'delete', $teachers->id], ['confirm' => __('Are you sure you want to delete # {0}?', $teachers->id)]) ?>
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
