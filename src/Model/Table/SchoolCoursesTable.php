<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;

/**
 * SchoolCourses Model
 *
 * @property \App\Model\Table\SubjectsTable&\Cake\ORM\Association\BelongsTo $Subjects
 * @property \App\Model\Table\TeachersTable&\Cake\ORM\Association\BelongsTo $Teachers
 * @property \App\Model\Table\TermsTable&\Cake\ORM\Association\BelongsTo $Terms
 * @property \App\Model\Table\SchedulesTable&\Cake\ORM\Association\BelongsToMany $Schedules
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\BelongsToMany $Students
 *
 * @method \App\Model\Entity\SchoolCourse newEmptyEntity()
 * @method \App\Model\Entity\SchoolCourse newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse get($primaryKey, $options = [])
 * @method \App\Model\Entity\SchoolCourse findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SchoolCourse patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCourse|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCourse saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCourse[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SchoolCoursesTable extends Table
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

        $this->setTable('school_courses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Teachers', [
            'foreignKey' => 'teacher_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsTo('TeachingAssistants', [
            'className' => 'Teachers', // Usa la misma tabla "teachers"
            'foreignKey' => 'teaching_assistant_id',
            'joinType' => 'LEFT',
        ]);
        $this->belongsTo('Terms', [
            'foreignKey' => 'term_id',
            'joinType' => 'INNER',
        ]);
        $this->belongsToMany('Schedules', [
            'foreignKey' => 'school_course_id',
            'targetForeignKey' => 'schedule_id',
            'joinTable' => 'school_courses_schedules',
        ]);
        $this->belongsToMany('Students', [
            'foreignKey' => 'school_course_id',
            'targetForeignKey' => 'student_id',
            'joinTable' => 'school_courses_students',
        ]);
        $this->belongsToMany('SchoolLevels', [
            'joinTable' => 'school_courses_school_levels',
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
            ->scalar('name')
            ->maxLength('name', 250)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('capacity')
            ->requirePresence('capacity', 'create')
            ->notEmptyString('capacity');

        $validator
            ->numeric('price')
            ->requirePresence('price', 'create')
            ->notEmptyString('costo_normal');

        $validator
            ->integer('subjet_id')
            ->notEmptyString('subjet_id');

        $validator
            ->integer('teacher_id')
            ->notEmptyString('teacher_id');

        $validator
            ->integer('term_id')
            ->notEmptyString('term_id');

        return $validator;
    }


    /**
     * @param Cake\Event\EventInterface $event
     * @param $entity
     * @param \ArrayObject $opcions
     */
    public function beforeSave(EventInterface $event, $entity, \ArrayObject $options) {
        if (empty($entity->min_year_of_birth))
            $entity->min_year_of_birth = null;
        if (empty($entity->max_year_of_birth))
            $entity->max_year_of_birth = null;
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
        $rules->add($rules->existsIn('teacher_id', 'Teachers'), ['errorField' => 'teacher_id']);
        $rules->add($rules->existsIn('term_id', 'Terms'), ['errorField' => 'term_id']);

        return $rules;
    }

    public function findCoursesForStudent(Query $query, array $options) {
        $school_level_id = $options['school_level_id'];
        $sex = $options['sex'];
        $student_id = $options['student_id'];
        $year_of_birth = $options['year_of_birth'];
        $enrolled = $options['enrolled'];

        $query = $query
            ->leftJoinWith('SchoolLevels', function ($q) {
                return $q;
            });
        if ($enrolled == null)
            $query = $query->leftJoinWith('SchoolCoursesStudents', function($q) use ($student_id) {
                return $q->where([
                    'SchoolCoursesStudents.student_id' => $student_id
                ]);
            });
        else 
            $query = $query
                ->innerJoin(['SchoolCoursesStudents' => 'school_courses_students'], [
                    'SchoolCoursesStudents.school_course_id = SchoolCourses.id'
                ])
                ->where ([
                    'SchoolCoursesStudents.student_id' => $student_id,
                ]);
        $query = $query->where([
                'OR' => [ ['SchoolCoursesStudents.is_confirmed' => 1],
                          ['sex IN' => [$sex, 'X'], 
                           "({$year_of_birth} OR SchoolLevels.id = {$school_level_id})"]
                ]
            ])
            ->group(['SchoolCourses.id'])
            ->order([
                'SchoolCoursesStudents.is_confirmed' => 'desc'
            ]);

        return $query;
    }

    public function findCoursesAvailableForStudent(Query $query, array $options) {
        $school_level_id = $options['school_level_id'];
        $sex = $options['sex'];
        $student_id = $options['student_id'];
        $year_of_birth = $options['year_of_birth'];

        $query = $query
            ->leftJoinWith('SchoolLevels', function ($q) {
                return $q;
            });
        
        $query = $query->leftJoinWith('SchoolCoursesStudents', function($q) use ($student_id) {
                return $q->where([
                    'SchoolCoursesStudents.student_id' => $student_id
                ]);
            });
        $query = $query->where([
                'sex IN' => [
                $sex, 'X'],
                "({$year_of_birth} OR SchoolLevels.id = {$school_level_id})",
                'student_id is null'
            ])
            ->order([
                'SchoolCoursesStudents.is_confirmed' => 'desc'
            ]);

        return $query;
    }

    public function findStudentsConfirmed(Query $query, array $options) {
        $query = $query
            ->innerJoin(['SchoolCoursesStudents' => 'school_courses_students'],
                [
                    'SchoolCoursesStudents.school_course_id = SchoolCourses.id'
                ])
            ->where(['SchoolCourses.id' => $options['id'],
                'is_confirmed' => 1
            ]);
        return $query;
    }
}
