<?php
namespace App\Models;
use App\Models\DefaultModel;

class ServicesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_postcode	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('sh.*,sd.*, shs.title as header_status, sds.title as details_status')
			->from($this->db->quoteName('#__ddc_service_headers', 'sh'))
			->rightJoin('#__ddc_service_details as sd on sd.service_header_id = sh.ddc_service_header_id')
			->rightJoin('#__ddc_statuses as shs on (shs.link_id = sh.state) AND (shs.table = "service_header")')
			->rightJoin('#__ddc_statuses as sds on (sds.link_id = sd.state) AND (sds.table = "service_details")')
			->group('sd.ddc_service_detail_id')
			->order('sh.book_date DESC');
		return $query;
	}	
	protected function _buildWhere(&$query, $user_id = 0, $id = 0, $service_date = null)
	{
		if($user_id > 0)
		{
			$query->where('sh.user_id = "'.$user_id .'"');
		}
		if($service_date != null)
		{
			$query->where('sh.ddc_service_header_id = "'.$id .'"');
		}
		
		return $query;
	}	
}