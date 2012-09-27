<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';
defined('_JEXEC') or die('Restricted access');

/**
 * account_billtoController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class account_billtoController extends RedshopCoreController
{
    /**
     * cancel function
     *
     * @access public
     * @return void
     */
    public function cancel()
    {
        $this->setRedirect('index.php');
    }

    /**
     * loadProducts function
     *
     * @access public
     * @return manufacturer select box
     */
    public function loadProducts()
    {
        $get    = $this->input->getArray($_GET);
        $taskid = $get['taskid'];

        $model = $this->getModel();

        $brands = $model->loadCatProductsManufacturer($taskid);

        $manufac_data = ($this->input->getInt('manufacture_id', 0)); // Manufacture Select Id

        jimport('joomla.application.module.helper');
        $module           = JModuleHelper::getModule('redshop_search');
        $params           = new JRegistry($module->params);
        $enableAjaxsearch = $params->get('enableAjaxsearch');
        $javaFun          = "";
        if ($enableAjaxsearch)
        {
            $javaFun = "makeUrl();";
        }
        if (count($brands) > 0)
        {

            $manufac     = array();
            $manufac[]   = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT_MANUFACTURE'));
            $manufacdata = @array_merge($manufac, $brands);

            echo JText::_('COM_REDSHOP_SELECT_MANUFACTURE') . '<br/>' . JHTML::_('select.genericlist', $manufacdata, 'manufacture_id', 'class="inputbox" size="1" onChange="' . $javaFun . '" ', 'value', 'text', $manufac_data);
        }
    }

    /**
     * ajaxsearch function
     *
     * @access public
     * @return search product results
     */
    public function ajaxsearch()
    {

        $model  = $this->getModel();
        $detail = $model->getajaxData();

        $encoded = json_encode($detail);
        ob_clean();
        echo "{\"results\": " . $encoded . "}";
        exit;
    }
}
