<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.controller' );
/**
 * search Controller
 *
 * @static
 * @package		redSHOP
 * @since 1.0
 */
class searchController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}
	/**
	 * cancel function
	 *
	 * @access public
	 * @return void
	 */
	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}
	/**
	 * display function
	 *
	 * @access public
	 * @return void
	 */
	function display()
	{
		parent::display();
	}
	/**
	 * loadProducts function
	 *
	 * @access public
	 * @return manufacturer select box
	 */
	function loadProducts(){

		$get = JRequest::get('get');
		$taskid = $get['taskid'];

		$model = $this->getModel();

		$brands = $model->loadCatProductsManufacturer($taskid);

		$manufac_data =  ( JRequest::getInt( 'manufacture_id', 0 ) ); // Manufacture Select Id

		jimport( 'joomla.application.module.helper' );
		$module = JModuleHelper::getModule( 'redshop_search' );
		$params = new JRegistry( $module->params );
		$enableAjaxsearch = $params->get('enableAjaxsearch');
		$javaFun = "";
		if($enableAjaxsearch)
			$javaFun = "makeUrl();";
		if (count($brands)>0){

			$manufac = array();
			$manufac[]   	= JHTML::_('select.option', '0',JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
			$manufacdata = @array_merge($manufac,$brands);

			echo JText::_('COM_REDSHOP_SELECT_MANUFACTURE').'<br/>'.JHTML::_('select.genericlist',$manufacdata,'manufacture_id','class="inputbox" size="1" onChange="'.$javaFun.'" ','value','text',$manufac_data);
		}
		exit;
	}
	/**
	 * ajaxsearch function
	 *
	 * @access public
	 * @return search product results
	 */
	function ajaxsearch(){

		$model = $this->getModel();
		$detail = $model->getajaxData();

		$encoded = json_encode($detail);
		ob_clean();
		echo "{\"results\": ".$encoded."}";
		exit;
	}
}