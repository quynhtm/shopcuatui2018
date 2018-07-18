<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers;

use App\Http\Controllers\Hr\RetirementController;
use App\Http\Models\Hr\Allowance;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\Device;
use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\JobAssignment;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDocument;
use App\Http\Models\Hr\HrMail;
use App\Http\Models\Hr\QuitJob;
use App\Http\Models\Hr\Retirement;
use App\Http\Models\Hr\Salary;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Upload;
use App\Library\PHPThumb\ThumbImg;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Config;

class AjaxUploadController extends BaseAdminController{

	function upload(){
		$action = addslashes(Request::get('act', ''));
		switch( $action ){
			case 'upload_image' :
				$this->upload_image();
				break;
			case 'remove_image' :
				$this->remove_image();
				break;
			case 'get_image_insert_content' :
				$this->get_image_insert_content();
				break;
			case 'upload_ext' :
				$this->upload_ext();
				break;
			default:
				$this->nothing();
				break;
		}
	}
	//Default
	function nothing(){
		die("Nothing to do...");
	}
	//Upload
	function upload_image() {
		$id_hiden =  FunctionLib::outputId(Request::get('id', 0));

		$type = Request::get('type', 1);
		$dataImg = $_FILES["multipleFile"];
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Data not exists!";


		switch( $type ){
			case 1 ://Img device
				$aryData = $this->uploadImageToFolderOnce($dataImg, $id_hiden, Define::FOLDER_DEVICE, $type);
				break;
			case 2 ://Img person
				$aryData = $this->uploadImageToFolderOnce($dataImg, $id_hiden, Define::FOLDER_PERSONAL, $type);
				break;
			default:
				break;
		}
		echo json_encode($aryData);
		exit();
	}
	function uploadImageToFolderOnce($dataImg, $id_hiden, $folder, $type){
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Upload Img!";
		$item_id = 0;

		if (!empty($dataImg)) {
			if($id_hiden == 0){

				switch($type){
					case 1://Img Banner
						$new_row['device_status'] = Define::IMAGE_ERROR;
						$item_id = Device::createItem($new_row);
						break;

					case 2://Img person
						$new_row['person_status'] = Define::STATUS_HIDE;
						$item_id = Person::createItem($new_row);
						break;
					default:
						break;
				}
			}elseif($id_hiden > 0){
				$item_id = $id_hiden;
			}
			if($item_id > 0){
				$aryError = $tmpImg = array();

				$file_name = Upload::uploadFile('multipleFile',
					$_file_ext = 'jpg,jpeg,png,gif',
					$_max_file_size = 10*1024*1024,
					$_folder = $folder,
					$type_json=0);

				if($file_name != '' && empty($aryError)) {
					$tmpImg['name_img'] = $file_name;
					$tmpImg['id_key'] = rand(10000, 99999);

					switch($type){
						case 1://Img device
							$result = Device::getItemById($item_id);
							if($result != null){
								$path_image = ($result->device_image != '') ? $result->device_image : '';
								if($path_image != ''){
									$folder_image = 'uploads/'.$folder;
                                    FunctionLib::unlinkFileAndFolder($path_image, $folder_image, 0, 0);
									$folder_thumb = 'uploads/thumbs/'.$folder;
                                    FunctionLib::unlinkFileAndFolder($path_image, $folder_thumb, 0, 0);
								}

								$path_image = $file_name;
								$new_row['device_image'] = $path_image;
                                Device::updateItem($item_id, $new_row);
								$arrSize = Define::$arrSizeImage;
								if(isset($arrSize[Define::sizeImage_300])){
                                    $size = $arrSize[Define::sizeImage_300];
									if(!empty($size)){
										$x = (int)$size['w'];
										$y = (int)$size['h'];
									}else{
										$x = $y = Define::sizeImage_300;
									}
								}
								$url_thumb = ThumbImg::thumbBaseNormal(Define::FOLDER_DEVICE, $file_name, $x, $y, '', true, true);
								$tmpImg['src'] = $url_thumb;
							}
							break;

						case 2://Img Person
							$result = Person::find($item_id);
							if($result != null){
								$path_image = ($result->person_avatar != '') ? $result->person_avatar : '';
								if($path_image != ''){
									$folder_image = 'uploads/'.$folder;
                                    FunctionLib::unlinkFileAndFolder($path_image, $folder_image, 0, 0);
									$folder_thumb = 'uploads/thumbs/'.$folder;
                                    FunctionLib::unlinkFileAndFolder($path_image, $folder_thumb, 0, 0);
								}

								$path_image = $file_name;
								$new_row['person_avatar'] = $path_image;
                                Person::updateItem($item_id, $new_row);
								$arrSize = Define::$arrSizeImage;
                                $x = $y = Define::sizeImage_240;
								if(isset($arrSize[Define::sizeImage_240])){
                                    $size = $arrSize[Define::sizeImage_240];
									if(!empty($size)){
										$x = (int)$size['w'];
										$y = (int)$size['h'];
									}
								}
								$url_thumb = ThumbImg::thumbBaseNormal(Define::FOLDER_PERSONAL, $file_name, $x, $y, '', true, true);
								$tmpImg['src'] = $url_thumb;
							}
							break;
						default:
							break;
					}

					$aryData['intIsOK'] = 1;
					$aryData['id_item'] = FunctionLib::inputId($item_id);
					$aryData['info'] = $tmpImg;
				}
			}

		}
		return $aryData;
	}
	function uploadImageToFolder($dataImg, $id_hiden, $folder, $type){

		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Upload Img!";
		$item_id = 0;

		if (!empty($dataImg)) {

			if($id_hiden == 0){
				switch($type){
					case 2://Img News
						$new_row['news_created'] = time();
						$new_row['news_status'] = CGlobal::IMAGE_ERROR;
						$item_id = News::addData($new_row);
						break;
					default:
						break;
				}
			}elseif($id_hiden > 0){
				$item_id = $id_hiden;
			}

			if($item_id > 0){
				$aryError = $tmpImg = array();

				$file_name = Upload::uploadFile('multipleFile',
					$_file_ext = 'jpg,jpeg,png,gif',
					$_max_file_size = 10*1024*1024,
					$_folder = $folder.'/'.$item_id,
					$type_json=0);

				if ($file_name != '' && empty($aryError)){

					$tmpImg['name_img'] = $file_name;
					$tmpImg['id_key'] = rand(10000, 99999);

					switch($type){
						case 2://Img News
							$result = News::getById($item_id);
							if($result != null){
								$aryTempImages = ($result->news_image_other != '') ? unserialize($result->news_image_other) : array();

								$aryTempImages[] = $file_name;

								$new_row['news_image_other'] = serialize($aryTempImages);
								News::updateData($item_id, $new_row);

								$path_image = $file_name;

								$arrSize = CGlobal::$arrSizeImg;
								if(isset($arrSize['4'])){
									$size = explode('x', $arrSize['4']);
									if(!empty($size)){
										$x = (int)$size[0];
										$y = (int)$size[1];
									}else{
										$x = $y = 400;
									}
								}
								$url_thumb = ThumbImg::thumbBaseNormal(CGlobal::FOLDER_NEWS, $item_id, $file_name, $x, $y, '', true, true);
								$tmpImg['src'] = $url_thumb;
							}
							break;
						default:
							break;
					}

					$aryData['intIsOK'] = 1;
					$aryData['id_item'] = $item_id;
					$aryData['info'] = $tmpImg;
				}
			}
		}
		return $aryData;
	}
	//Delte Img
	function remove_image(){
		$id =  (int)FunctionLib::outputId(Request::get('id', 0));
		$nameImage = Request::get('nameImage', '');
		$type = (int)Request::get('type', 1);

		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Remove Img!";
		$aryData['nameImage'] = $nameImage;

		switch( $type ){
			case 1://Img device
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case 2://Img Person
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_MAIL://File mail
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_DOCUMENT://File document
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_LUONG://File salary
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_PHUCAP://File salary
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
            case Define::FILE_TYPE_KHENTHUONG:
            case Define::FILE_TYPE_DANHHIEU :
            case Define::FILE_TYPE_KYLUAT :
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_RETIREMENT:
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_QUITJOB:
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_CONTRACTS:
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			case Define::FILE_TYPE_JOB_ASSIGNMENT:
				if($id > 0 && $nameImage != ''){
					$delete_action = $this->delete_image_item($id, $nameImage, $type);
					if($delete_action == 1){
						$aryData['intIsOK'] = 1;
						$aryData['msg'] = "Remove Img!";
					}
				}
				break;
			default:
				$folder_image = '';
				break;
		}
		echo json_encode($aryData);
		exit();
	}
	function delete_image_item($id, $nameImage, $type){
		$delete_action = 0;
		$aryImages  = array();
		$folder_image =  $folder_thumb = '';
		//get img in DB and remove it
		switch( $type ){
			case 1://Img device
				$result = Device::getItemById($id);
				if($result != null){
					$aryImages = array($result->device_image);
				}
				$folder_image = 'uploads/'.Define::FOLDER_DEVICE;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_DEVICE;
				break;
			case 2://Img Person
				$result = Person::find($id);
				if($result != null){
					$aryImages = array($result->person_avatar);
				}
				$folder_image = 'uploads/'.Define::FOLDER_PERSONAL;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_PERSONAL;
				break;
			case Define::FILE_TYPE_MAIL://File mail
				$result = HrMail::getItemById($id);
				if($result != null){
					$aryImages = unserialize($result->hr_mail_files);
				}
				$folder_image = 'uploads/'.Define::FOLDER_MAIL;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_MAIL;
				break;
			case Define::FILE_TYPE_DOCUMENT://File document
				$result = HrDocument::getItemById($id);
				if($result != null){
					$aryImages = unserialize($result->hr_document_files);
				}
				$folder_image = 'uploads/'.Define::FOLDER_DOCUMENT;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_DOCUMENT;
				break;
			case Define::FILE_TYPE_LUONG://File salary
				$result = Salary::find($id);
				if($result != null){
					$aryImages = unserialize($result->salary_file_attach);
				}
				$folder_image = 'uploads/'.Define::FOLDER_SALARY;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_SALARY;
				break;
			case Define::FILE_TYPE_PHUCAP://File salary
				$result = Allowance::find($id);
				if($result != null){
					$aryImages = unserialize($result->allowance_file_attack);
				}
				$folder_image = 'uploads/'.Define::FOLDER_ALLOWANCE;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_ALLOWANCE;
				break;
			case Define::FILE_TYPE_KHENTHUONG:
            case Define::FILE_TYPE_DANHHIEU :
            case Define::FILE_TYPE_KYLUAT :
				$result = Bonus::find($id);
				if($result != null){
					$aryImages = unserialize($result->bonus_file_attack);
				}
				$folder_image = 'uploads/'.Define::FOLDER_BONUS;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_BONUS;
				break;
			case Define::FILE_TYPE_RETIREMENT :
				$result = Retirement::find($id);
				if($result != null){
					$aryImages = unserialize($result->retirement_file_attack);
				}
				$folder_image = 'uploads/'.Define::FOLDER_RETIREMENT;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_RETIREMENT;
				break;
			case Define::FILE_TYPE_QUITJOB :
				$result = QuitJob::find($id);
				if($result != null){
					$aryImages = unserialize($result->quit_job_file_attack);
				}
				$folder_image = 'uploads/'.Define::FOLDER_QUITJOB;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_QUITJOB;
				break;
			case Define::FILE_TYPE_CONTRACTS :
				$result = HrContracts::find($id);
				if($result != null){
					$aryImages = unserialize($result->contracts_file_attack);
				}
				$folder_image = 'uploads/'.Define::FOLDER_CONTRACTS;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_CONTRACTS;
				break;
			case Define::FILE_TYPE_JOB_ASSIGNMENT :
				$result = JobAssignment::find($id);
				if($result != null){
					$aryImages = unserialize($result->job_assignment_file_attack);
				}
				$folder_image = 'uploads/'.Define::FOLDER_JOB_ASSIGNMENT;
				$folder_thumb = 'uploads/thumbs/'.Define::FOLDER_JOB_ASSIGNMENT;
				break;
			default:
				$folder_image = '';
				$folder_thumb = '';
				break;
		}

		if(is_array($aryImages) && count($aryImages) > 0) {
			foreach ($aryImages as $k => $v) {
				if($v === $nameImage){
					if($type == 1){//Img device
                        FunctionLib::unlinkFileAndFolder($nameImage, $folder_image, true, 0);
						FunctionLib::unlinkFileAndFolder($nameImage, $folder_thumb, true, 0);
					}else{
                        FunctionLib::unlinkFileAndFolder($nameImage, $folder_image, true, $id);
                        FunctionLib::unlinkFileAndFolder($nameImage, $folder_thumb, true, $id);
					}

					unset($aryImages[$k]);
					if(!empty($aryImages)){
						$aryImages = array_values($aryImages);
						$aryImages = serialize($aryImages);
					}else{
						$aryImages = '';
					}

					switch( $type ){
						case 1://Img device
							$new_row['device_image'] = $aryImages;
							Device::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_MAIL://File mail
							$new_row['hr_mail_files'] = $aryImages;
							HrMail::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_DOCUMENT://File document
							$new_row['hr_document_files'] = $aryImages;
							HrDocument::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_LUONG:
							$new_row['salary_file_attach'] = $aryImages;
							Salary::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_PHUCAP:
							$new_row['allowance_file_attack'] = $aryImages;
							Allowance::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_KHENTHUONG:
                        case Define::FILE_TYPE_DANHHIEU :
                        case Define::FILE_TYPE_KYLUAT :
							$new_row['bonus_file_attack'] = $aryImages;
							Bonus::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_RETIREMENT :
							$new_row['retirement_file_attack'] = $aryImages;
							Retirement::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_QUITJOB :
							$new_row['quit_job_file_attack'] = $aryImages;
							QuitJob::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_CONTRACTS :
							$new_row['contracts_file_attack'] = $aryImages;
							HrContracts::updateItem($id, $new_row);
							break;
						case Define::FILE_TYPE_JOB_ASSIGNMENT :
							$new_row['job_assignment_file_attack'] = $aryImages;
							JobAssignment::updateItem($id, $new_row);
							break;
						default:
							$folder_image = '';
							break;
					}

					$delete_action = 1;
					break;
				}
			}
		}

		//xoa khi chua update vao db, anh moi up load
		if($delete_action == 1){
			FunctionLib::unlinkFileAndFolder($nameImage, $folder_image, true, $id);
			$delete_action = 1;
		}
		return $delete_action;
	}


	//Get Img Content
	function get_image_insert_content(){

		$id_hiden = (int)Request::get('id_hiden', 0);
		$type = (int)Request::get('type', 1);
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Data not exists!";

		if($id_hiden > 0){
			switch( $type ){
				case 2://Img News
					$aryData = $this->getImgContent($id_hiden, CGlobal::FOLDER_NEWS, $type);
					break;
				default:
					break;
			}
		}
		echo json_encode($aryData);
		exit();
	}
	function getImgContent($id_hiden, $folder, $type){

		$aryImages = array();
		$aryData = array();

		switch( $type ){
			case 2://Img device
				$result = News::getById($id_hiden);
				if($result != null){
					$aryImages = ($result->news_image_other != '') ? unserialize($result->news_image_other) : array();
				}
				break;
			default:
				break;
		}

		if(is_array($aryImages) && !empty($aryImages)){
			foreach($aryImages as $k => $item){
				$aryData['item'][$k]['large'] = ThumbImg::thumbBaseNormal($folder, $id_hiden, $item, 800, 800, '', true, true);
				$aryData['item'][$k]['small'] = ThumbImg::thumbBaseNormal($folder, $id_hiden, $item, 400, 400, '', true, true);
			}
		}

		$aryData['intIsOK'] = 1;
		$aryData['msg'] = "Data exists!";
		return $aryData;
	}

	//Upload document
	function upload_ext() {
		$id_hiden =  Request::get('id', 0);
		$id_hiden = FunctionLib::outputId($id_hiden);

		$id_hiden_person =  Request::get('id_hiden_person', 0);
		$id_hiden_person = FunctionLib::outputId($id_hiden_person);

		$type = Request::get('type', 1);
		$dataFile = $_FILES["multipleFile"];
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Data not exists!";

		switch( $type ){
			case Define::FILE_TYPE_MAIL ://File Mail
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_MAIL, $type);
				break;
			case Define::FILE_TYPE_DOCUMENT ://File Document
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_DOCUMENT, $type);
				break;
			case Define::FILE_TYPE_LUONG ://File attach của Lương
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_SALARY, $type);
				break;
			case Define::FILE_TYPE_PHUCAP :
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_ALLOWANCE, $type);
				break;
			case Define::FILE_TYPE_KHENTHUONG ://File 12: khen thương, 13:danh hiệu, 14: kỉ luật
			case Define::FILE_TYPE_DANHHIEU :
			case Define::FILE_TYPE_KYLUAT :
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_BONUS, $type);
				break;
			case Define::FILE_TYPE_RETIREMENT:
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_RETIREMENT, $type, $id_hiden_person);
				break;
			case Define::FILE_TYPE_QUITJOB:
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_QUITJOB, $type, $id_hiden_person);
				break;
			case Define::FILE_TYPE_CONTRACTS:
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_CONTRACTS, $type, $id_hiden_person);
				break;
			case Define::FILE_TYPE_JOB_ASSIGNMENT:
				$aryData = $this->uploadDocumentToFolder($dataFile, $id_hiden, Define::FOLDER_JOB_ASSIGNMENT, $type, $id_hiden_person);
				break;
			default:
				break;
		}
		echo json_encode($aryData);
		exit();
	}
	function uploadDocumentToFolder($dataImg, $id_hiden, $folder, $type, $id_hiden_person=0){
		$aryData = array();
		$aryData['intIsOK'] = -1;
		$aryData['msg'] = "Upload file!";
		$item_id = 0;
		if (!empty($dataImg)) {
			if($id_hiden == 0){

				switch($type){
					case Define::FILE_TYPE_MAIL://File document
						$new_row['hr_mail_created'] = time();
						$new_row['hr_mail_status'] = Define::IMAGE_ERROR;
						$item_id = HrMail::createItem($new_row);
						break;
					case Define::FILE_TYPE_DOCUMENT://File document
						$new_row['hr_document_created'] = time();
						$new_row['hr_document_status'] = Define::IMAGE_ERROR;
						$item_id = HrDocument::createItem($new_row);
						break;
					case Define::FILE_TYPE_LUONG://File lương
						$new_row['salary_note'] = '';
						$new_row['salary_person_id'] = $id_hiden_person;
						$item_id = Salary::createItem($new_row);
						break;
					case Define::FILE_TYPE_PHUCAP:
						$new_row['allowance_note'] = '';
						$new_row['allowance_person_id'] = $id_hiden_person;
						$item_id = Allowance::createItem($new_row);
						break;
					case Define::FILE_TYPE_KHENTHUONG:
                    case Define::FILE_TYPE_DANHHIEU :
                    case Define::FILE_TYPE_KYLUAT :
						$new_row['bonus_note'] = '';
						$item_id = Bonus::createItem($new_row);
						break;
					case Define::FILE_TYPE_RETIREMENT:
						$new_row['retirement_note'] = '';
						$new_row['retirement_person_id'] = $id_hiden_person;
						$item_id = Retirement::createItem($new_row);
						break;
					case Define::FILE_TYPE_QUITJOB:
						$new_row['quit_job_note'] = '';
						$new_row['quit_job_person_id'] = $id_hiden_person;
						$new_row['quit_job_type'] = Define::QUITJOB_CHUYEN_CONGTAC;
						$item_id = QuitJob::createItem($new_row);
						break;
					case Define::FILE_TYPE_CONTRACTS:
						$new_row['contracts_creater_time'] = time();
						$new_row['contracts_person_id'] = $id_hiden_person;
						$item_id = HrContracts::createItem($new_row);
						break;
					case Define::FILE_TYPE_JOB_ASSIGNMENT:
						$new_row['job_assignment_date_creater'] = time();
						$new_row['job_assignment_person_id'] = $id_hiden_person;
						$item_id = JobAssignment::createItem($new_row);
						break;
					default:
						break;
				}
			}elseif($id_hiden > 0){
				$item_id = $id_hiden;
			}

			if($item_id > 0){
				$aryError = $tmpImg = array();
				$file_name = Upload::uploadFile('multipleFile',
					$_file_ext = 'jpg,jpeg,png,gif,txt,ppt,pptx,xls,xlsx,doc,docx,pdf,rar,zip,tar,mp4,flv,avi,3gp,mov',
					$_max_file_size = 50*1024*1024,
					$_folder = $folder.'/'.$item_id,
					$type_json=0);
				if($file_name != '' && empty($aryError)) {
					$tmpImg['name_file'] = $file_name;
					$tmpImg['id_key'] = rand(10000, 99999);

					switch($type){
						case Define::FILE_TYPE_MAIL://File Mail
							$result = HrMail::getItemById($item_id);
							if($result != null){
								$arr_file = ($result->hr_mail_files != '') ? unserialize($result->hr_mail_files) : array();
								$arr_file[] = $file_name;
								$new_row['hr_mail_files'] = serialize($arr_file);
								HrMail::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;
						case Define::FILE_TYPE_DOCUMENT://File Document
							$result = HrDocument::getItemById($item_id);
							if($result != null){
								$arr_file = ($result->hr_document_files != '') ? unserialize($result->hr_document_files) : array();
								$arr_file[] = $file_name;
								$new_row['hr_document_files'] = serialize($arr_file);
								HrDocument::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;

						case Define::FILE_TYPE_LUONG://File lương
							$result = Salary::find($item_id);
							if($result != null){
								$arr_file = ($result->salary_file_attach != '') ? unserialize($result->salary_file_attach) : array();
								$arr_file[] = $file_name;
								$new_row['salary_file_attach'] = serialize($arr_file);
								$new_row['salary_person_id'] = $id_hiden_person;
                                Salary::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;

						case Define::FILE_TYPE_PHUCAP://File lương
							$result = Allowance::find($item_id);
							if($result != null){
								$arr_file = ($result->allowance_file_attack != '') ? unserialize($result->allowance_file_attack) : array();
								$arr_file[] = $file_name;
								$new_row['allowance_file_attack'] = serialize($arr_file);
								$new_row['allowance_person_id'] = $id_hiden_person;
                                Allowance::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;
						case Define::FILE_TYPE_KHENTHUONG:
                        case Define::FILE_TYPE_DANHHIEU :
                        case Define::FILE_TYPE_KYLUAT :
							$result = Bonus::find($item_id);
							if($result != null){
								$arr_file = ($result->bonus_file_attack != '') ? unserialize($result->bonus_file_attack) : array();
								$arr_file[] = $file_name;
								$new_row['bonus_file_attack'] = serialize($arr_file);
                                Bonus::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;;
									}
								}
							}
							break;

						case Define::FILE_TYPE_RETIREMENT:
							$result = Retirement::find($item_id);
							if($result != null){
								$arr_file = ($result->retirement_file_attack != '') ? unserialize($result->retirement_file_attack) : array();
								$arr_file[] = $file_name;
								$new_row['retirement_file_attack'] = serialize($arr_file);
								$new_row['retirement_person_id'] = $id_hiden_person;

								Retirement::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;
									}
								}
							}
							break;
						case Define::FILE_TYPE_QUITJOB:
							$result = QuitJob::find($item_id);
							if($result != null){
								$arr_file = ($result->quit_job_file_attack != '') ? unserialize($result->quit_job_file_attack) : array();
								$arr_file[] = $file_name;
								$new_row['quit_job_file_attack'] = serialize($arr_file);
								$new_row['quit_job_person_id'] = $id_hiden_person;
								$new_row['quit_job_type'] = Define::QUITJOB_CHUYEN_CONGTAC;
								QuitJob::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;
									}
								}
							}
							break;
						case Define::FILE_TYPE_CONTRACTS:
							$result = HrContracts::find($item_id);
							if($result != null){
								$arr_file = ($result->contracts_file_attack != '') ? unserialize($result->contracts_file_attack) : array();
								$arr_file[] = $file_name;
								$new_row['contracts_file_attack'] = serialize($arr_file);
								$new_row['contracts_person_id'] = $id_hiden_person;

								HrContracts::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;
									}
								}
							}
							break;
						case Define::FILE_TYPE_JOB_ASSIGNMENT:
							$result = JobAssignment::find($item_id);
							if($result != null){
								$arr_file = ($result->job_assignment_file_attack != '') ? unserialize($result->job_assignment_file_attack) : array();
								$arr_file[] = $file_name;
								$new_row['job_assignment_file_attack'] = serialize($arr_file);
								$new_row['job_assignment_person_id'] = $id_hiden_person;

								JobAssignment::updateItem($item_id, $new_row);
								$url_file = Config::get('config.WEB_ROOT').'uploads/'.$folder.'/'.$item_id.'/'.$file_name;
								$tmpImg['src'] = $url_file;
								foreach($arr_file as $_k=>$_v){
									if($_v == $file_name){
										$tmpImg['name_key'] = $_k;
									}
								}
							}
							break;
						default:
							break;
					}

					$aryData['intIsOK'] = 1;
					$aryData['id_item'] = FunctionLib::inputId($item_id);
					$aryData['info'] = $tmpImg;
				}
			}
		}
		return $aryData;
	}
}