<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');

/**
 * Renders a Productfinder Form
 *
 * @package        RedSHOP.Backend
 * @subpackage     Element
 * @since          1.5
 */
class JFormFieldTicket extends JFormField
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var        string
	 */
	protected $type = 'ticket';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		JText::script('PLG_REDSHOP_PAYMENT_QUICKBOOK_APP_ID_REQUIRED');
		RedshopConfig::script('SITE_URL', JUri::root());

		// Get system plugin params if available else return an error
		$quickBookSystem = JPluginHelper::getPlugin('system', 'quickbook');

		if (empty($quickBookSystem))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_SYSTEM'), 'error');
		}
		else
		{
			$quickBookSystemParams = new JRegistry($quickBookSystem->params);

			RedshopConfig::script('SECRET_WORD', $quickBookSystemParams->get('secretWord'));
		}

		// Set redshop config javascript header
		RedshopConfig::scriptDeclaration();

		JFactory::getDocument()->addScript(JUri::root(true) . '/plugins/redshop_payment/quickbook/media/js/quickbook.js');
		JFactory::getDocument()->addStyleSheet(
			JUri::root(true) . '/plugins/redshop_payment/quickbook/media/css/quickbook.css'
		);

		// Translate placeholder text
		$hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

		// Initialize some field attributes.
		$size         = !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
		$class        = !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$readonly     = $this->readonly ? ' readonly' : '';
		$disabled     = $this->disabled ? ' disabled' : '';
		$required     = $this->required ? ' required aria-required="true"' : '';
		$hint         = $hint ? ' placeholder="' . $hint . '"' : '';
		$autocomplete = !$this->autocomplete ? ' autocomplete="off"' : ' autocomplete="' . $this->autocomplete . '"';
		$autocomplete = $autocomplete == ' autocomplete="on"' ? '' : $autocomplete;
		$autofocus    = $this->autofocus ? ' autofocus' : '';
		$spellcheck   = $this->spellcheck ? '' : ' spellcheck="false"';
		$pattern      = !empty($this->pattern) ? ' pattern="' . $this->pattern . '"' : '';
		$inputmode    = !empty($this->inputmode) ? ' inputmode="' . $this->inputmode . '"' : '';
		$dirname      = !empty($this->dirname) ? ' dirname="' . $this->dirname . '"' : '';

		// Initialize JavaScript field attributes.
		$onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

		$html[] = '<div class="input-append">';

		$html[] = '<a href="#getTicketModal" role="button" id="get_connection_button" class="btn btn-primary" data-toggle="modal">'
					. JText::_('PLG_REDSHOP_PAYMENT_QUICKBOOK_GET_CONNECTION_TICKET_BUTTON')
				. '</a>';

		$html[] = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . $dirname . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $hint . $onchange . $maxLength . $required . $autocomplete . $autofocus . $spellcheck . $inputmode . $pattern . ' />';

		$html[] = '</div>';

		$html[] = $this->getModalHtml();

		return implode($html);
	}

	private function getModalHtml()
	{
		$html = '
		<div id="getTicketModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="getTicketModalLabel" aria-hidden="true">

			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			  <h1 id="getTicketModalLabel">' . JText::_("PLG_REDSHOP_PAYMENT_QUICKBOOK_GET_CONNECTION_TICKET_TITLE") . '</h1>
			</div>
			<div class="modal-body">
				<p>
					<ol>
						<li>Copy following URL which you need to add as a <b>Subscription URL<b> while you will create QBMS Application in next step.
							<pre>' . JUri::root() . 'index.php?option=com_redshop&view=plugin&type=redshop_payment&tmpl=component&task=setconnectionticket</pre>
						</li>
						<li>
							<p>Follow the steps on the application registration page here: https://developer.intuit.com/Application/Create/QBMS</p>
						</li>
						<li>
							<p>After you have developed your QBMS application, you need to attach your QBMS account to your application registration. You can do that by visiting the links below in a web browser.</p>
							<p>
								For <span class="label label-success">Production</span> applications visit this page in a web browser: <a target="_blank" id="app_id_link_production" href="">Click me to get connection ticket for Production</a>
							</p>
							<p>
								For <span class="label label-important">Development</span> applications, visit this page in a web browser: <a target="_blank" id="app_id_link_develop" href="">Click me to get connection ticket for Development</a>
							</p>
						</li>
					</ol>
				</p>
			</div>
			<div class="modal-footer" style="text-align:left;">
				<span>Yes, I have follow above steps!</span>
				<a href="#" class="btn btn-primary" id="generate_conn_ticket">Give me connection ticket</a>
			</div>
		</div>';

		return $html;
	}
}
