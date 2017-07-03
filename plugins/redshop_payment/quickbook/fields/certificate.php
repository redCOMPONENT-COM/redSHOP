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
 * Generate CSR certificate for quickbook integration
 *
 * @package     RedSHOP.Backend
 * @subpackage  Element
 * @since       1.5
 */
class JFormFieldCertificate extends JFormFieldText
{
	/**
	 * Element name
	 *
	 * @access    protected
	 * @var       string
	 */
	protected $type = 'certificate';

	/**
	 * Security word from system plugin to improved Security
	 *
	 * @access    protected
	 * @var       string
	 */
	protected $secretWord = null;

	/**
	 * Method to get the field input mark up.
	 *
	 * @return  string  The field input mark up.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		JText::script('PLG_REDSHOP_PAYMENT_QUICKBOOK_CERTIFICATE_TEXT_REQUIRED');
		RedshopHelperConfig::script('SITE_URL', JUri::root());

		// Get system plugin params if available else return an error
		$quickBookSystem = JPluginHelper::getPlugin('system', 'quickbook');

		if (!empty($quickBookSystem))
		{
			$quickBookSystemParams = new JRegistry($quickBookSystem->params);
			$this->secretWord      = $quickBookSystemParams->get('secretWord', false);
		}

		// Set redshop config javascript header
		RedshopHelperConfig::scriptDeclaration();

		$parentInput = parent::getInput();

		$html[] = RedshopLayoutHelper::render(
				'certificate',
				[
					'parentInput' => $parentInput
				],
				JPATH_SITE . '/plugins/redshop_payment/quickbook/layouts'
			);

		$html[] = $this->getModalHtml();

		return implode($html);
	}

	/**
	 * Get HTML for popup
	 *
	 * @return  html  Modal HTML code
	 */
	private function getModalHtml()
	{
		$title               = JText::_("PLG_REDSHOP_PAYMENT_QUICKBOOK_GENERATE_CERTIFICATE_MODAL_TITLE");
		$privateKeyButtonTxt = JText::_("PLG_REDSHOP_PAYMENT_QUICKBOOK_PRIVATE_KEY_BUTTON_TEXT");
		$pemKeyButtonTxt     = JText::_("PLG_REDSHOP_PAYMENT_QUICKBOOK_PEM_BUTTON_TEXT");
		$userAgreementText   = JText::_("PLG_REDSHOP_PAYMENT_QUICKBOOK_USER_AGREEMENT_TEXT");

		$html = RedshopLayoutHelper::render(
				'modal_certificate',
				[
					'title' 				=> $title,
					'privateKeyButtonTxt' 	=> $privateKeyButtonTxt,
					'pemKeyButtonTxt' 		=> $pemKeyButtonTxt,
					'userAgreementText'		=> $userAgreementText
				],
				JPATH_SITE . 'plugins/redshop_payment/quickbook/layouts/layouts'
			);

		return $html;
	}
}
