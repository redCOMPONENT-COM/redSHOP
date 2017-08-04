<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * redSHOP template manager
 *
 * @package  RedSHOP
 * @since    2.5
 */
class Redtemplate
{
	/**
	 * @deprecated  2.0.3
	 */
	public $redshop_template_path;

	/**
	 * @deprecated  2.0.3
	 */
	protected static $templatesArray = array();

	/**
	 * @deprecated  2.0.3
	 */
	protected static $instance = null;

	/**
	 * Returns the RedTemplate object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  RedTemplate  The RedTemplate object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * Load initial files
	 */
	public function __construct()
	{
		$this->redshop_template_path = JPATH_SITE . '/components/com_redshop/templates';

		if (!is_dir($this->redshop_template_path))
		{
			jimport('joomla.filesystem.folder');
			chmod(JPATH_SITE . '/components/com_redshop', 0755);
			JFolder::create($this->redshop_template_path, 0755);
		}
	}

	/**
	 * Get Template Values
	 *
	 * @param   string  $name                  Name template hint
	 * @param   string  $templateSection       Template section
	 * @param   string  $descriptionSeparator  Description separator
	 * @param   string  $lineSeparator         Line separator
	 *
	 * @return array|string
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getTemplateValues($name, $templateSection, $descriptionSeparator,
	 * $lineSeparator) instead
	 */
	public static function getTemplateValues($name, $templateSection = '', $descriptionSeparator = '-', $lineSeparator = '<br />')
	{
		return RedshopHelperTemplate::getTemplateValues($name, $templateSection, $descriptionSeparator, $lineSeparator);
	}

	/**
	 * Method to get Template
	 *
	 * @param   string   $section  Set section Template
	 * @param   integer  $tId      Template Id
	 * @param   string   $name     Template Name
	 *
	 * @return  array              Template Array
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getTemplate($section, $tId, $name) instead
	 */
	public function getTemplate($section = '', $tId = 0, $name = "")
	{
		return RedshopHelperTemplate::getTemplate($section, $tId, $name);
	}

	/**
	 * Method to read Template from file
	 *
	 * @param   string   $section   Template Section
	 * @param   string   $fileName  Template File Name
	 * @param   boolean  $isAdmin   Check for administrator call
	 *
	 * @return  string              Template Content
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::readtemplateFile($section, $fileName, $isAdmin) instead
	 */
	public function readtemplateFile($section, $fileName, $isAdmin = false)
	{
		return RedshopHelperTemplate::readTemplateFile($section, $fileName, $isAdmin);
	}

	/**
	 * Method to get Template file path
	 *
	 * @param   string   $section   Template Section
	 * @param   string   $fileName  Template File Name
	 * @param   boolean  $isAdmin   Check for administrator call
	 *
	 * @return  string              Template File Path
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getTemplatefilepath($section, $fileName, $isAdmin) instead
	 */
	public function getTemplatefilepath($section, $fileName, $isAdmin = false)
	{
		return RedshopHelperTemplate::getTemplateFilePath($section, $fileName, $isAdmin);
	}

	/**
	 * Template View selector
	 *
	 * @param   string  $section  Template Section
	 *
	 * @return  string            Template Joomla view name
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getTemplateView($section) instead
	 */
	public function getTemplateView($section)
	{
		return RedshopHelperTemplate::getTemplateView($section);
	}

	/**
	 * Method to parse joomla content plugin onContentPrepare event
	 *
	 * @param   string  $string  Joomla content
	 *
	 * @return  string           Modified content
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::parseredSHOPplugin($string) instead
	 */
	public function parseredSHOPplugin($string = "")
	{
		return RedshopHelperTemplate::parseRedshopPlugin($string);
	}

	/**
	 * Collect Template Sections for installation
	 *
	 * @param   string   $templateName  Template Name
	 * @param   boolean  $setFlag       Set true if you want html special character in template content
	 *
	 * @return  string                   redSHOP Template Contents
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getInstallSectionTemplate($templateName, $setFlag) instead
	 */
	public function getInstallSectionTemplate($templateName, $setFlag = false)
	{
		return RedshopHelperTemplate::getInstallSectionTemplate($templateName, $setFlag);
	}

	/**
	 * Collect list of redSHOP Template
	 *
	 * @param   string  $sectionValue  Template Section selected value
	 *
	 * @return  array                 Template Section List options
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getTemplateSections($sectionValue) instead
	 */
	public function getTemplateSections($sectionValue = "")
	{
		return RedshopHelperTemplate::getTemplateSections($sectionValue);
	}

	/**
	 * Collect Mail Template Section Select Option Value
	 *
	 * @param   string  $sectionValue  Selected Section Name
	 *
	 * @return  array                 Mail Template Select list options
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getMailSections($sectionValue) instead
	 */
	public function getMailSections($sectionValue = "")
	{
		return RedshopHelperTemplate::getMailSections($sectionValue);
	}

	/**
	 * Collect redSHOP costume field section select list option
	 *
	 * @param   string  $sectionValue  Selected option Value
	 *
	 * @return  array                 Costume field Select list options
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getFieldSections($sectionValue) instead
	 */
	public function getFieldSections($sectionValue = "")
	{
		return RedshopHelperTemplate::getFieldSections($sectionValue);
	}

	/**
	 * Collect Costume field type select list options
	 *
	 * @param   string  $sectionValue  Selected field type section
	 *
	 * @return  array                 Costume field type option list
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::getFieldTypeSections($sectionValue) instead
	 */
	public function getFieldTypeSections($sectionValue = "")
	{
		return RedshopHelperTemplate::getFieldTypeSections($sectionValue);
	}

	/**
	 * Prepare Options for Select list
	 *
	 * @param   array   $options       Associative Options array
	 * @param   string  $sectionValue  Get single Section name
	 *
	 * @return  mixed   String or array based on $sectionValue
	 *
	 * @deprecated  2.0.0.3  Use RedshopHelperTemplate::prepareSectionOptions($options, $sectionValue) instead
	 */
	public function prepareSectionOptions($options, $sectionValue)
	{
		return RedshopHelperTemplate::prepareSectionOptions($options, $sectionValue);
	}
}
