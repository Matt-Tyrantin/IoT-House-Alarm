<?php
	namespace App\Entities;

	require_once 'entity.php';

	class Alarm extends Entity 
	{
		protected static $entity_name = 'alarms';

		protected static $columns = ['id', 'name', 'location'];
	}
?>