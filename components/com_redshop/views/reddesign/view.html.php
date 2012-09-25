<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('restricted access');

require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');

class reddesignViewreddesign extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $mainframe;
        $document = JFactory::getDocument();
        JHTML::Script('jquery.js', 'components/com_reddesign/assets/js/', false);
        JHTML::Script('ui.js', 'components/com_reddesign/assets/js/', false);
        $document->addCustomTag('<script type="text/javascript">jQuery.noConflict();</script>');

        // css files
        $cssfile = "components" . DS . "com_reddesign" . DS . "assets" . DS . "css" . DS . "style.css";

        $html = "<link href=\"$cssfile\" rel=\"stylesheet\" type=\"text/css\" />";
        $mainframe->addCustomHeadTag($html);

        $option = JRequest::getVar('option', 'com_redshop');
        $Itemid = JRequest::getVar('Itemid');
        //$product_id = JRequest :: getVar('productid');
        // redshop product detail
        $pid = JRequest::getInt('pid');
        $cid = JRequest::getInt('cid');

        require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'helper.php');
        $redhelper = new redhelper();

        $chkprodesign = $redhelper->CheckIfRedProduct($pid);
        if (!$chkprodesign)
        {
            //	$mainframe->Redirect ( 'index.php?option=' . $option . '&view=product&pid='.$pid.'&cid='.$cid.'&Itemid='.$Itemid);
        }

        $model =& $this->getModel("reddesign");

        $product_detail = $model->getProductDetail($pid);
        $product_design = $model->getProductDesign($pid);

        $product_id = $product_design[0]->designtype_id;

        $designtypedetail = $redhelper->getDesignType($product_id);

        $templatedetail = $redhelper->getDesignTypeTemplate($designtypedetail->designtemplate);

        $list = array();

        // temporary product id treat as design id....
        $images      = $model->getDesignTypeImages($product_id);
        $optionimage = array();
        for ($i = 0; $i < count($images); $i++)
        {
            $optionimage[] = JHTML::_('select.option', $images[$i]->image_id, $images[$i]->image_name);
        }
        $lists["selimage"] = JHTML::_('select.genericlist', $optionimage, 'selimage', 'class="inputbox" size="1" ', 'value', 'text');

        // redshop product detail
        $this->assignRef('pid', $pid);
        $this->assignRef('cid', $cid);

        $this->assignRef('product_detail', $product_detail);

        $this->assignRef('designtype_detail', $designtypedetail);
        $this->assignRef('templatedetail', $templatedetail);
        $this->assignRef('image_id', $images[0]->image_id);
        $this->assignRef('design', $design);
        $this->assignRef('lists', $lists);
        parent::display($tpl);
    }
}
