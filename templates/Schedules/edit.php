<?= $this->Form->create($schedule) ?>
<fieldset>
    <?php
        echo $this->Form->control('day_id', [
            'templates' => 'multichecked_form',
            'options' => $days,
            'label' => ['text' => 'Días de la semana']
        ]);
        echo $this->Form->control('start', [
            'step' => '1800'
        ]);
        echo $this->Form->control('end', [
            'step' => '1800'
        ]);
    ?>
</fieldset>
<?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
