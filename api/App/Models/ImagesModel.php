<?php
namespace App\Models;
use App\Models\DefaultModel;

class ImagesModel extends DefaultModel
{
	protected $_published 	= 1;
	protected $_location	= null;
	
	
	protected function _buildQuery()
  	{
  		$query = $this->db->getQuery(true);
  		$query->select('i.*')
  			->from($this->db->quoteName('#__ddc_images', 'i'))
  			->group('i.ddc_image_id');
		return $query;
	}	
	protected function _buildWhere(&$query, $id,$link_id, $linkedtable)
	{
		if((int)$id>0)
		{
			$query->where('i.ddc_image_id = '.$id);
		}
		if((int)$link_id>0)
		{
			$query->where('i.link_id = '.$link_id);
		}
		if((int)$linkedtable!=null)
		{
			$query->where('i.linked_table = '.$linkedtable);
		}
		if($this->_published!=null)
		{
			$query->where('i.state = "'.(int)$this->_published.'"');
		}
		return $query;
	}
	
	public function removeImage($id,$link_id,$linkedtable)
	{
		if($row = $this->getItemById($id,$link_id, $linkedtable))
		{
			$this->addLog(JPATH_BASE.'/'.$row->image_link." | ".$link_id);
			unlink(JPATH_BASE.'/'.$row->image_link);
			$condition = "ddc_image_id = ".$row->ddc_image_id;
			if($this->delete('#__ddc_images', $condition)){
				return true;
			}
			return false;
		}
		return true;
	}

	public function addImage($item_id,$linkedtable,$user_id)
	{
		$return = array('success'=>false);
		if($_FILES["upload_photo"]["error"] == 0)
		{		
			$fileName = $_FILES["upload_photo"]["name"];
			$fileTmpLoc = $_FILES["upload_photo"]["tmp_name"];
			$fileType = $_FILES["upload_photo"]["type"];
			$fileSize = $_FILES["upload_photo"]["size"];
			$fileErrorMsg = $_FILES["upload_photo"]["error"];
			$ext = explode(".", $fileName);
			$ext = $ext[1];
			$fname = date("Ymdhhiiss").$linkedtable.$item_id."_temp.".$ext;
			$newName = date("Ymdhhiiss").$linkedtable.$item_id.".".$ext;
			$path = 'assets/com_ddcshopbox/images/';
			$dest = $fname;
			$dest1 = $newName;
				
			if(!$fileTmpLoc)
			{
				$return["msg"] = "Error, please first select a file!";
				return $return;
			}
			else if($fileSize > 5242880)
			{ // if file size is larger than 5 Megabytes
				$return["msg"] = "ERROR: Your file was larger than 5 Megabytes in size.";
				unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				return $return;
			}
			else if (!preg_match("/.(gif|jpg|png|jpeg)$/i", $fileName) )
			{
				// This condition is only if you wish to allow uploading of specific file types
				$return["msg"] = "ERROR: Your image was not .gif, .jpg, or .png.";
				unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				return $return;
			}
			else if ($fileErrorMsg == 1)
			{ // if file upload error key is equal to 1
				$return["msg"] = "ERROR: An error occured while processing the file. Try again.";
				return $return;
			}
			// Place it into your "uploads" folder mow using the move_uploaded_file() function
			$moveResult = move_uploaded_file($fileTmpLoc, JPATH_BASE."/assets/com_profiles/images/".$dest);
			// Check to make sure the move result is true before continuing
			if ($moveResult != true)
			{
				$return["msg"] = "ERROR: File not uploaded. Try again.";
				unlink($fileTmpLoc); // Remove the uploaded file from the PHP temp folder
				return $return;
			}
			// ---------- Include Universal Image Resizing Function --------
			$target_file = JPATH_BASE."/assets/com_profiles/images/".$dest;
			$resized_file = JPATH_BASE."/assets/com_profiles/images/".$dest1;
			$wmax = 640;
			$hmax = 480;
			$this->img_resize($target_file, $resized_file, $wmax, $hmax, $ext);
			unlink(JPATH_BASE."/assets/com_profiles/images/".$dest);
			// ----------- End Universal Image Resizing Function -----------
			$date = date("Y-m-d H:i:s");
			$columns = array('link_id', 
						'linked_table', 
						'image_link', 
						'state', 
						'modified_on', 
						'modified_by', 
						'created_on', 
						'created_by'
					);
			$values = array($item_id, 
							$linkedtable, 
							'assets/com_profiles/images/'.$dest1, 
							1, 
							$date, 
							$user_id, 
							$date, 
							$user_id
					);
			if ( $row = $this->insert('#__ddc_images',$columns,$values) )
			{
				$return['success'] = true;
				$return['msg'] = 'File successfully uploaded';
	
			}else{
				$return['msg'] = 'File did not save, please try again.';
			}
		}
		return $return;
	}

	// Function for resizing jpg, gif, or png image files
	public function img_resize($target, $newcopy, $w, $h, $ext) {
		list($w_orig, $h_orig) = getimagesize($target);
		$scale_ratio = $w_orig / $h_orig;
		if (($w / $h) > $scale_ratio) {
			$w = $h * $scale_ratio;
		} else {
			$h = $w / $scale_ratio;
		}
		$img = "";
		$ext = strtolower($ext);
		if ($ext == "gif"){
			$img = imagecreatefromgif($target);
		} else if($ext =="png"){
			$img = imagecreatefrompng($target);
		} else {
			$img = imagecreatefromjpeg($target);
		}
		$tci = imagecreatetruecolor($w, $h);
		// imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
		imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
		imagejpeg($tci, $newcopy, 80);
	}
}