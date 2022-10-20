<?php

Route::redirect('/', '/login')->middleware('guest');

Auth::routes();

Route::get('/dashboard', 'DashboardController@index')->name('home');

Route::group(['middleware' => 'auth'], function(){
	Route::get('users', 'User\UsersController@index')->name('users.index');
	Route::post('users', 'User\UsersController@store')->name('users.store');
	Route::get('users/{user}/status', 'User\UsersController@toggleStatus')->name('users.status');
	Route::get('/profile', 'Profile\ProfileController@index')->name('profile.index');
	Route::patch('/profile', 'Profile\ProfileController@update')->name('profile.update');
	Route::get('/profile/password', 'Profile\ProfileController@getPasswordForm')->name('profile.password.index');
	Route::post('/profile/password', 'Profile\ProfileController@postPasswordForm')->name('profile.password.store');
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
Route::get('outlets/{outlet}/edit', 'Outlet\OutletsController@edit')->name('outlets.edit');
Route::post('/outlets', 'Outlet\OutletsController@store')->name('outlets.store');
Route::get('/outlets/{outlet}', 'Outlet\OutletsController@show')->name('outlets.show');
Route::patch('/outlets/{outlet}', 'Outlet\OutletsController@update')->name('outlets.update');
Route::delete('/outlets/{outlet}', 'Outlet\OutletsController@destroy')->name('outlets.destroy');
Route::post('/outlets/{outlet}/opening-balance', 'Outlet\OpeningBalanceController@store')
                ->name('outlets.opening-balance.store');
Route::patch('/outlets/opening-balance/{sales}', 'Outlet\OpeningBalanceController@update')
                ->name('outlets.opening-balance.update');

/**
 * Outlet Collections
 */
Route::get('collections', 'Outlet\CollectionsController@index')->name('collections.index');
Route::get('outlet/{outlet}/collections', 'Outlet\CollectionsController@show')->name('outlet.collections.index');
Route::post('collections', 'Outlet\CollectionsController@store')->name('collections.store');
Route::get('collections/{transaction}', 'Outlet\CollectionsController@edit')->name('collections.edit');
Route::patch('collections/{transaction}', 'Outlet\CollectionsController@update')->name('collections.update');
Route::delete('collections/{transaction}', 'Outlet\CollectionsController@destroy')->name('collections.destroy');

/**
 * Ready Sale
 */
Route::get('readysales', 'ReadySale\ReadySalesController@index')->name('readysales.index');
Route::get('readysales/{readySale}/edit', 'ReadySale\ReadySalesController@edit')->name('readysales.edit');
Route::post('readysales', 'ReadySale\ReadySalesController@store')->name('readysales.store');
Route::patch('readysales/{readySale}', 'ReadySale\ReadySalesController@update')->name('readysales.update');
Route::delete('readysales/{readySale}', 'ReadySale\ReadySalesController@destroy')->name('readysales.destroy');

/**
 * Vendor Management
 */
Route::get('/vendors', 'Vendors\VendorsController@index')->name('vendors.index');
Route::get('/vendor/excel', 'Vendors\VendorsController@excel')->name('vendors.excel');
Route::get('/vendor/pdf', 'Vendors\VendorsController@pdf')->name('vendors.pdf');
Route::get('/vendors/{vendor}', 'Vendors\VendorsController@show')->name('vendors.show');
Route::post('/vendors', 'Vendors\VendorsController@store')->name('vendors.store');
Route::put('/vendors/{vendor}', 'Vendors\VendorsController@update')->name('vendors.update');
Route::delete('/vendors/{vendor}', 'Vendors\VendorsController@destroy')->name('vendors.destroy');
Route::patch('/vendors/opening-balance/{purchase}', 'Vendors\OpeningBalanceController@update')->name('vendors.opening-balance.update');

/**
 * Vendor Payments
 */
Route::get('/vendor/{vendor}/payments', 'Vendors\PaymentsController@index')->name('vendor.payments.index');
Route::post('/vendor/{vendor}/payments', 'Vendors\PaymentsController@store')->name('vendor.payments.store');
Route::patch('/vendor/{transaction}/payments', 'Vendors\PaymentsController@update')->name('vendor.payments.update');
Route::delete('/vendor/{transaction}/payments', 'Vendors\PaymentsController@destroy')->name('vendor.payments.destroy');

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
Route::delete('sales/{sales}', 'Store\SalesController@destroy')->name('sales.destroy');
Route::patch('sales/{sales}', 'Store\SalesController@update')->name('sales.update');
Route::get('sales', 'Store\SalesController@index')->name('sales.index');
Route::patch('sales/{sales}/transactions', 'Sales\SalesTransactionsController@update')->name('sales.transactions.update');
Route::get('purchases', 'Store\PurchasesController@index')->name('purchases.index');
Route::get('purchases/{purchase}', 'Store\PurchasesController@show')->name('purchases.show');
Route::get('purchases/{purchase}', 'Store\PurchasesController@show')->name('purchases.show');
Route::post('purchases', 'Store\PurchasesController@store')->name('purchases.store');
Route::patch('purchases/{purchase}', 'Store\PurchasesController@update')->name('purchases.update');
Route::delete('purchases/{purchase}', 'Store\PurchasesController@destroy')->name('purchases.destroy');
Route::patch('purchases/{purchase}/transactions', 'Purchase\PurchaseTransactionsController@update')
       ->name('purchases.transactions.update');
Route::get('store-report', 'Store\StoreReportsController@index')->name('stores.report.index');
Route::get('store-report/export-to-pdf', 'Store\StoreReportsController@exportToPdf')->name('stores.report.export-pdf.index');

/**
 * Transactions (Purchase/Sales)
 */
Route::post('transactions/purchase/{purchase}', 'Transaction\PurchasesController@store')->name('purchases.transactions.store');
Route::post('transactions/sales/{sales}', 'Transaction\SalesController@store')->name('sales.transactions.store');
Route::delete('transactions/{transaction}', 'Transaction\TransactionsController@destroy')->name('transactions.destroy');

/**
 * Bankings
 */
Route::get('bankings', 'Banking\TransactionsController@index')->name('bankings.index');
Route::post('bankings', 'Banking\TransactionsController@store')->name('bankings.store');
Route::patch('transactions/{transaction}', 'Banking\TransactionsController@update')->name('transactions.update');