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
		$this->add_hook('smtp_connect', array($this, 'setSmtpPerIdentity'));
		$this->add_hook('identity_form', array($this, 'addSmtpSettingsToIdentityForm'));
		$this->add_hook('identity_create', array($this, 'addSmtpSettingsToRecord'));
		$this->add_hook('identity_update', array($this, 'saveSmtpSettings'));
	}

	function addSmtpSettingsToIdentityForm($args)
	{
		$form = $args['form'];
		$record = $args['record'];
		
		$smtpSettingsForm = array('smtpSettings' => array(
			'name' => $this->gettext('smtp_settings_header'),
			'content' => array(
				'smtp_standard'		=> array('type' => 'checkbox', 'label' => $this->gettext('use_default_smtp_server')),
				'smtp_server'  		=> array('type' => 'text'),
				'smtp_port'				=> array('type' => 'text'),
				'smtp_user'				=> array('type' => 'text'),
				'smtp_pass'				=> array('type' => 'text'),
				'smtp_auth_type'	=> array('type' => 'text'),
				'smtp_helo_host'	=> array('type' => 'text')
			)
		));

		$form = $form + $smtpSettingsForm;

		$smtpSettingsRecord = array(
			'smtp_standard'		=> '1',
			'smtp_server'  		=> 'test',
			'smtp_port'				=> '',
			'smtp_user'				=> '',
			'smtp_pass'				=> '',
			'smtp_auth_type'	=> '',
			'smtp_helo_host'	=> ''
		);

		$record = $record + $smtpSettingsRecord;

		$OUTPUT = array('form' => $form,
				'record' => $record);

		return $OUTPUT;
	}

	function addSmtpSettingsToRecord($args)
	{
		write_log("identity.log",$args["record"]);
		return $args;
	}

	function saveSmtpSettings($args)
	{
		write_log("identity.log",$args["record"]);
		return $args;
	}

	function setSmtpPerIdentity($args)
	{
		return $args;
	}
}
?>
