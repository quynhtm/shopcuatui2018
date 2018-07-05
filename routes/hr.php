<?php

/*thông tin Department */
Route::match(['GET','POST'],'department/view', array('as' => 'hr.departmentView','uses' => HResources.'\HrDepartmentController@view'));
Route::get('department/edit/{id?}',array('as' => 'hr.departmentEdit','uses' => HResources.'\HrDepartmentController@getItem'));
Route::post('department/edit/{id?}', array('as' => 'hr.departmentEdit','uses' => HResources.'\HrDepartmentController@postItem'));
Route::get('department/deleteDepartment', array('as' => 'hr.deleteDepartment','uses' => HResources.'\HrDepartmentController@deleteDepartment'));

/*Cau hinh Department */
Route::match(['GET','POST'],'departmentconfig/view', array('as' => 'hr.departmentConfigView','uses' => HResources.'\DepartmentConfigController@view'));
Route::get('departmentconfig/edit/{id?}',array('as' => 'hr.departmentConfigEdit','uses' => HResources.'\DepartmentConfigController@getItem'));
Route::post('departmentconfig/edit/{id?}', array('as' => 'hr.departmentConfigEdit','uses' => HResources.'\DepartmentConfigController@postItem'));
Route::get('departmentconfig/deleteDepartmentConfig', array('as' => 'hr.deleteDepartmentConfig','uses' => HResources.'\DepartmentConfigController@deleteDepartmentConfig'));


/*thông tin Nhân sự */
Route::match(['GET','POST'],'personnel/view', array('as' => 'hr.personnelView','uses' => HResources.'\PersonController@view'));
Route::get('personnel/detail/{id?}', array('as' => 'hr.personnelDetail','uses' => HResources.'\PersonController@getDetail'));
Route::get('personnel/infoPerson/{id?}', array('as' => 'hr.personnelInfoPerson','uses' => HResources.'\PersonController@getInfoPerson'));
Route::get('personnel/edit/{id?}', array('as' => 'hr.personnelEdit','uses' => HResources.'\PersonController@getItem'));
Route::post('personnel/edit/{id?}', array('as' => 'hr.personnelEdit','uses' => HResources.'\PersonController@postItem'));
Route::get('personnel/editAccount/{id?}', array('as' => 'hr.personnelEditAccount','uses' => HResources.'\PersonController@getPersonWithAccount'));
Route::post('personnel/editAccount/{id?}', array('as' => 'hr.personnelEditAccount','uses' => HResources.'\PersonController@postPersonWithAccount'));
Route::get('personnel/personStatusDelete/{id?}', array('as' => 'hr.personnelStatusDelete','uses' => HResources.'\PersonController@statusDeletePerson'));
Route::post('personnel/personDelete/{id?}', array('as' => 'hr.personnelDelete','uses' => HResources.'\PersonController@deletePerson'));
Route::get('personnel/viewInfoPersonal', array('as' => 'hr.personnelInfo','uses' => HResources.'\PersonController@viewInfoPersonal'));

//thông tin nhân sự mở rộng
Route::get('personExtend/edit/{person_id?}', array('as' => 'hr.personnelExtendEdit','uses' => HResources.'\PersonExtendController@getItem'));
Route::post('personExtend/edit/{person_id?}', array('as' => 'hr.personnelExtendEdit','uses' => HResources.'\PersonExtendController@postItem'));

/*Thông tin hợp đồng lao động*/
Route::get('infoPerson/viewContracts/{person_id?}', array('as' => 'hr.viewContracts','uses' => HResources.'\InfoPersonController@viewContracts'));
Route::get('infoPerson/EditContracts', array('as' => 'hr.EditContracts','uses' => HResources.'\InfoPersonController@editContracts'));
Route::post('infoPerson/PostContracts', array('as' => 'hr.PostContracts','uses' => HResources.'\InfoPersonController@postContracts'));
Route::post('infoPerson/DeleteContracts', array('as' => 'hr.DeleteContracts','uses' => HResources.'\InfoPersonController@deleteContracts'));

/*Thông tin khen thuong*/
Route::get('bonusPerson/viewBonus/{person_id?}', array('as' => 'hr.viewBonus','uses' => HResources.'\BonusPersonController@viewBonus'));
Route::get('bonusPerson/editBonus', array('as' => 'hr.editBonus','uses' => HResources.'\BonusPersonController@editBonus'));
Route::post('bonusPerson/postBonus', array('as' => 'hr.postBonus','uses' => HResources.'\BonusPersonController@postBonus'));
Route::post('bonusPerson/deleteBonus', array('as' => 'hr.deleteBonus','uses' => HResources.'\BonusPersonController@deleteBonus'));

/*Thông tin lương và phụ cấp*/
Route::get('salaryAllowance/viewSalaryAllowance/{person_id?}', array('as' => 'hr.viewSalaryAllowance','uses' => HResources.'\SalaryAllowanceController@viewSalaryAllowance'));
Route::get('salaryAllowance/editSalary', array('as' => 'hr.editSalary','uses' => HResources.'\SalaryAllowanceController@editSalary'));
Route::post('salaryAllowance/postSalary', array('as' => 'hr.postSalary','uses' => HResources.'\SalaryAllowanceController@postSalary'));
Route::post('salaryAllowance/deleteSalary', array('as' => 'hr.deleteSalary','uses' => HResources.'\SalaryAllowanceController@deleteSalary'));
Route::get('salaryAllowance/getInfoSalary/{id?}', array('as' => 'hr.getInfoSalary','uses' => HResources.'\SalaryAllowanceController@getInfoSalary'));
Route::get('salaryAllowance/editAllowance', array('as' => 'hr.editAllowance','uses' => HResources.'\SalaryAllowanceController@editAllowance'));
Route::post('salaryAllowance/postAllowance', array('as' => 'hr.postAllowance','uses' => HResources.'\SalaryAllowanceController@postAllowance'));
Route::post('salaryAllowance/deleteAllowance', array('as' => 'hr.deleteAllowance','uses' => HResources.'\SalaryAllowanceController@deleteAllowance'));

/*Hộ chiếu chưng minh thư*/
Route::get('passport/edit/{person_id?}', array('as' => 'hr.passportEdit','uses' => HResources.'\PassportController@getItem'));
Route::post('passport/edit/{person_id?}', array('as' => 'hr.passportEdit','uses' => HResources.'\PassportController@postItem'));

/*Nghỉ việc, chuyển công tác, chuyển phòng ban*/
Route::get('quitJob/editJob/{person_id?}', array('as' => 'hr.quitJobEdit','uses' => HResources.'\QuitJobController@getQuitJob'));
Route::post('quitJob/editJob/{person_id?}', array('as' => 'hr.quitJobEdit','uses' => HResources.'\QuitJobController@postQuitJob'));
Route::get('quitJob/editMove/{person_id?}', array('as' => 'hr.quitJobEditMove','uses' => HResources.'\QuitJobController@getJobMove'));
Route::post('quitJob/editMove/{person_id?}', array('as' => 'hr.quitJobEditMove','uses' => HResources.'\QuitJobController@postJobMove'));
Route::get('quitJob/editMoveDepart/{person_id?}', array('as' => 'hr.quitJobEditMoveDepart','uses' => HResources.'\QuitJobController@getJobMoveDepart'));
Route::post('quitJob/editMoveDepart/{person_id?}', array('as' => 'hr.quitJobEditMoveDepart','uses' => HResources.'\QuitJobController@postJobMoveDepart'));

/*Thiết lập thời gian nghỉ hưu*/
Route::get('retirement/edit/{person_id?}', array('as' => 'hr.retirementEdit','uses' => HResources.'\RetirementController@getItem'));
Route::post('retirement/edit/{person_id?}', array('as' => 'hr.retirementEdit','uses' => HResources.'\RetirementController@postItem'));
Route::get('retirement/editTime/{person_id?}', array('as' => 'hr.retirementEditTime','uses' => HResources.'\RetirementController@getItemTime'));
Route::post('retirement/editTime/{person_id?}', array('as' => 'hr.retirementEditTime','uses' => HResources.'\RetirementController@postItemTime'));

/*Bổ nhiêm nhiêm chức vụ*/
Route::get('jobAssignment/viewJobAssignment/{person_id?}', array('as' => 'hr.viewJobAssignment','uses' => HResources.'\JobAssignmentController@viewJobAssignment'));
Route::get('jobAssignment/editJobAssignment', array('as' => 'hr.editJobAssignment','uses' => HResources.'\JobAssignmentController@editJobAssignment'));
Route::post('jobAssignment/postJobAssignment', array('as' => 'hr.postJobAssignment','uses' => HResources.'\JobAssignmentController@postJobAssignment'));
Route::post('jobAssignment/deleteJobAssignment', array('as' => 'hr.deleteJobAssignment','uses' => HResources.'\JobAssignmentController@deleteJobAssignment'));
Route::post('jobAssignment/updateStatus', array('as' => 'hr.updateStatus','uses' => HResources.'\JobAssignmentController@updateStatus'));

/*
 * Thông tin đào tạo, công tác: lý lịch 2C
 */
Route::get('curriculumVitaePerson/viewCurriculumVitae/{person_id?}', array('as' => 'hr.viewCurriculumVitae','uses' => HResources.'\CurriculumVitaePersonController@viewCurriculumVitae'));
/*Quan hệ gia đình*/
Route::get('curriculumVitaePerson/editFamily', array('as' => 'hr.editFamily','uses' => HResources.'\CurriculumVitaePersonController@editFamily'));
Route::post('curriculumVitaePerson/postFamily', array('as' => 'hr.postFamily','uses' => HResources.'\CurriculumVitaePersonController@postFamily'));
Route::post('curriculumVitaePerson/deleteFamily', array('as' => 'hr.deleteFamily','uses' => HResources.'\CurriculumVitaePersonController@deleteFamily'));
/*Quan ly dao tạo, học tập*/
Route::get('curriculumVitaePerson/editStudy', array('as' => 'hr.editStudy','uses' => HResources.'\CurriculumVitaePersonController@editStudy'));
Route::post('curriculumVitaePerson/postStudy', array('as' => 'hr.postStudy','uses' => HResources.'\CurriculumVitaePersonController@postStudy'));
Route::post('curriculumVitaePerson/deleteStudy', array('as' => 'hr.deleteStudy','uses' => HResources.'\CurriculumVitaePersonController@deleteStudy'));
Route::post('curriculumVitaePerson/changeValueViewCurriculumVitae', array('as' => 'hr.changeValueViewCurriculumVitae','uses' => HResources.'\CurriculumVitaePersonController@changeValueViewCurriculumVitae'));


Route::get('infoPerson/viewInfoPersonOther/{person_id?}', array('as' => 'hr.viewInfoPersonOther','uses' => HResources.'\InfoPersonController@viewInfoPersonOther'));
Route::get('infoPerson/viewTransferWork/{person_id?}', array('as' => 'hr.viewTransferWork','uses' => HResources.'\InfoPersonController@viewTransferWork'));
Route::get('infoPerson/viewTransferDepartment/{person_id?}', array('as' => 'hr.viewTransferDepartment','uses' => HResources.'\InfoPersonController@viewTransferDepartment'));
/*Thông tin tạo tài khoản từ person*/
Route::get('infoPerson/getInfoPerson/{person_id?}', array('as' => 'hr.getInfoPerson','uses' => HResources.'\InfoPersonController@getInfoPerson'));
Route::post('infoPerson/getInfoPerson/{person_id?}', array('as' => 'hr.getInfoPerson','uses' => HResources.'\InfoPersonController@postInfoPerson'));

//List thong tin chung cac nhan su
Route::match(['GET','POST'],'personList/viewBirthday', array('as' => 'hr.viewBirthday','uses' => HResources.'\PersonListController@viewBirthday'));
Route::match(['GET','POST'],'personList/viewQuitJob', array('as' => 'hr.viewQuitJob','uses' => HResources.'\PersonListController@viewQuitJob'));
Route::match(['GET','POST'],'personList/viewMoveJob', array('as' => 'hr.viewMoveJob','uses' => HResources.'\PersonListController@viewMoveJob'));
Route::match(['GET','POST'],'personList/viewRetired', array('as' => 'hr.viewRetired','uses' => HResources.'\PersonListController@viewRetired'));
Route::match(['GET','POST'],'personList/viewPreparingRetirement', array('as' => 'hr.viewPreparingRetirement','uses' => HResources.'\PersonListController@viewPreparingRetirement'));
Route::match(['GET','POST'],'personList/viewDealineContract', array('as' => 'hr.viewDealineContract','uses' => HResources.'\PersonListController@viewDealineContract'));
Route::match(['GET','POST'],'personList/viewDealineSalary', array('as' => 'hr.viewDealineSalary','uses' => HResources.'\PersonListController@viewDealineSalary'));
Route::match(['GET','POST'],'personList/viewDeletePerson', array('as' => 'hr.viewDeletePerson','uses' => HResources.'\PersonListController@viewDeletePerson'));
Route::match(['GET','POST'],'personList/viewDangVienPerson', array('as' => 'hr.viewDangVienPerson','uses' => HResources.'\PersonListController@viewDangVienPerson'));

/*Định nghĩa chung*/
Route::match(['GET','POST'],'defined/view',array('as' => 'hr.definedView','uses' => HResources.'\HrDefinedController@view'));
Route::post('defined/edit/{id?}',array('as' => 'hr.definedEdit','uses' => HResources.'\HrDefinedController@postItem'));
Route::get('defined/deleteDefined',array('as' => 'hr.deleteDefined','uses' => HResources.'\HrDefinedController@deleteDefined'));
Route::post('defined/ajaxLoadForm',array('as' => 'hr.loadForm','uses' => HResources.'\HrDefinedController@ajaxLoadForm'));
Route::post('defined/importDataToExcel',array('as' => 'hr.importDataToExcel','uses' => HResources.'\HrDefinedController@importDataToExcel'));


/*thông tin Device */
Route::match(['GET','POST'],'device/view', array('as' => 'hr.deviceView','uses' => HResources.'\DeviceController@view'));
Route::match(['GET','POST'],'device/viewDeviceUse', array('as' => 'hr.viewDeviceUse','uses' => HResources.'\DeviceController@viewDeviceUse'));
Route::match(['GET','POST'],'device/viewDeviceNotUse', array('as' => 'hr.viewDeviceNotUse','uses' => HResources.'\DeviceController@viewDeviceNotUse'));
Route::get('device/edit/{id?}',array('as' => 'hr.deviceEdit','uses' => HResources.'\DeviceController@getItem'));
Route::post('device/edit/{id?}', array('as' => 'hr.deviceEdit','uses' => HResources.'\DeviceController@postItem'));
Route::get('device/deleteDevice', array('as' => 'hr.deleteDevice','uses' => HResources.'\DeviceController@deleteDevice'));
Route::match(['GET','POST'],'device/export', array('as' => 'hr.exportDevice','uses' => HResources.'\DeviceController@exportDevice'));

/*thông tin Document: mail, document */
Route::match(['GET','POST'],'mail/viewsend', array('as' => 'hr.HrMailViewSend','uses' => HResources.'\HrMailController@viewSend'));
Route::match(['GET','POST'],'mail/viewget', array('as' => 'hr.HrMailViewGet','uses' => HResources.'\HrMailController@viewGet'));
Route::match(['GET','POST'],'mail/viewdraft', array('as' => 'hr.HrMailViewDraft','uses' => HResources.'\HrMailController@viewDraft'));
Route::get('mail/viewItemGet/{id?}',array('as' => 'hr.HrMailViewItemGet','uses' => HResources.'\HrMailController@viewItemGet'));
Route::get('mail/viewItemSend/{id?}',array('as' => 'hr.HrMailViewItemSend','uses' => HResources.'\HrMailController@viewItemSend'));
Route::get('mail/viewItemDraft/{id?}',array('as' => 'hr.HrMailViewItemDraft','uses' => HResources.'\HrMailController@viewItemDraft'));
Route::get('mail/ajaxItemForward',array('as' => 'hr.ajaxItemForward','uses' => HResources.'\HrMailController@ajaxItemForward'));
Route::get('mail/ajaxItemReply',array('as' => 'hr.ajaxItemReply','uses' => HResources.'\HrMailController@ajaxItemReply'));
Route::get('mail/edit/{id?}',array('as' => 'hr.HrMailEdit','uses' => HResources.'\HrMailController@getItem'));
Route::post('mail/edit/{id?}', array('as' => 'hr.HrMailEdit','uses' => HResources.'\HrMailController@postItem'));
Route::get('mail/deleteHrMail', array('as' => 'hr.deleteHrMail','uses' => HResources.'\HrMailController@deleteHrMail'));

Route::match(['GET','POST'],'document/viewsend', array('as' => 'hr.HrDocumentViewSend','uses' => HResources.'\HrDocumentController@viewSend'));
Route::match(['GET','POST'],'document/viewget', array('as' => 'hr.HrDocumentViewGet','uses' => HResources.'\HrDocumentController@viewGet'));
Route::match(['GET','POST'],'document/viewdraft', array('as' => 'hr.HrDocumentViewDraft','uses' => HResources.'\HrDocumentController@viewDraft'));
Route::get('document/viewItemGet/{id?}',array('as' => 'hr.HrDocumentViewItemGet','uses' => HResources.'\HrDocumentController@viewItemGet'));
Route::get('document/viewItemSend/{id?}',array('as' => 'hr.HrDocumentViewItemSend','uses' => HResources.'\HrDocumentController@viewItemSend'));
Route::get('document/viewItemDraft/{id?}',array('as' => 'hr.HrDocumentViewItemDraft','uses' => HResources.'\HrDocumentController@viewItemDraft'));
Route::get('document/ajaxItemForward',array('as' => 'hr.ajaxItemDocumentForward','uses' => HResources.'\HrDocumentController@ajaxItemForward'));
Route::get('document/ajaxItemReply',array('as' => 'hr.ajaxItemDocumentReply','uses' => HResources.'\HrDocumentController@ajaxItemReply'));
Route::get('document/edit/{id?}',array('as' => 'hr.HrDocumentEdit','uses' => HResources.'\HrDocumentController@getItem'));
Route::post('document/edit/{id?}', array('as' => 'hr.HrDocumentEdit','uses' => HResources.'\HrDocumentController@postItem'));
Route::get('document/deleteHrDocument', array('as' => 'hr.deleteHrDocument','uses' => HResources.'\HrDocumentController@deleteHrDocument'));

//Report
Route::match(['GET','POST'],'report/viewTienLuongCongChuc', array('as' => 'hr.viewTienLuongCongChuc','uses' => HResources.'\ReportController@viewTienLuongCongChuc'));
Route::match(['GET','POST'],'report/viewLuongDetailPerson', array('as' => 'hr.viewLuongDetailPerson','uses' => HResources.'\ReportController@viewLuongDetailPerson'));
Route::match(['GET','POST'],'report/exportTienLuongCongChuc', array('as' => 'hr.exportTienLuongCongChuc','uses' => HResources.'\ReportController@exportTienLuongCongChuc'));


Route::match(['GET','POST'],'staff/view', array('as' => 'hr.HrstaffView','uses' => HResources.'\HrStaffController@view'));

//Thang bang luong
Route::post('wageStepConfig/ajaxGetOption', array('as' => 'admin.wageStepConfig','uses' => HResources.'\HrWageStepConfigController@ajaxGetOption'));//ajax
Route::match(['GET','POST'],'wage-step-config/view', array('as' => 'hr.wageStepConfigView','uses' => HResources.'\HrWageStepConfigController@view'));
Route::post('wage-step-config/edit/{id?}',array('as' => 'hr.wageStepConfigEdit','uses' => HResources.'\HrWageStepConfigController@postItem'));
Route::get('wage-step-config/deleteWageStepConfig', array('as' => 'hr.deleteWageStepConfig','uses' => HResources.'\HrWageStepConfigController@deleteWageStepConfig'));
Route::post('wage-step-config/ajaxLoadForm',array('as' => 'hr.loadFormWageStepConfig','uses' => HResources.'\HrWageStepConfigController@ajaxLoadForm'));

Route::match(['GET','POST'],'wage-step-config/viewNgachCongChuc', array('as' => 'hr.wageStepConfigViewNgachCongChuc','uses' => HResources.'\HrWageStepConfigController@viewNgachCongChuc'));
Route::post('wage-step-config-ngach-cong-chuc/edit/{id?}',array('as' => 'hr.wageStepConfigEditNgachCongChuc','uses' => HResources.'\HrWageStepConfigController@postItemNgachCongChuc'));
Route::post('wage-step-config/ajaxLoadFormNgachCongChuc',array('as' => 'hr.ajaxLoadFormNgachCongChuc','uses' => HResources.'\HrWageStepConfigController@ajaxLoadFormNgachCongChuc'));

Route::match(['GET','POST'],'wage-step-config/viewMaNgachCongChuc', array('as' => 'hr.wageStepConfigViewMaNgachCongChuc','uses' => HResources.'\HrWageStepConfigController@viewMaNgachCongChuc'));
Route::post('wage-step-config-ma-ngach-cong-chuc/edit/{id?}',array('as' => 'hr.wageStepConfigEditMaNgachCongChuc','uses' => HResources.'\HrWageStepConfigController@postItemMaNgachCongChuc'));
Route::post('wage-step-config/ajaxLoadFormMaNgachCongChuc',array('as' => 'hr.ajaxLoadFormMaNgachCongChuc','uses' => HResources.'\HrWageStepConfigController@ajaxLoadFormMaNgachCongChuc'));


Route::match(['GET','POST'],'wage-step-config/viewBacLuong', array('as' => 'hr.wageStepConfigViewBacLuong','uses' => HResources.'\HrWageStepConfigController@viewBacLuong'));
Route::post('wage-step-config-bac-luong/edit/{id?}',array('as' => 'hr.wageStepConfigEditBacLuong','uses' => HResources.'\HrWageStepConfigController@postItemBacLuong'));
Route::post('wage-step-config/ajaxLoadFormBacLuong',array('as' => 'hr.ajaxLoadFormBacLuong','uses' => HResources.'\HrWageStepConfigController@ajaxLoadFormBacLuong'));