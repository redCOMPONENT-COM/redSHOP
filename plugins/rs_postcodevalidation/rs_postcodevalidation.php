<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.plugin.plugin' );
 
/**
 * Example system plugin
 */
class plgSystemrs_postcodevalidation extends JPlugin
{
	/**
	* Constructor.
	*
	* @access protected
	* @param object $subject The object to observe
	* @param array   $config  An array that holds the plugin configuration
	* @since 1.0
	*/
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	 
	// Do some extra initialisation in this constructor if required
	}
	 
	/**
	* Do something onAfterDispatch
	*/
	function onAfterDispatch()
	{
		$redshop_option = JRequest::getVar ( 'option' );
		$redshop_view = JRequest::getVar ( 'view' );

		if($redshop_option == "com_redshop" && ($redshop_view == "checkout" || $redshop_view == "registration" || $redshop_view == "account_billto" || $redshop_view == "account_shipto"))
		{
			$document =& JFactory::getDocument();
			JHTML::Script('registration_uk.js', 'plugins/system/rs_postcodevalidation/js/',false);
			$headerstuff = $document->getHeadData();
			$scripts = $headerstuff['scripts'];
			foreach ($scripts as $path => $val)
			{
				if (strpos($path, 'registration.js') !== false) unset($scripts[$path]);
			}
			$headerstuff['scripts'] = $scripts;
			$document->setHeadData($headerstuff);
			
		}	
	}
}
?>
