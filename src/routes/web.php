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

    Route::get('/Organization/members', [OrganizationController::class, 'members'])->name('organization.members');
    Route::get('Organization/members/get', [OrganizationController::class,'getMembers'])->name('organization.members.list');
    Route::post('/Organization/changeMemberType', [OrganizationController::class, 'changeMemberType'])->name('organization.changeMemberType');

    Route::get('Organization/list', [OrganizationController::class,'list'])->name('organization.list');
    Route::get('Organization/get', [OrganizationController::class,'get'])->name('organizations.get');

    Route::get('/organization/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::post('/organization/editUpdate', [OrganizationController::class, 'editUpdate'])->name('organization.editUpdate');
    Route::get('/organization/members', [OrganizationController::class,'memberlist'])->name('org.members.list');
});

Route::group(['middleware' => ['web','signed']], function() {
    Route::get('invite-link/{org}/{email}', [OrganizationController::class,'joinOrganization'])
   ->name('invite-link');   
});