<?php

Route::get('student/fundingType/ilis/{loc}', 'Student\DomesticController@fundingSearch');

Route::get('student/list', 'Student\StudentController@list');
Route::get('student/getstudents', 'Student\StudentController@getStudents');
Route::get('student/search/{student}', 'Student\StudentController@search');
Route::get('student/{student}/info', 'Student\StudentController@info');
Route::get('student/details/{student_id}/preview', 'Student\StudentController@generate_student_details');
Route::get('student/details/report-avetmiss/{student_id}/{value}', 'Student\StudentController@update_avetmiss_report');
Route::post('student/update-report-course-status', 'Student\StudentController@update_report_course_status');

// Student Attachments AISS
Route::get('student/aiss-attachment/fetch/{student_id}', 'Student\StudentAissAttachmentController@fetch');
Route::post('student/aiss-attachment/upload/{student_id}', 'Student\StudentAissAttachmentController@upload');
Route::get('student/aiss-attachment/preview/{id}', 'Student\StudentAissAttachmentController@preview');
Route::delete('student/aiss-attachment/delete/{id}', 'Student\StudentAissAttachmentController@destroy');
Route::put('student/aiss-attachment/rename/{id}', 'Student\StudentAissAttachmentController@rename');

// Student Attachments
Route::get('student/attachment/fetch/{student_id}', 'Student\StudentAttachmentController@fetch');
Route::post('student/attachment/upload/{student_id}', 'Student\StudentAttachmentController@upload');
Route::get('student/attachment/preview/{id}', 'Student\StudentAttachmentController@preview');
Route::delete('student/attachment/delete/{id}', 'Student\StudentAttachmentController@destroy');
Route::put('student/attachment/rename/{id}', 'Student\StudentAttachmentController@rename');

// Student Email Warning
Route::get('student/warning-letters/{student_id}', 'Student\EmailWarningController@get_warning_letters');
Route::get('student/warning-letter/{student_id}/{emailTemp_id}/preview', 'Student\EmailWarningController@view_email_warnings');
Route::get('student/warning-letter/{student_id}/{emailTemp_id}/send', 'Student\EmailWarningController@send_email_warnings');
Route::get('student/warning-letter/test_send', 'Automation\AutoEmailWarningController@automate');

// Student Resource
Route::resource('student', 'Student\StudentController');


// Student Notes
Route::resource('student-notes', 'Student\StudentNoteController');


// student top - search
Route::get('student/top-search/{search}', 'Student\StudentController@top_search');

// student fetch - student portal
Route::get('student/fetch/{student_id}', 'Student\StudentController@fetch_student');


// Student Portal Logins
Route::get('student/fetch-logins/{student_id}', 'Student\StudentController@fetch_logins');
Route::post('/student/store-logins', 'Student\StudentController@store_logins');


// Student Course Workbook
Route::get('student/course/workbook/fetch/{student_id}', 'Student\StudentWorkbookController@show');
Route::post('student/course/workbook/upload', 'Student\StudentWorkbookController@store');
Route::get('student/course/workbook/download/{id}', 'Student\StudentWorkbookController@download_workbook');
Route::get('student/course/workbook/delete/{id}', 'Student\StudentWorkbookController@destroy');

// Process ID/AccessCode
Route::get('access-code', 'Student\AccessCodeController@index');
Route::get('access-code/list','Student\AccessCodeController@access_code_list');
Route::post('access-code/store','Student\AccessCodeController@storeCode');
Route::delete('access-code/delete/{id}', 'Student\AccessCodeController@destroy');

// Certificate of Enrolment
Route::get('/student/coe/{student}/{code}', 'Student\StudentController@generate_coe');

//new student
Route::prefix('test')->group(function () {
    Route::get('student/{student}', 'NewStudent\StudentController@student');
    Route::post('student', 'NewStudent\StudentController@storeupdate');
    Route::post('student/contact', 'NewStudent\StudentController@contact_storeupdate');
    Route::post('student/visa', 'NewStudent\StudentController@visa_storeupdate');
    Route::post('student/avetmiss', 'NewStudent\StudentController@avetmiss_storeupdate');
    Route::get('student/{student}/show', 'NewStudent\StudentController@show');
    Route::post('student/completion', 'NewStudent\StudentController@completion_storeupdate');
    Route::post('student/certcompletion', 'NewStudent\StudentController@certcompletion_storeupdate');
});


// new student profile
Route::prefix('student-profile')->group(function() {
    Route::get('{id}', 'Newest\Student\StudentController@show');
    
    //  course tab controller
    Route::resource('course', 'Newest\Student\CourseController');
    Route::get('/fetch-funding/{location}', 'Newest\Student\CourseController@fetch_funding');
    
    // units tab controller
    Route::resource('course-unit', 'Newest\Student\CourseUnitController');
    Route::post('fetch-course-units', 'Newest\Student\CourseUnitController@fetch_units');

    // issuance tab controller
    Route::post('fetch-issuance', 'Newest\Student\IssuanceController@fetch_issuance');
    Route::post('issuance', 'Newest\Student\IssuanceController@store');
    Route::get('generate-issuance-pdf/{id}', 'Newest\Student\IssuanceController@generate_pdf');
});
// fetching all active courses
Route::get('/fetch-course-selection', 'Newest\Student\CourseController@fetch_all_courses');

// fetch course fee
Route::post('/fetch-course-fee', 'Newest\Student\CourseController@fetch_course_fee');
// fetch course units
Route::post('/fetch-course-units', 'Newest\Student\CourseController@fetch_course_units');