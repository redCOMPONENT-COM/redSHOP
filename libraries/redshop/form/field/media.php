<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Form.Field
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

require_once JPATH_LIBRARIES . '/redshop/library.php';

/**
 * Media field
 *
 * @since  2.1.0
 */
class RedshopFormFieldMedia extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  2.1.0
	 */
	public $type = 'Media';

	/**
	 * Input field attributes
	 *
	 * @var  array
	 */
	protected $attribs = array();

	/**
	 * Method to get the field input markup.
	 *
	 * @return   string   The field input markup.
	 */
	protected function getInput()
	{
		$mediaDOMId        = isset($this->element['media-dom']) ? (string) $this->element['media-dom'] : null;
		$mediaType         = isset($this->element['media-type']) ? (string) $this->element['media-type'] : null;
		$mediaSection      = isset($this->element['media-section']) ? (string) $this->element['media-section'] : null;
		$showGallery       = isset($this->element['media-gallery']) ? boolval((string) $this->element['media-gallery']) : false;
		$useMediaPath      = isset($this->element['media-newMediaPath']) ? boolval((string) $this->element['media-newMediaPath']) : false;
		$showAlternateText = isset($this->element['media-alternate']) ? boolval((string) $this->element['media-alternate']) : false;
		$referenceId       = isset($this->element['media-reference']) ? (int) $this->element['media-reference'] : 0;
		$mediaId           = isset($this->element['media-id']) ? (int) $this->element['media-id'] : 0;

		$media = RedshopEntityMediaImage::getInstance();

		if ($mediaId)
		{
			$media = RedshopEntityMediaImage::getInstance($mediaId);
		}

		$html = '';

		if ($showAlternateText)
		{
			$inputId  = 'dropzone_alternate_text[' . $mediaDOMId . ']';
			$inputId .= !empty($mediaId) ? '[media-' . $mediaId . ']' : '[]';
			$html     = '<div class="row-fluid">'
				. '<label>' . JText::_('COM_REDSHOP_MEDIA_ALTERNATE_TEXT') . '</label>'
				. '<input type="text" class="form-control"'
				. 'name="' . $inputId . '" value="' . $media->get('media_alternate_text') . '"'
				. ' placeholder="' . JText::_('COM_REDSHOP_TOOLTIP_MEDIA_ALTERNATE_TEXT') . '"/>'
				. '<p></p></div>';
		}

		$html .= RedshopHelperMediaImage::render(
			$mediaDOMId,
			$mediaType,
			$referenceId,
			$mediaSection,
			$media->get('media_name'),
			$showGallery,
			$useMediaPath,
			$mediaId
		);

		return $html;
	}
}
