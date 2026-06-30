<?php
/**
 * Russian CP1251 language file for Allowed Email Domains, SMF 2.0 non-UTF-8.
 */

if (!defined('SMF'))
	die('No direct access...');

$txt['aed_admin_title'] = 'Разрешённые почтовые домены';
$txt['aed_enabled'] = 'Включить ограничение почтовых доменов';
$txt['aed_enabled_desc'] = 'Если включено, регистрация пользователей и смена email пользователем в своём профиле разрешаются только для доменов, указанных ниже. Запрещённые домены и домены первого уровня имеют приоритет над разрешёнными.';
$txt['aed_allowed_domains'] = 'Разрешённые почтовые домены';
$txt['aed_allowed_domains_desc'] = 'Введите по одному полному домену в строке, например: example.com или company.org. Эти записи проверяются как точное совпадение домена после @.';
$txt['aed_allowed_tlds'] = 'Разрешённые домены первого уровня';
$txt['aed_allowed_tlds_desc'] = 'Введите по одному домену первого уровня в строке без точки, например: ru, com, org. Если указать ru, будут разрешены любые адреса в зоне .ru, кроме заблокированных ниже.';
$txt['aed_denied_domains'] = 'Запрещённые почтовые домены';
$txt['aed_denied_domains_desc'] = 'Введите по одному полному домену в строке, например: badmail.com или spam.example.com. Эти домены будут запрещены даже при разрешённом домене первого уровня.';
$txt['aed_denied_tlds'] = 'Запрещённые домены первого уровня';
$txt['aed_denied_tlds_desc'] = 'Введите по одному домену первого уровня в строке без точки, например: xyz, top, click. Эти домены первого уровня будут запрещены даже при наличии полного домена в списке разрешённых.';
$txt['aed_email_domain_not_allowed'] = 'Почтовый домен &quot;%s&quot; не разрешён на этом форуме.';
$txt['aed_test_title'] = 'Проверка текущих настроек';
$txt['aed_test_desc'] = 'Введите email, чтобы увидеть, будет ли он разрешён или заблокирован по текущим сохранённым настройкам. Если изменили списки выше, сначала сохраните их.';
$txt['aed_test_email'] = 'Email для проверки';
$txt['aed_test_button'] = 'Проверить email';
$txt['aed_test_empty'] = 'Введите email для проверки.';
$txt['aed_test_invalid'] = 'Адрес &quot;%s&quot; не содержит пригодный для проверки почтовый домен.';
$txt['aed_test_allowed_disabled'] = 'Разрешён: ограничения сейчас выключены. Определённый домен: &quot;%s&quot;.';
$txt['aed_test_blocked_denied_domain'] = 'Заблокирован: домен &quot;%s&quot; есть в списке запрещённых почтовых доменов.';
$txt['aed_test_blocked_denied_tld'] = 'Заблокирован: домен первого уровня &quot;%s&quot; для домена &quot;%s&quot; есть в списке запрещённых доменов первого уровня.';
$txt['aed_test_allowed_domain'] = 'Разрешён: домен &quot;%s&quot; есть в списке разрешённых почтовых доменов.';
$txt['aed_test_allowed_tld'] = 'Разрешён: домен первого уровня &quot;%s&quot; для домена &quot;%s&quot; есть в списке разрешённых доменов первого уровня.';
$txt['aed_test_blocked_no_match'] = 'Заблокирован: домена &quot;%s&quot; нет в списке разрешённых почтовых доменов, и домена первого уровня &quot;%s&quot; нет в списке разрешённых доменов первого уровня.';
$txt['aed_test_blocked_no_match_no_tld'] = 'Заблокирован: домена &quot;%s&quot; нет в списке разрешённых почтовых доменов, и для него нет подходящего домена первого уровня.';

$txt['aed_log_enabled'] = 'Логировать регистрации и смену email';
$txt['aed_log_enabled_desc'] = 'Если включено логирование, попытки регистрации и смены email пользователем в своём профиле записываются в <a href="%1$s">раздел лога ошибок форума для этого мода</a>.';
$txt['aed_log_action_registration'] = 'регистрация';
$txt['aed_log_action_profile_email_change'] = 'смена email в профиле';
$txt['aed_log_status_allowed'] = 'разрешено';
$txt['aed_log_status_blocked'] = 'заблокировано';
$txt['aed_log_restrictions_enabled'] = 'ограничения включены';
$txt['aed_log_restrictions_disabled'] = 'ограничения выключены';
$txt['aed_log_reason_empty_domain'] = 'нет пригодного почтового домена';
$txt['aed_log_reason_disabled'] = 'ограничения выключены';
$txt['aed_log_reason_denied_domain'] = 'запрещён полный домен';
$txt['aed_log_reason_denied_tld'] = 'запрещён домен первого уровня';
$txt['aed_log_reason_allowed_domain'] = 'разрешён полный домен';
$txt['aed_log_reason_allowed_tld'] = 'разрешён домен первого уровня';
$txt['aed_log_reason_no_allowed_match'] = 'нет совпадения с разрешённым доменом или доменом первого уровня';
$txt['aed_log_message'] = 'Разрешённые почтовые домены: %1$s; email=%2$s; домен=%3$s; домен первого уровня=%4$s; результат=%5$s; причина=%6$s; %7$s.';
$txt['errortype_aed_email'] = 'Разрешённые почтовые домены';
$txt['errortype_aed_email_desc'] = 'Попытки регистрации и смены email, записанные модом «Разрешённые почтовые домены».';
