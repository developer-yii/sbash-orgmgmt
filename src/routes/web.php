<?php

use Sbash\Orgmgmt\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web','auth']], function() {
  	// uses 'auth' middleware plus all middleware from $middlewareGroups['web']
  	// keep web middleware before auth otherwise it wont work  	
	Route::get('/Organization/settings', [OrganizationController::class, 'settings'])->name('organization.settings');
    Route::post('/Organization/addUpdate', [OrganizationController::class, 'addUpdate'])->name('organization.addUpdate');
    Route::post('/Organization/nameCheck', [OrganizationController::class, 'checkName'])->name('organization.checkName');

    Route::get('/Organization/invite', [OrganizationController::class, 'invite'])->name('organization.invite');
    Route::post('/Organization/sendInvite', [OrganizationController::class, 'sendInvite'])->name('organization.send.invite');
});

Route::group(['middleware' => ['web','signed']], function() {
    Route::get('invite-link/{org}', [OrganizationController::class,'joinOrganization'])
   ->name('invite-link');   
});