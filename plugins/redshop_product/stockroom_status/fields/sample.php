<?php
/**
 * @package     Redshop
 * @subpackage  Plugin.redshop_product
 *
 * @copyright   Copyright (C) 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Form Field class to show Sample Invoice PDF template
 * Supports a one line text field.
 *
 * @since  1.5
 */
class JFormFieldSample extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'Sample';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		$html = <<<EOF
<table>
  <tbody>
  <tr>
 	 <td colspan="4">Hello Administrator,</td>
  </tr>
  <tr>
  	<td colspan="4">The following product/s have reached minimum stock level.</td>
  </tr>
  <tr>
    <td colspan="4">
      <table border="1">
      <tbody>
        <tr>
          <td>Product Number</td>
          <td>Product Name</td>
          <td>Stockroom Name</td>
          <td>Current Stock</td>
        </tr>
        <!--  {product_loop_start} -->
        <tr>
          <td>{product_number}</td>
          <td>{product_name}</td>
          <td>{stockroom_name}</td>
          <td>{stock_status}</td>
        </tr>
        <!--  {product_loop_end} -->
      </tbody>
      </table>
    </td>
  </tr>
  <tr>
  	<td colspan="4">Regards,</td>
  </tr>
  <tr>
  	<td colspan="4">Stockkeeper</td>
  </tr>
  </tbody>
</table>
EOF;

		// Style needed for J2.5
		return '<pre style="float: left;width: 100%;">' . htmlentities($html) . '</pre>';
	}
}
