<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validator;
use Cake\Event\EventInterface;
use Cake\Network\Session;

/**
 * SchoolCoursesStudents Model
 *
 * @property \App\Model\Table\SchoolCoursesTable&\Cake\ORM\Association\BelongsTo $SchoolCourses
 * @property \App\Model\Table\StudentsTable&\Cake\ORM\Association\BelongsTo $Students
 *
 * @method \App\Model\Entity\SchoolCoursesStudent newEmptyEntity()
 * @method \App\Model\Entity\SchoolCoursesStudent newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent get($primaryKey, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\SchoolCoursesStudent[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class SchoolCoursesStudentsTable extends Table
{
    use LocatorAwareTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('school_courses_students');
        $this->setDisplayField('id');
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

        return $rules;
    }

    /**
     * @param Cake\Event\EventInterface $event
     * @param $entity
     * @param \ArrayObject $opcions
     */
    public function beforeSave(EventInterface $event, $entity, \ArrayObject $options) {
        if ($entity->is_confirmed == 1) {
            $schoolCourse = $this->SchoolCourses->get($entity->school_course_id);
            $cost = 0;
            // load info del alumno
            $student = $this->Students->get($entity->student_id);
            // Revisar si el curso es de pago obligatorio
            if ($schoolCourse->pago_obligatorio == 1 || $student->externo == 1) {
                $cost = $schoolCourse->price;
            } else {
                // Determinar si el curso tendrá costo
                $free_courses_confirmed = $this->find('all')
                    ->where([
                        'student_id' => $entity->student_id,
                        'is_confirmed' => 1,
                        'cost' => 0
                    ])->count();
                
                $cost = $free_courses_confirmed == 0 ? 0 : $schoolCourse->price;

            }

            $entity->cost = $cost;
        }
    }


    /**
     * @param Cake\Event\EventInterface $event
     * @param $entity
     * @param \ArrayObject $options
     */
    public function afterSave(EventInterface $event, $entity, \ArrayObject $options) {
        $total_confirmed = $this->findBySchoolCourseIdAndIsConfirmed($entity->school_course_id, 1)->count();
        $schoolCourse = $this->SchoolCourses->get($entity->school_course_id);
        $schoolCourse->occupancy = $total_confirmed;
        $this->SchoolCourses->save($schoolCourse);

        // log
        $log_status = $entity->isNew() == 1 ? "CREATED" : "UPDATED";
        $this->_updateLog($entity, $log_status);
    }

    /**
     * @param Cake\Event\EventInterface $event
     * @param $entity
     * @param \ArrayObject $options
     */
    public function afterDelete(EventInterface $event, $entity, \ArrayObject $options) {
        if ($entity->is_confirmed == 1) {
            $schoolCourse = $this->SchoolCourses->get($entity->school_course_id);
            $schoolCourse->occupancy -= 1;
            $this->SchoolCourses->save($schoolCourse);
        }

        // log
        $this->_updateLog($entity, 'DELETED');
    }

    /**
     * @param Cake\Event\EventInterface $event
     * @param $entity
     * @param \ArrayObject $options
     */
    public function afterDeleteCommit(EventInterface $event, $entity, \ArrayObject $options) {
        // Si el curso eliminado está activo y tiene valor 0
        if ($entity->is_confirmed == 1 && $entity->cost == 0) {
            $schoolCoursesId = $this->find('all')
                ->select(['school_course_id'])
                ->where([
                    'student_id' => $entity->student_id,
                    'is_confirmed' => 1,
                ]);


            if ($schoolCoursesId->count() > 0) {
                $schoolCoursesId = array_values(array_column($schoolCoursesId->toArray(), 'school_course_id'));
                $schoolCourses = $this->SchoolCourses->find('all')
                    ->where(['SchoolCourses.id in' => $schoolCoursesId,
                        'pago_obligatorio' => 0
                    ]);
                if ($schoolCourses->count() > 0) {
                    $schoolCourse = $schoolCourses->first();
                    $newEntity = $this->findBySchoolCourseIdAndStudentId($schoolCourse->id, $entity->student_id)->first();
                    $newEntity->cost = 0;
                    $this->save($newEntity);
                }
            }
        }
    }

    /**
     * @param $entity
     */
    private function _updateLog ($entity, $status) {
        $logs = $this->getTableLocator()->get('SchoolCoursesStudentsLogs');
        $new_registry = $logs->newEmptyEntity();
        $new_registry = $logs->patchEntity($new_registry, $entity->toArray());
        $new_registry->status = $status;
        $new_registry->user_id = $_SESSION['Auth']['id'];
        $logs->save($new_registry);

    }
}
