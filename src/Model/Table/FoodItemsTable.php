<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class FoodItemsTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Uuid');
	}
}
?>