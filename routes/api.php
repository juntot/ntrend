<?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the 'api' middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::group(['middleware' => ['sessions']], function () {

// // ============================  FORMS ===============================

// // ADMIN
// Route::get('getform', 'FormController@getForm');
// Route::get('getformbydept', 'FormController@getFormByDept');
// Route::get('updateformbydept/{navname?}/{formid?}/{deptid?}/{status?}', 'FormController@updateFormByDept');


// // FORMS
// Route::get('getuserform', 'FormController@getUserForm'); //get form by auth user
// Route::get('getformusers', 'FormController@getFormUsers'); // by form by all users
// Route::get('updateformbyusers/{empid?}/{formname?}/{status?}', 'FormController@updateFormByUsers');

// Route::get('getformapprovers', 'FormController@getFormApprovers'); //setting up users approval 
// Route::get('getformnavapproval', 'FormController@getFormNavApproval'); //navigation for approval
// Route::get('updateformapprover/{empid?}/{formname?}/{status?}', 'FormController@updateFormApprover');


// // LEAVE-FORM
// Route::post('addleave', 'LeaveController@addLeave');
// Route::post('updateleave', 'LeaveController@updateLeave');
// Route::get('deleteleave/{leaveid?}', 'LeaveController@deleteLeave');
// Route::get('getleavebyemployee', 'LeaveController@getLeaveByEmployee');
// Route::get('getLeaveApprover', 'LeaveController@getLeaveApprover'); //get approvers for leave

// // UNDERTIME-FORM
// Route::post('addundertime', 'UndertimeController@addUndertime');
// Route::post('updateundertime', 'UndertimeController@updateUndertime');
// Route::get('deleteundertime/{Undertimeid?}', 'UndertimeController@deleteUndertime');
// Route::get('getundertimebyemployee', 'UndertimeController@getUndertimeByEmployee');
// Route::get('getUndertimeApprover', 'UndertimeController@getUndertimeApprover'); // get approvers for undertime

// // SALARY DISCREPANCY-FORM
// Route::post('addsalarydiscrepancy', 'SalaryDiscrepancyController@addSalaryDiscrepancy');
// Route::post('updatesalarydiscrepancy', 'SalaryDiscrepancyController@updateSalaryDiscrepancy');
// Route::get('deletesalarydiscrepancy/{saldiscid?}', 'SalaryDiscrepancyController@deleteSalaryDiscrepancy');
// Route::get('getsalarydiscrepancybyemployee', 'SalaryDiscrepancyController@getSalaryDiscrepancyByEmployee');
// Route::get('getSalaryDiscrepancyApprover', 'SalaryDiscrepancyController@getSalaryDiscrepancyApprover'); // get approvers

// // ATTENDANCE SUPPLEMENTARY FORM
// Route::post('addSupplementary', 'SupplementaryController@addSupplementary');
// Route::post('updateSupplementary', 'SupplementaryController@updateSupplementary');
// Route::get('deleteSupplementary/{workid?}', 'SupplementaryController@deleteSupplementary');
// Route::get('getSupplementarybyemployee', 'SupplementaryController@getSupplementaryByEmployee');
// Route::get('getSupplementaryApprover', 'SupplementaryController@getSupplementaryApprover'); // get approvers

// // OFFSET FORM
// Route::post('addOffset', 'OffsetController@addOffset');
// Route::post('updateOffset', 'OffsetController@updateOffset');
// Route::get('deleteOffset/{workid?}', 'OffsetController@deleteOffset');
// Route::get('getOffsetbyemployee', 'OffsetController@getOffsetByEmployee');
// Route::get('getOffsetApprover', 'OffsetController@getOffsetApprover'); // get approvers

// // INCIDENT FORM
// Route::post('addIncidentReport', 'IncidentReportController@addIncidentReport');
// Route::post('updateIncidentReport', 'IncidentReportController@updateIncidentReport');
// Route::get('deleteIncidentReport/{workid?}', 'IncidentReportController@deleteIncidentReport');
// Route::get('getIncidentReportbyemployee', 'IncidentReportController@getIncidentReportByEmployee');
// Route::get('getIncidentReportApprover', 'IncidentReportController@getIncidentReportApprover'); // get approvers

// // COMPANY LOAN FORM
// Route::post('addLoan', 'LoanController@addLoan');
// Route::post('updateLoan', 'LoanController@updateLoan');
// Route::get('deleteLoan/{workid?}', 'LoanController@deleteLoan');
// Route::get('getLoanbyemployee', 'LoanController@getLoanByEmployee');
// Route::get('getLoanApprover', 'LoanController@getLoanApprover'); // get approvers

// // CLEARANCE NOT YET DONE

// // CALLING CARD REQUEST-FORM
// Route::post('addcallingcard', 'CallingCardController@addCallingCard');
// Route::post('updatecallingcard', 'CallingCardController@updateCallingCard');
// Route::get('deletecallingcard/{ccid?}', 'CallingCardController@deleteCallingCard');
// Route::get('getcallingcardbyemployee', 'CallingCardController@getCallingCardByEmployee');
// Route::get('getCallingCardApprover', 'CallingCardController@getCallingCardApprover'); // get approvers

// // LAPTOP REQUEST-FORM NOT YET DONE
// Route::post('addLaptopRequest', 'LaptopRequestController@addLaptopRequest');
// Route::post('updateLaptopRequest', 'LaptopRequestController@updateLaptopRequest');
// Route::get('deleteLaptopRequest/{ccid?}', 'LaptopRequestController@deleteLaptopRequest');
// Route::get('getLaptopRequestbyemployee', 'LaptopRequestController@getLaptopRequestByEmployee');
// Route::get('getLaptopRequestApprover', 'LaptopRequestController@getLaptopRequestApprover'); // get approvers

// // WORK REQUEST-FORM IT
// Route::post('addworkrequest', 'WorkRequestController@addWorkRequest');
// Route::post('updateworkrequest', 'WorkRequestController@updateWorkRequest');
// Route::get('deleteworkrequest/{workid?}', 'WorkRequestController@deleteWorkRequest');
// Route::get('getworkrequestbyemployee', 'WorkRequestController@getWorkRequestByEmployee');
// Route::get('getWorkRequestApprover', 'WorkRequestController@getWorkRequestApprover'); // get approvers

// // FINANCIAL ADVANTAGE FORM
// Route::post('addFinancialAdvantage', 'FinancialAdvantageController@addFinancialAdvantage');
// Route::post('updateFinancialAdvantage', 'FinancialAdvantageController@updateFinancialAdvantage');
// Route::get('deleteFinancialAdvantage/{workid?}', 'FinancialAdvantageController@deleteFinancialAdvantage');
// Route::get('getFinancialAdvantagebyemployee', 'FinancialAdvantageController@getFinancialAdvantageByEmployee');
// Route::get('getFinancialAdvantageApprover', 'FinancialAdvantageController@getFinancialAdvantageApprover'); // get approvers

// // CANVAS FORM
// Route::post('addCanvas', 'CanvasController@addCanvas');
// Route::post('updateCanvas', 'CanvasController@updateCanvas');
// Route::get('deleteCanvas/{workid?}', 'CanvasController@deleteCanvas');
// Route::get('getCanvasbyemployee', 'CanvasController@getCanvasByEmployee');
// Route::get('getCanvasApprover', 'CanvasController@getCanvasApprover'); // get approvers

// // MIIS FORM
// Route::post('addMIIS', 'MIISController@addMIIS');
// Route::post('updateMIIS', 'MIISController@updateMIIS');
// Route::get('deleteMIIS/{workid?}', 'MIISController@deleteMIIS');
// Route::get('getMIISbyemployee', 'MIISController@getMIISByEmployee');
// Route::get('getMIISApprover', 'MIISController@getMIISApprover'); // get approvers

// // PRF FORM
// Route::post('addPRF', 'PRFController@addPRF');
// Route::post('updatePRF', 'PRFController@updatePRF');
// Route::get('deletePRF/{workid?}', 'PRFController@deletePRF');
// Route::get('getPRFbyemployee', 'PRFController@getPRFByEmployee');
// Route::get('getPRFApprover', 'PRFController@getPRFApprover'); // get approvers

// // PRS FORM
// Route::get('getPRSbyemployee', 'PRSController@getPRSByEmployee');
// Route::post('actionReceivedPRS', 'PRSController@actionReceivedPRS'); //recieved iTEM / NOT

// // TRAVEL FORM
// Route::post('addTravel', 'TravelController@addTravel');
// Route::post('updateTravel', 'TravelController@updateTravel');
// Route::get('deleteTravel/{workid?}', 'TravelController@deleteTravel');
// Route::get('getTravelbyemployee', 'TravelController@getTravelByEmployee');
// Route::get('getTravelApprover', 'TravelController@getTravelApprover'); // get approvers


// // URGENT CHECK FORM
// Route::post('addUrgentCheck', 'UrgentCheckController@addUrgentCheck');
// Route::post('updateUrgentCheck', 'UrgentCheckController@updateUrgentCheck');
// Route::get('deleteUrgentCheck/{workid?}', 'UrgentCheckController@deleteUrgentCheck');
// Route::get('getUrgentCheckbyemployee', 'UrgentCheckController@getUrgentCheckByEmployee');
// Route::get('getUrgentCheckApprover', 'UrgentCheckController@getUrgentCheckApprover'); // get approvers

// // SUPPLIER ACCREDITATION
// Route::post('addSupplierAccreditation', 'SupplierAccreditationController@addSupplierAccreditation');
// Route::post('updateSupplierAccreditation', 'SupplierAccreditationController@updateSupplierAccreditation');
// Route::get('deleteSupplierAccreditation/{workid?}', 'SupplierAccreditationController@deleteSupplierAccreditation');
// Route::get('getSupplierAccreditationbyemployee', 'SupplierAccreditationController@getSupplierAccreditationByEmployee');
// Route::get('getSupplierAccreditationApprover', 'SupplierAccreditationController@getSupplierAccreditationApprover'); // get approvers



// // FORM APPROVERS ===========================================================================
// // LEAVE
// Route::get('approvalleaverequest', 'LeaveController@approvalLeaveRequest');
// Route::post('actionformleave', 'LeaveController@actionFormLeave');

// // UNDERTIME
// Route::get('approvalUndertimerequest', 'UndertimeController@approvalUndertimeRequest');
// Route::post('actionformUndertime', 'UndertimeController@actionFormUndertime');

// // SALARY DISCREPANCY
// Route::get('approvalSalaryDiscrepancyRequest', 'SalaryDiscrepancyController@approvalSalaryDiscrepancyRequest');
// Route::post('actionformSalaryDiscrepancy', 'SalaryDiscrepancyController@actionFormSalaryDiscrepancy');

// // SUPPLEMENTARY
// Route::get('approvalSupplementaryRequest', 'SupplementaryController@approvalSupplementaryRequest');
// Route::post('actionformSupplementary', 'SupplementaryController@actionFormSupplementary');

// // OFFSET
// Route::get('approvalOffsetRequest', 'OffsetController@approvalOffsetRequest');
// Route::post('actionformOffset', 'OffsetController@actionFormOffset');

// // INCIDENT REPORT
// Route::get('approvalIncidentReportRequest', 'IncidentReportController@approvalIncidentReportRequest');
// Route::post('actionformIncidentReport', 'IncidentReportController@actionFormIncidentReport');

// // COMPANY LOAN
// Route::get('approvalLoanRequest', 'LoanController@approvalLoanRequest');
// Route::post('actionformLoan', 'LoanController@actionFormLoan');

// // CLEARANCE FORM
// // Route::get('approvalLoanRequest', 'LoanController@approvalLoanRequest');
// // Route::post('actionformLoan', 'LoanController@actionFormLoan');

// // CALLING CARD
// Route::get('approvalCallingCardRequest', 'CallingCardController@approvalCallingCardRequest');
// Route::post('actionformCallingCard', 'CallingCardController@actionFormCallingCard');

// // LAPTOP REQUEST
// Route::get('approvalLaptopRequest', 'LaptopRequestController@approvalLaptopRequest');
// Route::post('actionformLaptopRequest', 'LaptopRequestController@actionFormLaptopRequest');

// // WORK REQUEST
// Route::get('approvalWorkRequest', 'WorkRequestController@approvalWorkRequest');
// Route::post('actionformWorkRequest', 'WorkRequestController@actionFormWorkRequest');

// // FINANCIAL ADVANTAGE
// Route::get('approvalFinancialAdvantageRequest', 'FinancialAdvantageController@approvalFinancialAdvantageRequest');
// Route::post('actionformFinancialAdvantage', 'FinancialAdvantageController@actionFormFinancialAdvantage');

// // FINANCIAL ADVANTAGE
// Route::get('approvalCanvasRequest', 'CanvasController@approvalCanvasRequest');
// Route::post('actionformCanvas', 'CanvasController@actionFormCanvas');

// // MIIS
// Route::get('approvalMIISRequest', 'MIISController@approvalMIISRequest');
// Route::post('actionformMIIS', 'MIISController@actionFormMIIS');

// // MIIS
// Route::get('approvalPRFRequest', 'PRFController@approvalPRFRequest');
// Route::post('actionformPRF', 'PRFController@actionFormPRF');

// // TRAVEL FORM
// Route::get('approvalTravelRequest', 'TravelController@approvalTravelRequest');
// Route::post('actionformTravel', 'TravelController@actionFormTravel');

// // TRAVEL FORM
// Route::get('approvalUrgentCheckRequest', 'UrgentCheckController@approvalUrgentCheckRequest');
// Route::post('actionformUrgentCheck', 'UrgentCheckController@actionFormUrgentCheck');

// // SUPPLIER ACCREDITATION
// Route::get('approvalSupplierAccreditationRequest', 'SupplierAccreditationController@approvalSupplierAccreditationRequest');
// Route::post('actionformSupplierAccreditation', 'SupplierAccreditationController@actionFormSupplierAccreditation');

// // PRS
// Route::get('approvalPRSRequest', 'PRSController@approvalPRSRequest');





// // =============================  API  ==============================

// // USERS
// Route::get('updateuserroles/{empid?}/{role?}/{val?}', 'UserController@updateUserRole');
// Route::get('updateuserformroles/{empid?}/{requesttype?}/{role?}', 'UserController@updateUserFormRoles');


// // EMPLOYEE FORM
// Route::post('newavatar', 'EmployeeController@newAvatar');
// Route::post('addemp','EmployeeController@addEmp');
// Route::post('updateemp','EmployeeController@updateEmp');

// Route::get('getemployees', 'EmployeeController@getEmployees');
// Route::get('getempinfo', 'EmployeeController@getEmpInfo');


// // BRANCH FROM
// Route::post('addbranch', 'BranchController@addBranch');
// Route::post('updatebranch', 'BranchController@updateBranch');
// Route::get('getbranch', 'BranchController@getBranch');


// // DEPARTMENT
// Route::post('adddept', 'DepartmentController@addDept');
// Route::post('updatedept', 'DepartmentController@updateDept');
// Route::get('getdept', 'DepartmentController@getDept');


// // JOB POSITION
// Route::post('addposition', 'JobPositionController@addPosition');
// Route::post('updateposition', 'JobPositionController@updatePosition');
// Route::get('getposition', 'JobPositionController@getPosition');


// // // ANNOUNCEMENT
// // Route::post('addpost', 'AnnouncementController@addPost');
// // Route::get('delpost/{postid}', 'AnnouncementController@delPost');



// // FROMS
// // Route::post('addform', 'FormController@addPost');


// // FORM DETAILS
// Route::get('addformbydept/{formid?}/{deptid?}', 'FormDetailsController@addFormByDept'); // add / update(exists)
// Route::get('uncheckformbydept/{formid?}/{deptid?}', 'FormDetailsController@unCheckFormByDept'); // add / update(exists)


// // GRAPH
// Route::get('chart', 'HomeController@chart');

// // ANNOUNCEMENT
// Route::post('addAnnouncement', 'AnnouncementController@addAnnouncement');
// Route::get('getAnnouncement', 'AnnouncementController@getAnnouncement');
// Route::get('delAnnouncement/{postID?}', 'AnnouncementController@delAnnouncement');
// Route::get('delAnnouncementLogical/{postID?}', 'AnnouncementController@delAnnouncementLogical');

// // PAYSLIPS
// Route::post('addPaySlip', 'PaySlipController@addPaySlip');
// Route::get('getAllPaySlip', 'PaySlipController@getAllPaySlip');
// Route::get('getPaySlipByEmployee', 'PaySlipController@getPaySlipByEmployee');

// // PAYSLIPS PUBLIC


// // VIDEOS
// Route::get('getVid', 'VideoController@getVid');
// Route::post('addVid', 'VideoController@addVid');
// Route::get('delVid/{id?}', 'VideoController@delVid');





// // =================================== PAGINATION ===================================
// Route::get('postPaginate','AnnouncementController@postPaginate')->name('paginate');


// // =================================== SETTINGS =====================================
// Route::get('graph', 'SettingsController@graph');


















// // USERS
// Route::get('updateadmin/{empid?}/{usertype?}', 'UserController@updateAdmin');
// Route::get('updateuserformroles/{empid?}/{requesttype?}/{role?}', 'UserController@updateUserFormRoles');


// // EMPLOYEE FORM
// Route::post('newavatar', 'EmployeeController@newAvatar');
// Route::post('addemp','EmployeeController@addEmp');

// Route::get('/getemployees', 'EmployeeController@getEmployees');
// Route::get('/getempinfo', 'EmployeeController@getEmpInfo');


// // BRANCH FROM
// Route::post('addbranch', 'BranchController@addBranch');
// Route::get('getbranch', 'BranchController@getBranch');


// // DEPARTMENT
// Route::post('adddept', 'DepartmentController@addDept');
// Route::get('getdept', 'DepartmentController@getDept');


// // JOB POSITION
// Route::post('addposition', 'JobPositionController@addPosition');
// Route::get('getposition', 'JobPositionController@getPosition');


// // ANNOUNCEMENT
// Route::post('addpost', 'AnnouncementController@addPost');



// // FROMS
// Route::post('addform', 'FormController@addPost');
// Route::get('getform', 'FormController@getForm');
// Route::get('getformbydept', 'FormController@getFormByDept');
// Route::get('updateformbydept/{formid?}/{deptid?}/{status?}', 'FormController@updateFormByDept');

// // FORM DETAILS
// Route::get('addformbydept/{formid?}/{deptid?}', 'FormDetailsController@addFormByDept'); // add / update(exists)
// Route::get('uncheckformbydept/{formid?}/{deptid?}', 'FormDetailsController@unCheckFormByDept'); // add / update(exists)


// // GRAPH
// Route::get('chart', 'HomeController@chart');