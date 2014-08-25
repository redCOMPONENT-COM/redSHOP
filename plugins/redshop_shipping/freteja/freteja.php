<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');
/**
 * Joomla! System Logging Plugin
 *
 * @package        Joomla
 * @subpackage     System
 */
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

JLoader::import('LoadHelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminConfiguration');
JLoader::load('RedshopHelperAdminShipping');

class plgredshop_shippingfreteja extends JPlugin
{
	var $payment_code = "freteja";
	var $classname = "freteja";

	function onShowconfig($ps)
	{
		if ($ps->element == $this->classname)
		{
			?>
			<table class="admintable">
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FJ_HANDLING_FEE_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="FJ_Handling_Fee"
					           value="<?php echo FJ_Handling_Fee; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FJ_HANDLING_FEE_LBL'), JText::_('COM_REDSHOP_FJ_HANDLING_FEE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FJ_NCDEMPRESA_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="FJ_nCdEmpresa" value="<?php echo FJ_nCdEmpresa; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FJ_NCDEMPRESA_LBL'), JText::_('COM_REDSHOP_FJ_NCDEMPRESA_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>

				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FJ_SDSSENHA_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="FJ_sDsSenha" value="<?php echo FJ_sDsSenha; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FJ_SDSSENHA_LBL'), JText::_('COM_REDSHOP_FJ_SDSSENHA_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FJ_EMBARCADOR_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="FJ_Embarcador" value="<?php echo FJ_Embarcador; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FJ_EMBARCADOR_LBL'), JText::_('COM_REDSHOP_FJ_EMBARCADOR_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FJ_TIPOFRETE_LBL') ?></strong></td>
					<td><select name="FJ_TipoFrete">
							<option value="C" <?=(FJ_TipoFrete == 'C') ? 'selected="selected"' : ''?>>CIF ( Frete pago
								pelo Cliente )
							</option>
							<option value="F" <?=(FJ_TipoFrete == 'F') ? 'selected="selected"' : ''?>>FOB ( Frete pago
								pela Empresa )
							</option>
						</select>
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FJ_TIPOFRETE_LBL'), JText::_('COM_REDSHOP_FJ_TIPOFRETE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
				<tr class="row0">
					<td><strong><?php echo JText::_('COM_REDSHOP_FJ_PICKUPZIPCODE_LBL') ?></strong></td>
					<td><input class="inputbox" type="text" name="FJ_Order_Pickup_Postcode"
					           value="<?php echo FJ_Order_Pickup_Postcode; ?>">
					</td>
					<td><?php echo JHTML::tooltip(JText::_('COM_REDSHOP_FJ_PICKUPZIPCODE_LBL'), JText::_('COM_REDSHOP_FJ_PICKUPZIPCODE_LBL'), 'tooltip.png', '', '', false);?></td>
				</tr>
			</table>

			<?php

			return true;
		}
	}

	function onWriteconfig($d)
	{
		if ($d['element'] == $this->classname)
		{
			$maincfgfile = JPATH_ROOT . '/plugins/' . $d['plugin'] . '/' . $this->classname . '/' . $this->classname . '.cfg.php';

			$my_config_array = array(
				"FJ_Handling_Fee"          => $d['FJ_Handling_Fee'],
				"FJ_nCdEmpresa"            => $d['FJ_nCdEmpresa'],
				"FJ_sDsSenha"              => $d['FJ_sDsSenha'],
				"FJ_Embarcador"            => $d['FJ_Embarcador'],
				"FJ_TipoFrete"             => $d['FJ_TipoFrete'],
				"FJ_Order_Pickup_Postcode" => $d['FJ_Order_Pickup_Postcode']
				// END CUSTOM CODE
			);

			$config = '';
			$config = "<?php ";

			foreach ($my_config_array as $key => $value)
			{
				$config .= "define ('$key', '$value');\n";
			}

			$config .= "?>";

			if ($fp = fopen($maincfgfile, "w"))
			{
				fputs($fp, $config, strlen($config));
				fclose($fp);

				return true;
			}
			else
			{
				return false;
			}
		}
	}

	function onListRates(&$d)
	{
		include_once JPATH_ROOT . '/plugins/redshop_shipping/' . $this->classname . '/' . $this->classname . '.cfg.php';
		$shippinghelper = new shipping;
		$producthelper = new producthelper;
		$redconfig = new Redconfiguration;
		$rate = 0;
		$shipping = $shippinghelper->getShippingMethodByClass($this->classname);

		$shippingrate = array();

		if (isset($d['shipping_box_id']) && $d['shipping_box_id'])
		{
			$whereShippingBoxes = $shippinghelper->getBoxDimensions($d['shipping_box_id']);
		}
		else
		{
			$whereShippingBoxes = array();
			$productData = $shippinghelper->getProductVolumeShipping();
			$whereShippingBoxes['box_length'] = $productData[2]['length'];
			$whereShippingBoxes['box_width'] = $productData[1]['width'];
			$whereShippingBoxes['box_height'] = $productData[0]['height'];
		}

		// conversation of weight ( ration )
		$volRatio = $producthelper->getUnitConversation('mm', DEFAULT_VOLUME_UNIT);
		$unitRatio = $producthelper->getUnitConversation('gram', DEFAULT_WEIGHT_UNIT);

		$totaldimention = $shippinghelper->getCartItemDimention();
		$carttotalQnt = $totaldimention['totalquantity'];
		$carttotalWeight = $totaldimention['totalweight'];

		if (is_array($whereShippingBoxes) && count($whereShippingBoxes) > 0 && $volRatio > 0)
		{
			$carttotalLength = $whereShippingBoxes['box_length'] * $volRatio;
			$carttotalWidth = $whereShippingBoxes['box_width'] * $volRatio;
			$carttotalHeight = $whereShippingBoxes['box_height'] * $volRatio;
		}
		else
		{
			return $shippingrate;
		}

		// check for not zero
		if ($unitRatio != 0)
		{
			$carttotalWeight = $carttotalWeight * $unitRatio; // converting weight in kg
		}

		if ($carttotalWeight > 0)
		{
			$FJ_nCdEmpresa = FJ_nCdEmpresa;
			$FJ_sDsSenha = FJ_sDsSenha;
			$FJ_Embarcador = FJ_Embarcador;
			$FJ_TipoFrete = FJ_TipoFrete;
			$qtde_produtos = 1;
			$Order_Pickup_Postcode = FJ_Order_Pickup_Postcode;

			$shippinginfo = $shippinghelper->getShippingAddress($d['users_info_id']);

			if (count($shippinginfo) < 1)
			{
				return $shippingrate;
			}

			$billing = $producthelper->getUserInformation($shippinginfo->user_id);

			if (count($billing) < 1)
			{
				return $shippingrate;
			}

			$Order_Destination_Postcode = $shippinginfo->zipcode;
			$order_total = $d['ordertotal'];
			$params = array(
				"nCdEmpresa"        => $FJ_nCdEmpresa,
				"sDsSenha"          => $FJ_sDsSenha,
				/*"embarcador" 	   		=> $FJ_Embarcador,
				"destinatario" 	   		=> '00987551965',*/
				"tipo_frete"        => $FJ_TipoFrete,
				"volumes"           => $qtde_produtos,
				"StrRetorno"        => "xml",
				"sCepOrigem"        => $Order_Pickup_Postcode, # para testar = 95650000
				"sCepDestino"       => $Order_Destination_Postcode, # para testar = 08090284
				"nVlComprimento"    => $carttotalLength,
				"nVlAltura"         => $carttotalHeight,
				"nVlLargura"        => $carttotalWidth,
				"nVlPeso"           => $carttotalWeight,
				"nVlValorDeclarado" => $order_total,
				"nCdFormato"        => 1
			);
			$postdata = http_build_query($params);
			$url_busca = "http://www.freteja.com.br/cotac.php?" . $postdata;

			if (ini_get('allow_url_fopen') == '1')
			{
				$conteudo = file_get_contents(str_replace('&amp;', '&', $url_busca));

				if ($conteudo === false)
				{
					echo "WS FreteJa: Sistema Indisponível";

					return false;
				}
			}
			else
			{
				if (function_exists('curl_init'))
				{
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url_busca);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0');
					$conteudo = curl_exec($ch);
					$curl_erro = curl_errno($ch);

					if (curl_errno($ch) != 0)
					{
						echo "WS FreteJa erro CURL:" . curl_error($ch);

						return false;
					}
					curl_close($ch);
				}
				else
				{
					echo "WS FreteJa erro: Sem \"CURL lib\" e sem \"allow_url_fopen\"";

					return false;
				}
			}

			if ($conteudo != "")
			{
				$xml_FJ = new DomDocument;
				$dom = $xml_FJ->loadXML(trim($conteudo));

				$i = 0;
				while (isset($xml_FJ->getElementsByTagName('Codigo')->item($i)->nodeValue))
				{
					$erro = $xml_FJ->getElementsByTagName('MsgErro')->item($i)->nodeValue;
					$name = $xml_FJ->getElementsByTagName('Nome')->item($i)->nodeValue;
					$codigo = $xml_FJ->getElementsByTagName('Codigo')->item($i)->nodeValue;
					$prazo = $xml_FJ->getElementsByTagName('PrazoEntrega')->item($i)->nodeValue;
					$valor = $xml_FJ->getElementsByTagName('Valor')->item($i)->nodeValue;
					$APcharge = $valor;

					if ($erro != '')
					{
						echo "WS FreteJa erro: " . $erro . "";

						return false;
					}

					$desc_prazo = '';

					if ($prazo != '')
					{
						if ($prazo == 1)
						{
							$desc_prazo = "<b>Entrega</b>: " . $prazo . " dia util.";
						}
						else
						{
							$desc_prazo = "<b>Entrega</b>: " . $prazo . " dias úteis.";
						}
					}

					if ($APcharge == 0)
					{
						echo "WS FreteJa erro: valor do envio não disponível";

						return false;
					}

					$APcharge = str_replace(',', '.', $APcharge);
					$Total_Shipping_Handling = $APcharge + $Order_FJ_Handling_Fee;

					$shipping_rate_id = $shippinghelper->encryptShipping(__CLASS__ . "|" . $shipping->name . "|" . $name . "|" . number_format($Total_Shipping_Handling, 2, '.', '') . "|" . $codigo . "|single|0");
					$shippingrate[$rate]->text = $name . " " . $desc_prazo;
					$shippingrate[$rate]->value = $shipping_rate_id;
					$shippingrate[$rate]->rate = $Total_Shipping_Handling;
					$shippingrate[$rate]->vat = 0;
					$i++;
					$rate++;
				}
			}
			else
			{
				$error = false;
			}
		}

		return $shippingrate;
	}
}

?>