<?php
/**
 * English language file for Allowed Email Domains.
 */

if (!defined('SMF'))
	die('No direct access...');

$txt['aed_admin_title'] = 'Allowed Email Domains';
$txt['aed_enabled'] = 'Enable email domain restrictions';
$txt['aed_enabled_desc'] = 'When enabled, user registration and users\' own profile email changes are allowed only for the domains configured below. Denied domains and denied TLDs override allowed entries.';
$txt['aed_allowed_domains'] = 'Allowed mail domains';
$txt['aed_allowed_domains_desc'] = 'Enter one full domain per line, for example: example.com or company.org. These entries match the email domain exactly.';
$txt['aed_allowed_tlds'] = 'Allowed top-level domains';
$txt['aed_allowed_tlds_desc'] = 'Enter one TLD per line without the leading dot, for example: ru, com, org. If ru is listed, any address ending in .ru is allowed unless blocked below.';
$txt['aed_denied_domains'] = 'Denied mail domains';
$txt['aed_denied_domains_desc'] = 'Enter one full domain per line, for example: badmail.com or spam.example.com. These entries are blocked even if their TLD is allowed.';
$txt['aed_denied_tlds'] = 'Denied top-level domains';
$txt['aed_denied_tlds_desc'] = 'Enter one TLD per line without the leading dot, for example: xyz, top, click. These entries are blocked even if a matching full domain is listed as allowed.';
$txt['aed_email_domain_not_allowed'] = 'Email domain &quot;%s&quot; is not allowed on this forum.';
$txt['aed_test_title'] = 'Test current settings';
$txt['aed_test_desc'] = 'Enter an email address to see whether it would be allowed or blocked by the currently saved settings. Save changed lists before testing them.';
$txt['aed_test_email'] = 'Email address to test';
$txt['aed_test_button'] = 'Test email';
$txt['aed_test_empty'] = 'Enter an email address to test.';
$txt['aed_test_invalid'] = 'The address &quot;%s&quot; does not contain a usable email domain.';
$txt['aed_test_allowed_disabled'] = 'Allowed: restrictions are currently disabled. Detected domain: &quot;%s&quot;.';
$txt['aed_test_blocked_denied_domain'] = 'Blocked: domain &quot;%s&quot; is in the denied mail domains list.';
$txt['aed_test_blocked_denied_tld'] = 'Blocked: TLD &quot;%s&quot; for domain &quot;%s&quot; is in the denied TLD list.';
$txt['aed_test_allowed_domain'] = 'Allowed: domain &quot;%s&quot; is in the allowed mail domains list.';
$txt['aed_test_allowed_tld'] = 'Allowed: TLD &quot;%s&quot; for domain &quot;%s&quot; is in the allowed TLD list.';
$txt['aed_test_blocked_no_match'] = 'Blocked: domain &quot;%s&quot; is not in the allowed mail domains list, and TLD &quot;%s&quot; is not in the allowed TLD list.';
$txt['aed_test_blocked_no_match_no_tld'] = 'Blocked: domain &quot;%s&quot; is not in the allowed mail domains list and has no usable TLD match.';

$txt['aed_log_enabled'] = 'Log registration and profile email-change attempts';
$txt['aed_log_enabled_desc'] = 'When enabled, every registration attempt and every profile email change handled by this mod is written to the <a href="%1$s">filtered forum error log for this mod</a>.';
$txt['aed_log_action_registration'] = 'registration';
$txt['aed_log_action_profile_email_change'] = 'profile email change';
$txt['aed_log_status_allowed'] = 'allowed';
$txt['aed_log_status_blocked'] = 'blocked';
$txt['aed_log_restrictions_enabled'] = 'restrictions enabled';
$txt['aed_log_restrictions_disabled'] = 'restrictions disabled';
$txt['aed_log_reason_empty_domain'] = 'no usable email domain';
$txt['aed_log_reason_disabled'] = 'restrictions are disabled';
$txt['aed_log_reason_denied_domain'] = 'denied full domain';
$txt['aed_log_reason_denied_tld'] = 'denied TLD';
$txt['aed_log_reason_allowed_domain'] = 'allowed full domain';
$txt['aed_log_reason_allowed_tld'] = 'allowed TLD';
$txt['aed_log_reason_no_allowed_match'] = 'no allowed domain or TLD match';
$txt['aed_log_message'] = 'Allowed Email Domains: %1$s; email=%2$s; domain=%3$s; TLD=%4$s; result=%5$s; reason=%6$s; %7$s.';
$txt['errortype_aed_email'] = 'Allowed Email Domains';
$txt['errortype_aed_email_desc'] = 'Registration and profile email-change attempts logged by the Allowed Email Domains mod.';
