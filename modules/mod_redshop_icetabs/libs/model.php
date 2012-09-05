<?php
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	mod_lofarticlesslideshow
 * @copyright	Copyright (C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
if( !class_exists('LofContentModelArticles') ) {
require_once( JPATH_SITE.DS.'components'.DS.'com_content'.DS.'models'. DS.'articles.php' );

	/**
	 * Lof ContentModelArticles Model Class
	 */
	class LofContentModelArticles extends ContentModelArticles {
		/**
		 * @var string $name;
		 *
		 * @access public
		 */
		var $name = 'LofContentModelArticles';
		
		/**
		 * override method
		 */
		function _getListQuery(){
			$query = parent::_getListQuery();
			$aId 	= $this->getState('filter.a_id');
	
			if (is_numeric($aId))
			{
				$type = $this->getState('filter.a_id.include', true) ? '= ' : '<>';
				$query->where('a.id '.$type.(int) $aId);
			}
			else if (is_array($aId))
			{
				JArrayHelper::toInteger($aId);
				$aId = implode(',', $aId);
				$type = $this->getState('filter.a_id.include', true) ? 'IN' : 'NOT IN';
				$query->where('a.id '.$type.' ('.$aId.')');
			}
			return $query;
		}
	}
}
?>