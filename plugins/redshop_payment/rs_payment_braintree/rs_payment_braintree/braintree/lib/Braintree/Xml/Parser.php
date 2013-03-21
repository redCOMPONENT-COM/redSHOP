<?php

/**
 * Braintree XML Parser
 *
 * @copyright  2010 Braintree Payment Solutions
 */
/**
 * Parses incoming Xml into arrays using PHP's
 * built-in SimpleXML, and its extension via
 * Iterator, SimpleXMLIterator
 *
 * @copyright  2010 Braintree Payment Solutions
 */
class Braintree_Xml_Parser
{

	private static $_xmlRoot;
	private static $_responseType;

	/**
	 * sets up the SimpleXMLIterator and starts the parsing
	 * @access public
	 *
	 * @param string $xml
	 *
	 * @return array array mapped to the passed xml
	 */
	public static function arrayFromXml($xml)
	{
		// SimpleXML provides the root information on construct
		$iterator = new SimpleXMLIterator($xml);
		$xmlRoot = Braintree_Util::delimiterToCamelCase($iterator->getName());
		$type = $iterator->attributes()->type;

		self::$_xmlRoot = $iterator->getName();
		self::$_responseType = $type;

		// Return the mapped array with the root element as the header
		return array($xmlRoot => self::_iteratorToArray($iterator));

	}

	/**
	 * processes SimpleXMLIterator objects recursively
	 *
	 * @access protected
	 *
	 * @param object $iterator
	 *
	 * @return array xml converted to array
	 */
	private static function _iteratorToArray($iterator)
	{
		$xmlArray = array();
		$value = null;

		// Rewind the iterator and check if the position is valid
		// If not, return the string it contains
		$iterator->rewind();

		if (!$iterator->valid())
		{
			return self::_typecastXmlValue($iterator);
		}

		for ($iterator->rewind(); $iterator->valid(); $iterator->next())
		{

			$tmpArray = null;
			$value = null;

			// Get the attribute type string for use in conditions below
			$attributeType = $iterator->attributes()->type;

			// Extract the parent element via xpath query
			$parentElement = $iterator->xpath($iterator->key() . '/..');

			if ($parentElement[0] instanceof SimpleXMLIterator)
			{
				$parentElement = $parentElement[0];
				$parentKey = Braintree_Util::delimiterToCamelCase($parentElement->getName());
			}
			else
			{
				$parentElement = null;
			}


			if ($parentKey == "customFields")
			{
				$key = Braintree_Util::delimiterToUnderscore($iterator->key());
			}
			else
			{
				$key = Braintree_Util::delimiterToCamelCase($iterator->key());
			}

			// Process children recursively
			if ($iterator->hasChildren())
			{
				// Return the child elements
				$value = self::_iteratorToArray($iterator->current());

				// If the element is an array type,
				// Use numeric keys to allow multiple values
				if ($attributeType != 'array')
				{
					$tmpArray[$key] = $value;
				}
			}
			else
			{
				// Cast values according to attributes
				$tmpArray[$key] = self::_typecastXmlValue($iterator->current());
			}

			// Set the output string
			$output = isset($value) ? $value : $tmpArray[$key];

			// Determine if there are multiple tags of this name at the same level
			if (isset($parentElement) &&
				($parentElement->attributes()->type == 'collection') &&
				$iterator->hasChildren()
			)
			{
				$xmlArray[$key][] = $output;
				continue;
			}

			// If the element was an array type, output to a numbered key
			// Otherwise, use the element name
			if ($attributeType == 'array')
			{
				$xmlArray[] = $output;
			}
			else
			{
				$xmlArray[$key] = $output;
			}
		}

		return $xmlArray;
	}

	/**
	 * typecast xml value based on attributes
	 *
	 * @param object $valueObj SimpleXMLElement
	 *
	 * @return mixed value for placing into array
	 */
	private static function _typecastXmlValue($valueObj)
	{
		// Get the element attributes
		$attribs = $valueObj->attributes();
		// The element is null, so jump out here
		if (isset($attribs->nil) && $attribs->nil)
		{
			return null;
		}
		// Switch on the type attribute
		// Switch works even if $attribs->type isn't set
		switch ($attribs->type)
		{
			case 'datetime':
				return self::_timestampToUTC((string) $valueObj);
				break;
			case 'date':
				return new DateTime((string) $valueObj);
				break;
			case 'integer':
				return (int) $valueObj;
				break;
			case 'boolean':
				$value = (string) $valueObj;
				// Look for a number inside the string
				if (is_numeric($value))
				{
					return (bool) $value;
				}
				else
				{
					// Look for the string "true", return false in all other cases
					return ($value != "true") ? false : true;
				}
				break;
			case 'array':
				return array();
			default:
				return (string) $valueObj;
		}

	}

	/**
	 * convert xml timestamps into DateTime
	 *
	 * @param string $timestamp
	 *
	 * @return string UTC formatted datetime string
	 */
	private static function _timestampToUTC($timestamp)
	{
		$tz = new DateTimeZone('UTC');
		// Strangely DateTime requires an explicit set below
		// To show the proper time zone
		$dateTime = new DateTime($timestamp, $tz);
		$dateTime->setTimezone($tz);

		return $dateTime;
	}
}
