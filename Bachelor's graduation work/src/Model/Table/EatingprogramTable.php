<?php 

namespace App\Model\Table;

	use Cake\ORM\Table;
	use Cake\Validation\Validator;

	class EatingprogramTable extends Table {

		
		public function initialize(array $config)
    	{
        	parent::initialize($config);

        	$this->table('eatingprogram');
        	$this->primaryKey('id');

        	$this->hasMany('routineeatingmenu', [
                'foreignKey' => 'eatingprogram_id',
                'dependent' => true,
                'cascadeCallbacks' => true,
            ]);

            $this->hasOne('Routine', ['className' => 'Routine', 'foreignKey' => 'id', 'bindingKey' => 'routine_id']);
    	}

        public function formateatingprogram($program) {
            if ($program == null)
                return null;
            else {
                $obj = new \stdClass;
                $obj->id = $program->id;
                $obj->name = $program->name;
                $obj->active = $program->active;
                $obj->routine_id = $program->routine_id;
                $key2 = [];
                $key2[$program->routine->routineday[0]->id] = 0;
                $key2[$program->routine->routineday[1]->id] = 1;
                $key3 = [];
                for ($ii=0; $ii < 2; $ii++)
                    foreach ($program->routine->routineday[$ii]->eating as $key => $value) 
                        $key3[$value->id] = $program->routine->routineday[$ii]->id;
                $days=[];
                foreach ($program->routineeatingmenu as $key => $menu) {
                    if (!array_key_exists($menu->day_number, $days)) {
                        $ii = $key2[$key3[$menu->eating_id]];
                        foreach ($program->routine->routineday[$ii]->eating as $key => $value) {
                        //$days[$menu->day_number][(int)$value->id] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                            $days[$menu->day_number][$key] = (object)['foods' => [], 'eating' => $value, "number" => $key];
                            $keys[$value->id] = $key;
                        }
                    }
                //$days[$menu->day_number][(int)$menu->eating_id]->foods[count($days[$menu->day_number][(int)$menu->eating_id]->foods)] = $menu;
                    $menu->food->colories = round($menu->cnt/100 * $menu->food->colories, 2);
                    $menu->food->hidrocarbonats = round($menu->cnt/100 * $menu->food->hidrocarbonats, 2);
                    $menu->food->fats = round($menu->cnt/100 * $menu->food->fats, 2);
                    $menu->food->proteins = round($menu->cnt/100 * $menu->food->proteins, 2);
                    $days[$menu->day_number][$keys[$menu->eating_id]]->foods[count($days[$menu->day_number][$keys[$menu->eating_id]]->foods)] = $menu;
                }
                $obj->days = $days;
            //$eatings[$program->id] = $obj;
                return $obj;
            }
        }

	}