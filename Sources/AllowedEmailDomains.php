<?php
/**
 * Allowed Email Domains
 *
 * Restricts user registration and user-owned profile email changes to allowed
 * mail domains and allowed top-level domains, with optional denied domain/TLD
 * lists that override allowed entries. Works via SMF integration hooks.
 *
 * @package AllowedEmailDomains
 * @version 1.5.2
 */

if (!defined('SMF'))
	die('No direct access...');


/**
 * Returns the mod copyright line for the SMF credits page.
 *
 * @return string HTML copyright line.
 */
function aed_credits_line()
{
	return '<a href="https://github.com/realdigger/SMF-Allowed-Email-Domains" target="_blank" class="new_win">Allowed Email Domains</a> &copy; 2026, digger';
}

/**
 * SMF 2.1 credits hook. Adds the mod copyright line to the Modifications
 * section on the public credits page.
 */
function aed_credits()
{
	global $context;

	if (!isset($context['copyrights']) || !is_array($context['copyrights']))
		$context['copyrights'] = array();

	if (!isset($context['copyrights']['mods']) || !is_array($context['copyrights']['mods']))
		$context['copyrights']['mods'] = array();

	$line = aed_credits_line();
	if (!in_array($line, $context['copyrights']['mods']))
		$context['copyrights']['mods'][] = $line;
}

/**
 * SMF 2.0 menu buttons hook. Adds the mod copyright line to the Modifications
 * section on the public credits page.
 *
 * @param array $buttons Menu buttons definition.
 */
function aed_credits_smf20(&$buttons)
{
	global $context;

	if (empty($context['current_action']) || $context['current_action'] !== 'credits')
		return;

	if (!isset($context['copyrights']) || !is_array($context['copyrights']))
		$context['copyrights'] = array();

	if (!isset($context['copyrights']['mods']) || !is_array($context['copyrights']['mods']))
		$context['copyrights']['mods'] = array();

	$line = aed_credits_line();
	if (!in_array($line, $context['copyrights']['mods']))
		$context['copyrights']['mods'][] = $line;
}

/**
 * Adds a separate settings page to the administration menu.
 *
 * @param array $admin_areas SMF admin menu definition.
 */
function aed_admin_areas(&$admin_areas)
{
	global $txt;

	loadLanguage('AllowedEmailDomains');

	if (isset($admin_areas['config']) && isset($admin_areas['config']['areas']))
	{
		$admin_areas['config']['areas']['allowedemaildomains'] = array(
			'label' => $txt['aed_admin_title'],
			'function' => 'aed_admin_settings',
			'permission' => array('admin_forum'),
			'icon' => aed_is_smf21() ? 'modifications' : 'modifications.gif',
		);
	}
}

/**
 * Displays and saves the mod settings page.
 */
function aed_admin_settings()
{
	global $context, $scripturl, $sourcedir, $txt;

	isAllowedTo('admin_forum');

	loadLanguage('ManageSettings');
	loadLanguage('Help');
	loadLanguage('AllowedEmailDomains');

	require_once($sourcedir . '/ManageServer.php');

	$aed_log_desc = !empty($txt['aed_log_enabled_desc']) && strpos($txt['aed_log_enabled_desc'], '%1$s') !== false
		? sprintf($txt['aed_log_enabled_desc'], $scripturl . '?action=admin;area=logs;sa=errorlog;desc;filter=error_type;value=aed_email')
		: $txt['aed_log_enabled_desc'];

	$config_vars = array(
		array('check', 'aed_enabled', 'subtext' => $txt['aed_enabled_desc']),
		array('check', 'aed_log_enabled', 'subtext' => $aed_log_desc),
		'',
		array('large_text', 'aed_allowed_domains', 8, 'subtext' => $txt['aed_allowed_domains_desc']),
		array('large_text', 'aed_allowed_tlds', 4, 'subtext' => $txt['aed_allowed_tlds_desc']),
		'',
		array('large_text', 'aed_denied_domains', 8, 'subtext' => $txt['aed_denied_domains_desc']),
		array('large_text', 'aed_denied_tlds', 4, 'subtext' => $txt['aed_denied_tlds_desc']),
	);

	$context['page_title'] = $txt['aed_admin_title'];
	$context['settings_title'] = $txt['aed_admin_title'];
	$context['post_url'] = $scripturl . '?action=admin;area=allowedemaildomains;save';
	$context['sub_template'] = 'aed_settings';
	$context['aed_test_email'] = '';
	$context['aed_test_result'] = array();

	if (isset($_GET['save']))
	{
		checkSession();

		$_POST['aed_allowed_domains'] = isset($_POST['aed_allowed_domains']) ? aed_normalize_list($_POST['aed_allowed_domains'], false) : '';
		$_POST['aed_allowed_tlds'] = isset($_POST['aed_allowed_tlds']) ? aed_normalize_list($_POST['aed_allowed_tlds'], true) : '';
		$_POST['aed_denied_domains'] = isset($_POST['aed_denied_domains']) ? aed_normalize_list($_POST['aed_denied_domains'], false) : '';
		$_POST['aed_denied_tlds'] = isset($_POST['aed_denied_tlds']) ? aed_normalize_list($_POST['aed_denied_tlds'], true) : '';

		saveDBSettings($config_vars);

		if (aed_is_smf21())
			$_SESSION['adm-save'] = true;

		redirectexit('action=admin;area=allowedemaildomains');
	}

	if (isset($_GET['test']))
	{
		checkSession();

		$context['aed_test_email'] = isset($_POST['aed_test_email']) ? trim($_POST['aed_test_email']) : '';
		$context['aed_test_result'] = aed_build_test_result($context['aed_test_email']);
	}

	prepareDBSettingContext($config_vars);
}

/**
 * Combined settings page template: standard SMF settings plus the email tester.
 */
function template_aed_settings()
{
	if (function_exists('template_show_settings'))
		template_show_settings();

	template_aed_email_test();
}

/**
 * Renders the email test block for the admin settings page.
 */
function template_aed_email_test()
{
	global $context, $scripturl, $txt;

	$email = isset($context['aed_test_email']) ? $context['aed_test_email'] : '';
	$result = !empty($context['aed_test_result']) ? $context['aed_test_result'] : array();
	$class = !empty($result['class']) ? $result['class'] : 'infobox';
	$message = !empty($result['message']) ? $result['message'] : '';

	if (aed_is_smf21())
	{
		echo '
					<div class="cat_bar">
						<h3 class="catbg">', $txt['aed_test_title'], '</h3>
					</div>
					<div class="windowbg noup">
						<form action="', $scripturl, '?action=admin;area=allowedemaildomains;test" method="post" accept-charset="', $context['character_set'], '">
							<p class="smalltext">', $txt['aed_test_desc'], '</p>';

		echo '
							<dl class="settings">
								<dt><label for="aed_test_email">', $txt['aed_test_email'], '</label></dt>
								<dd>
									<input type="email" name="aed_test_email" id="aed_test_email" value="', aed_htmlspecialchars($email), '" size="40">
									<input type="submit" value="', $txt['aed_test_button'], '" class="button">';

		if ($message !== '')
			echo '
									<div class="', $class, '" style="margin-top: 0.5em;">', $message, '</div>';

		echo '
								</dd>
							</dl>
							<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '">
						</form>
					</div>';
	}
	else
	{
		echo '
	<br class="clear" />
	<div class="cat_bar">
		<h3 class="catbg">', $txt['aed_test_title'], '</h3>
	</div>
	<div class="windowbg2">
		<span class="topslice"><span></span></span>
		<div class="content">
			<form action="', $scripturl, '?action=admin;area=allowedemaildomains;test" method="post" accept-charset="', $context['character_set'], '">
				<p class="smalltext">', $txt['aed_test_desc'], '</p>';

		echo '
				<dl class="settings">
					<dt><label for="aed_test_email">', $txt['aed_test_email'], '</label></dt>
					<dd>
						<input type="text" name="aed_test_email" id="aed_test_email" value="', aed_htmlspecialchars($email), '" size="40" class="input_text" />
						<input type="submit" value="', $txt['aed_test_button'], '" class="button_submit" />';

		if ($message !== '')
			echo '
						<div class="', $class, '" style="margin-top: 0.5em;">', $message, '</div>';

		echo '
					</dd>
				</dl>
				<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
			</form>
		</div>
		<span class="botslice"><span></span></span>
	</div>';
	}
}

/**
 * SMF 2.1 registration validation hook.
 *
 * @param array $regOptions Registration options.
 * @param array $reg_errors Existing registration errors.
 */
function aed_register_check(&$regOptions, &$reg_errors)
{
	if (empty($regOptions['email']))
		return;

	$check = aed_check_email_against_settings($regOptions['email'], true);
	aed_log_email_attempt('registration', $regOptions['email'], $check);

	if (!aed_restrictions_enabled())
		return;

	if (empty($check['allowed']))
		$reg_errors[] = array('done', aed_error_message($regOptions['email']), false);
}

/**
 * SMF 2.0 registration hook.
 *
 * @param array $regOptions Registration options.
 * @param array $theme_vars Theme vars.
 */
function aed_register(&$regOptions, &$theme_vars)
{
	if (empty($regOptions['email']))
		return;

	$check = aed_check_email_against_settings($regOptions['email'], true);
	aed_log_email_attempt('registration', $regOptions['email'], $check);

	if (!aed_restrictions_enabled())
		return;

	if (empty($check['allowed']))
		fatal_error(aed_error_message($regOptions['email']), false);
}

/**
 * SMF 2.1 profile save hook. Applies only to users changing their own email.
 *
 * @param array $profile_vars Profile variables to save.
 * @param array $post_errors Profile form errors.
 * @param int $memID Member id being edited.
 * @param array $cur_profile Current profile values.
 * @param string $current_area Current profile area.
 */
function aed_profile_save(&$profile_vars, &$post_errors, $memID, $cur_profile, $current_area)
{
	global $user_info;

	if (empty($profile_vars['email_address']))
		return;

	// Requirement: restrict users changing email in their own profiles. Do not block admins editing other users.
	if (empty($user_info['id']) || (int) $memID !== (int) $user_info['id'])
		return;

	$check = aed_check_email_against_settings($profile_vars['email_address'], true);
	aed_log_email_attempt('profile_email_change', $profile_vars['email_address'], $check);

	if (!aed_restrictions_enabled())
		return;

	if (empty($check['allowed']))
		$post_errors[] = aed_error_message($profile_vars['email_address']);
}

/**
 * SMF 2.0 member data change hook. Applies only to users changing their own email.
 *
 * @param array $member_names Member names affected by updateMemberData.
 * @param string $var Member data field name.
 * @param mixed $data New value.
 */
function aed_change_member_data($member_names, $var, $data)
{
	global $user_info;

	if ($var !== 'email_address' || empty($data))
		return;

	if (empty($_REQUEST['action']) || $_REQUEST['action'] !== 'profile')
		return;

	if (!is_array($member_names))
		$member_names = array($member_names);

	// Requirement: restrict users changing email in their own profiles. Do not block admins editing other users.
	if (empty($user_info['username']) || !in_array($user_info['username'], $member_names))
		return;

	$check = aed_check_email_against_settings($data, true);
	aed_log_email_attempt('profile_email_change', $data, $check);

	if (!aed_restrictions_enabled())
		return;

	if (empty($check['allowed']))
		fatal_error(aed_error_message($data), false);
}


/**
 * Determines whether the mod is enabled.
 *
 * @return bool
 */
function aed_restrictions_enabled()
{
	global $modSettings;

	return !empty($modSettings['aed_enabled']);
}

/**
 * Checks whether an email address is allowed by the configured domain/TLD lists.
 *
 * Denied full domains and denied TLDs have priority over allowed entries.
 * After denied-list checks, the domain must match either the allowed full-domain
 * list or the allowed TLD list.
 *
 * @param string $email Email address.
 * @return bool
 */
function aed_email_is_allowed($email)
{
	$check = aed_check_email_against_settings($email, false);

	return !empty($check['allowed']);
}

/**
 * Checks an email address and returns a structured result.
 *
 * @param string $email Email address.
 * @param bool $respect_enabled Whether disabled restrictions should return allowed immediately.
 * @return array
 */
function aed_check_email_against_settings($email, $respect_enabled)
{
	$domain = aed_extract_email_domain($email);
	$tld = aed_extract_tld($domain);

	if ($domain === '')
		return array(
			'allowed' => true,
			'reason' => 'empty_domain',
			'domain' => '',
			'tld' => '',
		);

	if ($respect_enabled && !aed_restrictions_enabled())
		return array(
			'allowed' => true,
			'reason' => 'disabled',
			'domain' => $domain,
			'tld' => $tld,
		);

	$denied_domains = aed_get_list_setting('aed_denied_domains', false);
	if (in_array($domain, $denied_domains))
		return array(
			'allowed' => false,
			'reason' => 'denied_domain',
			'domain' => $domain,
			'tld' => $tld,
		);

	$denied_tlds = aed_get_list_setting('aed_denied_tlds', true);
	if ($tld !== '' && in_array($tld, $denied_tlds))
		return array(
			'allowed' => false,
			'reason' => 'denied_tld',
			'domain' => $domain,
			'tld' => $tld,
		);

	$allowed_domains = aed_get_list_setting('aed_allowed_domains', false);
	if (in_array($domain, $allowed_domains))
		return array(
			'allowed' => true,
			'reason' => 'allowed_domain',
			'domain' => $domain,
			'tld' => $tld,
		);

	$allowed_tlds = aed_get_list_setting('aed_allowed_tlds', true);
	if ($tld !== '' && in_array($tld, $allowed_tlds))
		return array(
			'allowed' => true,
			'reason' => 'allowed_tld',
			'domain' => $domain,
			'tld' => $tld,
		);

	return array(
		'allowed' => false,
		'reason' => 'no_allowed_match',
		'domain' => $domain,
		'tld' => $tld,
	);
}

/**
 * Builds the admin-page test result message for an email address.
 *
 * @param string $email Email address to test.
 * @return array
 */
function aed_build_test_result($email)
{
	global $txt;

	$email = trim((string) $email);
	if ($email === '')
		return array(
			'class' => 'errorbox',
			'message' => $txt['aed_test_empty'],
		);

	$check = aed_check_email_against_settings($email, true);
	$domain = !empty($check['domain']) ? $check['domain'] : '';
	$tld = !empty($check['tld']) ? $check['tld'] : '';

	switch ($check['reason'])
	{
		case 'empty_domain':
			return array(
				'class' => 'errorbox',
				'message' => sprintf($txt['aed_test_invalid'], aed_htmlspecialchars($email)),
			);

		case 'disabled':
			return array(
				'class' => 'infobox',
				'message' => sprintf($txt['aed_test_allowed_disabled'], aed_htmlspecialchars($domain)),
			);

		case 'denied_domain':
			return array(
				'class' => 'errorbox',
				'message' => sprintf($txt['aed_test_blocked_denied_domain'], aed_htmlspecialchars($domain)),
			);

		case 'denied_tld':
			return array(
				'class' => 'errorbox',
				'message' => sprintf($txt['aed_test_blocked_denied_tld'], aed_htmlspecialchars($tld), aed_htmlspecialchars($domain)),
			);

		case 'allowed_domain':
			return array(
				'class' => 'infobox',
				'message' => sprintf($txt['aed_test_allowed_domain'], aed_htmlspecialchars($domain)),
			);

		case 'allowed_tld':
			return array(
				'class' => 'infobox',
				'message' => sprintf($txt['aed_test_allowed_tld'], aed_htmlspecialchars($tld), aed_htmlspecialchars($domain)),
			);

		default:
			return array(
				'class' => 'errorbox',
				'message' => $tld === '' ? sprintf($txt['aed_test_blocked_no_match_no_tld'], aed_htmlspecialchars($domain)) : sprintf($txt['aed_test_blocked_no_match'], aed_htmlspecialchars($domain), aed_htmlspecialchars($tld)),
			);
	}
}

/**
 * Builds a localized error message for a blocked email domain.
 *
 * @param string $email Email address.
 * @return string
 */
function aed_error_message($email)
{
	global $txt;

	loadLanguage('AllowedEmailDomains');

	$domain = aed_extract_email_domain($email);
	if ($domain === '')
		$domain = $email;

	return sprintf($txt['aed_email_domain_not_allowed'], aed_htmlspecialchars($domain));
}

/**
 * Gets and normalizes a multiline setting from modSettings.
 *
 * @param string $setting Setting name.
 * @param bool $tld_mode Whether entries are TLDs.
 * @return array
 */
function aed_get_list_setting($setting, $tld_mode)
{
	global $modSettings;
	static $cache = array();

	$cache_key = $setting . '_' . ($tld_mode ? 'tld' : 'domain');
	if (isset($cache[$cache_key]))
		return $cache[$cache_key];

	$raw = isset($modSettings[$setting]) ? $modSettings[$setting] : '';
	$normalized = aed_normalize_list($raw, $tld_mode);
	$cache[$cache_key] = $normalized === '' ? array() : explode("\n", $normalized);

	return $cache[$cache_key];
}

/**
 * Normalizes admin-entered domain/TLD lists to one lowercase entry per line.
 *
 * @param string $raw Raw list.
 * @param bool $tld_mode Whether entries are TLDs.
 * @return string
 */
function aed_normalize_list($raw, $tld_mode)
{
	$raw = str_replace(array("\r\n", "\r"), "\n", (string) $raw);
	$parts = preg_split('~[\s,;]+~', $raw, -1, PREG_SPLIT_NO_EMPTY);
	$items = array();

	foreach ($parts as $part)
	{
		$item = aed_normalize_entry($part, $tld_mode);
		if ($item !== '' && !in_array($item, $items))
			$items[] = $item;
	}

	return implode("\n", $items);
}

/**
 * Normalizes one domain/TLD entry.
 *
 * @param string $entry Raw entry.
 * @param bool $tld_mode Whether entry is a TLD.
 * @return string
 */
function aed_normalize_entry($entry, $tld_mode)
{
	$entry = strtolower(trim((string) $entry));
	$entry = trim($entry, " \t\n\r\0\x0B.");

	if ($entry === '')
		return '';

	if (strpos($entry, '@') !== false)
		$entry = aed_extract_email_domain($entry);

	if ($tld_mode)
	{
		$entry = ltrim($entry, '.');
		if (strpos($entry, '.') !== false)
			$entry = aed_extract_tld($entry);

		return preg_match('~^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$~', $entry) ? $entry : '';
	}

	return preg_match('~^(?=.{1,253}$)(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$~', $entry) ? $entry : '';
}

/**
 * Extracts the domain part from an email address.
 *
 * @param string $email Email address.
 * @return string
 */
function aed_extract_email_domain($email)
{
	$email = strtolower(trim(strtr((string) $email, array('&#039;' => '\''))));
	$at = strrpos($email, '@');

	if ($at === false)
		return '';

	return trim(substr($email, $at + 1), " \t\n\r\0\x0B.");
}

/**
 * Extracts the last label from a domain.
 *
 * @param string $domain Domain name.
 * @return string
 */
function aed_extract_tld($domain)
{
	$domain = trim(strtolower((string) $domain), " \t\n\r\0\x0B.");
	if ($domain === '')
		return '';

	$parts = explode('.', $domain);
	return end($parts);
}


/**
 * Determines whether attempt logging is enabled.
 *
 * @return bool
 */
function aed_logging_enabled()
{
	global $modSettings;

	return !empty($modSettings['aed_log_enabled']);
}

/**
 * Logs a registration or profile email change attempt to SMF's error log table.
 *
 * @param string $attempt_type registration|profile_email_change.
 * @param string $email Email address involved in the attempt.
 * @param array $check Structured result from aed_check_email_against_settings().
 */
function aed_log_email_attempt($attempt_type, $email, $check)
{
	global $txt;

	if (!aed_logging_enabled())
		return;

	loadLanguage('AllowedEmailDomains');

	$domain = isset($check['domain']) && $check['domain'] !== '' ? $check['domain'] : '-';
	$tld = isset($check['tld']) && $check['tld'] !== '' ? $check['tld'] : '-';
	$reason = isset($check['reason']) ? $check['reason'] : 'unknown';
	$status = !empty($check['allowed']) ? $txt['aed_log_status_allowed'] : $txt['aed_log_status_blocked'];
	$action_key = $attempt_type === 'profile_email_change' ? 'aed_log_action_profile_email_change' : 'aed_log_action_registration';
	$reason_key = 'aed_log_reason_' . $reason;

	$message = sprintf(
		$txt['aed_log_message'],
		isset($txt[$action_key]) ? $txt[$action_key] : $attempt_type,
		aed_format_email_for_log($email),
		$domain,
		$tld,
		$status,
		isset($txt[$reason_key]) ? $txt[$reason_key] : $reason,
		aed_restrictions_enabled() ? $txt['aed_log_restrictions_enabled'] : $txt['aed_log_restrictions_disabled']
	);

	aed_insert_error_log($message);
}

/**
 * Inserts one mod entry into SMF's log_errors table using a custom error_type.
 *
 * @param string $message Log message.
 */
function aed_insert_error_log($message)
{
	global $sc, $user_info, $smcFunc, $scripturl, $last_error;

	if (empty($user_info['id']))
		$user_info['id'] = 0;
	if (empty($user_info['ip']))
		$user_info['ip'] = '';

	$message = strtr($message, array('<' => '&lt;', '>' => '&gt;', '"' => '&quot;'));
	$message = strtr($message, array('&lt;br /&gt;' => '<br />', '&lt;br&gt;' => '<br>', '&lt;b&gt;' => '<strong>', '&lt;/b&gt;' => '</strong>', "\n" => aed_is_smf21() ? '<br>' : '<br />'));

	$query_string = empty($_SERVER['QUERY_STRING']) ? (empty($_SERVER['REQUEST_URL']) ? '' : str_replace($scripturl, '', $_SERVER['REQUEST_URL'])) : $_SERVER['QUERY_STRING'];
	$query_string = aed_htmlspecialchars(((defined('SMF') && (SMF == 'SSI' || SMF == 'BACKGROUND')) ? '' : '?') . preg_replace(array('~;sesc=[^&;]+~', '~' . preg_quote(session_name(), '~') . '=' . preg_quote(session_id(), '~') . '[&;]~'), array(';sesc', ''), $query_string));

	if (isset($_POST['board']) && !isset($_GET['board']))
		$query_string .= ($query_string == '' ? 'board=' : ';board=') . $_POST['board'];

	$error_info = array((int) $user_info['id'], time(), $user_info['ip'], $query_string, $message, isset($sc) ? (string) $sc : '', 'aed_email', '', 0);

	if (!empty($last_error) && $last_error == $error_info)
		return;

	if (isset($smcFunc['db_error_insert']))
	{
		$insert_info = $error_info;
		$insert_info[] = '';
		$smcFunc['db_error_insert']($insert_info);
	}
	else
	{
		$smcFunc['db_insert']('',
			'{db_prefix}log_errors',
			array('id_member' => 'int', 'log_time' => 'int', 'ip' => 'string-16', 'url' => 'string-65534', 'message' => 'string-65534', 'session' => 'string', 'error_type' => 'string', 'file' => 'string-255', 'line' => 'int'),
			$error_info,
			array('id_error')
		);
	}

	$last_error = $error_info;
}

/**
 * Formats an email address for logging.
 *
 * @param string $email Email address.
 * @return string
 */
function aed_format_email_for_log($email)
{
	return trim((string) $email);
}

/**
 * HTML-escapes output in both SMF 2.0 and SMF 2.1.
 *
 * @param string $value Raw value.
 * @return string
 */
function aed_htmlspecialchars($value)
{
	global $smcFunc;

	if (isset($smcFunc['htmlspecialchars']))
		return $smcFunc['htmlspecialchars']($value);

	return htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
}

/**
 * Extracts a numeric SMF version from a version string.
 *
 * @param string $version Version string.
 * @return string
 */
function aed_version_number($version)
{
	return preg_match('~(\d+\.\d+(?:\.\d+)?)~', (string) $version, $match) ? $match[1] : '0.0';
}

/**
 * Detects SMF 2.1+.
 *
 * @return bool
 */
function aed_is_smf21()
{
	global $forum_version;

	if (defined('SMF_VERSION'))
		return version_compare(aed_version_number(SMF_VERSION), '2.1', '>=');

	if (!empty($forum_version))
		return version_compare(aed_version_number($forum_version), '2.1', '>=');

	return false;
}
