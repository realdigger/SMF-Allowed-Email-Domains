<?php
/**
 * Installer for Allowed Email Domains.
 */

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('No direct access...');

if (!function_exists('add_integration_function'))
	require_once($sourcedir . '/Subs.php');

aed_install_hooks(true);

function aed_install_hooks($install)
{
	global $sourcedir, $modSettings;

	$registration_hook = aed_install_is_smf21() ? 'integrate_register_check' : 'integrate_register';
	$registration_function = aed_install_is_smf21() ? 'aed_register_check' : 'aed_register';
	$profile_hook = aed_install_is_smf21() ? 'integrate_profile_save' : 'integrate_change_member_data';
	$profile_function = aed_install_is_smf21() ? 'aed_profile_save' : 'aed_change_member_data';
	$credits_hook = aed_install_is_smf21() ? 'integrate_credits' : 'integrate_menu_buttons';
	$credits_function = aed_install_is_smf21() ? 'aed_credits' : 'aed_credits_smf20';

	$hooks = array(
		'integrate_pre_include' => '$sourcedir/AllowedEmailDomains.php',
		'integrate_admin_areas' => 'aed_admin_areas',
		$registration_hook => $registration_function,
		$profile_hook => $profile_function,
		$credits_hook => $credits_function,
	);

	foreach ($hooks as $hook => $function)
		add_integration_function($hook, $function, true);

	$defaults = array(
		'aed_enabled' => 0,
		'aed_log_enabled' => 0,
		'aed_custom_error_message' => '',
		'aed_allowed_domains' => '',
		'aed_allowed_tlds' => '',
		'aed_denied_domains' => '',
		'aed_denied_tlds' => '',
	);

	$settings = array();
	foreach ($defaults as $setting => $default)
	{
		if (!isset($modSettings[$setting]))
			$settings[$setting] = $default;
	}

	if (!empty($settings))
		updateSettings($settings);
}


function aed_install_version_number($version)
{
	return preg_match('~(\d+\.\d+(?:\.\d+)?)~', (string) $version, $match) ? $match[1] : '0.0';
}

function aed_install_is_smf21()
{
	global $forum_version;

	if (defined('SMF_VERSION'))
		return version_compare(aed_install_version_number(SMF_VERSION), '2.1', '>=');

	if (!empty($forum_version))
		return version_compare(aed_install_version_number($forum_version), '2.1', '>=');

	return false;
}
