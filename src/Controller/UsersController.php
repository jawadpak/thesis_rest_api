<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

class UsersController extends AppController {

	public function initialize() {
		parent::initialize();
		$this->loadComponent('RequestHandler');
		//$this->loadModel('Users', 'UserPlans');
		$this->status = "Ok";
		$this->message = "";
		$this->UserPlans = TableRegistry::get('UserPlans');
		$this->UserPlanMeals = TableRegistry::get('UserPlanMeals');
		$this->Recipes = TableRegistry::get('Recipes');

	}

	public function index() {
		//$recipes = $this->Recipes->find('all');
		$a = array(
			0 => array('Person' => array('name' => 'Jeff'), 'Friend' => array(array('name' => 'Nate'))),
			1 => array('Person' => array('name' => 'Tracy'), 'Friend' => array(array('name' => 'Lindsay'))),
			2 => array('Person' => array('name' => 'Adam'), 'Friend' => array(array('name' => 'Bob'))),
		);
		$this->set([
			'recipes' => $a,
			'_serialize' => ['recipes'],
		]);
	}

	public function add() {
		$user = $this->Users->newEntity($this->request->getData());
		if ($this->Users->save($user)) {
			$message = 'Saved';
		} else {
			$message = 'Error';
		}

		$this->set([
			'message' => $message,
			'user' => $user,
			'_serialize' => ['message', 'user'],
		]);
	}

	public function dailyPlan() {
		$user_id = $this->request->getData("user_id");

		if (!$user_id) {
			$this->message = "User ID is missing";
			$this->status = "Error";
		} else {
			$plan_date = $this->request->getData("plan_date") ? $this->request->getData("plan_date") : date('Y-m-d');
			//find the record by userid and plan date
			$user_plan = $this->UserPlans->findByUserIdAndPlanDate($user_id, $plan_date);
			//$user_plan = $this->UserPlans->findByUserIdAndPlanDate(11987, "2011-08-10");
			if (!$user_plan) {
				$user_plan = $this->UserPlans->newEntity($this->request->getData());
				if (!$this->UserPlans->save($user_plan)) {
					$this->message = "UserPlans table has error";
					$this->status = "Error";
				}
			}
			$user_plan_id = $user_plan["id"];
			$user_meal_plan = $this->UserPlanMeals->mealPlanInfo($user_plan_id);

			if (!$user_meal_plan) {
				//need to check whn create new record
				$user_meal_plan = $this->UserPlanMeals->generateUserMealPlan($user_id, $user_plan_id);
			}

			//$user_plan_meal = $this->UserPlanMeals->findByUserIdAndPlanDate($user_id, $plan_date);
		}

		$this->set([
			'message' => $this->message,
			'status' => $this->status,
			//'user' => $user,
			'user_meal_plan' => $user_meal_plan,
			'_serialize' => ['message', 'status', 'user_meal_plan'],
		]);
		/*$a = array(
			    0 => array('Person' => array('name' => 'Jeff'), 'Friend' => array(array('name' => 'Nate'))),
			    1 => array('Person' => array('name' => 'Tracy'), 'Friend' => array(array('name' => 'Lindsay'))),
			    2 => array('Person' => array('name' => 'Adam'), 'Friend' => array(array('name' => 'Bob'))),
			    );
			    $this->set([
			    'recipes' => $a,
			    '_serialize' => ['recipes'],
		*/
	}

	public function getRecipe() {

		$recipes = $this->Recipes->find('all')
			->contain(["RecipeNutrients", 'RecipeItems'])
			->limit(10);

		$this->set([
			'message' => "dsdsdds",
			'recipes' => $recipes,
			'_serialize' => ['message', 'recipes'],
		]);
	}

}
