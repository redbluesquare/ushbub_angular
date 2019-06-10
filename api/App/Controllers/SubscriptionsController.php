<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\SubscriptionsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class SubscriptionsController extends DefaultController
{
	
	public function index()
	{
		$model = new SubscriptionsModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems(null, null, 30);
		$result = 0;
		for($i=0;$i<count($items);$i++)
		{
			$result++;
		}
		return $result;
	}
	public function getCount()
	{
		$model = new SubscriptionsModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems(null, null, 30);
		$result = 0;
		for($i=0;$i<count($items);$i++)
		{
			$param_array = json_decode($items[$i]->params);
			if($param_array['sendupdates']==1)
			{
				$result++;
			}
		}
		return $result;
	}
	
	public function add()
	{
		$id = $this->getInput()->getString('id');
		$model = new SubscriptionsModel($this->getInput(), $this->getContainer()->get('db'));
		$fname = $this->getInput()->getString('fname');
		$lname = $this->getInput()->getString('lname');
		$email = $this->getInput()->getString('email');
		$catid = $this->getInput()->getString('catid');
		$state = 1;
		if($this->getInput()->getString('email')==null)
		{
			return array("success"=>false);
		}
		$params = array("sendupdates"=>1);
		$date = Date("Y-m-d H:i:s");
		$table = "#__contact_details";
		$item = null;
		$item = $model->getItemById($id, $email);
		$result = array("success"=>false);
		$result['success'] = false;
		if(count($item)==0)
		{
			$safe = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $fname." ".$lname)));
			$columns = array("id", "name", "alias", "email_to", "published", "catid", "params", "created", "modified", "metakey", "metadesc", "metadata", "language");
			$data = array($id, $fname." ".$lname, $safe, $email, $state, $catid, json_encode($params), $date, $date, ' ', ' ', ' ', '*');
			if($model->insert($table, $columns, $data))
			{
				$result['success'] = true;
			}
		}else{
			if($item->params!=null)
			{
				$item_params = json_decode($item->params,true);
				$params = array_merge($item_params, $params);
			}
			$fields = array($this->getContainer()->get('db')->qn('catid'). " = ".$this->getContainer()->get('db')->q($catid),$this->getContainer()->get('db')->qn('params'). " = ".$this->getContainer()->get('db')->q(json_encode($params)),$this->getContainer()->get('db')->qn('published'). " = '1'",$this->getContainer()->get('db')->qn('modified'). " = '". $date ."'");
			$conditions = array($this->getContainer()->get('db')->qn('id'). ' = '. $this->getContainer()->get('db')->q($item->id) );
			if($model->update($table, $fields, $conditions))
			{
				$result['success'] = true;
			}
		}
		return $result;
	}
}