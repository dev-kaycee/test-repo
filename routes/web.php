<?php

use App\Http\Controllers\Tenant\SmmeController;
use Illuminate\Support\Facades\Route;

//base64

Route::get('/', function () {
	return view('welcome');
})->name('landing');

Route::get('book-demo', function () {
	return view('book-demo');
})->name('book-demo');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login')->name('auth2.login');
$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Change Password Routes...
$this->get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
$this->patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index');
    
    Route::resource('roles', 'Admin\RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'Admin\UsersController');
		Route::resource('tenants', 'Admin\TenantsController');       //TODO
		Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::resource('teams', 'Admin\TeamsController');
    Route::post('teams_mass_destroy', ['uses' => 'Admin\TeamsController@massDestroy', 'as' => 'teams.mass_destroy']);
    Route::resource('products', 'Admin\ProductsController');
    Route::post('products_mass_destroy', ['uses' => 'Admin\ProductsController@massDestroy', 'as' => 'products.mass_destroy']);
    Route::post('products_restore/{id}', ['uses' => 'Admin\ProductsController@restore', 'as' => 'products.restore']);
    Route::delete('products_perma_del/{id}', ['uses' => 'Admin\ProductsController@perma_del', 'as' => 'products.perma_del']);


    Route::get('/team-select', ['uses' => 'Auth\TeamSelectController@select', 'as' => 'team-select.select']);
    Route::post('/team-select', ['uses' => 'Auth\TeamSelectController@storeSelect', 'as' => 'team-select.select']);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'tenant', 'as' => 'tenant.'], function () {
	Route::get('/home', 'Tenant\HomeController@index');

	Route::resource('roles', 'Admin\RolesController');
	Route::post('roles_mass_destroy', ['uses' => 'Admin\RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
	Route::resource('users', 'Admin\UsersController');
	Route::resource('tenants', 'Admin\TenantsController');       //TODO
	Route::post('users_mass_destroy', ['uses' => 'Admin\UsersController@massDestroy', 'as' => 'users.mass_destroy']);
	Route::resource('teams', 'Admin\TeamsController');
	Route::post('teams_mass_destroy', ['uses' => 'Admin\TeamsController@massDestroy', 'as' => 'teams.mass_destroy']);
	Route::resource('products', 'Admin\ProductsController');
	Route::post('products_mass_destroy', ['uses' => 'Admin\ProductsController@massDestroy', 'as' => 'products.mass_destroy']);
	Route::post('products_restore/{id}', ['uses' => 'Admin\ProductsController@restore', 'as' => 'products.restore']);
	Route::delete('products_perma_del/{id}', ['uses' => 'Admin\ProductsController@perma_del', 'as' => 'products.perma_del']);


	Route::get('/team-select', ['uses' => 'Auth\TeamSelectController@select', 'as' => 'team-select.select']);
	Route::post('/team-select', ['uses' => 'Auth\TeamSelectController@storeSelect', 'as' => 'team-select.select']);

	//projects
//	Route::resource('tenants', 'Admin\TenantsController');       //TODO
	Route::resource('projects', 'Tenant\ProjectsController');

	Route::resource('locations', 'Tenant\LocationController');
  //finance
	Route::resource('quotes', 'Tenant\QuoteController');
	Route::get('quotes/{quote}/pdf', ['uses' => 'Tenant\QuoteController@generatePDF', 'as' => 'quotes.pdf']);

	Route::resource('invoices', 'Tenant\InvoiceController');
	Route::get('invoices/{invoice}/pdf', ['uses' => 'Tenant\InvoiceController@generatePDF', 'as' => 'invoices.pdf']);

	//assets types
	Route::resource('asset-types', 'Tenant\AssetTypeController');
	//assets
	Route::resource('assets', 'Tenant\AssetController');
	Route::get('assets/search', ['uses' => 'Tenant\AssetController@search', 'as' => 'assets.search']);

	//SMMES
	Route::resource('smmes',  'Tenant\SmmeController');
	$this->get('/smmes/search', ['uses' => 'Tenant\SmmeController@search', 'as' => 'smmes.search']);


});