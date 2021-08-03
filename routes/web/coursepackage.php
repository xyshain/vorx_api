<?php

/*
|--------------------------------------------------------------------------
| Course Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Resource
Route::post('course-package/detail/store', 'Program\CoursePackageController@store_course_package_detail');
Route::post('course-package/change-order', 'Program\CoursePackageController@change_order_package_detail');
Route::post('course-package/detail/update', 'Program\CoursePackageController@update_package_detail');
Route::resource('course-package', 'Program\CoursePackageController');
Route::get('course-package/delete/{id}', 'Program\CoursePackageController@destroy')->name('course-package.delete');
Route::get('course-packages/list', 'Program\CoursePackageController@course_package_list')->name('course-package.list');
Route::put('course-packages/store-update', 'Program\CoursePackageController@course_package_store_update')->name('course-package.store_update');
Route::get('course-packages/destroy/{id}', 'Program\CoursePackageController@course_package_details_destroy')->name('course-package.delete');
