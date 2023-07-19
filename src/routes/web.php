<?php

use Sbash\Orgmgmt\Controllers\OrganizationController;
use Sbash\Orgmgmt\Controllers\OrganizationRequestController;
use Sbash\Orgmgmt\Controllers\InvitedUserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web','auth']], function() {
  	// uses 'auth' middleware plus all middleware from $middlewareGroups['web']
  	// keep web middleware before auth otherwise it wont work  	
	Route::get('/Organization/settings', [OrganizationController::class, 'settings'])->name('organization.settings');
    Route::post('/Organization/addUpdate', [OrganizationController::class, 'addUpdate'])->name('organization.addUpdate');
    Route::post('/Organization/nameCheck', [OrganizationController::class, 'checkName'])->name('organization.checkName');

    Route::get('/Organization/my-list', [OrganizationController::class, 'mylist'])->name('organization.mylist');
    Route::get('Organization/mylistget', [OrganizationController::class,'getMyList'])->name('organizations.getmylist');
    Route::get('/Organization/details', [OrganizationController::class, 'details'])->name('organization.details');

    Route::get('/Organization/invite', [OrganizationController::class, 'invite'])->name('organization.invite');
    Route::post('/Organization/sendInvite', [OrganizationController::class, 'sendInvite'])->name('organization.send.invite');

    Route::get('/Organization/members', [OrganizationController::class, 'members'])->name('organization.members');
    Route::get('Organization/members/get', [OrganizationController::class,'getMembers'])->name('organization.members.list');
    Route::post('/Organization/changeMemberType', [OrganizationController::class, 'changeMemberType'])->name('organization.changeMemberType');

    Route::get('Organization/join/list', [OrganizationController::class,'orgJoinList'])->name('organization.join.list');
    Route::get('Organization/join/get', [OrganizationController::class,'getOrgs'])->name('organizations.join.get');

    Route::post('Organization/join/request', [OrganizationRequestController::class,'joinRequest'])->name('organizations.join.request');

    Route::get('Organization/requests/list', [OrganizationRequestController::class,'index'])->name('organization.request.list');
    Route::get('Organization/requests/get', [OrganizationRequestController::class,'getOrgRequests'])->name('organizations.request.get');
    Route::post('Organization/requests/action', [OrganizationRequestController::class,'action'])->name('organizations.request.action');
    Route::post('Organization/requests/details', [OrganizationRequestController::class,'details'])->name('organization.request.details');
    

    Route::get('Organization/list', [OrganizationController::class,'list'])->name('organization.list');
    Route::get('Organization/get', [OrganizationController::class,'get'])->name('organizations.get');

    Route::get('/organization/edit', [OrganizationController::class, 'edit'])->name('organization.edit');
    Route::post('/organization/editUpdate', [OrganizationController::class, 'editUpdate'])->name('organization.editUpdate');
    Route::get('/organization/members', [OrganizationController::class,'memberlist'])->name('org.members.list');

    Route::post('/organization/remove-member', [InvitedUserController::class, 'remove'])->name('organization.member.remove');

});

Route::group(['middleware' => ['web','signed']], function() {
    Route::get('invite-link/{org}/{email}/{action}', [OrganizationController::class,'joinOrganization'])
   ->name('invite-link');   
});