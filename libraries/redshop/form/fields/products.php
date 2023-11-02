<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Element
 *
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Form\FormField;

/**
 * element for default product layout
 *
 * @package        Joomla
 * @subpackage     redSHOP
 * @since          1.5
 */
class JFormFieldProducts extends FormField
{
    /**
     * Element name
     *
     * @access    Public
     * @var        string
     */
    public $type = 'Products';

    protected function getInput()
    {
        $name      = $this->name;
        $fieldName = $this->name;

        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_redshop/tables');

        $product = JTable::getInstance('product_detail', 'Table');

        if ($this->value) {
            $product->load($this->value);
        } else {
            $product->product_name = Text::_('COM_REDSHOP_SELECT_A_PRODUCT');
        }

        Factory::getDocument()->addScriptDeclaration(
            "
            function jSelectProduct(id, title, object) {
                document.getElementById(object + '_id').value = id;
                document.getElementById(object + '_name').value = title;
                window.parent.SqueezeBox.close();
            }
        "
        );

        $footer = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">'
            . Text::_('JTOOLBAR_CLOSE') . '</button>';

        $value         = htmlspecialchars($product->product_name, ENT_QUOTES, 'UTF-8');
        $attributes[] = 'style="background: #ffffff;"';
        $attributes[] = ($this->required) ? 'required="required"' : '';
        $class[]      = ($this->required) ? 'required=' : '';
        $attributes    = array_merge($attributes, $class);
        $attributes    = trim(implode(' ', array_unique($attributes)));

        $html = '<div class="controls">';
        $html .= '<div class="input-group">';
        $html .= '<input type="text" class="form-control" id="' . $name . '_name" value="' . $value . '" ' . 'disabled="disabled"' . ' />';
        $html .= '<button data-bs-target="#selectProductModal" class="btn btn-primary" data-bs-toggle="modal">
                    <span class="icon-list icon-white" aria-hidden="true"></span> ' . Text::_('COM_REDSHOP_Select') . '
                  </button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<input type="hidden" id="' . $name . '_id" name="' . $fieldName . '" value="' . (int) $this->value . '"' . $attributes . ' />';

        $html .= HTMLHelper::_(
            'bootstrap.renderModal',
            'selectProductModal',
            [
                'title'       => Text::_('COM_REDSHOP_SELECT_A_PRODUCT'),
                'backdrop'    => 'static',
                'keyboard'    => true,
                'closeButton' => false,
                'footer'      => $footer,
                'url'         => Route::_('index.php?option=com_redshop&amp;view=product&amp;layout=element&amp;tmpl=component&amp;object=' . $name),
                'height'      => '400px',
                'width'       => '800px',
                'modalWidth'  => '70',
            ]
        );

        return $html;
    }
}
