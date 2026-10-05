<?php
use Migrations\AbstractMigration;

class NewMigration extends AbstractMigration
{

    public function up()
    {
        $this->table('profiles')
            ->dropForeignKey([], 'fk_profiles_users')
            ->removeIndexByName('fk_profiles_users_idx')
            ->update();

        $this->table('profiles')
            ->removeColumn('userid')
            ->changeColumn('id', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->update();

        $this->table('exercise')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 2000,
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

        $this->table('exercise_musculgroup', ['id' => false, 'primary_key' => ['musculgroup_id', 'exercise_id']])
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
            ->addIndex(
                [
                    'exercise_id',
                ],
                ['unique' => true]
            )
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

        $this->table('trainingprogramday_exercise')
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
            ->addColumn('position', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
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

        $this->table('exercise_musculgroup')
            ->addForeignKey(
                'exercise_id',
                'exercise',
                'id',
                [
                    'update' => 'RESTRICT',
                    'delete' => 'RESTRICT'
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

        $this->table('trainingprogramday_exercise')
            ->addForeignKey(
                'exercise_id',
                'exercise',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->addForeignKey(
                'trainingprogramday_id',
                'trainingprogramday',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
                ]
            )
            ->update();

        $this->table('profiles')
            ->addColumn('userId', 'integer', [
                'after' => 'somatotype',
                'default' => null,
                'length' => 10,
                'null' => false,
            ])
            ->addIndex(
                [
                    'userId',
                ],
                [
                    'name' => 'fk_profiles_users_idx',
                ]
            )
            ->update();

        $this->table('profiles')
            ->addForeignKey(
                'userId',
                'users',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->dropTable('excersice');

        $this->dropTable('excersice_musculgroup');

        $this->dropTable('trainingprogramdayexcersice');
    }

    public function down()
    {
        $this->table('exercise_musculgroup')
            ->dropForeignKey(
                'exercise_id'
            )
            ->dropForeignKey(
                'musculgroup_id'
            );

        $this->table('trainingprogramday_exercise')
            ->dropForeignKey(
                'exercise_id'
            )
            ->dropForeignKey(
                'trainingprogramday_id'
            );

        $this->table('profiles')
            ->dropForeignKey(
                'userId'
            );

        $this->table('excersice')
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

        $this->table('excersice_musculgroup', ['id' => false, 'primary_key' => ['musculgroup_id', 'excersice_id']])
            ->addColumn('musculgroup_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('excersice_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'excersice_id',
                ]
            )
            ->addIndex(
                [
                    'musculgroup_id',
                ]
            )
            ->create();

        $this->table('trainingprogramdayexcersice', ['id' => false, 'primary_key' => ['trainingprogramday_id', 'exercise_id']])
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

        $this->table('excersice_musculgroup')
            ->addForeignKey(
                'excersice_id',
                'excersice',
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

        $this->table('trainingprogramdayexcersice')
            ->addForeignKey(
                'exercise_id',
                'excersice',
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

        $this->table('profiles')
            ->removeIndexByName('fk_profiles_users_idx')
            ->update();

        $this->table('profiles')
            ->addColumn('userid', 'integer', [
                'after' => 'somatotype',
                'default' => null,
                'length' => 10,
                'null' => false,
            ])
            ->changeColumn('id', 'integer', [
                'default' => null,
                'length' => 10,
                'null' => false,
            ])
            ->removeColumn('userId')
            ->addIndex(
                [
                    'userid',
                ],
                [
                    'name' => 'fk_profiles_users_idx',
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

        $this->dropTable('exercise');

        $this->dropTable('exercise_musculgroup');

        $this->dropTable('trainingprogramday_exercise');
    }
}

