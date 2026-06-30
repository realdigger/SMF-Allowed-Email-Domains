<?php
/**
 * Uninstaller for Allowed Email Domains.
 */

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('No direct access...');

if (!function_exists('remove_integration_function'))
	require_once($sourcedir . '/Subs.php');

aed_uninstall_hooks();

function aed_uninstall_hooks()
{
	$hooks = array(
		'integrate_pre_include' => '$sourcedir/AllowedEmailDomains.php',
		'integrate_admin_areas' => 'aed_admin_areas',
		'integrate_register_check' => 'aed_register_check',
		'integrate_register' => 'aed_register',
		'integrate_profile_save' => 'aed_profile_save',
		'integrate_change_member_data' => 'aed_change_member_data',
		'integrate_credits' => 'aed_credits',
		'integrate_menu_buttons' => 'aed_credits_smf20',
	);

	foreach ($hooks as $hook => $function)
		remove_integration_function($hook, $function);
}
