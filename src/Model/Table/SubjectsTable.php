<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Subjects Model
 *
 * @property \App\Model\Table\TeachersTable&\Cake\ORM\Association\BelongsToMany $Teachers
 *
 * @method \App\Model\Entity\Subject newEmptyEntity()
 * @method \App\Model\Entity\Subject newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Subject[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Subject get($primaryKey, $options = [])
 * @method \App\Model\Entity\Subject findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Subject patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Subject[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Subject|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subject saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Subject[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Subject[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Subject[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Subject[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SubjectsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('subjects');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Teachers', [
            'foreignKey' => 'subject_id',
            'targetForeignKey' => 'teacher_id',
            'joinTable' => 'subjects_teachers',
        ]);

        $this->belongsToMany('SchoolLevels', [
            'joinTable' => 'subjects_school_levels',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('id_number')
            ->maxLength('id_number', 20)
            ->allowEmptyString('id_number');

        $validator
            ->scalar('name')
            ->maxLength('name', 45)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('capacity')
            ->allowEmptyString('capacity');

        $validator
            ->scalar('institute')
            ->notEmptyString('institute');

        $validator
            ->scalar('sex')
            ->requirePresence('sex', 'create')
            ->notEmptyString('sex');

        $validator
            ->scalar('tipo_academia')
            ->requirePresence('tipo_academia', 'create')
            ->notEmptyString('tipo_academia');

        $validator
            ->scalar('grade_level')
            ->allowEmptyString('grade_level');

        $validator
            ->numeric('costo_normal')
            ->allowEmptyString('costo_normal');

        $validator
            ->numeric('costo_material')
            ->allowEmptyString('costo_material');

        $validator
            ->numeric('costo_cumbres')
            ->allowEmptyString('costo_cumbres');

        $validator
            ->numeric('costo_segundo_semestre')
            ->allowEmptyString('costo_segundo_semestre');

        $validator
            ->numeric('costo_externos')
            ->allowEmptyString('costo_externos');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->allowEmptyString('active');

        $validator
            ->allowEmptyString('seleccionado');

        $validator
            ->notEmptyString('pago_obligatorio');

        return $validator;
    }
}
