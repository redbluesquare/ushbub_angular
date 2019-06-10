<?php
namespace App\Controllers;
use App\Controllers\DefaultController;
use App\Models\ProfilesModel;
use App\Models\UsergroupsModel;
use App\Models\CurrenciesModel;
use Joomla\Session\Session;
use Joomla\Event\Dispatcher;
use App\Models\App\Models;
use Joomla\Http\Http;

class CurrenciesController extends DefaultController
{
	
	public function index()
	{
		$item = new \StdClass();
		$item->success = false;
		$input = $this->getInput()->json;
		$currModel = new CurrenciesModel($this->getInput(), $this->getContainer()->get('db'));
		$method = $this->getInput()->getMethod();
		if($method=='GET'){

			$item->currencies = $currModel->listItems();
			$item->success = True;

			return $item;
		}
		if($method=='POST'){
			return $item;
		}
		if($method=='DELETE'){

			return $item;
		}
		if($method=='UPDATE'){

			return $item;
		}
		return $item;
	}

}