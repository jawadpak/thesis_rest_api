<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MealsTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Uuid');
		$this->hasMany('MealItems');
		//$this->hasOne('UserPlanMeals');

		/*$this->belongsTo('MealItems', [
			            'foreignKey' => 'meal_id',
			            'joinType' => 'INNER',
			            'className' => 'organizations',
		*/
		/*$this->hasMany = array(

			'className' => 'MealItems',
			'foreignKey' => 'meal_id',
			'order' => 'Rand()',
			'limit' => '1',
			'dependent' => true,

		);*/
	}
}
?>