<?php

namespace App\Controller\Component;

use Cake\Controller\Component;

class DietComponent extends Component
{
    
    //Ectomorphic
    //Для эктоморфов: белки - 20-30%, углеводы - 50-60%, жиры — 20-30%
    const ECT_PROTEINS_MIN = 0.2;
    const ECT_PROTEINS_MAX = 0.3;
        
    const ECT_CARBOHYDRATES_MIN = 0.5;
    const ECT_CARBOHYDRATES_MAX = 0.6;
        
     const ECT_FATS_MIN = 0.2;
     const ECT_FATS_MAX = 0.3;
    
    //Endomorphic
    //Для эндоморфов: белки - 40-50%, углеводы -30-40%, жиры - 10-15%.
     const END_PROTEINS_MIN = 0.4;
     const END_PROTEINS_MAX = 0.5;
        
     const END_CARBOHYDRATES_MIN = 0.3;
     const END_CARBOHYDRATES_MAX = 0.4;
        
     const END_FATS_MIN = 0.1;
     const END_FATS_MAX = 0.15;
    
    //Mesomorphic
    //Для мезоморфов: белки - 30-40%, углеводы - 40-50%, жиры — 10-20%.
     const MES_PROTEINS_MIN = 0.3;
     const MES_PROTEINS_MAX = 0.4;
        
     const MES_CARBOHYDRATES_MIN = 0.4;
     const MES_CARBOHYDRATES_MAX = 0.5;
        
     const MES_FATS_MIN = 0.1;
     const MES_FATS_MAX = 0.2;
    
    //Ккал в 1гр (те же коэффициенты, что в калькуляторе kalories.js)
     const KCAL_PROTEINS = 4;
     const KCAL_FATS = 9;
     const KCAL_CARBOHYDRATES = 4;

    //Тот же поправочный коэффициент, что в калькуляторе (kalories.js),
    //применяется к итоговым граммам Б/Ж/У
    const MACRO_ADJUST_FACTOR = 0.96;
    
    public $ave_kcal = 1;

    //Коэф. активности по умолчанию, если в профиле он не указан
    //(соответствует варианту "минимум или отсутствие физической нагрузки")
    const DEFAULT_ACTIVITY = 1.2;

    /* Возвращает коэффициент активности из профиля,
       либо значение по умолчанию, если в профиле он не указан */
    public function getActivityCoef($profile) {
        return (!empty($profile->activity)) ? $profile->activity : self::DEFAULT_ACTIVITY;
    }

    /* Возвращает возраст из профиля.
       Форма профиля (Profile/create.ctp) больше не содержит поля "age" —
       пользователь вводит только дату рождения ("birthday"), поэтому сырое
       поле age в таблице profiles, как правило, не заполнено (null/0).
       Если age не указан явно, считаем его из birthday. */
    public function getAge($profile) {
        if (!empty($profile->age)) {
            return $profile->age;
        }
        if (!empty($profile->birthday)) {
            $tz = new \DateTimeZone('Europe/Moscow');
            $interval = $profile->birthday->diff(new \DateTime('now', $tz));
            return (int)$interval->format('%Y');
        }
        return 0;
    }

    //Значения aimTrain в профиле (те же, что в Calculator/Kalories.ctp и Element/profile/info.ctp)
    const AIM_LOSS = 1;      //похудение
    const AIM_GAIN = 2;      //набор мышечной массы
    const AIM_MAINTAIN = 3;  //поддержание мышечной массы

    //Поправка к калорийности под цель тренировки — как в kalories.js
    const AIM_OFFSET_LOSS = -500;
    const AIM_OFFSET_GAIN = 500;

    /* Возвращает поправку к суточной калорийности (±500 ккал) в зависимости
       от цели тренировки в профиле (aimTrain). Для похудения отдаёт -500,
       для набора массы +500, для поддержания (или если цель не указана) - 0.
       Это та же логика, что уже применяется в калькуляторе (kalories.js). */
    public function getAimOffset($profile) {
        if (empty($profile->aimTrain)) {
            return 0;
        }
        switch ((int)$profile->aimTrain) {
            case self::AIM_LOSS:
                return self::AIM_OFFSET_LOSS;
            case self::AIM_GAIN:
                return self::AIM_OFFSET_GAIN;
            default:
                return 0;
        }
    }


    /* $weight - вес 
    $growth - рост 
    $age - возраст
    $sex - пол
    $actCoef - коэф. активности */
    public function Harris_Benedict($weight, $growth, $age, $sex, $actCoef) {
    
        switch ($sex) {
        
            case "male":
                $tmp = 88.362+(13.397*$weight)+(4.799*$growth)-(5.677*$age);
                break;
            case "female":
                $tmp = 447.593+(9.247*$weight)+(3.098*$growth)-(4.33*$age);
                break;
        
        }
        
        $tmp *= $actCoef;
        return $tmp;
    
    }
    
    /* $weight - вес 
    $growth - рост 
    $age - возраст
    $sex - пол
    $actCoef - коэф. активности 
    $fat - процент жира 
    $waist - обхват талии */
    public function Catch_McArdle($weight, $growth, $age, $sex, $actCoef, $fat, $waist) {   
    
        if ($fat == 0) {
            
            switch ($sex) {
                case "male":
                    $fat = (((4.15*$waist/2.54)-(0.082*$weight/0.454)-98.42)/($weight/0.454))*100;
                    break;
                case "female":
                    $fat = (((4.15*$waist/2.54)-(0.082*$weight/0.454)-76.76)/($weight/0.454))*100;
                    break;

            } 
            
        }
        
        $tmp = 370+(21.6*$weight*((100-$fat)/100));
        $tmp *= $actCoef;
        return $tmp;
        
    }

    public function setAveKkal($val) {
        $this->ave_kcal = $val;
    }
    
    public function PFC_for_day($somatotype, $weight) {
        
        switch ($somatotype) {
                
            case 1://эктоморф
                //белки
                $pr_min = self::ECT_PROTEINS_MIN;
                $pr_max = self::ECT_PROTEINS_MAX;
                //жиры
                $ft_min = self::ECT_FATS_MIN;
                $ft_max = self::ECT_FATS_MAX;
                //углеводы
                $ca_min = self::ECT_CARBOHYDRATES_MIN;
                $ca_max = self::ECT_CARBOHYDRATES_MAX;
                break;
            case 2://мезоморф
                //белки
                $pr_min = self::MES_PROTEINS_MIN;
                $pr_max = self::MES_PROTEINS_MAX;
                //жиры
                $ft_min = self::MES_FATS_MIN;
                $ft_max = self::MES_FATS_MAX;
                //углеводы
                $ca_min = self::MES_CARBOHYDRATES_MIN;
                $ca_max = self::MES_CARBOHYDRATES_MAX;   
                break;
            case 3://эндоморф
                //белки
                $pr_min = self::END_PROTEINS_MIN;
                $pr_max = self::END_PROTEINS_MAX;
                //жиры
                $ft_min = self::END_FATS_MIN;
                $ft_max = self::END_FATS_MAX;
                //углеводы
                $ca_min = self::END_CARBOHYDRATES_MIN;
                $ca_max = self::END_CARBOHYDRATES_MAX;   
                break;
                
        }
        
        //белки
        $prKcalL = $this -> ave_kcal * $pr_min;
        $prKcalR = $this -> ave_kcal * $pr_max; 
        $avePrKcal = ($prKcalL + $prKcalR)/2;  //среднее для белков в ккал

        $prGrL = $prKcalL / self::KCAL_PROTEINS;
        $prGrR = $prKcalR / self::KCAL_PROTEINS;
        $avePrGr = ($prGrL + $prGrR)/2;  //среднее для белков в граммах

        $prCfL = $prGrL / $weight;
        $prCfR = $prGrR / $weight;
        $avePrCf = ($prCfL + $prCfR)/2;  //среднее значение для белков (коэф)

        //жиры
        $ftKcalL = $this -> ave_kcal * $ft_min;
        $ftKcalR = $this -> ave_kcal * $ft_max;
        $aveFtKcal = ($ftKcalL + $ftKcalR)/2;  //среднее для жиров в ккал

        $ftGrL = $ftKcalL / self::KCAL_FATS;
        $ftGrR = $ftKcalR / self::KCAL_FATS;
        $aveFtGr = ($ftGrL + $ftGrR)/2;  //среднее для жиров в граммах

        $ftCfL = $ftGrL / $weight;
        $ftCfR = $ftGrR / $weight;
        $aveFtCf = ($ftCfL + $ftCfR)/2;  //среднее значение для жиров (коэф)

        //углеводы
        $caKcalL = $this -> ave_kcal * $ca_min;
        $caKcalR = $this -> ave_kcal * $ca_max;
        $aveCaKcal = ($caKcalL + $caKcalR)/2;  //среднее для углеводов в ккал

        $caGrL = $caKcalL / self::KCAL_CARBOHYDRATES;
        $caGrR = $caKcalR / self::KCAL_CARBOHYDRATES;
        $aveCaGr = ($caGrL + $caGrR)/2;  //среднее для углеводов в граммах

        $caCfL = $caGrL / $weight;
        $caCfR = $caGrR / $weight;
        $aveCaCf = ($caCfL + $caCfR)/2;  //среднее значение для углеводов (коэф)  
        
        // Б/Ж/У возвращаем в граммах (а не в ккал), с тем же коэффициентом 0.96, что в калькуляторе
        $res = [
            "avePrCf" => "" . ($avePrGr * self::MACRO_ADJUST_FACTOR),
            "aveFtCf" => "" . ($aveFtGr * self::MACRO_ADJUST_FACTOR),
            "aveCaCf" => "" . ($aveCaGr * self::MACRO_ADJUST_FACTOR),
        ];
        
        return $res;
        
    }

}