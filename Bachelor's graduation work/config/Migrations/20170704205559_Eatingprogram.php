<?php
use Migrations\AbstractMigration;

class Eatingprogram extends AbstractMigration
{

    public function up()
    {
        $this->table('routineeatingmenu')
            ->dropForeignKey([], 'primary')
            ->update();

        $this->table('eatingprogram')
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 200,
                'null' => false,
            ])
            ->addColumn('routine_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'routine_id',
                ]
            )
            ->create();

        $this->table('eatingprogram')
            ->addForeignKey(
                'routine_id',
                'routine',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->update();

        $this->table('routineeatingmenu')
            ->addColumn('eatingprogram_id', 'integer', [
                'after' => 'food_id',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('day_number', 'integer', [
                'after' => 'eatingprogram_id',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addColumn('cnt', 'integer', [
                'after' => 'day_number',
                'default' => null,
                'length' => 11,
                'null' => false,
            ])
            ->addIndex(
                [
                    'eatingprogram_id',
                ],
                [
                    'name' => 'fk_routineatingmenu_eatingprogram1_idx',
                ]
            )
            ->update();

        $this->table('food')
            ->addColumn('proteins', 'float', [
                'after' => 'fats',
                'default' => null,
                'length' => null,
                'null' => false,
            ])
            ->update();

        $this->table('routineeatingmenu')
            ->addForeignKey(
                'eatingprogram_id',
                'eatingprogram',
                'id',
                [
                    'update' => 'NO_ACTION',
                    'delete' => 'NO_ACTION'
                ]
            )
            ->addForeignKey(
                [
                    'eating_id',
                    'food_id',
                    'eatingprogram_id',
                    'day_number',
                ],
                '',
                '',
                [
                    'update' => '',
                    'delete' => ''
                ]
            )
            ->update();
    }

    public function down()
    {
        $this->table('eatingprogram')
            ->dropForeignKey(
                'routine_id'
            );

        $this->table('routineeatingmenu')
            ->dropForeignKey(
                'eatingprogram_id'
            )
            ->dropForeignKey(
                [
                    'eating_id',
                    'food_id',
                    'eatingprogram_id',
                    'day_number',
                ]
            );

        $this->table('routineeatingmenu')
            ->removeIndexByName('fk_routineatingmenu_eatingprogram1_idx')
            ->update();

        $this->table('routineeatingmenu')
            ->removeColumn('eatingprogram_id')
            ->removeColumn('day_number')
            ->removeColumn('cnt')
            ->update();

        $this->table('food')
            ->removeColumn('proteins')
            ->update();

        $this->table('routineeatingmenu')
            ->addForeignKey(
                [
                    'eating_id',
                    'food_id',
                ],
                '',
                '',
                [
                    'update' => '',
                    'delete' => ''
                ]
            )
            ->update();

        $this->dropTable('eatingprogram');
    }
}

