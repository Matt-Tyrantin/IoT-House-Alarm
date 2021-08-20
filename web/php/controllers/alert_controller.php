<?php
    namespace App\Controllers;
    
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/controllers/controller.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/entities/alert.php');
    require_once($_SERVER['DOCUMENT_ROOT'] . '/php/entities/alarm.php');

    use \App\Controllers\Controller as Controller;
    use \App\Entities\Alert as Alert;
    use \App\Entities\Alarm as Alarm;

	class AlertController extends Controller 
    {
        /**
         * Returns list of alerts with given parameters
         * 
         * Returns list of all alerts if parameters are not given
         */
        public function Get($params = null) 
        {
            if ($params === null) {
                return Alert::GetAll();
            } else if (is_array($params)) {
                return Alert::GetAll($params);
            } else {
                throw new \Exception("Pramaters GET for Alert are of unknown type: ".gettype($params));
            }
        }

        /**
         * Creates a new alert with given parameters. Params should be:
         *      'date' => date of the alert
         *      'time' => time at whcich the alert occured
         *      'alarm_id' => ID of unit which initiated the alert,
         *      'alarm_name' => name of the unit which initiated the alert
         */
        public function Post($params)
        {
            $alarm = Alarm::Get($params['alarm_id']);

            if ($alarm == null) {
                $alarm = new Alarm([
                    'id' => $params['alarm_id'],
                    'name' => $params['alarm_name']
                ]);

                $alarm->Save();
            }

            $alert = new Alert([
                'date' => date('Y-m-d'),
                'time' => date('H:i:s'),
                'alarm_id' => $params['alarm_id']
            ]);

            return $alert->Save();
        }

        /**
         * Deletes all alerts.
         */
        public function Delete()
        {
            foreach (Alert::GetAll() as $alert) {
                $alert->Delete();
            }

            return true;
        }
	}
?>