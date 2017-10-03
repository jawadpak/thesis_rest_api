<?php
namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\Utility\Text;

class UuidBehavior extends Behavior {

	public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options) {

		if ($this->_table->schema()->hasColumn("uuid")) {
			$entity->set([
				'uuid' => Text::uuid(),
			]);
		}
	}

}
?>