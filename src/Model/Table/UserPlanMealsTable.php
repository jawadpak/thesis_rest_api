<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class UserPlanMealsTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Uuid');
		$this->Meals = TableRegistry::get('Meals');
		$this->MealItems = TableRegistry::get('MealItems');
		$this->conn = ConnectionManager::get('default');

		$this->belongsTo('Meals', [
			'foreignKey' => 'meal_id',
			'className' => 'meals',
		]);

		$this->belongsTo('MealItems', [
			'foreignKey' => 'meal_item_id',
			'className' => 'meal_items',
		]);

		/*$this->belongsTo('ChildItem', [
			'foreignKey' => 'parent_id',
			'className' => 'meal_items',
		]);*/
	}

	public function mealPlanInfo($user_plan_id) {
		//$user_plans = TableRegistry::get('UserPlans');
		$query = $this->find('all')
			->select([
				'id', 'user_id', 'user_plan_id', 'meal_id', 'meal_name', 'meal_item_id', 'tracked', 'sequence',
			])
			->where([
				'UserPlanMeals.user_plan_id' => $user_plan_id,
			])
			->order(['UserPlanMeals.sequence' => 'ASC'])
			->contain([
				'MealItems' => function ($q) {
					return $q->autoFields(false)
						->select([
							'id', 'meal_id', 'parent_id', 'food_item_type', 'food_item_id', 'name', 'size_major', 'size_minor', 'unit', 'weight', 'weight_per_unit', 'prep_time', 'cook_time', 'directions']
						)
					//->contain(['MealItemNutrients'])
						->contain([
							'FoodItems' => function ($q) {
								return $q->autoFields(false)
									->select([
										'id']);
							},
							'MealItemNutrients' => function ($q) {
								return $q->autoFields(false)
									->select([
										'id', 'P203', 'P204', 'P205', 'P208', 'P255', 'P303']);
							},
							'ChildItems' => function ($q) {
								return $q->autoFields(false)
									->select([
										'id', 'meal_id', 'parent_id', 'food_item_type', 'food_item_id', 'name', 'size_major', 'size_minor', 'unit', 'weight', 'weight_per_unit', 'prep_time', 'cook_time', 'directions']);
							},

						]);

				},

			]);

		// Calling all() will execute the query
		// and return the result set.
		//return $query->first();
		return $query->toArray();
	}

	public function generateUserMealPlan($user_id, $user_plan_id) {

		$stmt = $this->conn->execute("SELECT M.id as meal_id,title as meal_name ,MI.id AS meal_item_id, MI.name, M.sequence
			FROM meals M
			LEFT JOIN meal_items MI
			ON MI.meal_id = M.id
			GROUP BY MI.meal_id
			ORDER BY RAND()");

		$randomMealItem = $stmt->fetchAll('assoc');

		foreach ($randomMealItem as $mealItem) {
			$mealItemObj = $this->newEntity($mealItem);
			$mealItemObj->set([
				'user_id' => $user_id,
				'user_plan_id' => $user_plan_id,
			]);
			$this->save($mealItemObj);
		}

	}
}
?>