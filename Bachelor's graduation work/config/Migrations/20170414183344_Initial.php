<?php
use Migrations\AbstractMigration;

class Initial extends AbstractMigration
{

    public $autoId = false;

    public function up()
    {

        $this->table('eating')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('time', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('routineId', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'routineId',
                ]
            )
            ->create();

        $this->table('excersice_musculgroup')
            ->addColumn('musculgroup_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('exercise_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['musculgroup_id', 'exercise_id'])
            ->addIndex(
                [
                    'exercise_id',
                ]
            )
            ->addIndex(
                [
                    'musculgroup_id',
                ]
            )
            ->create();

        $this->table('exercise')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('img', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->addColumn('video', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->create();

        $this->table('food')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('colories', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('hidrocarbonats', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('fats', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->create();

        $this->table('musculgroup')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->create();

        $this->table('profiles')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('sex', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('age', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('growth', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('weight', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('aimTrain', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('active', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('somatotype', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('userid', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'userid',
                ]
            )
            ->create();

        $this->table('routine')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('wakeupTime', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('trainTime', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('eatCount', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('sleepTime', 'time', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('active', 'boolean', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('usersId', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'usersId',
                ]
            )
            ->create();

        $this->table('routineeatingmenu')
            ->addColumn('eating_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('food_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['eating_id', 'food_id'])
            ->addIndex(
                [
                    'eating_id',
                ]
            )
            ->addIndex(
                [
                    'food_id',
                ]
            )
            ->create();

        $this->table('trainingprogram')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('users_id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(
                [
                    'users_id',
                ]
            )
            ->create();

        $this->table('trainingprogramday')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('number', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('trainingprogram_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'trainingprogram_id',
                ]
            )
            ->create();

        $this->table('trainingprogramdayexcersice')
            ->addColumn('trainingprogramday_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('exercise_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addPrimaryKey(['trainingprogramday_id', 'exercise_id'])
            ->addColumn('podhod', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('repeats', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('weight', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addIndex(
                [
                    'exercise_id',
                ]
            )
            ->addIndex(
                [
                    'trainingprogramday_id',
                ]
            )
            ->create();

        $this->table('users')
            ->addColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('password', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('role', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('apiKey', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->create();

        $this->table('eating')
            ->addForeignKey(
                'routineId',
                'routine',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('excersice_musculgroup')
            ->addForeignKey(
                'exercise_id',
                'exercise',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'musculgroup_id',
                'musculgroup',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('profiles')
            ->addForeignKey(
                'userid',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('routine')
            ->addForeignKey(
                'usersId',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('routineeatingmenu')
            ->addForeignKey(
                'eating_id',
                'eating',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'food_id',
                'food',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('trainingprogram')
            ->addForeignKey(
                'users_id',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('trainingprogramday')
            ->addForeignKey(
                'trainingprogram_id',
                'trainingprogram',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('trainingprogramdayexcersice')
            ->addForeignKey(
                'exercise_id',
                'exercise',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                'trainingprogramday_id',
                'trainingprogramday',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('eating')
            ->dropForeignKey(
                'routineId'
            );

        $this->table('excersice_musculgroup')
            ->dropForeignKey(
                'exercise_id'
            )
            ->dropForeignKey(
                'musculgroup_id'
            );

        $this->table('profiles')
            ->dropForeignKey(
                'userid'
            );

        $this->table('routine')
            ->dropForeignKey(
                'usersId'
            );

        $this->table('routineeatingmenu')
            ->dropForeignKey(
                'eating_id'
            )
            ->dropForeignKey(
                'food_id'
            );

        $this->table('trainingprogram')
            ->dropForeignKey(
                'users_id'
            );

        $this->table('trainingprogramday')
            ->dropForeignKey(
                'trainingprogram_id'
            );

        $this->table('trainingprogramdayexcersice')
            ->dropForeignKey(
                'exercise_id'
            )
            ->dropForeignKey(
                'trainingprogramday_id'
            );

        $this->dropTable('eating');
        $this->dropTable('excersice_musculgroup');
        $this->dropTable('exercise');
        $this->dropTable('food');
        $this->dropTable('musculgroup');
        $this->dropTable('profiles');
        $this->dropTable('routine');
        $this->dropTable('routineeatingmenu');
        $this->dropTable('trainingprogram');
        $this->dropTable('trainingprogramday');
        $this->dropTable('trainingprogramdayexcersice');
        $this->dropTable('users');
    }
}
