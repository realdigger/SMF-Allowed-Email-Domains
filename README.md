[![GitHub release](https://img.shields.io/github/release/realdigger/SMF-Allowed-Email-Domains.svg)](https://github.com/realdigger/SMF-Allowed-Email-Domains/releases)
[![SMF](https://img.shields.io/badge/SMF-2.0-blue.svg?style==flat)](https://simplemachines.org)
[![SMF](https://img.shields.io/badge/SMF-2.1-blue.svg?style==flat)](https://simplemachines.org)
[![Hooks](https://img.shields.io/badge/hooks%20only-✓-blue.svg?style==flat)]()
[![license](https://img.shields.io/github/license/realdigger/SMF-Allowed-Email-Domains.svg)]()

# Allowed Email Domains mod for SMF

- **Package:** Allowed Email Domains
- **License:** The MIT License (MIT) [https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)
- **Compatible with:** SMF 2.0, SMF 2.1
- **Languages:** English, Russian
- **Core edits:** none, integration hooks only
- **Current package version:** 1.5.3

Allowed Email Domains is an SMF package that restricts user registration and profile email changes by email domain.

## Installation

Download the tar.gz archive of the current mod version and install it through the standard SMF Package Manager.

Existing settings are preserved during reinstall and uninstall.

## Description

The modification allows an administrator to restrict which email domains can be used for new registrations and profile email changes.

The package works via SMF integration hooks only and does not modify the forum source code.

## Compatibility

- SMF 2.0.x
- SMF 2.1.x
- PHP 5.4-compatible syntax for SMF 2.0 installations.

## Features

- Enable or disable restrictions from the admin panel.
- Allow specific full email domains, for example `example.com`.
- Allow complete top-level domains, for example `ru`, `com`, `org`.
- Deny specific full email domains, for example `badmail.com`.
- Deny complete top-level domains, for example `xyz`, `top`, `click`.
- Built-in email tester on the settings page.
- Custom user-facing blocked-domain message with fallback to the language-file message.
- Optional logging of registration and profile email-change attempts to the standard forum error log.
- Quick link from settings to the filtered error-log section for this mod.

## Rule priority

Denied rules have priority over allowed rules.

Check order:

1. If restrictions are disabled, the email is allowed.
2. If the full email domain is in the denied domains list, the email is blocked.
3. If the email TLD is in the denied TLD list, the email is blocked.
4. If the full email domain is in the allowed domains list, the email is allowed.
5. If the email TLD is in the allowed TLD list, the email is allowed.
6. Otherwise, the email is blocked.

Example: if `com` is allowed as a TLD, `user@badmail.com` can still be blocked by adding `badmail.com` to the denied domains list.

## Admin settings

Open:

```text
Admin -> Configuration -> Allowed Email Domains
```

Settings:

- Enable email domain restrictions.
- Allowed mail domains: one full domain per line.
- Allowed top-level domains: one TLD per line.
- Denied mail domains: one full domain per line.
- Denied top-level domains: one TLD per line.
- Custom blocked-domain message shown to users. Leave empty to use the standard language-file message. Placeholders: `{email}`, `{domain}`, `{tld}`.
- Log registration and profile email-change attempts.

The settings page also contains a test field. Enter an email address and the package will show the result directly below the input field: allowed or blocked by the currently saved settings, including the reason and the final user-facing message for blocked emails.

When logging is enabled, attempts are stored in SMF's standard `log_errors` table with the custom type `aed_email`. They can be viewed through the standard error log filtered by this mod type:

```text
Admin -> Logs -> Error Log -> Allowed Email Domains
```

## Установка

Загрузите архив tar.gz актуальной версии мода и установите его через стандартный менеджер пакетов SMF.

При переустановке и удалении существующие настройки сохраняются.

## Описание

Мод позволяет ограничить регистрацию пользователей и смену email в профиле только разрешёнными почтовыми доменами.

Мод работает только через интеграционные хуки SMF и не изменяет исходный код форума.

## Совместимость

- SMF 2.0.x
- SMF 2.1.x
- Для SMF 2.0 используется синтаксис, совместимый с PHP 5.4.

## Возможности

- Включение и отключение ограничений в админке.
- Список разрешённых полных почтовых доменов, например `example.com`.
- Список разрешённых доменов первого уровня, например `ru`, `com`, `org`.
- Список запрещённых полных почтовых доменов, например `badmail.com`.
- Список запрещённых доменов первого уровня, например `xyz`, `top`, `click`.
- Встроенная проверка email на странице настроек.
- Своё сообщение пользователю при блокировке с возвратом к стандартному сообщению из языкового файла, если поле пустое.
- Вывод причины результата проверки: разрешён, заблокирован по домену, заблокирован по домену первого уровня, нет совпадения с разрешающими правилами и т.п.
- Опциональное логирование попыток регистрации и смены email в стандартный лог ошибок форума.
- Быстрая ссылка из настроек на раздел лога ошибок с фильтром по этому моду.

## Приоритет правил

Запрещающие правила имеют приоритет над разрешающими.

Порядок проверки:

1. Если ограничения выключены, email разрешается.
2. Если полный домен email есть в списке запрещённых доменов, email блокируется.
3. Если домен первого уровня email есть в списке запрещённых доменов первого уровня, email блокируется.
4. Если полный домен email есть в списке разрешённых доменов, email разрешается.
5. Если домен первого уровня email есть в списке разрешённых доменов первого уровня, email разрешается.
6. В остальных случаях email блокируется.

Пример: если домен первого уровня `com` разрешён, адрес `user@badmail.com` всё равно можно заблокировать, добавив `badmail.com` в список запрещённых доменов.

## Настройки

Страница настроек находится здесь:

```text
Админка -> Конфигурация -> Разрешённые почтовые домены
```

Доступные настройки:

- включить ограничения почтовых доменов;
- разрешённые почтовые домены: по одному полному домену на строку;
- разрешённые домены первого уровня: по одному домену первого уровня на строку;
- запрещённые почтовые домены: по одному полному домену на строку;
- запрещённые домены первого уровня: по одному домену первого уровня на строку;
- своё сообщение пользователю при блокировке. Если поле пустое, используется стандартное сообщение из языкового файла. Можно использовать подстановки `{email}`, `{domain}`, `{tld}`;
- включить логирование попыток регистрации и смены email.

На странице настроек есть тестовое поле. Введите email, и мод покажет результат проверки прямо под полем ввода. Проверка выполняется по текущим сохранённым настройкам. Для заблокированного адреса также выводится итоговое сообщение, которое будет показано пользователю.

## Логирование

Если включено логирование, попытки регистрации и смены email пользователем в своём профиле записываются в стандартный лог ошибок форума с типом `aed_email`. Просмотр записей доступен в разделе лога ошибок с фильтром по этому моду:

```text
Админка -> Логи -> Логи ошибок -> Разрешённые почтовые домены
```

В лог записываются действие, email, домен, домен первого уровня, результат проверки, причина и состояние ограничений.

## License / Лицензия

MIT License. See [LICENSE](LICENSE).
