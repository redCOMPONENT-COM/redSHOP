<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JLoader::import('redshop.library');

/**
 * Plugin will syncronize data to redSHOP B2b
 *
 * @since  2.0.4
 */
class PlgRedshop_ProductSync_B2b extends JPlugin
{
	/**
	 *
	 * Method is called by the product view
	 *
	 * @param   object   $data   Product data
	 * @param   boolean  $isNew  Product is new
	 *
	 * @return  boolean
	 */
	public function onAfterProductSave(&$data, $isNew)
	{
		$db          = JFactory::getDbo();
		$url         = $this->params->get('url', '');
		$accessToken = $this->getAccessToken();
		$params      = '/index.php?webserviceClient=site&webserviceVersion=1.2.0&option=redshopb&view=product&api=hal';
		$result      = array();
		$type        = 'POST';
		$insert      = true;

		if (empty($url))
		{
			return true;
		}

		if (empty($accessToken))
		{
			return true;
		}

		if (!$isNew)
		{
			$redshopBId = $this->getRedshopBId($data->product_id);

			if ($redshopBId)
			{
				$insert = false;
				$result['id'] = $redshopBId;
				$type = 'PUT';
			}
		}

		$result['name']             = $data->product_name;
		$result['alias']            = JFilterOutput::stringURLUnicodeSlug($data->product_name);
		$result['sku']              = $data->product_number;
		$result['manufacturer_sku'] = $data->manufacturer_id;
		$result['date_new']         = date('Y-m-d');
		$result['price']            = $data->product_price;
		$result['company']          = $this->params->get('company', 2);

		if ($data->manufacturer_id > 0)
		{
			$result['manufacturer_id']  = $data->manufacturer_id;
		}

		$ch = curl_init($url . $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($result));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
		$return = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error  = curl_error($ch);
		curl_close($ch);
		$return = json_decode($return);
		$productId = $return->result;

		if ($productId > 0 && $insert)
		{
			$query = $db->getQuery(true);
			$columns = array('redshop_product_id', 'redshopb_product_id');
			$values = array($db->q((int) $data->product_id), $db->q((int) $productId));

			$query
				->insert($db->quoteName('#__redshop_redshopb_xref'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

			$db->setQuery($query)->execute();
		}

		return true;
	}

	/**
	 *
	 * Method is called after product is deleted
	 *
	 * @param   array  $ids  Product id list
	 *
	 * @return  boolean
	 */
	public function onAfterProductDelete($ids)
	{
		$url = $this->params->get('url', '');
		$params = '/index.php?webserviceClient=site&webserviceVersion=1.2.0&option=redshopb&view=product&api=hal';
		$accessToken = $this->getAccessToken();

		foreach ($ids as $id)
		{
			$redshopBId = $this->getRedshopBId($id);

			if (!$redshopBId)
			{
				continue;
			}

			$ch = curl_init($url . $params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('id' => $redshopBId)));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
			$result = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error  = curl_error($ch);
			curl_close($ch);

			if (json_decode($result)->result == 1)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_redshopb_xref'))
					->where(array($db->qn('redshopb_product_id') . ' = ' . $db->q((int) $redshopBId)));

				$db->setQuery($query)->execute();
			}
		}

		return true;
	}

	/**
	 *
	 * Method is called by the Manufacturer view
	 *
	 * @param   object   $data   Manufacturer data
	 * @param   boolean  $isNew  Manufacturer is new
	 *
	 * @return  boolean
	 */
	public function onAfterManufacturerSave(&$data, $isNew)
	{
		$db          = JFactory::getDbo();
		$url         = $this->params->get('url', '');
		$accessToken = $this->getAccessToken();
		$params      = '/index.php?webserviceClient=site&webserviceVersion=1.1.0&option=redshopb&view=manufacturer&api=hal';
		$result      = array();
		$type        = 'POST';
		$insert      = true;

		if (empty($url))
		{
			return true;
		}

		if (empty($accessToken))
		{
			return true;
		}

		if (!$isNew)
		{
			$redshopBId = $this->getRedshopBManufacturerId($data->manufacturer_id);

			if ($redshopBId)
			{
				$insert = false;
				$result['id'] = $redshopBId;
				$type = 'PUT';
			}
		}

		$result['name']  = $data->manufacturer_name;
		$result['alias'] = JFilterOutput::stringURLUnicodeSlug($data->manufacturer_name);

		$ch = curl_init($url . $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($result));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
		$return = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$error  = curl_error($ch);
		curl_close($ch);
		$return = json_decode($return);
		$manufacturerId = $return->result;

		if ($manufacturerId > 0 && $insert)
		{
			$query = $db->getQuery(true);
			$columns = array('redshop_manufacturer_id', 'redshopb_manufacturer_id');
			$values = array($db->q((int) $data->manufacturer_id), $db->q((int) $manufacturerId));

			$query
				->insert($db->quoteName('#__redshop_redshopb_manufacturer_xref'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));

			$db->setQuery($query)->execute();
		}

		return true;
	}

	/**
	 *
	 * Method is called after Manufacturer is deleted
	 *
	 * @param   array  $ids  Manufacturer id list
	 *
	 * @return  boolean
	 */
	public function onAfterManufacturerDelete($ids)
	{
		$url = $this->params->get('url', '');
		$params = '/index.php?webserviceClient=site&webserviceVersion=1.1.0&option=redshopb&view=manufacturer&api=hal';
		$accessToken = $this->getAccessToken();

		foreach ($ids as $id)
		{
			$redshopBId = $this->getRedshopBManufacturerId($id);

			if (!$redshopBId)
			{
				continue;
			}

			$ch = curl_init($url . $params);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('id' => $redshopBId)));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
			$result = curl_exec($ch);
			$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error  = curl_error($ch);
			curl_close($ch);

			if (json_decode($result)->result == 1)
			{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete($db->qn('#__redshop_redshopb_manufacturer_xref'))
					->where(array($db->qn('redshopb_manufacturer_id') . ' = ' . $db->q((int) $redshopBId)));

				$db->setQuery($query)->execute();
			}
		}

		return true;
	}

	/**
	 * Method to get Access Token
	 *
	 * @return  string
	 */
	public function getAccessToken()
	{
		$url          = $this->params->get('url', '') . '/index.php?option=token&api=oauth2';
		$clientId     = $this->params->get('client_id', '');
		$clientSecret = $this->params->get('client_secret', '');
		$params       = array(
				'grant_type'    => 'client_credentials',
				'client_id'     => $clientId,
				'client_secret' => $clientSecret
			);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result)->access_token;
	}

	/**
	 * Method to get redSHOPB Product ID
	 *
	 * @param   int  $productId  Product id
	 *
	 * @return  interger
	 */
	public function getRedshopBId($productId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('redshopb_product_id'))
			->from($db->qn('#__redshop_redshopb_xref'))
			->where($db->qn('redshop_product_id') . ' = ' . $db->q((int) $productId));

		return $db->setQuery($query)->loadResult();
	}

	/**
	 * Method to get redSHOPB Manufacturer ID
	 *
	 * @param   int  $manufacturerId  Manufacturer id
	 *
	 * @return  interger
	 */
	public function getRedshopBManufacturerId($manufacturerId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->qn('redshopb_manufacturer_id'))
			->from($db->qn('#__redshop_redshopb_manufacturer_xref'))
			->where($db->qn('redshop_manufacturer_id') . ' = ' . $db->q((int) $manufacturerId));

		return $db->setQuery($query)->loadResult();
	}
}
