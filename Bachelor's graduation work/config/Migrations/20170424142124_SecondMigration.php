<?php
use Migrations\AbstractMigration;

class SecondMigration extends AbstractMigration
{

    public function up()
    {
        /*$this->table('excersice_musculgroup')
            ->dropForeignKey([], 'excersice_musculgroup_ibfk_1')
            ->dropForeignKey([], 'excersice_musculgroup_ibfk_2')
            ->removeIndexByName('primary')
            ->update();

			echo("ok1");
			*/
        /*$this->table('excersice_musculgroup')
            ->removeColumn('exercise_id')
            ->update();
			
			echo("ok");
		*/	
        /*$this->table('routine')
            ->dropForeignKey([], 'routine_ibfk_1')
            ->removeIndexByName('usersId')
            ->update();
*/
/*        $this->table('routine')
            ->removeColumn('usersId')
            ->changeColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->update();
*/    /*    $this->table('trainingprogramdayexcersice')
            ->dropForeignKey([], 'trainingprogramdayexcersice_ibfk_1')
            ->update();
*/
/*        $this->table('eating')
            ->changeColumn('id', 'integer', [
//                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->update();
*/
/*        $this->table('musculgroup')
            ->changeColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('trainingprogram')
            ->changeColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('trainingprogramday')
            ->changeColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->update();
*/
      /*  $this->table('excersice')
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
*/
       /* $this->table('excersice_musculgroup')
            ->addColumn('excersice_id', 'integer', [
                'after' => 'musculgroup_id',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'excersice_id',
                ],
                [
                    'name' => 'fk_excersice_musculgroup_exercise1_idx',
                ]
            )
            ->update();
*/
       /* $this->table('routine')
            ->addColumn('userId', 'integer', [
                'after' => 'active',
                'default' => null,
                'length' => 10,
                'null' => false,
            ])
            ->addIndex(
                [
                    'userId',
                ],
                [
                    'name' => 'userId',
                ]
            )
            ->update();
*/
/*        $this->table('trainingprogram')
            ->addColumn('active', 'boolean', [
                'after' => 'users_id',
                'default' => '0',
                'length' => null,
                'null' => false,
            ])
            ->update();
*/
        /*$this->table('excersice_musculgroup')
            ->addForeignKey(
                [
                    'excersice_id',
                    'musculgroup_id',
                ],
                '',
                '',
                [
                    'update' => '',
                    'delete' => ''
                ]
            )
            ->addForeignKey(
                'ce',
                'excersice',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('routine')
            ->addForeignKey(
                'userId',
                'users',
                'id',
                [
                    'update' => 'CASCADE',
                    'delete' => 'CASCADE'
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
            ->update();

        $this->dropTable('exercise');*/
    }

    public function down()
    {
        $this->table('excersice_musculgroup')
            ->dropForeignKey(
                [
                    'excersice_id',
                    'musculgroup_id',
                ]
            )
            ->dropForeignKey(
                'excersice_id'
            );

        $this->table('routine')
            ->dropForeignKey(
                'userId'
            );

        $this->table('trainingprogramdayexcersice')
            ->dropForeignKey(
                'exercise_id'
            );

        $this->table('exercise')
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

        $this->table('excersice_musculgroup')
            ->removeIndexByName('fk_excersice_musculgroup_exercise1_idx')
            ->update();

        $this->table('excersice_musculgroup')
            ->addColumn('exercise_id', 'integer', [
                'after' => 'musculgroup_id',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->removeColumn('excersice_id')
            ->addIndex(
                [
                    'exercise_id',
                ],
                [
                    'name' => 'fk_excersice_musculgroup_exercise1_idx',
                ]
            )
            ->update();

        $this->table('routine')
            ->removeIndexByName('userId')
            ->update();

        $this->table('routine')
            ->addColumn('usersId', 'integer', [
                'after' => 'active',
                'default' => null,
                'length' => 10,
                'null' => false,
            ])
            ->changeColumn('id', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->removeColumn('userId')
            ->addIndex(
                [
                    'usersId',
                ],
                [
                    'name' => 'fk_routine_users1_idx',
                ]
            )
            ->update();

        $this->table('eating')
            ->changeColumn('id', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('musculgroup')
            ->changeColumn('id', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('trainingprogram')
            ->changeColumn('id', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->removeColumn('active')
            ->update();

        $this->table('trainingprogramday')
            ->changeColumn('id', 'integer', [
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->update();

        $this->table('excersice_musculgroup')
            ->addForeignKey(
                [
                    'exercise_id',
                    'musculgroup_id',
                ],
                '',
                '',
                [
                    'update' => '',
                    'delete' => ''
                ]
            )
            ->addForeignKey(
                'exercise_id',
                'exercise',
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
            ->update();

        $this->dropTable('excersice');
    }
}

