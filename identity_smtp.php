<?php
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
#	$this->add_hook('message_outgoing_headers', array($this, 'messageOutgoingHeaders'));
		$this->add_hook('smtp_connect', array($this, 'smtpWillConnect'));
		$this->add_hook('identity_form', array($this, 'identityFormWillBeDisplayed'));
		$this->add_hook('identity_create', array($this, 'identityWasCreated'));
		$this->add_hook('identity_update', array($this, 'identityWasUpdated'));
		$this->add_hook('identity_delete', array($this, 'identityWasDeleted'));
	}

	function smtpLog($message)
	{
		write_log("identity_smtp_plugin", $message);
	}

	function saveSmtpSettings($args)
	{
		$identities = rcmail::get_instance()->config->get('identity_smpt');

		if ($identities->) {
			rcmail::get_instance()->user->save_prefs(array('identity_smtp' => null));
	}	
		rcmail::get_instance()->user->save_prefs(array('identity_smtp' => $args));
	}

	function loadSmtpSettings($args)
	{

		$currentSettings = rcmail::get_instance()->config->get('identity_smpt');
		
		$smtpSettingsRecord = array(
			'smtp_standard'		=> '',
			'smtp_server'			=> '',
			'smtp_port'				=> '',
			'smtp_user'				=> '',
			'smtp_pass'				=> '',
			'smtp_auth_type'	=> '',
			'smtp_helo_host'	=> ''
		);

		return $smtpSettingsRecord;
	}

	function identityFormWillBeDisplayed($args)
	{
		$form = $args['form'];
		$record = $args['record'];
		
		$smtpSettingsForm = array('smtpSettings' => array(
			'name' => $this->gettext('smtp_settings_header'),
			'content' => array(
				'smtp_standard'		=> array('type' => 'checkbox', 'label' => $this->gettext('use_default_smtp_server')),
				'smtp_server'			=> array('type' => 'text'),
				'smtp_port'				=> array('type' => 'text'),
				'smtp_user'				=> array('type' => 'text'),
				'smtp_pass'				=> array('type' => 'text'),
				'smtp_auth_type'	=> array('type' => 'text'),
				'smtp_helo_host'	=> array('type' => 'text')
			)
		));

		$form = $form + $smtpSettingsForm;

		# Load the stored smtp settings
		$smtpSettingsRecord = $this->loadSmtpSettings(null);

		$record = $record + $smtpSettingsRecord;

		$OUTPUT = array('form' => $form,
				'record' => $record);

		return $OUTPUT;
	}

# This function is called when a new identity is created. We want to use the default smtp server here
	function identityWasCreated($args)
	{

		return $args;
	}

# This function is called when the users saves a changed identity. It is responsible for saving the smtp settings
	function identityWasUpdated($args)
	{
		$this->smtpLog($args);
		#get_input_value('myvar', RCUBE_INPUT_POST);
		return $args;
	}
	function identityWasDeleted($id)
	{
		# Return false to not abort the deletion of the identity
		return false;
	}

	function messageOutgoingHeaders($args)
	{
		
	}


# This function is called when an email is sent and it should pull the correct smtp settings for the used identity and insert them
	function smtpWillConnect($args)
	{
		return $args;
	}
}
?>
