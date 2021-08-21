<?php
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/api/mail', function(){
    $email = 'gonzales.junrey@wealthbank.com.ph';
    Mail::to($email)->send(new \App\Mail\FormMail('gonzales.junrey@wealthbank.com.ph','00001', 'fomr type'));
    return 'awsss';
});



Route::group(['middleware' => 'prevent-back-history'], function(){

    Route::get('calendar', function(){
        return view('calendar');
    });


    Route::get('login', 'UserController@login')->name('login');
    Route::post('authlogin','UserController@authLogin');

    Route::get('terms-and-conditions', 'TermsConditionController@showTerms');
    // Route::get('/{any?}', function () {
    //     return view('admin');
    // })->middleware('admin');


    // Route::get('admin/{any?}', function(){
    //     return view('admin');
    // });

    // Route::get('/{any?}', 'UserController@authlogin')->middleware('authusers')->name('home');

    // Route::get('/{any?}',function(){return view('authuser');})->name('dashboard');

    Route::group(['middleware' => 'authusers'], function () {
        // Route::get('/{any?}','UserController@Home')->name('home');
        // Route::get('/{any?}/{param1?}','UserController@Home')->name('home');
        Route::get('/{any?}','UserController@Home')->where('any', '^((?!api).)*$')->name('home');

        Route::get('/api/logout', 'UserController@logout')->name('logout');




        // ============================ GLOBAL ===============================
            Route::get('/api/global-getinfo', 'GlobalController@getGlobalEmployeeInfo');


        // ============================ COMPANY PROFILE =======================-
            Route::get('/api/get-compprofile-nav', 'CompProfileController@getCompProfileNav');
            Route::get('/api/get-compprofile-category-all', 'CompProfileController@getCompProfileCategoryAll');
            Route::post('/api/add-compprofile-category', 'CompProfileController@addCompProfileCategory');
            Route::get('/api/del-compprofile-category/{id?}', 'CompProfileController@delCompProfileCategory');
            Route::get('/api/get-compprofile-category-details/{id?}', 'CompProfileController@getCompProfileCategoryDetails');

            Route::post('/api/add-compprofile', 'CompProfileController@addCompProfile');

            Route::get('/api/compprofile-per-detail/{detail_id?}', 'CompProfileController@CompProfilePerDetail');
            Route::post('/api/del-compprofile-per-detail', 'CompProfileController@delCompProfilePerDetail');

            // Route::post('/api/set-comp-profile', 'CompProfileController@setCompProfile');
            // Route::post('/api/add-vp1-head', 'CompProfileController@addVp1Head');
            // Route::post('/api/add-vp2-head', 'CompProfileController@addVp2Head');

            // Route::post('/api/add-vp1-head-member', 'CompProfileController@addVp1HeadMember');
            // Route::post('/api/add-vp2-head-member', 'CompProfileController@addVp2HeadMember');

            // Route::post('/api/update-head', 'CompProfileController@updateHead');
            // Route::post('/api/modify-head', 'CompProfileController@modifyHead'); // this will only remove the head without removing reference to sub members
            // Route::post('/api/remove-head', 'CompProfileController@removeHead');
            // Route::post('/api/remove-head-member', 'CompProfileController@removeHeadMember');

            // Route::get('/api/get-comp-profile', 'CompProfileController@getCompProfile');
            // Route::get('/api/get-comp-profile-primary', 'CompProfileController@getCompProfilePrimary');
            // Route::get('/api/get-comp-profile-secondary/{type?}', 'CompProfileController@getCompProfileSecondary');
            // Route::get('/api/get-comp-profile-notlisted', 'CompProfileController@getCompProfileNotListed');
            // Route::get('/api/get-comp-profile-getmembers/{id?}', 'CompProfileController@getCompProfileMembers');


        // ============================  FORMS ===============================

            // ADMIN
            Route::get('/api/getform', 'FormController@getForm');
            Route::get('/api/getformbydept', 'FormController@getFormByDept');
            Route::get('/api/updateformbydept/{navname?}/{formid?}/{deptid?}/{status?}', 'FormController@updateFormByDept');
            Route::post('/api/updateTerms', 'TermsConditionController@updateTerms');
            Route::get('/api/getTermsCode', 'TermsConditionController@getTermsCode');



            // FORMS
            Route::get('/api/getuserform', 'FormController@getUserForm'); //get form by auth user

            Route::get('/api/getformusers', 'FormController@getFormUsers'); // by form by all users
            Route::get('/api/updateformbyusers/{empid?}/{formname?}/{status?}', 'FormController@updateFormByUsers');

            Route::get('/api/getformapprovers', 'FormController@getFormApprovers'); //setting up users approval
            Route::get('/api/getFormApproversByEmployee/{empid?}', 'FormController@getFormApproversByEmployee'); //SETTING APPROVAL PER USERS
            Route::get('/api/getformnavapproval', 'FormController@getFormNavApproval'); //navigation for approval

            Route::get('/api/updateformapprover/{empid?}/{formname?}/{status?}', 'FormController@updateFormApprover');
            Route::get('/api/updateformapproverByEmployee/{empid?}/{approverid}/{formname?}/{status?}', 'FormController@updateFormApproverByEmployee');


            // LEAVE-FORM
            Route::post('/api/addleave', 'LeaveController@addLeave');
            Route::post('/api/updateleave', 'LeaveController@updateLeave');
            Route::post('/api/deleteleave/{leaveid?}', 'LeaveController@deleteLeave');
            Route::get('/api/getleavebyemployee', 'LeaveController@getLeaveByEmployee');
            Route::get('/api/getLeaveApprover', 'LeaveController@getLeaveApprover'); //get approvers for leave

            // UNDERTIME-FORM
            Route::post('/api/addundertime', 'UndertimeController@addUndertime');
            Route::post('/api/updateundertime', 'UndertimeController@updateUndertime');
            Route::get('/api/deleteundertime/{Undertimeid?}', 'UndertimeController@deleteUndertime');
            Route::get('/api/getundertimebyemployee', 'UndertimeController@getUndertimeByEmployee');
            Route::get('/api/getUndertimeApprover', 'UndertimeController@getUndertimeApprover'); // get approvers for undertime

            // OVERTIME-FORM
            Route::post('/api/addovertime', 'OvertimeController@addOvertime');
            Route::post('/api/updateovertime', 'OvertimeController@updateOvertime');
            Route::get('/api/deleteovertime/{Overtimeid?}', 'OvertimeController@deleteOvertime');
            Route::get('/api/getovertimebyemployee', 'OvertimeController@getOvertimeByEmployee');
            Route::get('/api/getovertimeApprover', 'OvertimeController@getOvertimeApprover'); // get approvers for undertime

            // SALARY DISCREPANCY-FORM
            Route::post('/api/addsalarydiscrepancy', 'SalaryDiscrepancyController@addSalaryDiscrepancy');
            Route::post('/api/updatesalarydiscrepancy', 'SalaryDiscrepancyController@updateSalaryDiscrepancy');
            Route::get('/api/deletesalarydiscrepancy/{saldiscid?}', 'SalaryDiscrepancyController@deleteSalaryDiscrepancy');
            Route::get('/api/getsalarydiscrepancybyemployee', 'SalaryDiscrepancyController@getSalaryDiscrepancyByEmployee');
            Route::get('/api/getSalaryDiscrepancyApprover', 'SalaryDiscrepancyController@getSalaryDiscrepancyApprover'); // get approvers

            // ATTENDANCE SUPPLEMENTARY FORM
            Route::post('/api/addSupplementary', 'SupplementaryController@addSupplementary');
            Route::post('/api/updateSupplementary', 'SupplementaryController@updateSupplementary');
            Route::get('/api/deleteSupplementary/{workid?}', 'SupplementaryController@deleteSupplementary');
            Route::get('/api/getSupplementarybyemployee', 'SupplementaryController@getSupplementaryByEmployee');


            Route::get('/api/get-supplementary-approvalwitnes', 'SupplementaryController@getSupplementaryApproverWitness'); // confirm as witness approvers';
            Route::post('/api/confirm-as-witness', 'SupplementaryController@confirmAsWitness'); // confirm as witness approvers';
            Route::get('/api/getSupplementaryApprover', 'SupplementaryController@getSupplementaryApprover'); // get approvers

            // OFFSET FORM
            Route::post('/api/addOffset', 'OffsetController@addOffset');
            Route::post('/api/updateOffset', 'OffsetController@updateOffset');
            Route::get('/api/deleteOffset/{workid?}', 'OffsetController@deleteOffset');
            Route::get('/api/getOffsetbyemployee', 'OffsetController@getOffsetByEmployee');
            Route::get('/api/getOffsetApprover', 'OffsetController@getOffsetApprover'); // get approvers

            // INCIDENT FORM
            Route::post('/api/addIncidentReport', 'IncidentReportController@addIncidentReport');
            Route::post('/api/updateIncidentReport', 'IncidentReportController@updateIncidentReport');
            Route::get('/api/deleteIncidentReport/{workid?}', 'IncidentReportController@deleteIncidentReport');
            Route::get('/api/getIncidentReportbyemployee', 'IncidentReportController@getIncidentReportByEmployee');
            Route::get('/api/getIncidentReportApprover', 'IncidentReportController@getIncidentReportApprover'); // get approvers

            // COMPANY LOAN FORM
            Route::post('/api/addLoan', 'LoanController@addLoan');
            Route::post('/api/updateLoan', 'LoanController@updateLoan');
            Route::get('/api/deleteLoan/{workid?}', 'LoanController@deleteLoan');
            Route::get('/api/getLoanbyemployee', 'LoanController@getLoanByEmployee');
            Route::get('/api/getLoanApprover', 'LoanController@getLoanApprover'); // get approvers

            // CLEARANCE NOT YET DONE

            // CALLING CARD REQUEST-FORM
            Route::post('/api/addcallingcard', 'CallingCardController@addCallingCard');
            Route::post('/api/updatecallingcard', 'CallingCardController@updateCallingCard');
            Route::get('/api/deletecallingcard/{ccid?}', 'CallingCardController@deleteCallingCard');
            Route::get('/api/getcallingcardbyemployee', 'CallingCardController@getCallingCardByEmployee');
            Route::get('/api/getCallingCardApprover', 'CallingCardController@getCallingCardApprover'); // get approvers

            // LAPTOP REQUEST-FORM NOT YET DONE
            Route::post('/api/addLaptopRequest', 'LaptopRequestController@addLaptopRequest');
            Route::post('/api/updateLaptopRequest', 'LaptopRequestController@updateLaptopRequest');
            Route::get('/api/deleteLaptopRequest/{ccid?}', 'LaptopRequestController@deleteLaptopRequest');
            Route::get('/api/getLaptopRequestbyemployee', 'LaptopRequestController@getLaptopRequestByEmployee');
            Route::get('/api/getLaptopRequestApprover', 'LaptopRequestController@getLaptopRequestApprover'); // get approvers

            // WORK REQUEST-FORM IT
            Route::post('/api/addworkrequest', 'WorkRequestController@addWorkRequest');
            Route::post('/api/updateworkrequest', 'WorkRequestController@updateWorkRequest');
            Route::get('/api/deleteworkrequest/{workid?}', 'WorkRequestController@deleteWorkRequest');
            Route::get('/api/getworkrequestbyemployee', 'WorkRequestController@getWorkRequestByEmployee');
            Route::get('/api/getWorkRequestApprover', 'WorkRequestController@getWorkRequestApprover'); // get approvers

            // FINANCIAL ADVANTAGE FORM
            Route::post('/api/addFinancialAdvantage', 'FinancialAdvantageController@addFinancialAdvantage');
            Route::post('/api/updateFinancialAdvantage', 'FinancialAdvantageController@updateFinancialAdvantage');
            Route::get('/api/deleteFinancialAdvantage/{workid?}', 'FinancialAdvantageController@deleteFinancialAdvantage');
            Route::get('/api/getFinancialAdvantagebyemployee', 'FinancialAdvantageController@getFinancialAdvantageByEmployee');
            Route::get('/api/getFinancialAdvantageApprover', 'FinancialAdvantageController@getFinancialAdvantageApprover'); // get approvers

            // CANVAS FORM
            Route::post('/api/addCanvas', 'CanvasController@addCanvas');
            Route::post('/api/updateCanvas', 'CanvasController@updateCanvas');
            Route::get('/api/deleteCanvas/{workid?}', 'CanvasController@deleteCanvas');
            Route::get('/api/getCanvasbyemployee', 'CanvasController@getCanvasByEmployee');
            Route::get('/api/getCanvasApprover', 'CanvasController@getCanvasApprover'); // get approvers

            // MIIS FORM
            Route::post('/api/addMIIS', 'MIISController@addMIIS');
            Route::post('/api/updateMIIS', 'MIISController@updateMIIS');
            Route::get('/api/deleteMIIS/{workid?}', 'MIISController@deleteMIIS');
            Route::get('/api/getMIISbyemployee', 'MIISController@getMIISByEmployee');
            Route::get('/api/getMIISApprover', 'MIISController@getMIISApprover'); // get approvers

            // PRF FORM
            Route::post('/api/addPRF', 'PRFController@addPRF');
            Route::post('/api/updatePRF', 'PRFController@updatePRF');
            Route::get('/api/deletePRF/{workid?}', 'PRFController@deletePRF');
            Route::get('/api/getPRFbyemployee', 'PRFController@getPRFByEmployee');
            Route::get('/api/getPRFApprover', 'PRFController@getPRFApprover'); // get approvers

            // PRS FORM
            Route::get('/api/getPRSbyemployee', 'PRSController@getPRSByEmployee');
            Route::post('/api/actionReceivedPRS', 'PRSController@actionReceivedPRS'); //recieved iTEM / NOT

            // TRAVEL FORM
            Route::post('/api/addTravel', 'TravelController@addTravel');
            Route::post('/api/updateTravel', 'TravelController@updateTravel');
            Route::get('/api/deleteTravel/{workid?}', 'TravelController@deleteTravel');
            Route::get('/api/getTravelbyemployee', 'TravelController@getTravelByEmployee');
            Route::get('/api/getTravelApprover', 'TravelController@getTravelApprover'); // get approvers


            // URGENT CHECK FORM
            Route::post('/api/addUrgentCheck', 'UrgentCheckController@addUrgentCheck');
            Route::post('/api/updateUrgentCheck', 'UrgentCheckController@updateUrgentCheck');
            Route::get('/api/deleteUrgentCheck/{workid?}', 'UrgentCheckController@deleteUrgentCheck');
            Route::get('/api/getUrgentCheckbyemployee', 'UrgentCheckController@getUrgentCheckByEmployee');
            Route::get('/api/getUrgentCheckApprover', 'UrgentCheckController@getUrgentCheckApprover'); // get approvers

            // SUPPLIER ACCREDITATION
            Route::post('/api/addSupplierAccreditation', 'SupplierAccreditationController@addSupplierAccreditation');
            Route::post('/api/updateSupplierAccreditation', 'SupplierAccreditationController@updateSupplierAccreditation');
            Route::get('/api/deleteSupplierAccreditation/{workid?}', 'SupplierAccreditationController@deleteSupplierAccreditation');
            Route::get('/api/getSupplierAccreditationbyemployee', 'SupplierAccreditationController@getSupplierAccreditationByEmployee');
            Route::get('/api/getSupplierAccreditationApprover', 'SupplierAccreditationController@getSupplierAccreditationApprover'); // get approvers

            // OVERRIDE FORM
            Route::post('/api/search-override-emp', 'OverrideController@searchEmp');
            Route::post('/api/addoverride', 'OverrideController@addOverride');
            Route::post('/api/updateoverride', 'OverrideController@updateOverride');
            Route::post('/api/deleteoverride/{overrideID?}', 'OverrideController@deleteOverride');
            Route::get('/api/getoverride', 'OverrideController@getOverride');
            Route::get('/api/getOverrideApprover', 'OverrideController@getOverrideApprover');

            // FORM APPROVERS ===========================================================================
            // LEAVE
            Route::get('/api/approvalleaverequest', 'LeaveController@approvalLeaveRequest');
            Route::post('/api/actionformleave', 'LeaveController@actionFormLeave');

            // UNDERTIME
            Route::get('/api/approvalUndertimerequest', 'UndertimeController@approvalUndertimeRequest');
            Route::post('/api/actionformUndertime', 'UndertimeController@actionFormUndertime');

            // OVERTIME
            Route::get('/api/approvalOvertimerequest', 'OvertimeController@approvalOvertimeRequest');
            Route::post('/api/actionformOvertime', 'OvertimeController@actionFormOvertime');

            // SALARY DISCREPANCY
            Route::get('/api/approvalSalaryDiscrepancyRequest', 'SalaryDiscrepancyController@approvalSalaryDiscrepancyRequest');
            Route::post('/api/actionformSalaryDiscrepancy', 'SalaryDiscrepancyController@actionFormSalaryDiscrepancy');

            // SUPPLEMENTARY
            Route::get('/api/approvalSupplementaryRequest', 'SupplementaryController@approvalSupplementaryRequest');
            Route::post('/api/actionformSupplementary', 'SupplementaryController@actionFormSupplementary');

            // OFFSET
            Route::get('/api/approvalOffsetRequest', 'OffsetController@approvalOffsetRequest');
            Route::post('/api/actionformOffset', 'OffsetController@actionFormOffset');

            // INCIDENT REPORT
            Route::get('/api/approvalIncidentReportRequest', 'IncidentReportController@approvalIncidentReportRequest');
            Route::post('/api/actionformIncidentReport', 'IncidentReportController@actionFormIncidentReport');

            // COMPANY LOAN
            Route::get('/api/approvalLoanRequest', 'LoanController@approvalLoanRequest');
            Route::post('/api/actionformLoan', 'LoanController@actionFormLoan');

            // CLEARANCE FORM
            // Route::get('/api/approvalLoanRequest', 'LoanController@approvalLoanRequest');
            // Route::post('/api/actionformLoan', 'LoanController@actionFormLoan');

            // CALLING CARD
            Route::get('/api/approvalCallingCardRequest', 'CallingCardController@approvalCallingCardRequest');
            Route::post('/api/actionformCallingCard', 'CallingCardController@actionFormCallingCard');

            // LAPTOP REQUEST
            Route::get('/api/approvalLaptopRequest', 'LaptopRequestController@approvalLaptopRequest');
            Route::post('/api/actionformLaptopRequest', 'LaptopRequestController@actionFormLaptopRequest');

            // WORK REQUEST
            Route::get('/api/approvalWorkRequest', 'WorkRequestController@approvalWorkRequest');
            Route::post('/api/actionformWorkRequest', 'WorkRequestController@actionFormWorkRequest');

            // FINANCIAL ADVANTAGE
            Route::get('/api/approvalFinancialAdvantageRequest', 'FinancialAdvantageController@approvalFinancialAdvantageRequest');
            Route::post('/api/actionformFinancialAdvantage', 'FinancialAdvantageController@actionFormFinancialAdvantage');

            // FINANCIAL ADVANTAGE
            Route::get('/api/approvalCanvasRequest', 'CanvasController@approvalCanvasRequest');
            Route::post('/api/actionformCanvas', 'CanvasController@actionFormCanvas');

            // MIIS
            Route::get('/api/approvalMIISRequest', 'MIISController@approvalMIISRequest');
            Route::post('/api/actionformMIIS', 'MIISController@actionFormMIIS');

            // MIIS
            Route::get('/api/approvalPRFRequest', 'PRFController@approvalPRFRequest');
            Route::post('/api/actionformPRF', 'PRFController@actionFormPRF');

            // TRAVEL FORM
            Route::get('/api/approvalTravelRequest', 'TravelController@approvalTravelRequest');
            Route::post('/api/actionformTravel', 'TravelController@actionFormTravel');

            // TRAVEL FORM
            Route::get('/api/approvalUrgentCheckRequest', 'UrgentCheckController@approvalUrgentCheckRequest');
            Route::post('/api/actionformUrgentCheck', 'UrgentCheckController@actionFormUrgentCheck');

            // SUPPLIER ACCREDITATION
            Route::get('/api/approvalSupplierAccreditationRequest', 'SupplierAccreditationController@approvalSupplierAccreditationRequest');
            Route::post('/api/actionformSupplierAccreditation', 'SupplierAccreditationController@actionFormSupplierAccreditation');

            // PRS
            Route::get('/api/approvalPRSRequest', 'PRSController@approvalPRSRequest');

            // OVERRIDE
            Route::get('/api/approvalOverrideForm', 'OverrideController@approvalOverrideForm');
            Route::post('/api/actionformOverride', 'OverrideController@actionFormOverride');


            // =============================  API  ==============================

            // USERS
            Route::get('/api/updateuserroles/{empid?}/{role?}/{val?}', 'UserController@updateUserRole');
            Route::get('/api/updateuserformroles/{empid?}/{requesttype?}/{role?}', 'UserController@updateUserFormRoles');

            Route::post('/api/resetpass', 'UserController@resetPass');
            Route::post('/api/changepass', 'UserController@changePass');


            // EMPLOYEE FORM
            Route::post('/api/newavatar', 'EmployeeController@newAvatar');
            Route::post('/api/addemp','EmployeeController@addEmp');
            Route::post('/api/update_emp_avatar','EmployeeController@updateEmpAvatar');
            Route::post('/api/updateemp','EmployeeController@updateEmp');
            Route::post('/api/del-emp','EmployeeController@delEmp');
            Route::post('/api/activate-emp','EmployeeController@activateEmp');
            Route::post('/api/upload_employee', 'EmployeeController@uploadXls');


            Route::get('/api/getemployees', 'EmployeeController@getEmployees');
            Route::get('/api/getemployees-inactive', 'EmployeeController@getEmployeesInactive');
            Route::get('/api/getempinfo', 'EmployeeController@getEmpInfo');


            Route::get('/api/get_columns', 'EmployeeController@getColumns');



            // BRANCH FROM
            Route::post('/api/addbranch', 'BranchController@addBranch');
            Route::post('/api/updatebranch', 'BranchController@updateBranch');
            Route::get('/api/getbranch', 'BranchController@getBranch');
            Route::post('/api/delbranch', 'BranchController@delBranch');


            // DEPARTMENT
            Route::post('/api/adddept', 'DepartmentController@addDept');
            Route::post('/api/updatedept', 'DepartmentController@updateDept');
            Route::get('/api/getdept', 'DepartmentController@getDept');
            Route::post('/api/deldept', 'DepartmentController@delDept');


            // JOB POSITION
            Route::post('/api/addposition', 'JobPositionController@addPosition');
            Route::post('/api/updateposition', 'JobPositionController@updatePosition');
            Route::get('/api/getposition', 'JobPositionController@getPosition');
            Route::post('/api/delposition', 'JobPositionController@delPosition');

            // Company Category
            Route::post('/api/addcompany', 'CompanyController@addCompany');
            Route::post('/api/updatecompany', 'CompanyController@updateCompany');
            Route::get('/api/getcompany', 'CompanyController@getCompany');
            Route::post('/api/delcompany', 'CompanyController@delCompany');

            // // ANNOUNCEMENT
            // Route::post('/api/addpost', 'AnnouncementController@addPost');
            // Route::get('/api/delpost/{postid}', 'AnnouncementController@delPost');



            // FROMS
            // Route::post('/api/addform', 'FormController@addPost');


            // FORM DETAILS
            Route::get('/api/addformbydept/{formid?}/{deptid?}', 'FormDetailsController@addFormByDept'); // add / update(exists)
            Route::get('/api/uncheckformbydept/{formid?}/{deptid?}', 'FormDetailsController@unCheckFormByDept'); // add / update(exists)


            // GRAPH
            Route::get('/api/chart', 'HomeController@chart');

            // ANNOUNCEMENT
            Route::post('/api/addAnnouncement', 'AnnouncementController@addAnnouncement');
            Route::post('/api/updateAnnouncement', 'AnnouncementController@updateAnnouncement');
            Route::get('/api/getAnnouncement', 'AnnouncementController@getAnnouncement');
            Route::get('/api/delAnnouncementLogical/{postID?}', 'AnnouncementController@delAnnouncementLogical');

                // TAGGING
            // Route::get('/api/search-emp/{searchkey?}', 'AnnouncementController@searchEmp');
            // Route::get('/api/search-dept/{searchkey?}', 'AnnouncementController@searchDept');
            // Route::get('/api/search-comp/{searchkey?}', 'AnnouncementController@searchComp');
            
            Route::post('/api/search-emp', 'AnnouncementController@searchEmp');
            Route::post('/api/search-dept', 'AnnouncementController@searchDept');
            Route::post('/api/search-comp', 'AnnouncementController@searchComp');

                // COMMENTS
            Route::post('/api/addComment', 'CommentController@addComment');
            Route::post('/api/editComment', 'CommentController@editComment');
            Route::get('/api/deleteComment/{commentID}', 'CommentController@deleteComment');

            // PAYSLIPS
            Route::post('/api/addPaySlip', 'PaySlipController@addPaySlip');
            Route::post('/api/delete-payslip', 'PaySlipController@deletePayslip');
            Route::get('/api/getAllPaySlip/{date?}', 'PaySlipController@getAllPaySlip');
            Route::get('/api/getPaySlipByEmployee', 'PaySlipController@getPaySlipByEmployee');
            Route::get('/api/get-cutoff-dates', 'PaySlipController@getCutoffDates');

            // POLICY ====================================================================================

            Route::get('/api/get-policy-nav', 'PolicyController@getPolicyNav');
            Route::get('/api/get-policy-category-all', 'PolicyController@getPolicyCategoryAll');
            Route::post('/api/add-policy-category', 'PolicyController@addPolicyCategory');
            Route::get('/api/del-policy-category/{id?}', 'PolicyController@delPolicyCategory');
            Route::get('/api/get-policy-category-details/{id?}', 'PolicyController@getPolicyCategoryDetails');

            Route::post('/api/add-policy', 'PolicyController@addPolicy');

            Route::get('/api/policy-per-detail/{detail_id?}', 'PolicyController@policyPerDetail');
            Route::post('/api/del-policy-per-detail', 'PolicyController@delPolicyPerDetail');



            // VIDEOS ====================================================================================
            Route::get('/api/getVid', 'VideoController@getVid');
            Route::post('/api/addVid', 'VideoController@addVid');
            Route::get('/api/delVid/{id?}', 'VideoController@delVid');
            Route::post('/api/updateVid', 'VideoController@updateVid');


            // DIRECTORY
            Route::get('api/getdirectory', 'DirectoryController@getDirectory');


            // =================================== PAGINATION ===================================
            Route::get('/api/postPaginate','AnnouncementController@postPaginate')->name('paginate');


            // =================================== SETTINGS =====================================
            Route::get('/api/graph', 'SettingsController@graph');
            Route::post('/api/change_logo', 'SettingsController@changeLogo');
            Route::get('/api/get_default_leaves_bypos', 'SettingsController@getDefaultLeaveByPos');
            Route::post('/api/set_default_leaves_bypos', 'SettingsController@defaultLeaveByPos');

            // get users by pos
            Route::get('/api/get_users_bypos/{posID?}', 'SettingsController@getUsersByPos');
            Route::post('/api/set_default_leaves_by_emp', 'SettingsController@defaultLeaveByEmp');

            // reset leave credits
            Route::post('/api/resetNow', 'SettingsController@resetNow');

            // forms
            Route::get('/api/get-form-settings', 'SettingsController@getForm_Settings');
            Route::post('/api/get-form-records', 'SettingsController@getFormRecords');
            Route::post('/api/disable-form-records', 'SettingsController@disableFormRecords');
            Route::post('/api/enable-form-records', 'SettingsController@enableFormRecords');

            // posts
            Route::post('/api/get-post-records', 'SettingsController@getPostRecords');
            Route::post('/api/disable-post-records', 'SettingsController@disablePostRecords');
            Route::post('/api/enable-post-records', 'SettingsController@enablePostRecords');
            Route::post('/api/delAnnouncement/{postID?}', 'SettingsController@delAnnouncement');


            // FROM GROUP
            Route::get('/api/get-form-group', 'SettingsController@getFormGroup');
            Route::post('/api/add-form-group', 'SettingsController@addFormGroup');
            Route::post('/api/update-form-group', 'SettingsController@updateFormGroup');
            Route::post('/api/delete-form-group', 'SettingsController@deleteFormGroup');

            // form group details
            Route::get('/api/get-form-group-details', 'SettingsController@getFormGroupDetails');
            Route::post('/api/set-form-group-details', 'SettingsController@setFormGroupDetails');


            Route::get('/api/get-form-nav', 'SettingsController@getFormNav');
            Route::get('/api/get-form-nav-approver', 'SettingsController@getFormNavApprover');


            // OVrride Settings
            Route::get('/api/get-override-company', 'SettingsController@getOverrideSetting');
            Route::post('/api/add-override-company', 'SettingsController@addOverrideSetting');
            Route::post('/api/update-override-company', 'SettingsController@updateOverrideSetting');
            Route::post('/api/del-override-company', 'SettingsController@delOverrideSetting');

            //  ============================ WITNESS ======================================
            Route::get('/api/get-witness-nav', 'WitnessSupController@getWitnessNav');
            Route::get('/api/get-witness-list/{empid?}', 'WitnessSupController@getWitnessList');
            Route::post('/api/add-witness', 'WitnessSupController@addWitness');
            Route::post('/api/del-witness', 'WitnessSupController@delWitness');



            // =============================  REPORTS  ==============================

            // check f has reports
            Route::get('/api/hasreports', 'ReportController@hasReport');


            // manage reports
            Route::get('/api/manage-reports', 'ReportController@manageReport');

            Route::get('/api/updatemanage-reports/{empid?}/{formname?}/{status?}', 'ReportController@updateManageReport');

            // Route::get('/api/getreports-byusers', 'ReportController@getReportByUsers');

            Route::get('/api/getreport-type', 'ReportController@getReportType');
            Route::get('/api/getreport/{datefrom?}/{dateto?}/{reporttype?}/{branch?}/{status?}/{company?}', 'ReportController@getReport');
            // Route::get('/api/getreport-eleave', 'ReportController@getReporteLeave');
            // Route::get('/api/getreport-eundertime', 'ReportController@getReporteUndertime');
            // Route::get('/api/getreport-esaldisc', 'ReportController@getReporteSalDisc');


            // ============================ NOTIFICATIONS =========================================
            Route::get('/api/get-leave-credits', 'NotificationController@getLeaveCredits');

            Route::get('/api/get-form-notifs', 'NotificationController@getFormNotifs');

            Route::post('/api/update-form-notifs', 'NotificationController@updateFormNotif');

            Route::get('/api/get-post-notifs', 'NotificationController@getPostNotifs');

            Route::post('/api/update-post-notifs', 'NotificationController@updatePostNotif');


            Route::post('/api/notif_center', 'NotificationController@notifCenter');
            // Route::get('/api/notif_center', 'NotificationController@notifCenter');




            // =========================== CALENDAR ==================================================
            Route::get('/api/get_events', 'CalendarController@getEvents');
            Route::post('/api/add_event', 'CalendarController@addEvent');
            Route::post('/api/update_event', 'CalendarController@updateEvent');
            Route::post('/api/del_event', 'CalendarController@delEvent');
            Route::post('/api/plot_calendar', 'CalendarController@plotCalendar');



            // ========================= WORK AROUND ==================================
            Route::get('/policy/api/logout', 'UserController@logout')->name('logout');

            Route::get('/policy/api/get-form-nav', 'SettingsController@getFormNav');
            Route::get('/policy/api/get-form-nav-approver', 'SettingsController@getFormNavApprover');

            Route::get('/policy/api/getuserform', 'FormController@getUserForm'); //get form by auth user
            Route::get('/policy/api/getformnavapproval', 'FormController@getFormNavApproval'); //navigation for approval
            Route::get('/policy/api/getempinfo', 'EmployeeController@getEmpInfo');
            Route::get('/policy/api/get-policy-nav', 'PolicyController@getPolicyNav');
            Route::get('/policy/api/policy-per-detail/{detail_id?}', 'PolicyController@policyPerDetail');
            Route::get('/policy/api/hasreports', 'ReportController@hasReport');

            Route::get('/policy/api/get-leave-credits', 'NotificationController@getLeaveCredits');
            Route::get('/policy/api/get-form-notifs', 'NotificationController@getFormNotifs');
            Route::get('/policy/api/get-post-notifs', 'NotificationController@getPostNotifs');
            Route::post('/policy/api/notif_center', 'NotificationController@notifCenter');

            Route::get('/policy/api/get-compprofile-nav', 'CompProfileController@getCompProfileNav');
            Route::get('/policy/api/get-witness-nav', 'WitnessSupController@getWitnessNav');




            Route::get('/company-profile/api/logout', 'UserController@logout')->name('logout');
            Route::get('/company-profile/api/get-form-nav', 'SettingsController@getFormNav');
            Route::get('/company-profile/api/get-form-nav-approver', 'SettingsController@getFormNavApprover');
            Route::get('/company-profile/api/getuserform', 'FormController@getUserForm'); //get form by auth user
            Route::get('/company-profile/api/getformnavapproval', 'FormController@getFormNavApproval'); //navigation for approval
            Route::get('/company-profile/api/getempinfo', 'EmployeeController@getEmpInfo');
            Route::get('/company-profile/api/get-policy-nav', 'PolicyController@getPolicyNav');
            Route::get('/company-profile/api/policy-per-detail/{detail_id?}', 'PolicyController@policyPerDetail');
            Route::get('/company-profile/api/hasreports', 'ReportController@hasReport');

            Route::get('/company-profile/api/get-leave-credits', 'NotificationController@getLeaveCredits');
            Route::get('/company-profile/api/get-form-notifs', 'NotificationController@getFormNotifs');
            Route::get('/company-profile/api/get-post-notifs', 'NotificationController@getPostNotifs');
            Route::post('/company-profile/api/notif_center', 'NotificationController@notifCenter');

            Route::get('/company-profile/api/get-compprofile-nav', 'CompProfileController@getCompProfileNav');
            Route::get('/company-profile/api/compprofile-per-detail/{detail_id?}', 'CompProfileController@CompProfilePerDetail');
            Route::get('/company-profile/api/get-witness-nav', 'WitnessSupController@getWitnessNav');
    });

});