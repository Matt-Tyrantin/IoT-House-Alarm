<?php
    namespace App\Controllers;
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/controllers/controller.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/entities/alarm.php');

    use \App\Controllers\Controller as Controller;
    use \App\Entities\Alarm as Alarm;

	class AlarmController extends Controller 
    {
        /**
         * Returns list of alarms with given parameters
         * 
         * Returns list of all alarms if parameters are not given
         */
        public function Get($params = null) 
        {
            if ($params === null) {
                return Alarm::GetAll();
            } else if (is_array($params)) {
                return Alarm::GetAll($params);
            } else {
                throw new \Exception("Pramaters GET for Alarm are of unknown type: ".gettype($params));
            }
        }

        /**
         * Creates a new alarm with given parameters. Params should be:
         *      'date' => date of the alert
         *      'time' => time at whcich the alert occured
         *      'alarm_id' => ID of unit which initiated the alrt
         */
        public function Post($params)
        {
            $alarm = new Alert([
                'id' => $params['id'],
                'name'=> $params['name']
            ]);

            return $alarm->Save();
        }

        /**
         * Updates an existing alarm device with given parameters
         */
        public function Put($params)
        {
            $alarm = new Alarm([
                'id' => $params['id'],
                'name' => $params['name'],
                'location' => $params['location']
            ]);

            return $alarm->Save();
        }
	}
?>