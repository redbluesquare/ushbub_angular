<?php
namespace App\Models;
use App\Models\DefaultModel;

class EmailmessagesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('em.*')
  			->from($this->db->quoteName('#__ddc_email_messages', 'em'))
  			->group('em.id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id)
	{
		if($id > 0)
		{
			$query->where('em.id = '.$id);
		}
		return $query;
	}

	public function email_logger($email_id,$token, $user_id = null, $userinfo_id = null)
	{
		$date = date('Y-m-d H:i:s');
		$data = array($email_id, $userinfo_id, $user_id, $token, $date, $user_id);
		$colums = array("email_id","userinfo_id","user_id","token","created_on","created_by");
		$table = '#__ddc_user_email_campaign_tracker';
		if($this->insert($table, $colums, $data)){
			return true;
		}
		return false;
	}
}