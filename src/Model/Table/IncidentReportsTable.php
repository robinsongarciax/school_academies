<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * IncidentReports Model
 *
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\TeachersTable&\Cake\ORM\Association\BelongsTo $Teachers
 * @property \App\Model\Table\SchoolCoursesTable&\Cake\ORM\Association\BelongsTo $SchoolCourses
 *
 * @method \App\Model\Entity\IncidentReport newEmptyEntity()
 * @method \App\Model\Entity\IncidentReport newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\IncidentReport[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IncidentReport get($primaryKey, $options = [])
 * @method \App\Model\Entity\IncidentReport findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\IncidentReport patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IncidentReport[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\IncidentReport|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IncidentReport saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IncidentReport[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\IncidentReport[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\IncidentReport[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\IncidentReport[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class IncidentReportsTable extends Table
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

        $this->setTable('incident_reports');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Students', [
            'foreignKey' => 'students_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'users_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Teachers', [
            'foreignKey' => 'teachers_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('SchoolCourses', [
            'foreignKey' => 'school_courses_id',
            'joinType' => 'INNER',
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
            ->scalar('subject')
            ->maxLength('subject', 150)
            ->requirePresence('subject', 'create')
            ->notEmptyString('subject');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->integer('students_id')
            ->notEmptyString('students_id');

        $validator
            ->integer('users_id')
            ->notEmptyString('users_id');

        $validator
            ->integer('teachers_id')
            ->notEmptyString('teachers_id');

        $validator
            ->integer('school_courses_id')
            ->notEmptyString('school_courses_id');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('students_id', 'Students'), ['errorField' => 'students_id']);
        $rules->add($rules->existsIn('users_id', 'Users'), ['errorField' => 'users_id']);
        $rules->add($rules->existsIn('teachers_id', 'Teachers'), ['errorField' => 'teachers_id']);
        $rules->add($rules->existsIn('school_courses_id', 'SchoolCourses'), ['errorField' => 'school_courses_id']);

        return $rules;
    }
}
