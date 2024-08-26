<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SchoolCoursesStudentsLogs Model
 *
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\BelongsTo $Students
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\SchoolCoursesStudentsLog newEmptyEntity()
 * @method \App\Model\Entity\SchoolCoursesStudentsLog newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog get($primaryKey, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudentsLog[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SchoolCoursesStudentsLogsTable extends Table
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

        $this->setTable('school_courses_students_logs');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('SchoolCourses', [
            'foreignKey' => 'school_course_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Students', [
            'foreignKey' => 'student_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
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
            ->integer('school_course_id')
            ->notEmptyString('school_course_id');

        $validator
            ->integer('student_id')
            ->notEmptyString('student_id');

        $validator
            ->decimal('cost')
            ->allowEmptyString('cost');

        $validator
            ->scalar('status')
            ->maxLength('status', 15)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->integer('user_id')
            ->notEmptyString('user_id');

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
        $rules->add($rules->existsIn('school_course_id', 'SchoolCourses'), ['errorField' => 'school_course_id']);
        $rules->add($rules->existsIn('student_id', 'Students'), ['errorField' => 'student_id']);
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
