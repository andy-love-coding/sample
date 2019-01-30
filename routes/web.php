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
Route::get('/','StaticPagesController@home') -> name('home');
Route::get('/help','StaticPagesController@help') -> name('help');
Route::get('/about','StaticPagesController@about') -> name('about');

Route::get('signup','UsersController@create') -> name('signup');
Route::resource('users','UsersController'); // 会生成7个路由

Route::get('login', 'SessionsController@create')->name('login');        // 显示登录页面
Route::post('login', 'SessionsController@store')->name('login');        // 存储登录会话
// 用form表单不支持delete请求，硬要提交delete请求，则需加一个隐藏域：<input type="hidden" name="_method" value="DELETE"> 
// 可由 {{ method_field('DELETE') }} 来生成上一行的表单域
Route::delete('logout', 'SessionsController@destroy')->name('logout');  // 删除登录会话
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email'); // 用户激活路由

// 显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// 密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// 只生成 post:store 和 delete:destroy 2个路由
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);

Route::get('/users/{user}/followings', 'UsersController@followings')->name('users.followings');// 博主(关注人)列表
Route::get('/users/{user}/followers', 'UsersController@followers')->name('users.followers');// 粉丝列表

Route::post('/users/followers/{user}', 'FollowersController@store')->name('followers.store'); // 关注用户
Route::delete('/users/followers/{user}', 'FollowersController@destroy')->name('followers.destroy'); // 取消关注
