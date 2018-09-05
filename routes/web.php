<?php

Route::redirect('/', '/login')->middleware('guest');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('home');

Route::group(['middleware' => 'auth'], function(){
	Route::get('users', 'User\UsersController@index')->name('users.index');
	Route::post('users', 'User\UsersController@store')->name('users.store');
	Route::get('users/{user}/status', 'User\UsersController@toggleStatus')->name('users.status');
	Route::get('/profile', 'Profile\ProfileController@index')->name('profile.index');
});

/**
 * Expense Management
 */
Route::get('/expenses/excel', 'Expense\ExpensesController@excel')->name('expenses.excel');
Route::get('/expenses/pdf', 'Expense\ExpensesController@pdf')->name('expenses.pdf');
Route::resource('/expenses', 'Expense\ExpensesController');

/**
 * Outlet Management
 */
Route::get('/outlets', 'Outlet\OutletsController@index')->name('outlets.index');
Route::get('/outlet/excel', 'Outlet\OutletsController@excel')->name('outlets.excel');
Route::get('/outlet/pdf', 'Outlet\OutletsController@pdf')->name('outlets.pdf');
Route::post('/outlets', 'Outlet\OutletsController@store')->name('outlets.store');
Route::get('/outlets/{outlet}', 'Outlet\OutletsController@show')->name('outlets.show');
Route::patch('/outlets/{outlet}', 'Outlet\OutletsController@update')->name('outlets.update');
Route::delete('/outlets/{outlet}', 'Outlet\OutletsController@destroy')->name('outlets.destroy');

/**
 * Vendor Management
 */
Route::get('/vendors', 'Vendor\VendorsController@index')->name('vendors.index');
Route::get('/vendor/excel', 'Vendor\VendorsController@excel')->name('vendors.excel');
Route::get('/vendor/pdf', 'Vendor\VendorsController@pdf')->name('vendors.pdf');
Route::get('/vendors/{vendor}', 'Vendor\VendorsController@show')->name('vendors.show');
Route::post('/vendors', 'Vendor\VendorsController@store')->name('vendors.store');
Route::put('/vendors/{vendor}', 'Vendor\VendorsController@update')->name('vendors.update');
Route::delete('/vendors/{vendor}', 'Vendor\VendorsController@destroy')->name('vendors.destroy');

/**
 * Product Management
 */
Route::resource('products', 'Product\ProductsController');

/**
 * Store Management & Store Report
 */
Route::get('store', 'Store\StoresController@index')->name('stores.index');
Route::get('sales/{sales}', 'Store\SalesController@show')->name('sales.show');
Route::post('sales', 'Store\SalesController@store')->name('sales.store');
Route::get('sales', 'Store\SalesController@index')->name('sales.index');
Route::get('purchases', 'Store\PurchasesController@index')->name('purchases.index');
Route::get('purchases/{purchase}', 'Store\PurchasesController@show')->name('purchases.show');
Route::get('purchases/{purchase}', 'Store\PurchasesController@show')->name('purchases.show');
Route::post('purchases', 'Store\PurchasesController@store')->name('purchases.store');
Route::get('store-report', 'Store\StoreReportsController@index')->name('stores.report.index');

/**
 * Transactions (Purchase/Sales)
 */
Route::post('transactions/purchase/{purchase}', 'Transaction\PurchasesController@store')->name('purchases.transactions.store');
Route::post('transactions/sales/{sales}', 'Transaction\SalesController@store')->name('sales.transactions.store');

/**
 * Bankings
 */
Route::get('bankings', 'Banking\TransactionsController@index')->name('bankings.index');
Route::post('bankings', 'Banking\TransactionsController@store')->name('bankings.store');
Route::patch('transactions/{transaction}', 'Banking\TransactionsController@update')->name('transactions.update');