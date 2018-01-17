<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class MealItemsTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Uuid');
		/*$this->hasMany('FoodItems', [
			'foreignKey' => 'food_item_id',
			'className' => 'food_items',
		]);*/

		$this->belongsTo('FoodItems', [
			'foreignKey' => 'food_item_id',
			'className' => 'food_items',
		]);

		$this->hasOne('MealItemNutrients', [
			'foreignKey' => 'meal_item_id',
			'className' => 'meal_item_nutrients',
		]);
		$this->hasMany('ChildItems', [
			'foreignKey' => 'parent_id',
			'className' => 'meal_items',

		]);

		// Model/Table/CommentsTable.php
		$this->belongsTo('ChildItems.ChildMealItemNutrients', [
			'foreignKey' => 'meal_item_id',
			'className' => 'meal_item_nutrients',
		]);

	}
}
?>