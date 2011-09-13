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
	private $from_identity;

	function init()
	{
		$this->include_script('identity_smtp.js');

		$this->add_hook('message_outgoing_headers', array($this, 'messageOutgoingHeaders'));
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
		$this->smtpLog("Save SMTP settings:");
		$this->smtpLog($args);

		$identities = rcmail::get_instance()->config->get('identity_smtp');

		if (!isset($identities))
		{
			$identities = array();
		}	

		$smtp_standard = get_input_value('_smtp_standard', RCUBE_INPUT_POST);
		$smtpSettingsRecord = array(
			'smtp_standard'		=> isset($smtp_standard),
			'smtp_server'		=> get_input_value('_smtp_server', RCUBE_INPUT_POST),
			'smtp_port'		=> get_input_value('_smtp_port', RCUBE_INPUT_POST),
			'smtp_user'		=> get_input_value('_smtp_user', RCUBE_INPUT_POST),
			'smtp_pass'		=> rcmail::get_instance()->encrypt(get_input_value('_smtp_pass', RCUBE_INPUT_POST)),
			'smtp_auth_type'	=> get_input_value('_smtp_auth_type', RCUBE_INPUT_POST),
			'smtp_helo_host'	=> get_input_value('_smtp_helo_host', RCUBE_INPUT_POST)
		);
	
		$id = intval($args['id']);
		unset($identities[$id]);
		$identities += array( $id => $smtpSettingsRecord );

		$this->smtpLog("New identities array:");
		$this->smtpLog($identities);

		rcmail::get_instance()->user->save_prefs(array('identity_smtp' => $identities));
	}

	function loadSmtpSettings($args)
	{
		$this->smtpLog("Load smpt settings:");
		$this->smtpLog($args);

		$smtpSettings = rcmail::get_instance()->config->get('identity_smtp');
		$id = intval($args['identity_id']);
		$smtpSettingsRecord = array(
			'smtp_standard'		=> $smtpSettings[$id]['smtp_standard'],
			'smtp_server'		=> $smtpSettings[$id]['smtp_server'],
			'smtp_port'		=> $smtpSettings[$id]['smtp_port'],
			'smtp_user'		=> $smtpSettings[$id]['smtp_user'],
			'smtp_pass'		=> rcmail::get_instance()->decrypt($smtpSettings[$id]['smtp_pass']),
			'smtp_auth_type'	=> $smtpSettings[$id]['smtp_auth_type'],
			'smtp_helo_host'	=> $smtpSettings[$id]['smtp_helo_host']
		);

		if (is_null($smtpSettingsRecord['smtp_standard'])) {
			$smtpSettingsRecord['smtp_standard'] = true;
		}

		$this->smtpLog($smtpSettingsRecord);

		return $smtpSettingsRecord;
	}

	function identityFormWillBeDisplayed($args)
	{
		$form = $args['form'];
		$record = $args['record'];

		# Load the stored smtp settings
		$smtpSettingsRecord = $this->loadSmtpSettings($record);

		if (!isset($record['identity_id']))
		{
			# FIX ME
			$smtpSettingsForm = array('smtpSettings' => array(
				'name' => $this->gettext('smtp_settings_header'),
				'content' => array(
					'text' => array('label' => $this->gettext('smtp_settings_not_available'), 'value' => ' ')
					)
				));
		} else {
			$smtpSettingsForm = array('smtpSettings' => array(
				'name' => $this->gettext('smtp_settings_header'),
				'content' => array(
					'smtp_standard'		=> array('type' => 'checkbox', 
									'label' => $this->gettext('use_default_smtp_server'),
									'onclick' => 'identity_smtp_toggle_standard_server()'
								),
					'smtp_server'		=> array('type' => 'text', 'class' => 'identity_smtp_form'),
					'smtp_port'		=> array('type' => 'text', 'class' => 'identity_smtp_form'),
					'smtp_user'		=> array('type' => 'text', 'class' => 'identity_smtp_form'),
					'smtp_pass'		=> array('type' => 'text', 'class' => 'identity_smtp_form'),
					'smtp_auth_type'	=> array('type' => 'text', 'class' => 'identity_smtp_form'),
					'smtp_helo_host'	=> array('type' => 'text', 'class' => 'identity_smtp_form')
				)
			));
			$this->smtpLog($smtpSettingsForm);
			if ($smtpSettingsRecord['smtp_standard'] || is_null($smtpSettingsRecord['smtp_standard'])) {
				foreach ($smtpSettingsForm['smtpSettings']['content'] as &$input) {
					if ($input['type'] == 'text') {
						$input['disabled'] = 'disabled';
					}
				}
			}
		}

		$form = $form + $smtpSettingsForm;
		$record = $record + $smtpSettingsRecord;
		
		$OUTPUT = array('form' => $form,
			'record' => $record);
		return $OUTPUT;
	}

	# This function is called when a new identity is created. We want to use the default smtp server here
	function identityWasCreated($args)
	{
		$this->smtpLog("Idenity was created:");
		$this->smtpLog($args);

		$this->saveSmtpSettings($args);
		return $args;
	}

	# This function is called when the users saves a changed identity. It is responsible for saving the smtp settings
	function identityWasUpdated($args)
	{
		$this->smtpLog("Idenity was updated:");
		$this->smtpLog($args);

		$this->saveSmtpSettings($args);
		return $args;
	}

	function identityWasDeleted($args)
	{
		$this->smtpLog("Idenity was deleted:");
		$this->smtpLog($args);

		$smtpSettings = rcmail::get_instance()->config->get('identity_smtp');
		$id = $args['id'];
		unset($smtpSettings[$id]);
		rcmail::get_instance()->user->save_prefs(array('identity_smtp' => $smtpSettings));

		# Return false to not abort the deletion of the identity
		return false;
	}

	function messageOutgoingHeaders($args)
	{
		$identities = rcmail::get_instance()->user->list_identities();
		foreach ($identities as $idx => $ident) {
			if ($identities[$idx]['email'] == $args['headers']['X-Sender']) {
				$this->from_identity = $identities[$idx]['identity_id'];
			}
		}

		return $args;
	}

	# This function is called when an email is sent and it should pull the correct smtp settings for the used identity and insert them
	function smtpWillConnect($args)
	{
		#$smtpSettings = $this->loadSmtpSettings(array('identity_id' => $this->from_identity));
		#if (!$smtpSettings['smtp_standard']) {
		#	$args['smtp_server'] = $smtpSettings['smtp_server'];
		#	$args['smtp_port'] = $smtpSettings['smtp_port'];
		#	$args['smtp_user'] = $smtpSettings['smtp_user'];
		#	$args['smtp_pass'] = rcmail::get_instance()->decrypt($smtpSettings['smtp_pass']);
		#	$args['smtp_auth_type'] = $smtpSettings['smtp_auth_type'];
		#	$args['smtp_helo_host'] = $smtpSettings['smtp_helo_host'];
		#}
		return $args;
	}
}
?>
