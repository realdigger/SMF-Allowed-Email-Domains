Allowed Email Domains 1.5.2
===========================

This SMF modification restricts user registration and profile email changes to:
- explicitly allowed full email domains;
- allowed top-level domains.

It can also block:
- explicitly denied full email domains;
- denied top-level domains.

Denied full domains and denied TLDs have priority over allowed entries.
For example, if com is allowed as a TLD, badmail.com can still be blocked in the denied domains list.
If xyz is denied as a TLD, any address ending in .xyz is blocked even if a full .xyz domain is listed as allowed.

The package uses SMF integration hooks only and does not modify the forum source code.

Optional logging:
If enabled in the mod settings, every registration attempt and every profile email change handled by this mod is written to SMF's standard error log table with the custom type aed_email. Use the standard error log filtered by "Allowed Email Domains" to view these entries.


Admin settings:
Admin -> Configuration -> Allowed Email Domains

Settings:
1. Enable email domain restrictions.
2. Allowed mail domains, one full domain per line, e.g. example.com.
3. Allowed top-level domains, one TLD per line, e.g. ru, com, org.
4. Denied mail domains, one full domain per line, e.g. badmail.com.
5. Denied top-level domains, one TLD per line, e.g. xyz, top, click.
6. Log registration and profile email-change attempts.

Built-in tester:
The settings page includes a test field where an administrator can enter an email address and see whether it is allowed or blocked by the currently saved settings. The result is shown directly below the test email input field. The result includes the reason, such as denied full domain, denied TLD, allowed full domain, allowed TLD, disabled restrictions, or no allowed match.

Compatibility:
- SMF 2.0.x
- SMF 2.1.x

License:
MIT License. See LICENSE.
