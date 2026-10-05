<?php
use Migrations\AbstractMigration;

class AddActivityToProfiles extends AbstractMigration
{
    /**
     * Добавляет в профиль поле "Коэффициент активности" (activity).
     * Раньше это значение выбиралось в форме профиля (Profile/create.ctp),
     * но физически в таблице profiles такого столбца не было, поэтому
     * выбор пользователя никогда не сохранялся и формула расчёта калорий
     * всегда использовала захардкоженный коэффициент 1.5.
     */
    public function up()
    {
        $this->table('profiles')
            ->addColumn('activity', 'float', [
                'default' => null,
                'null' => true,
                'after' => 'somatotype',
                'comment' => 'Коэффициент активности для формулы Харриса-Бенедикта (1.2 - 1.9)',
            ])
            ->update();
    }

    public function down()
    {
        $this->table('profiles')
            ->removeColumn('activity')
            ->update();
    }
}
