<?php
	namespace App\Entities;

	require_once 'entity.php';

	class Alert extends Entity 
	{
		protected static $entity_name = 'alerts';

		protected static $columns = ['id', 'date', 'time', 'alarm_id'];
	}
?>