<?php

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

Route::get('/', 'Frontend\IndexController@index')->name('index');

Auth::routes();
Route::get('/password/reset', 'Auth\ForgotPasswordController@showPage')->name('password.request');
Route::post('/password/reset', 'Auth\ForgotPasswordController@handler');
Route::post('/sms/send', 'Frontend\SmsController@send')->name('sms.send');

Route::get('/courses', 'Frontend\CourseController@index')->name('courses');
Route::get('/course/{id}/{slug}', 'Frontend\CourseController@show')->name('course.show');
Route::get('/course/{course_id}/video/{id}/{slug}', 'Frontend\VideoController@show')->name('video.show');

Route::post('/subscription/email', 'Frontend\IndexController@subscriptionHandler')->name('subscription.email');

Route::get('/vip', 'Frontend\RoleController@index')->name('role.index');

Route::get('/faq', 'Frontend\FaqController@index')->name('faq');
Route::get('/faq/category/{id}', 'Frontend\FaqController@showCategoryPage')->name('faq.category.show');
Route::get('/faq/article/{id}', 'Frontend\FaqController@showArticlePage')->name('faq.article.show');

// 支付回调
Route::post('/payment/callback', 'Frontend\PaymentController@callback')->name('payment.callback');

Route::group([
    'prefix' => '/member',
    'middleware' => ['auth'],
    'namespace' => 'Frontend'
], function () {
    Route::get('/', 'MemberController@index')->name('member');

    Route::get('/password_reset', 'MemberController@showPasswordResetPage')->name('member.password_reset');
    Route::post('/password_reset', 'MemberController@passwordResetHandler');
    Route::get('/avatar', 'MemberController@showAvatarChangePage')->name('member.avatar');
    Route::post('/avatar', 'MemberController@avatarChangeHandler');
    Route::get('/join_role_records', 'MemberController@showJoinRoleRecordsPage')->name('member.join_role_records');
    Route::get('/messages', 'MemberController@showMessagesPage')->name('member.messages');
    Route::get('/courses', 'MemberController@showBuyCoursePage')->name('member.courses');
    Route::get('/course/videos', 'MemberController@showBuyVideoPage')->name('member.course.videos');
    Route::get('/orders', 'MemberController@showOrdersPage')->name('member.orders');

    Route::post('/course/{id}/comment', 'CourseController@commentHandler')->name('course.comment');
    Route::post('/video/{id}/comment', 'VideoController@commentHandler')->name('video.comment');

    Route::post('/upload/image', 'UploadController@imageHandler')->name('upload.image');

    Route::get('/recharge', 'PaymentController@index')->name('member.recharge');
    Route::post('/recharge', 'PaymentController@rechargeHandler');
    Route::get('/recharge/records', 'MemberController@showRechargeRecordsPage')->name('member.recharge_records');

    Route::get('/course/{id}/buy', 'CourseController@showBuyPage')->name('member.course.buy');
    Route::post('/course/{id}/buy', 'CourseController@buyHandler');

    Route::get('/video/{id}/buy', 'VideoController@showBuyPage')->name('member.video.buy');
    Route::post('/video/{id}/buy', 'VideoController@buyHandler');

    Route::get('/vip/{id}/buy', 'RoleController@showBuyPage')->name('member.role.buy');
    Route::post('/vip/{id}/buy', 'RoleController@buyHandler');
});

// 后台登录
Route::get('/backend/login', 'Backend\AdministratorController@showLoginForm')->name('backend.login');
Route::post('/backend/login', 'Backend\AdministratorController@loginHandle');
Route::get('/backend/logout', 'Backend\AdministratorController@logoutHandle')->name('backend.logout');
// 修改密码
Route::get('/backend/edit/password', 'Backend\AdministratorController@showEditPasswordForm')->name('backend.edit.password');
Route::put('/backend/edit/password', 'Backend\AdministratorController@editPasswordHandle');

Route::group(['prefix' => 'backend', 'namespace' => 'Backend', 'middleware' => ['backend.login.check']], function () {
    // 主面板
    Route::get('/dashboard', 'DashboardController@index')->name('backend.dashboard.index');
    // 管理员
    Route::get('/administrator', 'AdministratorController@index')->name('backend.administrator.index');
    Route::get('/administrator/create', 'AdministratorController@create')->name('backend.administrator.create');
    Route::post('/administrator/create', 'AdministratorController@store');
    Route::get('/administrator/{id}/edit', 'AdministratorController@edit')->name('backend.administrator.edit');
    Route::put('/administrator/{id}/edit', 'AdministratorController@update');
    Route::get('/administrator/{id}/destroy', 'AdministratorController@destroy')->name('backend.administrator.destroy');
    // 角色
    Route::get('/administrator_role', 'AdministratorRoleController@index')->name('backend.administrator_role.index');
    Route::get('/administrator_role/create', 'AdministratorRoleController@create')->name('backend.administrator_role.create');
    Route::post('/administrator_role/create', 'AdministratorRoleController@store');
    Route::get('/administrator_role/{id}/edit', 'AdministratorRoleController@edit')->name('backend.administrator_role.edit');
    Route::put('/administrator_role/{id}/edit', 'AdministratorRoleController@update');
    Route::get('/administrator_role/{id}/destroy', 'AdministratorRoleController@destroy')->name('backend.administrator_role.destroy');
    Route::get('/administrator_role/{id}/permission', 'AdministratorRoleController@showSelectPermissionPage')->name('backend.administrator_role.permission');
    Route::post('/administrator_role/{id}/permission', 'AdministratorRoleController@handlePermissionSave');
    // 权限
    Route::get('/administrator_permission', 'AdministratorPermissionController@index')->name('backend.administrator_permission.index');
    Route::get('/administrator_permission/create', 'AdministratorPermissionController@create')->name('backend.administrator_permission.create');
    Route::post('/administrator_permission/create', 'AdministratorPermissionController@store');
    Route::get('/administrator_permission/{id}/edit', 'AdministratorPermissionController@edit')->name('backend.administrator_permission.edit');
    Route::put('/administrator_permission/{id}/edit', 'AdministratorPermissionController@update');
    Route::get('/administrator_permission/{id}/destroy', 'AdministratorPermissionController@destroy')->name('backend.administrator_permission.destroy');

    // 课程
    Route::get('/course', 'CourseController@index')->name('backend.course.index');
    Route::get('/course/create', 'CourseController@create')->name('backend.course.create');
    Route::post('/course/create', 'CourseController@store');
    Route::get('/course/{id}/edit', 'CourseController@edit')->name('backend.course.edit');
    Route::put('/course/{id}/edit', 'CourseController@update');
    Route::get('/course/{id}/delete', 'CourseController@destroy')->name('backend.course.destroy');
    // 视频
    Route::get('/video', 'CourseVideoController@index')->name('backend.video.index');
    Route::get('/video/create', 'CourseVideoController@create')->name('backend.video.create');
    Route::post('/video/create', 'CourseVideoController@store');
    Route::get('/video/{id}/edit', 'CourseVideoController@edit')->name('backend.video.edit');
    Route::put('/video/{id}/edit', 'CourseVideoController@update');
    Route::get('/video/{id}/delete', 'CourseVideoController@destroy')->name('backend.video.destroy');

    // 充值
    Route::get('/recharge', 'RechargeController@index')->name('backend.recharge');
    Route::get('/recharge/export', 'RechargeController@exportToExcel')->name('backend.recharge.export');

    // 会员
    Route::get('/member', 'MemberController@index')->name('backend.member.index');
    Route::get('/member/{id}', 'MemberController@show')->name('backend.member.show');

    // 公告
    Route::get('/announcement', 'AnnouncementController@index')->name('backend.announcement.index');
    Route::get('/announcement/create', 'AnnouncementController@create')->name('backend.announcement.create');
    Route::post('/announcement/create', 'AnnouncementController@store');
    Route::get('/announcement/{id}/edit', 'AnnouncementController@edit')->name('backend.announcement.edit');
    Route::put('/announcement/{id}/edit', 'AnnouncementController@update');
    Route::get('/announcement/{id}/delete', 'AnnouncementController@destroy')->name('backend.announcement.destroy');

    // 用户角色
    Route::get('/role', 'RoleController@index')->name('backend.role.index');
    Route::get('/role/create', 'RoleController@create')->name('backend.role.create');
    Route::post('/role/create', 'RoleController@store');
    Route::get('/role/{id}/edit', 'RoleController@edit')->name('backend.role.edit');
    Route::put('/role/{id}/edit', 'RoleController@update');
    Route::get('/role/{id}/delete', 'RoleController@destroy')->name('backend.role.destroy');

    // 邮件群发
    Route::get('/subscription_email', 'SubscriptionController@create')->name('backend.subscription.email');
    Route::post('/subscription_email', 'SubscriptionController@store');

    // 配置
    Route::get('/setting', 'SettingController@index')->name('backend.setting.index');
    Route::post('/setting', 'SettingController@saveHandler');

    // FAQ分类
    Route::get('/faq/category', 'FaqCategoryController@index')->name('backend.faq.category.index');
    Route::get('/faq/category/create', 'FaqCategoryController@create')->name('backend.faq.category.create');
    Route::post('/faq/category/create', 'FaqCategoryController@store');
    Route::get('/faq/category/{id}/edit', 'FaqCategoryController@edit')->name('backend.faq.category.edit');
    Route::put('/faq/category/{id}/edit', 'FaqCategoryController@update');
    Route::get('/faq/category/{id}/delete', 'FaqCategoryController@destroy')->name('backend.faq.category.destroy');

    // FAQ文章
    Route::get('/faq/article', 'FaqArticleController@index')->name('backend.faq.article.index');
    Route::get('/faq/article/create', 'FaqArticleController@create')->name('backend.faq.article.create');
    Route::post('/faq/article/create', 'FaqArticleController@store');
    Route::get('/faq/article/{id}/edit', 'FaqArticleController@edit')->name('backend.faq.article.edit');
    Route::put('/faq/article/{id}/edit', 'FaqArticleController@update');
    Route::get('/faq/article/{id}/delete', 'FaqArticleController@destroy')->name('backend.faq.article.destroy');

    // 图片上传
    Route::post('/upload/image', 'UploadController@uploadImageHandle')->name('backend.upload.image');

    // Ajax
    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function () {
        Route::get('/course', 'CourseController@index')->name('backend.ajax.course.index');
    });
});