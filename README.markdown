identity_smtp Roundcube Plugin
==============================

This roundcube plugin allows to setup identities with different smtp servers
than the servers default.

Installation
============

This plugin is available in the [Roundcube Plugin
repository](http://plugins.roundcube.net/packages/elm/identity_smtp).

Manual Installation
-------------------

    $ cd /path/to/roundcube/plugins
    $ git clone git://github.com/elm/Roundcube-SMTP-per-Identity-Plugin.git identity_smtp

The plugins folder must be named identity_smtp.

Add `identity_smtp` to `$rcmail_config['plugins']` in `config/main.inc.php`.

A default smtp server has to be set in `config/main.inc.php`. Otherwise
roundcube will not call any smtp function and the plugin will not work.

Usage
=====
In the indentities settings you can specify an alternative smtp server for every
identity to send mails. When composing a mail just choose the identity you want
to use to send a mail.

Examples
--------
### Gmail
* Server IP/Hostname: tls://smtp.gmail.com
* Server Port: 587
* Username: example@gmail.com
* Password: ...

Contact
=======
You can contact me at elm -at- skweez.net
