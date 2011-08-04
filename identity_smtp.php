/**
 * 
 * Per identity smtp settings
 *
 * Description
 *
 * @version 0.1
 * @author skweez.net
 * @url skweez.net
 *
 * MIT License
 *
 */

class identity_smtp extends rcube_plugin
{
	public $task = 'mail|settings';

	function init()
	{
		$this->add_hook('smtp_connect', array($this, 'setSmtpPerIdentity'));

		$rcmail = rcmail::get_instance();

		if ($rcmail->task == 'settings' && $rcmail->action == 'edit-identity')
		{
			
		}
	}

	function setSmtpPerIdentity($args)
	{
		return $args;
	}
}
