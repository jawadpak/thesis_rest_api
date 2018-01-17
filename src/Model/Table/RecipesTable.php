<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class RecipesTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Uuid');
		//$this->hasMany('MealItems');
		$this->hasOne('RecipeNutrients');
		/*$this->belongsTo('RecipeNutrients', [
			        'className' => 'RecipeNutrients',
			        'foreignKey' => 'recipe_id',
		*/

		/*$this->belongsTo('MealItems', [
			        'foreignKey' => 'meal_id',
			        'joinType'   => 'INNER',
		*/
		$this->hasMany('RecipeItems')
			->setForeignKey('recipe_id')
			->setDependent(true);

		/*$this->hasMany = array(
			            'className'  => 'RecipeItems',
			            'foreignKey' => 'recipe_id',
			            'dependent'  => true,
		*/

	}
}
