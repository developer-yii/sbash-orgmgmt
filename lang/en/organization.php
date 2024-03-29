<?php

return [
	'header' => [
	    'organization' => 'Organization',
	    'settings' => 'Settings',
	    'org_setting' => 'Organization Settings',
	    'invite_org' => 'Invite to Organization',
	    'org_members' => 'Organization Members',
	    'org_list' => 'Organization List',
	    'join_organization' => 'Join Organization',
	    'join_requests' => 'Join Requests',
	    'my_org_list' => 'My Organizations',
	],
	'label' => [
		'org_edit' => 'Organization Edit',
	],
	'table' =>[
		'member_type' => 'Member Type',
		'access_type' => 'Access Type',
		'action' => 'Action',
		'user' => 'User',
		'created' => 'Created At',
		'user_name' => 'User Name',
		'user_email' => 'User Email',
		'status' => 'Status',
	],
	'page' => [
		'add_organization' => 'Add Organization',
	],
	'form' => [
		'organization_name' => 'Organization Name',
		'short_name' => 'Short Name',
		'short_name_available' => 'Short name available',
		'public_pages' => 'Public pages',
		'email' => 'Email',
		'invite' => 'Invite',
		'owner' => 'Owner',
		'admin' => 'Admin',
		'member' => 'Member',
		'contributor' => 'Contributor',
		'inviting' => 'Inviting',
		'request' => 'Send Request',
		'requesting' => 'Sending Request',
		'email_forward' => 'Email Forward',
		'upload_logo' => 'Upload Logo',
		'enter_organization_name' => 'Enter Organization Name',
		'enter_short_name' => 'Enter Short Name',
		'enter_email' => 'Enter Email',
		'short_name_not_available' => 'Entered short name is not available',
		'processing' => 'Processing',
		'success' => 'Success',
		'error' => 'Error',
		'cancel' => 'Cancel',
		'close' => 'Close',
		'change' => 'Change',
		'signin' => 'Sign in',
		'save' => 'Save',
		'double_optin' => 'Opt In for Mail Communication',
		'add_note' => 'Add Note',
		'user_note' => 'User Note',
		'owner_note' => 'Owner Note',
		'pending' => 'Pending',
		'approve' => 'Approve',
		'reject' => 'Reject',
		'approval' => 'Approval',
		'default_footer' => 'Default Footer',
		'invite_note' => 'Invite Note',
		'description' => 'Description',
		'organizationinfo' => 'Display Organiziation Information'
	],
	'notification' => [
		'org_add_success' => 'Organization update success',
		'org_add_fail' => 'Organization update failed',	
		'invi_sent' => 'Invitation link sent',
		'join_request' => 'Organization Join request Sent',
		'join_request_failed' => 'Organization Join request failed',
		'already_member' => 'You are already member of requested Organization',
		'already_requested' => 'You have already requested to join this Organization',	
		'mem_type_changed' => 'Member type changed',
		'mem_type_change_fail' => 'Member type change failed',
		'no_view_set_perm' => 'You dont have permission to view settings',
		'no_invite_org_perm' => 'You dont have permission for invite to Organization',
		'no_member_view_perm' => 'You dont have permission to view Organization members',
		'no_member_type_change_perm' => 'You dont have permission to change member type',
		'no_org_list_perm' => 'You dont have permission to view Organizations',
		'no_org_edit_perm' => 'You dont have permission to edit Organizations',
		'no_org_found' => 'Organization not found!',
		'no_mem_list_perm' => 'You dont have permission to list Members',
		'request_approved' => 'Request Approved',
		'request_rejected' => 'Request Rejected',
		'status_update_fail' => 'Status change failed',
		'already_two_org_created' => 'You can not create more than 2 Organization',
		'member_remove_success' => 'Member Successfully removed from Organiziation',
		'member_remove_failed' => 'Member removal failed',
		'no_member_remove_perm' => 'You dont have permission to remove Member',
		'cant_remove_owner' => 'You cant remove Organization owner',
	],
	'validation' => [
		'email_not_registered' => 'Email is not registered in system',
		'org_text_1' => 'Organization not created, Please save Organization details in Organization/settings',
		'owner_org' => 'You are already owner of organization',
		'already_registered' => 'Email already member of organization',
		'org_val_1' => 'You can not change organizations main owners type',
		'sel_mem_type' => 'Select member type',
		'update_status' => 'Please update status',
		'already_sent' => 'Invite Already sent for joining organization',
		'invite_message_required' => 'Invite note is required',
		'select_image_file' => 'Please select an image file.'
	],
	'orgjoin' => [
		'text-1' => 'You have successfully joined Organization',
		'text-2' => 'Organization join failed',
		'text-3' => 'Already Member of Organization',
		'text-4' => 'is already member of',
		'text-5' => 'Login to Website, click below link',
		'text-6' => 'Organization Joining Request Rejected!',
		'text-7' => 'Organization Joining Request Already Rejected!',
		'copyright' => 'Copyright',
		'right_reserved' => 'All rights reserved',
	],
	'alert' => [
		'are_you_sure' => 'Are you sure to remove user from Organiziation?',
		'are_you_sure_sub' => 'You wont be able to reverse this!',
		'confirm_btn' => 'Yes, Remove User'
	],
	'title' => [
		'organizationinfo' => 'Display organization info on events page'
	]

];