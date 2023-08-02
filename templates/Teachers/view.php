<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Teacher $teacher
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Teacher'), ['action' => 'edit', $teacher->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Teacher'), ['action' => 'delete', $teacher->id], ['confirm' => __('Are you sure you want to delete # {0}?', $teacher->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Teachers'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Teacher'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="teachers view content">
            <h3><?= h($teacher->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($teacher->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Last Name') ?></th>
                    <td><?= h($teacher->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Second Last Name') ?></th>
                    <td><?= h($teacher->second_last_name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Email') ?></th>
                    <td><?= h($teacher->email) ?></td>
                </tr>
                <tr>
                    <th><?= __('Celular') ?></th>
                    <td><?= h($teacher->celular) ?></td>
                </tr>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $teacher->has('user') ? $this->Html->link($teacher->user->name, ['controller' => 'Users', 'action' => 'view', $teacher->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($teacher->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Active') ?></th>
                    <td><?= $this->Number->format($teacher->active) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($teacher->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($teacher->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Subjects') ?></h4>
                <?php if (!empty($teacher->subjects)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Id Number') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Capacity') ?></th>
                            <th><?= __('Institute') ?></th>
                            <th><?= __('Sex') ?></th>
                            <th><?= __('Tipo Academia') ?></th>
                            <th><?= __('Criterio Academia') ?></th>
                            <th><?= __('Grade Level') ?></th>
                            <th><?= __('Anio Nacimiento Minimo') ?></th>
                            <th><?= __('Anio Nacimiento Maximo') ?></th>
                            <th><?= __('Grado Minimo') ?></th>
                            <th><?= __('Grado Maximo') ?></th>
                            <th><?= __('Costo Normal') ?></th>
                            <th><?= __('Costo Material') ?></th>
                            <th><?= __('Costo Cumbres') ?></th>
                            <th><?= __('Costo Segundo Semestre') ?></th>
                            <th><?= __('Costo Externos') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Active') ?></th>
                            <th><?= __('Seleccionado') ?></th>
                            <th><?= __('Pago Obligatorio') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($teacher->subjects as $subjects) : ?>
                        <tr>
                            <td><?= h($subjects->id) ?></td>
                            <td><?= h($subjects->id_number) ?></td>
                            <td><?= h($subjects->name) ?></td>
                            <td><?= h($subjects->capacity) ?></td>
                            <td><?= h($subjects->institute) ?></td>
                            <td><?= h($subjects->sex) ?></td>
                            <td><?= h($subjects->tipo_academia) ?></td>
                            <td><?= h($subjects->criterio_academia) ?></td>
                            <td><?= h($subjects->grade_level) ?></td>
                            <td><?= h($subjects->anio_nacimiento_minimo) ?></td>
                            <td><?= h($subjects->anio_nacimiento_maximo) ?></td>
                            <td><?= h($subjects->grado_minimo) ?></td>
                            <td><?= h($subjects->grado_maximo) ?></td>
                            <td><?= h($subjects->costo_normal) ?></td>
                            <td><?= h($subjects->costo_material) ?></td>
                            <td><?= h($subjects->costo_cumbres) ?></td>
                            <td><?= h($subjects->costo_segundo_semestre) ?></td>
                            <td><?= h($subjects->costo_externos) ?></td>
                            <td><?= h($subjects->description) ?></td>
                            <td><?= h($subjects->active) ?></td>
                            <td><?= h($subjects->seleccionado) ?></td>
                            <td><?= h($subjects->pago_obligatorio) ?></td>
                            <td><?= h($subjects->created) ?></td>
                            <td><?= h($subjects->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Subjects', 'action' => 'view', $subjects->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Subjects', 'action' => 'edit', $subjects->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Subjects', 'action' => 'delete', $subjects->id], ['confirm' => __('Are you sure you want to delete # {0}?', $subjects->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related School Courses') ?></h4>
                <?php if (!empty($teacher->school_courses)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Capacity') ?></th>
                            <th><?= __('Price') ?></th>
                            <th><?= __('Subjet Id') ?></th>
                            <th><?= __('Teacher Id') ?></th>
                            <th><?= __('Term Id') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($teacher->school_courses as $schoolCourses) : ?>
                        <tr>
                            <td><?= h($schoolCourses->id) ?></td>
                            <td><?= h($schoolCourses->name) ?></td>
                            <td><?= h($schoolCourses->capacity) ?></td>
                            <td><?= h($schoolCourses->price) ?></td>
                            <td><?= h($schoolCourses->subjet_id) ?></td>
                            <td><?= h($schoolCourses->teacher_id) ?></td>
                            <td><?= h($schoolCourses->term_id) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'SchoolCourses', 'action' => 'view', $schoolCourses->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'SchoolCourses', 'action' => 'edit', $schoolCourses->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'SchoolCourses', 'action' => 'delete', $schoolCourses->id], ['confirm' => __('Are you sure you want to delete # {0}?', $schoolCourses->id)]) ?>
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
