<?php
/**
 * @package     RedSHOP
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Document class
 *
 * @since  1.0
 */
class RedshopHelperDocument
{
	/**
	 * Scripts marked as disabled
	 *
	 * @var  array
	 */
	protected static $disabledScripts = array('media/jui/js/bootstrap.js', 'media/jui/js/bootstrap.min.js');

	/**
	 * Stylesheets marked as disabled
	 *
	 * @var  array
	 */
	protected static $disabledStylesheets = array();

	/**
	 * Scripts that will be injected on top
	 *
	 * @var  array
	 */
	protected static $topScripts = array();

	/**
	 * Stylesheets that will be injected on top
	 *
	 * @var  array
	 */
	protected static $topStylesheets = array();

	/**
	 * Stylesheets that will be injected on bottom
	 *
	 * @var  array
	 */
	protected static $bottomStylesheets = array();

	/**
	 * Adds a linked script to the page
	 * This forces always use scripts versions
	 *
	 * @param   string  $url   URL to the linked script
	 * @param   string  $type  Type of script. Defaults to 'text/javascript'
	 * @param   boolean $defer Adds the defer attribute.
	 * @param   boolean $async Adds the async attribute.
	 *
	 * @return  self             Instance of $this to allow chaining
	 */
	public function addScript($url, $type = "text/javascript", $defer = false, $async = false)
	{
		$doc = JFactory::getDocument();
		$doc->addScriptVersion($url, null, $type, $defer, $async);

		return $this;
	}

	/**
	 * Add a script to the top of the document scripts
	 *
	 * @param   string  $url   URL to the linked script
	 * @param   string  $type  Type of script. Defaults to 'text/javascript'
	 * @param   boolean $defer Adds the defer attribute.
	 * @param   boolean $async Adds the async attribute.
	 *
	 * @return  self
	 */
	public function addTopScript($url, $type = "text/javascript", $defer = false, $async = false)
	{
		$script = array(
			'mime'  => $type,
			'defer' => $defer,
			'async' => $async
		);

		static::$topScripts[$url] = $script;

		return $this;
	}

	/**
	 * Add a script to the top of the document scripts
	 *
	 * @param   string  $url         URL to the linked style sheet
	 * @param   string  $type        Mime encoding type
	 * @param   string  $media       Media type that this stylesheet applies to
	 * @param   array   $attributes  Array of attributes
	 *
	 * @return  self
	 */
	public function addTopStylesheet($url, $type = 'text/css', $media = null, $attributes = array())
	{
		return self::addStylesheet('top', $url, $type, $media, $attributes);
	}

	/**
	 * Add a script to the bottom of the document scripts
	 *
	 * @param   string  $url         URL to the linked style sheet
	 * @param   string  $type        Mime encoding type
	 * @param   string  $media       Media type that this stylesheet applies to
	 * @param   array   $attributes  Array of attributes
	 *
	 * @return  self
	 */
	public function addBottomStylesheet($url, $type = 'text/css', $media = null, $attributes = array())
	{
		return self::addStylesheet('bottom', $url, $type, $media, $attributes);
	}

	/**
	 * Add a script to the bottom of the document scripts
	 *
	 * @param   string  $position    Position for put stylesheet.
	 * @param   string  $url         URL to the linked style sheet
	 * @param   string  $type        Mime encoding type
	 * @param   string  $media       Media type that this stylesheet applies to
	 * @param   array   $attributes  Array of attributes
	 *
	 * @return  self
	 */
	public function addStylesheet($position = 'top', $url = '', $type = 'text/css', $media = null, $attributes = array())
	{
		if (version_compare(JVERSION, '3.7.0', '<'))
		{
			$stylesheet = array(
				'mime'    => $type,
				'media'   => $media,
				'attribs' => $attributes
			);
		}
		else
		{
			$stylesheet = array('mime' => $type);

			if (!is_null($media))
			{
				$stylesheet['media'] = $media;
			}

			if (!empty($attributes))
			{
				$stylesheet['attribs'] = $attributes;
			}
		}

		if ($position == 'top')
		{
			static::$topStylesheets[$url] = $stylesheet;
		}
		else
		{
			static::$bottomStylesheets[$url] = $stylesheet;
		}

		return $this;
	}

	/**
	 * Clean header assets
	 *
	 * @return  void
	 */
	public function cleanHeader()
	{
		$this->cleanHeaderScripts();
		$this->cleanHeaderStylesheets();
		$this->injectTopScripts();
		$this->injectTopStylesheets();
		$this->injectBottomStylesheets();
	}

	/**
	 * Injects the pending scripts on the top of the scripts
	 *
	 * @return  self
	 */
	protected function injectTopScripts()
	{
		if (empty(static::$topScripts))
		{
			return $this;
		}

		$doc = JFactory::getDocument();

		$doc->_scripts = array_merge(static::$topScripts, $doc->_scripts);

		return $this;
	}

	/**
	 * Injects the top stylesheets on the top of the document stylesheets
	 *
	 * @return  self
	 */
	protected function injectTopStylesheets()
	{
		if (empty(static::$topStylesheets))
		{
			return $this;
		}

		$doc = JFactory::getDocument();

		$doc->_styleSheets = array_merge(static::$topStylesheets, $doc->_styleSheets);

		return $this;
	}

	/**
	 * Injects the bottom stylesheets on the bottom of the document stylesheets
	 *
	 * @return  self
	 */
	protected function injectBottomStylesheets()
	{
		if (empty(static::$bottomStylesheets))
		{
			return $this;
		}

		$doc = JFactory::getDocument();

		$doc->_styleSheets = array_merge($doc->_styleSheets, static::$bottomStylesheets);

		return $this;
	}

	/**
	 * Clear all the scripts marked as disabled
	 *
	 * @return  void
	 */
	protected function cleanHeaderScripts()
	{
		if (!empty(static::$disabledScripts))
		{
			foreach (static::$disabledScripts as $script)
			{
				$this->removeScript($script);
			}
		}
	}

	/**
	 * Clear all the stylesheets marked as disabled
	 *
	 * @return  void
	 */
	protected function cleanHeaderStylesheets()
	{
		if (!empty(static::$disabledStylesheets))
		{
			foreach (static::$disabledStylesheets as $stylesheet)
			{
				$this->removeStylesheet($stylesheet);
			}
		}
	}

	/**
	 * Disable Joomla Core JS mainly because before 3.2 it was using Mootools
	 *
	 * @param   boolean  $disableOnDebug  Disable it also when debug is enabled on config
	 *
	 * @return  void
	 */
	public function disableCoreJs($disableOnDebug = true)
	{
		$doc = JFactory::getDocument();

		if ($doc->_scripts)
		{
			$this->disableScript('/media/system/js/core.js');

			if ($disableOnDebug)
			{
				$this->disableScript('/media/system/js/core-uncompressed.js');
			}
		}
	}

	/**
	 * Avoid loading mootools in the current page
	 *
	 * @param   boolean  $disableCore     Disable core.js scripts
	 * @param   boolean  $disableOnDebug  Disable it also when debug is enabled on config
	 *
	 * @return  void
	 */
	public function disableMootools($disableCore = false, $disableOnDebug = true)
	{
		$doc = JFactory::getDocument();

		if ($doc->_scripts)
		{
			// Function used to replace window.addEvent()
			$doc->addScriptDeclaration("function do_nothing() { return; }");

			// Disable mootools javascript
			$this->disableScript('/media/system/js/mootools-core.js');
			$this->disableScript('/media/system/js/caption.js');
			$this->disableScript('/media/system/js/modal.js');
			$this->disableScript('/media/system/js/mootools.js');
			$this->disableScript('/plugins/system/mtupgrade/mootools.js');

			// Disabled mootools javascript when debugging site
			if ($disableOnDebug)
			{
				$this->disableScript('/media/system/js/mootools-core-uncompressed.js');
				$this->disableScript('/media/system/js/mootools-core-uncompressed.js');
				$this->disableScript('/media/system/js/caption-uncompressed.js');
			}

			// Disable Mootools More scripts too
			$this->disableMootoolsMore($disableOnDebug);

			// Core was using Mootools before v3.2
			if (version_compare(JVERSION, '3.2', 'lt'))
			{
				$this->disableCoreJs($disableOnDebug);
			}
		}

		// Disable css stylesheets
		if ($doc->_styleSheets)
		{
			$this->disableStylesheet('/media/system/css/modal.css');
		}
	}

	/**
	 * Disable mootools-more scripts
	 *
	 * @param   boolean  $disableOnDebug  Disable it also when debug is enabled on config
	 *
	 * @return  void
	 */
	public function disableMootoolsMore($disableOnDebug = true)
	{
		$doc = JFactory::getDocument();

		if ($doc->_scripts)
		{
			$this->disableScript('/media/system/js/mootools-more.js');

			if ($disableOnDebug)
			{
				$this->disableScript('/media/system/js/mootools-more-uncompressed.js');
			}
		}
	}

	/**
	 * Mark a script as disabled
	 *
	 * @param   string   $script          Script to disable
	 * @param   boolean  $disableOnDebug  Disable also uncompressed version
	 *
	 * @return  void
	 */
	public function disableScript($script, $disableOnDebug = true)
	{
		$script = trim($script);

		if ($script && !in_array($script, static::$disabledScripts))
		{
			array_push(static::$disabledScripts, $script);

			if ($disableOnDebug)
			{
				array_push(static::$disabledScripts, $this->getUncompressedPath($script));
			}
		}
	}

	/**
	 * Mark a stylesheet as disabled
	 *
	 * @param   string   $stylesheet      Stylesheets to disable
	 * @param   boolean  $disableOnDebug  Disable also uncompressed version
	 *
	 * @return  void
	 */
	public function disableStylesheet($stylesheet, $disableOnDebug = true)
	{
		$stylesheet = trim($stylesheet);

		if ($stylesheet && !in_array($stylesheet, static::$disabledStylesheets))
		{
			array_push(static::$disabledStylesheets, $stylesheet);

			if ($disableOnDebug)
			{
				array_push(static::$disabledScripts, $this->getUncompressedPath($stylesheet));
			}
		}
	}

	/**
	 * Get the route to an uncompressed asset bassed on the compressed path
	 *
	 * @param   string  $assetPath  Path to the asset
	 *
	 * @return  string
	 */
	protected function getUncompressedPath($assetPath)
	{
		$fileName      = basename($assetPath);
		$fileNameOnly  = pathinfo($fileName, PATHINFO_FILENAME);
		$fileExtension = pathinfo($assetPath, PATHINFO_EXTENSION);

		if (strrpos($fileNameOnly, '.min', '-4'))
		{
			$position = strrpos($fileNameOnly, '.min', '-4');
			$uncompressedFileName = str_replace('.min', '.', $fileNameOnly, $position);
			$uncompressedFileName  = $uncompressedFileName . $fileExtension;
		}
		else
		{
			$uncompressedFileName = $fileNameOnly . '-uncompressed.' . $fileExtension;
		}

		return str_replace($fileName, $uncompressedFileName, $assetPath);
	}

	/**
	 * Remove a script from the JDocument header
	 *
	 * @param   string  $script  Script path
	 *
	 * @return  void
	 */
	public function removeScript($script)
	{
		$doc = JFactory::getDocument();

		$script = trim($script);

		if (!empty($script))
		{
			$uri = JUri::getInstance();

			$relativePath   = trim(str_replace($uri->getPath(), '', JUri::root()), '/');
			$relativeScript = trim(str_replace($uri->getPath(), '', $script), '/');
			$relativeUrl    = str_replace($relativePath, '', $script);

			$mediaVersion = $doc->getMediaVersion();

			// Try to disable relative and full URLs
			unset($doc->_scripts[$script]);
			unset($doc->_scripts[$script . '?' . $mediaVersion]);

			unset($doc->_scripts[$relativeUrl]);
			unset($doc->_scripts[$relativeUrl . '?' . $mediaVersion]);

			unset($doc->_scripts[JUri::root(true) . $script]);
			unset($doc->_scripts[JUri::root(true) . $script . '?' . $mediaVersion]);

			unset($doc->_scripts[JUri::root(true) . '/' . $script]);
			unset($doc->_scripts[JUri::root(true) . '/' . $script . '?' . $mediaVersion]);

			unset($doc->_scripts[$relativeScript]);
			unset($doc->_scripts[$relativeScript . '?' . $mediaVersion]);
		}
	}

	/**
	 * Remove a stylesheet from the JDocument header
	 *
	 * @param   string  $stylesheet  URL to the stylesheet (both global/relative should work)
	 *
	 * @return  void
	 */
	public function removeStylesheet($stylesheet)
	{
		$stylesheet = trim($stylesheet);

		if (!empty($stylesheet))
		{
			$doc = JFactory::getDocument();
			$uri = JUri::getInstance();

			$relativePath   = trim(str_replace($uri->getPath(), '', JUri::root()), '/');
			$relativeStylesheet = trim(str_replace($uri->getPath(), '', $stylesheet), '/');
			$relativeUrl    = str_replace($relativePath, '', $stylesheet);

			$mediaVersion = $doc->getMediaVersion();

			// Try to disable relative and full URLs
			unset($doc->_styleSheets[$stylesheet]);
			unset($doc->_styleSheets[$stylesheet . '?' . $mediaVersion]);

			unset($doc->_styleSheets[$relativeUrl]);
			unset($doc->_styleSheets[$relativeUrl . '?' . $mediaVersion]);

			unset($doc->_styleSheets[JUri::root(true) . $stylesheet]);
			unset($doc->_styleSheets[JUri::root(true) . $stylesheet . '?' . $mediaVersion]);

			unset($doc->_styleSheets[JUri::root(true) . '/' . $stylesheet]);
			unset($doc->_styleSheets[JUri::root(true) . '/' . $stylesheet . '?' . $mediaVersion]);

			unset($doc->_styleSheets[$relativeStylesheet]);
			unset($doc->_styleSheets[$relativeStylesheet . '?' . $mediaVersion]);
		}
	}

	/**
	 * Redirect any non-existing method to JDocument
	 *
	 * @param   string  $method     Method called
	 * @param   array   $arguments  Arguments passed to the method
	 *
	 * @return  mixed
	 */
	public function __call($method, $arguments)
	{
		$doc = JFactory::getDocument();

		return call_user_func_array(array($doc, $method), $arguments);
	}
}
