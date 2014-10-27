<?php

namespace EllisLab\ExpressionEngine\Controllers\Settings;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use CP_Controller;

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2003 - 2014, EllisLab, Inc.
 * @license		http://ellislab.com/expressionengine/user-guide/license.html
 * @link		http://ellislab.com
 * @since		Version 3.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * ExpressionEngine CP Outgoing Email Settings Class
 *
 * @package		ExpressionEngine
 * @subpackage	Control Panel
 * @category	Control Panel
 * @author		EllisLab Dev Team
 * @link		http://ellislab.com
 */
class Email extends Settings {

	/**
	 * General Settings
	 */
	public function index()
	{
		$vars['sections'] = array(
			array(
				array(
					'title' => 'webmaster_email',
					'desc' => 'webmaster_email_desc',
					'fields' => array(
						'webmaster_email' => array('type' => 'text', 'required' => TRUE),
					)
				),
				array(
					'title' => 'webmaster_name',
					'desc' => 'webmaster_name_desc',
					'fields' => array(
						'webmaster_name' => array('type' => 'text')
					)
				),
				array(
					'title' => 'email_charset',
					'desc' => 'email_charset_desc',
					'fields' => array(
						'email_charset' => array('type' => 'text')
					)
				),
				array(
					'title' => 'mail_protocol',
					'desc' => 'mail_protocol_desc',
					'fields' => array(
						'mail_protocol' => array(
							'type' => 'dropdown',
							'choices' => array(
								'mail' => lang('php_mail'),
								'sendmail' => lang('sendmail'),
								'smtp' => lang('smtp')
							)
						)
					)
				),
			),
			'smtp_options' => array(
				array(
					'title' => 'smtp_server',
					'desc' => 'smtp_server_desc',
					'fields' => array(
						'smtp_server' => array('type' => 'text')
					)
				),
				array(
					'title' => 'smtp_username',
					'desc' => 'smtp_username_desc',
					'fields' => array(
						'smtp_username' => array('type' => 'text')
					)
				),
				array(
					'title' => 'smtp_password',
					'desc' => 'smtp_password_desc',
					'fields' => array(
						'smtp_password' => array('type' => 'text')
					)
				),
			),
			'sending_options' => array(
				array(
					'title' => 'mail_format',
					'desc' => 'mail_format_desc',
					'fields' => array(
						'mail_format' => array(
							'type' => 'dropdown',
							'choices' => array(
								'plain' => lang('plain_text'),
								'html' => lang('html')
							)
						)
					)
				),
				array(
					'title' => 'word_wrap',
					'desc' => 'word_wrap_desc',
					'fields' => array(
						'word_wrap' => array(
							'type' => 'inline_radio',
							'choices' => array(
								'y' => 'enable',
								'n' => 'disable'
							)
						)
					)
				),
			)
		);

		ee()->form_validation->set_rules(array(
			array(
				'field' => 'webmaster_email',
				'label' => 'lang:webmaster_email',
				'rules' => 'required|valid_email'
			),
			array(
				'field' => 'webmaster_name',
				'label' => 'lang:webmaster_name',
				'rules' => 'strip_tags|valid_xss_check'
			),
			array(
				'field' => 'smtp_server',
				'label' => 'lang:smtp_server',
				'rules' => 'callback__smtp_required_field'
			)
		));

		$base_url = cp_url('settings/email');

		if (AJAX_REQUEST)
		{
			ee()->form_validation->run_ajax();
			exit;
		}
		elseif (ee()->form_validation->run() !== FALSE)
		{
			if ($this->saveSettings($vars['sections']))
			{
				ee()->view->set_message('success', lang('preferences_updated'), lang('preferences_updated_desc'), TRUE);
			}

			ee()->functions->redirect($base_url);
		}
		elseif (ee()->form_validation->errors_exist())
		{
			ee()->view->set_message('issue', lang('settings_save_error'), lang('settings_save_error_desc'));
		}

		ee()->view->base_url = $base_url;
		ee()->view->ajax_validate = TRUE;
		ee()->view->cp_page_title = lang('outgoing_email');
		ee()->view->save_btn_text = 'btn_save_settings';
		ee()->view->save_btn_text_working = 'btn_save_settings_working';
		ee()->cp->render('settings/form', $vars);
	}

	// --------------------------------------------------------------------

	/**
	 * A validation callback for required email configuration strings only
	 * if SMTP is the selected protocol method
	 *
	 * @access	public
	 * @param	string	$str	the string being validated
	 * @return	boolean	Whether or not the string passed validation
	 **/
	public function _smtp_required_field($str)
	{
		if (ee()->input->post('mail_protocol') == 'smtp' && trim($str) == '')
		{
			ee()->form_validation->set_message('_smtp_required_field', lang('empty_stmp_fields'));
			return FALSE;
		}

		return TRUE;
	}
}
// END CLASS

/* End of file Email.php */
/* Location: ./system/EllisLab/ExpressionEngine/Controllers/Settings/Email.php */