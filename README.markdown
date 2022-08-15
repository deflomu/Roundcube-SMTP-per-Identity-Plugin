# identity_smtp Roundcube Plugin

This Roundcube plugin allows you to setup identities with different SMTP servers
from the RC installation's default.

## Installation

This plugin is available from [Packagist](https://packagist.org/packages/elm/identity_smtp).

### Manual Installation

    $ cd /path/to/roundcube/plugins
    $ git clone https://github.com/deflomu/Roundcube-SMTP-per-Identity-Plugin.git identity_smtp

To use a specific version:

    $ git checkout 1.6.0

Replace `1.6.0` with any version found [here](https://github.com/deflomu/Roundcube-SMTP-per-Identity-Plugin/tags)

The plugins folder must be named identity_smtp.

Add `identity_smtp` to `$rcmail_config['plugins']` in `config/main.inc.php`.

A default SMTP server has to be set in `config/main.inc.php`, otherwise
Roundcube will not call any SMTP function and the plugin will not work.

## Versions

Each version's Major + Minor will match the latest version of Roundcube that it's compatible with.
If you cannot find a version for your install, use the closest-matching newer version.

## Usage

In the `Identities` settings you can specify an alternative SMTP server for every
identity to send mail with. When composing an email, just choose the identity you
want to use to send mail.

## Examples

### Gmail

#### Roundcube 1.6+

* Server Host: `tls://smtp.gmail.com:587`
* Username: `example@gmail.com`
* Password: `HighlySecurePassw0rd`

#### Roundcube 1.5 & below

* Server IP/Hostname: `tls://smtp.gmail.com`
* Server Port: `587`
* Username: `example@gmail.com`
* Password: `HighlySecurePassw0rd`

Note: As of 30/MAY/2022 Google blocks sending email using your account's password.
To get a working password for sending email, please use an [App Specific Password](https://support.google.com/accounts/answer/185833?hl=en).
