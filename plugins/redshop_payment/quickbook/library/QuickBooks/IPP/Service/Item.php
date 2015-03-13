<?php

/**
 * 
 * 
 * Copyright (c) 2010 Keith Palmer / ConsoliBYTE, LLC.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.opensource.org/licenses/eclipse-1.0.php
 * 
 * @license LICENSE.txt
 * @author Keith Palmer <Keith@ConsoliBYTE.com>
 * 
 * @package QuickBooks
 * @subpackage IPP
 */


QuickBooks_Loader::load('/QuickBooks/IPP/Service.php');

class QuickBooks_IPP_Service_Item extends QuickBooks_IPP_Service
{
	public function findAll($Context, $realmID, $query = null, $page = 1, $size = 50, $options = array())
	{
		return parent::_findAll($Context, $realmID, QuickBooks_IPP_IDS::RESOURCE_ITEM, $query, null, $page, $size, '', $options);
	}
	
	public function findById($Context, $realmID, $ID)
	{
		$xml = null;
		return parent::_findById($Context, $realmID, QuickBooks_IPP_IDS::RESOURCE_ITEM, $ID, $xml);
	}

	/**
	 * Find an item by name 
	 *
	 * @param unknown_type $Context
	 * @param unknown_type $realmID
	 * @param unknown_type $name
	 */
	public function findByName($Context, $realmID, $name)
	{
		$IPP = $Context->IPP();
		
		if ($IPP->flavor() == QuickBooks_IPP_IDS::FLAVOR_DESKTOP)
		{
			for ($i = 0; $i < 999; $i++)
			{
				$list = $this->findAll($Context, $realmID, $name, $i, 50);
				
				foreach ($list as $Item)
				{
					if (strtolower($Item->getName()) == strtolower($name))
					{
						return $Item;
					}
				}
			}
			
			return false;
		}
		else
		{
			$xml = null;
			return parent::_findByName($Context, $realmID, QuickBooks_IPP_IDS::RESOURCE_ITEM, $name, $xml);
		}
	}	
	
	public function add($Context, $realmID, $Object)
	{
		return parent::_add($Context, $realmID, QuickBooks_IPP_IDS::RESOURCE_ITEM, $Object);
	}

	public function update($Context, $realm, $IDType, $Object)
	{
		return parent::_update($Context, $realm, QuickBooks_IPP_IDS::RESOURCE_ITEM, $Object, $IDType);
	}
	
	public function delete($Context, $realmID, $IDType)
	{
		return parent::_delete($Context, $realmID, QuickBooks_IPP_IDS::RESOURCE_ITEM, $IDType);
	}

	public function query($Context, $realm, $query)
	{
		return parent::_query($Context, $realm, $query);
	}
}