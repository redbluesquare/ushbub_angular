<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\VendorsModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;

class VendorsController extends DefaultController
{
	
	public function index()
	{
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems();
		$result = 0;
		for($i=0;$i<count($items);$i++)
		{
			$result++;
		}
		return $result;
	}
	public function getCount()
	{
		$model = new VendorsModel($this->getInput(), $this->getContainer()->get('db'));
		$items = $model->listItems();
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
		$title = $this->getInput()->getString('title');
		$description = $this->getInput()->getString('description');
		$shop_type = $this->getInput()->getString('shop_type');
		$email = $this->getInput()->getString('email');
		$full_name = $this->getInput()->getString('full_name');
		if($this->getInput()->getString('email')==null)
		{
			return array("success"=>false);
		}
		$date = Date("Y-m-d H:i:s");

		$result = array("success"=>false);

		$body = 'Title: '.$title.'\n Description: '.$description.'\n Shop Type: '.$shop_type.'\n Full Name: '.$full_name.'\n E-mail: '.$email;

		if(mail('admin@ushbub.co.uk', "New shop registration", $body)){
			$result['success'] = true;
			$result['msg'] = 'Excellent, you are one step closer to selling on our platform! To ensure you are human, we will contact you soon to verify your account.<br>You can create your account whilst we validate your details. <a class="btn btn-primary" href="https://www.ushbub.co.uk/registration-to-ushbub.html">Create</a>';
		}
		
		return $result;
	}
}