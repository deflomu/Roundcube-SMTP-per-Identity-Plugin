identity_smtp Roundcube Plugin
==============================

This roundcube plugin allows to setup identities with different smtp servers than the servers default.

This plugin works for me but it is not tested very well. Patches are welcome.

Installation
============
    $ cd /path/to/roundcube/plugins
    $ git clone git://github.com/elm/Roundcube-SMTP-per-Identity-Plugin.git identity_smtp

The plugins folder must be named identity_smtp.

Add `identity_smtp` to `$rcmail_config['plugins']` in `config/main.inc.php`.

Usage
=====
In the indentities settings you can specify an alternative smtp server for every identity to send mails. When composing a mail just choose the identity you want to use to send a mail.

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
