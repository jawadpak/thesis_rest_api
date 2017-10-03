<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class UserPlansTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Uuid');
	}

	public function findByUserIdAndPlanDate($userid, $plan_date) {
		//$user_plans = TableRegistry::get('UserPlans');
		$query = $this->find('all')
			->where([
				'UserPlans.plan_date' => $plan_date,
				'UserPlans.user_id' => $userid,
			]);
		// Calling all() will execute the query
		// and return the result set.
		return $query->first();
		//return $query->toArray();
	}
}
?>