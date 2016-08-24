<?php
/**
 * Copyright 2016 Klarna AB.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace Klarna\XMLRPC;

use PhpXmlRpc;

/**
 * This API provides a way to integrate with Klarna's services over the
 * XMLRPC protocol.
 *
 * All strings inputted need to be encoded with ISO-8859-1.<br>
 * In addition you need to decode HTML entities, if they exist.<br>
 */
class Klarna
{
    /**
     * Klarna PHP API version identifier.
     *
     * @var string
     */
    protected $version = 'php:api:5.0.0';

    /**
     * Klarna protocol identifier.
     *
     * @var string
     */
    protected $proto = '4.1';

    /**
     * The encoding used in Klarna.
     *
     * @var string
     */
    protected $encoding = 'ISO-8859-1';

    /**
     * Constants used with LIVE mode for the communications with Klarna.
     *
     * @var int
     */
    const LIVE = 0;

    /**
     * URL/Address to the live Klarna Online server.
     *
     * @var string
     */
    private static $liveAddr = 'payment.klarna.com';

    /**
     * Constants used with BETA mode for the communications with Klarna.
     *
     * @var int
     */
    const BETA = 1;

    /**
     * URL/Address to the beta test Klarna Online server.
     *
     * @var string
     */
    private static $betaAddr = 'payment.testdrive.klarna.com';

    /**
     * An object of PhpXmlRpc\Client, used to communicate with Klarna.
     *
     * @link https://packagist.org/packages/phpxmlrpc/phpxmlrpc
     *
     * @var PhpXmlRpc\Client
     */
    protected $xmlrpc;

    /**
     * Which server the Klarna API is using, LIVE or BETA (TESTING).
     *
     * @see Klarna::LIVE
     * @see Klarna::BETA
     *
     * @var int
     */
    protected $mode;

    /**
     * Associative array holding url information.
     *
     * @var array
     */
    private $url;

    /**
     * The estore's identifier received from Klarna.
     *
     * @var int
     */
    private $eid;

    /**
     * The estore's shared secret received from Klarna.
     *
     * <b>Note</b>:<br>
     * DO NOT SHARE THIS WITH ANYONE!
     *
     * @var string
     */
    private $secret;

    /**
     * Country constant.
     *
     * @see Country
     *
     * @var int
     */
    private $country;

    /**
     * Currency constant.
     *
     * @see Currency
     *
     * @var int
     */
    private $currency;

    /**
     * Language constant.
     *
     * @see Language
     *
     * @var int
     */
    private $language;

    /**
     * An array of articles for the current order.
     *
     * @var array
     */
    protected $goodsList;

    /**
     * An array of article numbers and quantity.
     *
     * @var array
     */
    protected $artNos;

    /**
     * An Address object containing the billing address.
     *
     * @var Address
     */
    protected $billing;

    /**
     * An Address object containing the shipping address.
     *
     * @var Address
     */
    protected $shipping;

    /**
     * External order numbers from other systems.
     *
     * @var string
     */
    protected $orderid = array('', '');

    /**
     * Reference (person) parameter.
     *
     * @var string
     */
    protected $reference = '';

    /**
     * Reference code parameter.
     *
     * @var string
     */
    protected $reference_code = '';

    /**
     * An array of named extra info.
     *
     * @var array
     */
    protected $extraInfo = array();

    /**
     * An array of named bank info.
     *
     * @var array
     */
    protected $bankInfo = array();

    /**
     * An array of named income expense info.
     *
     * @var array
     */
    protected $incomeInfo = array();

    /**
     * An array of named shipment info.
     *
     * @var array
     */
    protected $shipInfo = array();

    /**
     * An array of named travel info.
     *
     * @ignore Do not show this in PHPDoc.
     *
     * @var array
     */
    protected $travelInfo = array();

    /**
     * An array of named activate info.
     *
     * @ignore
     *
     * @var array
     */
    protected $activateInfo = array();

    /**
     * An array of named session id's.<br>
     * E.g. "dev_id_1" => ...<br>.
     *
     * @var array
     */
    protected $sid = array();

    /**
     * A comment sent in the XMLRPC communications.
     * This is resetted using clear().
     *
     * @var string
     */
    protected $comment = '';

    /**
     * Flag to indicate if the API should output verbose
     * debugging information.
     *
     * @var bool
     */
    public static $debug = false;

    /**
     * Turns on the internal XMLRPC debugging.
     *
     * @var bool
     */
    public static $xmlrpcDebug = false;

    /**
     * If this is set to true, XMLRPC invocation is disabled.
     *
     * @var bool
     */
    public static $disableXMLRPC = false;

    /**
     * If the estore is using a proxy which populates the clients IP to
     * x_forwarded_for
     * then and only then should this be set to true.
     *
     * <b>Note</b>:<br>
     * USE WITH CARE!
     *
     * @var bool
     */
    public static $x_forwarded_for = false;

    /**
     * Array of HTML entities, used to create numeric htmlentities.
     *
     * @ignore Do not show this in PHPDoc.
     *
     * @var array
     */
    protected static $htmlentities = false;

    /**
     * PClass list.
     *
     * @ignore Do not show this in PHPDoc.
     *
     * @var PClass[]
     */
    protected $pclasses;

    /**
     * \ArrayAccess instance.
     *
     * @ignore Do not show this in PHPDoc.
     *
     * @var \ArrayAccess
     */
    protected $config;

    /**
     * Client IP.
     *
     * @var string
     */
    protected $clientIP;

    /**
     * Empty constructor, because sometimes it's needed.
     */
    public function __construct()
    {
    }

    /**
     * Checks if the config has fields described in argument.<br>
     * Missing field(s) is in the exception message.
     *
     * To check that the config has eid and secret:<br>
     * <code>
     * try {
     *     $this->hasFields('eid', 'secret');
     * }
     * catch(\Exception $e) {
     *     echo "Missing fields: " . $e->getMessage();
     * }
     * </code>
     *
     * @throws \RuntimeException
     */
    protected function hasFields()
    {
        $missingFields = array();
        $args = func_get_args();
        foreach ($args as $field) {
            if (!isset($this->config[$field])) {
                $missingFields[] = $field;
            }
        }
        if (count($missingFields) > 0) {
            throw new \RuntimeException(
                'Config missing fields: '.implode(', ', $missingFields)
            );
        }
    }

    /**
     * Initializes the Klarna object accordingly to the set config object.
     *
     * @throws \RuntimeException For invalid configuration
     */
    protected function init()
    {
        $this->hasFields('eid', 'secret', 'mode');

        if (!is_int($this->config['eid'])) {
            $this->config['eid'] = intval($this->config['eid']);
        }

        if ($this->config['eid'] <= 0) {
            throw new \RuntimeException('Config field eid is not valid!');
        }

        if (!is_string($this->config['secret'])) {
            $this->config['secret'] = strval($this->config['secret']);
        }

        if (strlen($this->config['secret']) == 0) {
            throw new \RuntimeException('Config field secret is not valid!');
        }

        //Set the shop id and secret.
        $this->eid = $this->config['eid'];
        $this->secret = $this->config['secret'];

        //Set the country specific attributes.
        try {
            $this->hasFields('country', 'language', 'currency');

            //If hasFields doesn't throw exception we can set them all.
            $this->setCountry($this->config['country']);
            $this->setLanguage($this->config['language']);
            $this->setCurrency($this->config['currency']);
        } catch (\Exception $e) {
            //fields missing for country, language or currency
            $this->country = $this->language = $this->currency = null;
        }

        //Set addr and port according to mode.
        $this->mode = (int) $this->config['mode'];

        $this->url = array();

        // If a custom url has been added to the config, use that as xmlrpc
        // recipient.
        if (isset($this->config['url'])) {
            $this->url = parse_url($this->config['url']);
            if ($this->url === false) {
                $message = "Configuration value 'url' could not be parsed. ".
                    "(Was: '{$this->config['url']}')";
                self::printDebug(__METHOD__, $message);
                throw new \RuntimeException($message);
            }
        } else {
            $this->url['scheme'] = 'https';
            $this->url['port'] = 443;

            if ($this->mode === self::LIVE) {
                $this->url['host'] = self::$liveAddr;
            } else {
                $this->url['host'] = self::$betaAddr;
            }
        }

        try {
            $this->hasFields('xmlrpcDebug');
            self::$xmlrpcDebug = $this->config['xmlrpcDebug'];
        } catch (\Exception $e) {
            //No 'xmlrpcDebug' field ignore it...
        }

        try {
            $this->hasFields('debug');
            self::$debug = $this->config['debug'];
        } catch (\Exception $e) {
            //No 'debug' field ignore it...
        }

        // Default path to '/' if not set.
        if (!array_key_exists('path', $this->url)) {
            $this->url['path'] = '/';
        }

        $this->xmlrpc = new PhpXmlRpc\Client(
            $this->url['path'],
            $this->url['host'],
            $this->url['port'],
            $this->url['scheme']
        );

        $this->xmlrpc->setSSLVerifyHost(2);

        $this->xmlrpc->request_charset_encoding = $this->encoding;
    }

    /**
     * Method of ease for setting common config fields.
     *
     * <b>Note</b>:<br>
     * This disables the config file storage.<br>
     *
     * @param int    $eid      Merchant ID/EID
     * @param string $secret   Secret key/Shared key
     * @param int    $country  {@link Country}
     * @param int    $language {@link Language}
     * @param int    $currency {@link Currency}
     * @param int    $mode     {@link Klarna::LIVE} or {@link Klarna::BETA}
     *
     * @see Klarna::setConfig()
     * @see Config
     *
     * @throws Exception\KlarnaException
     */
    public function config(
        $eid,
        $secret,
        $country,
        $language,
        $currency,
        $mode = self::LIVE
    ) {
        try {
            $this->config = new Config(null);

            $this->config['eid'] = $eid;
            $this->config['secret'] = $secret;
            $this->config['country'] = $country;
            $this->config['language'] = $language;
            $this->config['currency'] = $currency;
            $this->config['mode'] = $mode;

            $this->init();
        } catch (\Exception $e) {
            $this->config = null;
            throw new Exception\KlarnaException(
                $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Sets and initializes this Klarna object using the supplied config object.
     *
     * @param Config $config Config object.
     *
     * @see Config
     *
     * @throws Exception\KlarnaException
     */
    public function setConfig(&$config)
    {
        $this->checkConfig($config);

        $this->config = $config;
        $this->init();
    }

    /**
     * Get the complete locale (country, language, currency) to use for the
     * values passed, or the configured value if passing null.
     *
     * @param mixed $country  country  constant or code
     * @param mixed $language language constant or code
     * @param mixed $currency currency constant or code
     *
     * @throws Exception\KlarnaException
     *
     * @return array
     */
    public function getLocale(
        $country = null,
        $language = null,
        $currency = null
    ) {
        $locale = array(
            'country' => null,
            'language' => null,
            'currency' => null,
        );

        if ($country === null) {
            // Use the configured country / language / currency
            $locale['country'] = $this->country;
            if ($this->language !== null) {
                $locale['language'] = $this->language;
            }

            if ($this->currency !== null) {
                $locale['currency'] = $this->currency;
            }
        } else {
            // Use the given country / language / currency
            if (!is_numeric($country)) {
                $country = Country::fromCode($country);
            }
            $locale['country'] = intval($country);

            if ($language !== null) {
                if (!is_numeric($language)) {
                    $language = Language::fromCode($language);
                }
                $locale['language'] = intval($language);
            }

            if ($currency !== null) {
                if (!is_numeric($currency)) {
                    $currency = Currency::fromCode($currency);
                }
                $locale['currency'] = intval($currency);
            }
        }

        // Complete partial structure with defaults
        if ($locale['currency'] === null) {
            $locale['currency'] = $this->getCurrencyForCountry(
                $locale['country']
            );
        }

        if ($locale['language'] === null) {
            $locale['language'] = $this->getLanguageForCountry(
                $locale['country']
            );
        }

        $this->checkCountry($locale['country']);
        $this->checkCurrency($locale['currency']);
        $this->checkLanguage($locale['language']);

        return $locale;
    }

    /**
     * Sets the country used.
     *
     * <b>Note</b>:<br>
     * If you input 'dk', 'fi', 'de', 'nl', 'no' or 'se', <br>
     * then currency and language will be set to mirror that country.<br>
     *
     * @param string|int $country {@link Country}
     *
     * @see Country
     *
     * @throws Exception\KlarnaException
     */
    public function setCountry($country)
    {
        if (!is_numeric($country)
            && (strlen($country) == 2 || strlen($country) == 3)
        ) {
            $country = Country::fromCode($country);
        }
        $this->checkCountry($country);
        $this->country = $country;
    }

    /**
     * Returns the country code for the set country constant.
     *
     * @param int $country {@link Country Country} constant.
     *
     * @return string Two letter code, e.g. "se", "no", etc.
     */
    public function getCountryCode($country = null)
    {
        if ($country === null) {
            $country = $this->country;
        }

        $code = Country::getCode($country);

        return (string) $code;
    }

    /**
     * Returns the {@link Country country} constant from the country code.
     *
     * @param string $code Two letter code, e.g. "se", "no", etc.
     *
     * @throws \RuntimeException
     *
     * @return int {@link Country Country} constant.
     */
    public static function getCountryForCode($code)
    {
        $country = Country::fromCode($code);
        if ($country === null) {
            throw new \RuntimeException("Unknown country code: {$code}");
        }

        return $country;
    }

    /**
     * Returns the country constant.
     *
     * @return int {@link Country}
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets the language used.
     *
     * <b>Note</b>:<br>
     * You can use the two letter language code instead of the constant.<br>
     * E.g. 'da' instead of using {@link Language::DA}.<br>
     *
     * @param string|int $language {@link Language}
     *
     * @see Language
     *
     * @throws Exception\KlarnaException
     */
    public function setLanguage($language)
    {
        if (!is_numeric($language) && strlen($language) == 2) {
            $this->setLanguage(self::getLanguageForCode($language));
        } else {
            $this->checkLanguage($language);
            $this->language = $language;
        }
    }

    /**
     * Returns the language code for the set language constant.
     *
     * @param int $language {@link Language Language} constant.
     *
     * @return string Two letter code, e.g. "da", "de", etc.
     */
    public function getLanguageCode($language = null)
    {
        if ($language === null) {
            $language = $this->language;
        }
        $code = Language::getCode($language);

        return (string) $code;
    }

    /**
     * Returns the {@link Language language} constant from the language code.
     *
     * @param string $code Two letter code, e.g. "da", "de", etc.
     *
     * @throws \RuntimeException
     *
     * @return int {@link Language Language} constant.
     */
    public static function getLanguageForCode($code)
    {
        $language = Language::fromCode($code);

        if ($language === null) {
            throw new \RuntimeException("Unknown language code: {$code}");
        }

        return $language;
    }

    /**
     * Returns the language constant.
     *
     * @return int {@link Language}
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Sets the currency used.
     *
     * <b>Note</b>:<br>
     * You can use the three letter shortening of the currency.<br>
     * E.g. "dkk", "eur", "nok" or "sek" instead of the constant.<br>
     *
     * @param string|int $currency {@link Currency}
     *
     * @see Currency
     *
     * @throws Exception\KlarnaException
     */
    public function setCurrency($currency)
    {
        if (!is_numeric($currency) && strlen($currency) == 3) {
            $this->setCurrency(self::getCurrencyForCode($currency));
        } else {
            $this->checkCurrency($currency);
            $this->currency = $currency;
        }
    }

    /**
     * Returns the {@link Currency currency} constant from the currency
     * code.
     *
     * @param string $code Two letter code, e.g. "dkk", "eur", etc.
     *
     * @throws \RuntimeException
     *
     * @return int {@link Currency Currency} constant.
     */
    public static function getCurrencyForCode($code)
    {
        $currency = Currency::fromCode($code);
        if ($currency === null) {
            throw new \RuntimeException("Unknown currency code: {$code}");
        }

        return $currency;
    }

    /**
     * Returns the the currency code for the set currency constant.
     *
     * @param int $currency {@link Currency Currency} constant.
     *
     * @return string Three letter currency code.
     */
    public function getCurrencyCode($currency = null)
    {
        if ($currency === null) {
            $currency = $this->currency;
        }

        $code = Currency::getCode($currency);

        return (string) $code;
    }

    /**
     * Returns the set currency constant.
     *
     * @return int {@link Currency}
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Returns the {@link Language language} constant for the specified
     * or set country.
     *
     * @param int $country {@link Country Country} constant.
     *
     * @deprecated Do not use.
     *
     * @return int|false if no match otherwise Language constant.
     */
    public function getLanguageForCountry($country = null)
    {
        if ($country === null) {
            $country = $this->country;
        }
        // Since getLanguage defaults to EN, check so we actually have a match
        $language = Country::getLanguage($country);
        if (Country::checkLanguage($country, $language)) {
            return $language;
        }

        return false;
    }

    /**
     * Returns the {@link Currency currency} constant for the specified
     * or set country.
     *
     * @param int $country {@link Country country} constant.
     *
     * @deprecated Do not use.
     *
     * @return int|false {@link Currency currency} constant.
     */
    public function getCurrencyForCountry($country = null)
    {
        if ($country === null) {
            $country = $this->country;
        }

        return Country::getCurrency($country);
    }

    /**
     * Sets the session id's for various device identification,
     * behaviour identification software.
     *
     * <b>Available named session id's</b>:<br>
     * string - dev_id_1<br>
     * string - dev_id_2<br>
     * string - dev_id_3<br>
     * string - beh_id_1<br>
     * string - beh_id_2<br>
     * string - beh_id_3<br>
     *
     * @param string $name Session ID identifier, e.g. 'dev_id_1'.
     * @param string $sid  Session ID.
     *
     * @throws Exception\KlarnaException
     */
    public function setSessionID($name, $sid)
    {
        $this->checkArgument($name, 'name');
        $this->checkArgument($sid, 'sid');

        $this->sid[$name] = $sid;
    }

    /**
     * Sets the shipment information for the upcoming transaction.<br>.
     *
     * Using this method is optional.
     *
     * <b>Available named values are</b>:<br>
     * int    - delay_adjust<br>
     * string - shipping_company<br>
     * string - shipping_product<br>
     * string - tracking_no<br>
     * array  - warehouse_addr<br>
     *
     * "warehouse_addr" is sent using {@link Address::toArray()}.
     *
     * Make sure you send in the values as the right data type.<br>
     * Use strval, intval or similar methods to ensure the right type is sent.
     *
     * @param string $name  key
     * @param mixed  $value value
     *
     * @throws Exception\KlarnaException
     */
    public function setShipmentInfo($name, $value)
    {
        $this->checkArgument($name, 'name');

        $this->shipInfo[$name] = $value;
    }

    /**
     * Sets the Activation information for the upcoming transaction.<br>.
     *
     * Using this method is optional.
     *
     * <b>Available named values are</b>:<br>
     * int    - flags<br>
     * int    - bclass<br>
     * string - orderid1<br>
     * string - orderid2<br>
     * string - ocr<br>
     * string - reference<br>
     * string - reference_code<br>
     * string - cust_no<br>
     *
     * Make sure you send in the values as the right data type.<br>
     * Use strval, intval or similar methods to ensure the right type is sent.
     *
     * @param string $name  key
     * @param mixed  $value value
     *
     * @see setShipmentInfo
     */
    public function setActivateInfo($name, $value)
    {
        $this->activateInfo[$name] = $value;
    }

    /**
     * Sets the extra information for the upcoming transaction.<br>.
     *
     * Using this method is optional.
     *
     * <b>Available named values are</b>:<br>
     * string - cust_no<br>
     * string - ready_date<br>
     * string - rand_string<br>
     * int    - bclass<br>
     * string - pin<br>
     *
     * Make sure you send in the values as the right data type.<br>
     * Use strval, intval or similar methods to ensure the right type is sent.
     *
     * @param string $name  key
     * @param mixed  $value value
     *
     * @throws Exception\KlarnaException
     */
    public function setExtraInfo($name, $value)
    {
        $this->checkArgument($name, 'name');

        $this->extraInfo[$name] = $value;
    }

    /**
     * Sets the income expense information for the upcoming transaction.<br>.
     *
     * Using this method is optional.
     *
     * Make sure you send in the values as the right data type.<br>
     * Use strval, intval or similar methods to ensure the right type is sent.
     *
     * @param string $name  key
     * @param mixed  $value value
     *
     * @throws Exception\KlarnaException
     */
    public function setIncomeInfo($name, $value)
    {
        $this->checkArgument($name, 'name');

        $this->incomeInfo[$name] = $value;
    }

    /**
     * Sets the bank information for the upcoming transaction.<br>.
     *
     * Using this method is optional.
     *
     * Make sure you send in the values as the right data type.<br>
     * Use strval, intval or similar methods to ensure the right type is sent.
     *
     * @param string $name  key
     * @param mixed  $value value
     *
     * @throws Exception\KlarnaException
     */
    public function setBankInfo($name, $value)
    {
        $this->checkArgument($name, 'name');

        $this->bankInfo[$name] = $value;
    }

    /**
     * Sets the travel information for the upcoming transaction.<br>.
     *
     * Using this method is optional.
     *
     * Make sure you send in the values as the right data type.<br>
     * Use strval, intval or similar methods to ensure the right type is sent.
     *
     * @param string $name  key
     * @param mixed  $value value
     *
     * @throws Exception\KlarnaException
     */
    public function setTravelInfo($name, $value)
    {
        $this->checkArgument($name, 'name');

        $this->travelInfo[$name] = $value;
    }

    /**
     * Set client IP.
     *
     * @param string $clientIP Client IP address
     */
    public function setClientIP($clientIP)
    {
        $this->clientIP = $clientIP;
    }

    /**
     * Returns the clients IP address.
     *
     * @return string
     */
    public function getClientIP()
    {
        if (isset($this->clientIP)) {
            return $this->clientIP;
        }

        $tmp_ip = '';
        $x_fwd = null;

        //Proxy handling.
        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            $tmp_ip = $_SERVER['REMOTE_ADDR'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $x_fwd = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (self::$x_forwarded_for && ($x_fwd !== null)) {
            $forwarded = explode(',', $x_fwd);

            return trim($forwarded[0]);
        }

        return $tmp_ip;
    }

    /**
     * Sets the specified address for the current order.
     *
     * <b>Address type can be</b>:<br>
     * {@link Flags::IS_SHIPPING}<br>
     * {@link Flags::IS_BILLING}<br>
     *
     * @param int     $type Address type.
     * @param Address $addr Specified address.
     *
     * @throws Exception\KlarnaException
     */
    public function setAddress($type, Address $addr)
    {
        if ($addr->isCompany === null) {
            $addr->isCompany = false;
        }

        if ($type === Flags::IS_SHIPPING) {
            $this->shipping = $addr;
            self::printDebug('shipping address array', $this->shipping);

            return;
        }

        if ($type === Flags::IS_BILLING) {
            $this->billing = $addr;
            self::printDebug('billing address array', $this->billing);

            return;
        }
        throw new \RuntimeException("Unknown address type: {$type}");
    }

    /**
     * Sets order id's from other systems for the upcoming transaction.<br>.
     *
     * @param string $orderid1 order id 1
     * @param string $orderid2 order id 2
     *
     * @see Klarna::setExtraInfo()
     *
     * @throws Exception\KlarnaException
     */
    public function setEstoreInfo($orderid1 = '', $orderid2 = '')
    {
        if (!is_string($orderid1)) {
            $orderid1 = strval($orderid1);
        }

        if (!is_string($orderid2)) {
            $orderid2 = strval($orderid2);
        }

        $this->orderid[0] = $orderid1;
        $this->orderid[1] = $orderid2;
    }

    /**
     * Sets the reference (person) and reference code, for the upcoming
     * transaction.
     *
     * If this is omitted, it can grab first name, last name from the address
     * and use that as a reference person.
     *
     * @param string $ref  Reference person / message to customer on invoice.
     * @param string $code Reference code / message to customer on invoice.
     */
    public function setReference($ref, $code)
    {
        $this->checkRef($ref, $code);
        $this->reference = $ref;
        $this->reference_code = $code;
    }

    /**
     * Returns the reference (person).
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Returns an associative array used to send the address to Klarna.
     * TODO: Kill it all.
     *
     * @param Address $addr Address object to assemble.
     *
     * @return array The address for the specified method.
     */
    protected function assembleAddr(Address $addr)
    {
        return $addr->toArray();
    }

    /**
     * Sets the comment field, which can be shown in the invoice.
     *
     * @param string $data comment to set
     */
    public function setComment($data)
    {
        $this->comment = $data;
    }

    /**
     * Returns the PNO/SSN encoding constant for currently set country.
     *
     * <b>Note</b>:<br>
     * Country, language and currency needs to match!
     *
     * @throws Exception\KlarnaException
     *
     * @return int {@link Encoding} constant.
     */
    public function getPNOEncoding()
    {
        $this->checkLocale();

        $country = Country::getCode($this->country);

        return Encoding::get($country);
    }

    /**
     * Purpose: The get_addresses function is used to retrieve a customer's
     * address(es). Using this, the customer is not required to enter any
     * information, only confirm the one presented to him/her.<br>.
     *
     * The get_addresses function can also be used for companies.<br>
     * If the customer enters a company number, it will return all the
     * addresses where the company is registered at.<br>
     *
     * The get_addresses function is ONLY allowed to be used for Swedish
     * persons with the following conditions:
     * <ul>
     *     <li>
     *          It can be only used if invoice or part payment is
     *          the default payment method
     *     </li>
     *     <li>
     *          It has to disappear if the customer chooses another
     *          payment method
     *     </li>
     *     <li>
     *          The button is not allowed to be called "get address", but
     *          "continue" or<br>
     *          it can be picked up automatically when all the numbers have
     *          been typed.
     *     </li>
     * </ul>
     *
     * <b>Type can be one of these</b>:<br>
     * {@link Flags::GA_ALL},<br>
     * {@link Flags::GA_LAST},<br>
     * {@link Flags::GA_GIVEN}.<br>
     *
     * @example docs/examples/getAddresses.php How to get a customers address.
     *
     * @param string $pno      Social security number, personal number, ...
     * @param int    $encoding {@link Encoding PNO Encoding} constant.
     * @param int    $type     Specifies returned information.
     *
     * @throws Exception\KlarnaException
     *
     * @return array An array of {@link Address} objects.
     */
    public function getAddresses(
        $pno,
        $encoding = null,
        $type = Flags::GA_GIVEN
    ) {
        if ($this->country !== Country::SE) {
            throw new \RuntimeException(
                'This method is only available for customers from: Sweden'
            );
        }

        //Get the PNO/SSN encoding constant.
        if ($encoding === null) {
            $encoding = $this->getPNOEncoding();
        }

        $this->checkPNO($pno, $encoding);

        $digestSecret = self::digest(
            self::colon(
                $this->eid,
                $pno,
                $this->secret
            )
        );

        $paramList = array(
            $pno,
            $this->eid,
            $digestSecret,
            $encoding,
            $type,
            $this->getClientIP(),
        );

        self::printDebug('get_addresses array', $paramList);

        $result = $this->xmlrpcCall('get_addresses', $paramList);

        self::printDebug('get_addresses result array', $result);

        $addrs = array();
        foreach ($result as $tmpAddr) {
            try {
                $addr = new Address();
                if ($type === Flags::GA_GIVEN) {
                    $addr->isCompany = (count($tmpAddr) == 5) ? true : false;
                    if ($addr->isCompany) {
                        $addr->setCompanyName($tmpAddr[0]);
                        $addr->setStreet($tmpAddr[1]);
                        $addr->setZipCode($tmpAddr[2]);
                        $addr->setCity($tmpAddr[3]);
                        $addr->setCountry($tmpAddr[4]);
                    } else {
                        $addr->setFirstName($tmpAddr[0]);
                        $addr->setLastName($tmpAddr[1]);
                        $addr->setStreet($tmpAddr[2]);
                        $addr->setZipCode($tmpAddr[3]);
                        $addr->setCity($tmpAddr[4]);
                        $addr->setCountry($tmpAddr[5]);
                    }
                } elseif ($type === Flags::GA_LAST) {
                    // Here we cannot decide if it is a company or not?
                    // Assume private person.
                    $addr->setLastName($tmpAddr[0]);
                    $addr->setStreet($tmpAddr[1]);
                    $addr->setZipCode($tmpAddr[2]);
                    $addr->setCity($tmpAddr[3]);
                    $addr->setCountry($tmpAddr[4]);
                } elseif ($type === Flags::GA_ALL) {
                    if (strlen($tmpAddr[0]) > 0) {
                        $addr->setFirstName($tmpAddr[0]);
                        $addr->setLastName($tmpAddr[1]);
                    } else {
                        $addr->isCompany = true;
                        $addr->setCompanyName($tmpAddr[1]);
                    }
                    $addr->setStreet($tmpAddr[2]);
                    $addr->setZipCode($tmpAddr[3]);
                    $addr->setCity($tmpAddr[4]);
                    $addr->setCountry($tmpAddr[5]);
                } else {
                    continue;
                }
                $addrs[] = $addr;
            } catch (\Exception $e) {
                //Silently fail
            }
        }

        return $addrs;
    }

    /**
     * Adds an article to the current goods list for the current order.
     *
     * <b>Note</b>:<br>
     * It is recommended that you use {@link Flags::INC_VAT}.<br>
     *
     * <b>Flags can be</b>:<br>
     * {@link Flags::INC_VAT}<br>
     * {@link Flags::IS_SHIPMENT}<br>
     * {@link Flags::IS_HANDLING}<br>
     * {@link Flags::PRINT_1000}<br>
     * {@link Flags::PRINT_100}<br>
     * {@link Flags::PRINT_10}<br>
     * {@link Flags::NO_FLAG}<br>
     *
     * Some flags can be added to each other for multiple options.
     *
     * @param int    $qty      Quantity.
     * @param string $artNo    Article number.
     * @param string $title    Article title.
     * @param int    $price    Article price.
     * @param float  $vat      VAT in percent, e.g. 25% is inputted as 25.
     * @param float  $discount Possible discount on article.
     * @param int    $flags    Options which specify the article
     *                         ({@link Flags::IS_HANDLING}) and it's price
     *                         ({@link Flags::INC_VAT})
     *
     * @see Klarna::reserveAmount()
     *
     * @throws Exception\KlarnaException
     */
    public function addArticle(
        $qty,
        $artNo,
        $title,
        $price,
        $vat,
        $discount = 0,
        $flags = Flags::INC_VAT
    ) {
        $this->checkQty($qty);

        // Either artno or title has to be set
        if ((($artNo === null) || ($artNo == ''))
            && (($title === null) || ($title == ''))
        ) {
            throw new \InvalidArgumentException('Either Title and ArtNo needs to be set');
        }

        $this->checkPrice($price);
        $this->checkVAT($vat);
        $this->checkDiscount($discount);
        $this->checkInt($flags, 'flags');

        //Create goodsList array if not set.
        if (!$this->goodsList || !is_array($this->goodsList)) {
            $this->goodsList = array();
        }

        //Populate a temp array with the article details.
        $tmpArr = array(
            'artno' => $artNo,
            'title' => $title,
            'price' => $price,
            'vat' => $vat,
            'discount' => $discount,
            'flags' => $flags,
        );

        //Add the temp array and quantity field to the internal goods list.
        $this->goodsList[] = array(
                'goods' => $tmpArr,
                'qty' => $qty,
        );

        if (count($this->goodsList) > 0) {
            self::printDebug(
                'article added',
                $this->goodsList[count($this->goodsList) - 1]
            );
        }
    }

    /**
     * Summarizes the prices of the held goods list.
     *
     * @return int total amount
     */
    public function summarizeGoodsList()
    {
        $amount = 0;
        if (!is_array($this->goodsList)) {
            return $amount;
        }
        foreach ($this->goodsList as $goods) {
            $price = $goods['goods']['price'];

            // Add VAT if price is Excluding VAT
            if (($goods['goods']['flags'] & Flags::INC_VAT) === 0) {
                $vat = $goods['goods']['vat'] / 100.0;
                $price *= (1.0 + $vat);
            }

            // Reduce discounts
            if ($goods['goods']['discount'] > 0) {
                $discount = $goods['goods']['discount'] / 100.0;
                $price *= (1.0 - $discount);
            }

            $amount += $price * (int) $goods['qty'];
        }

        return $amount;
    }

    /**
     * Reserves a purchase amount for a specific customer. <br>
     * The reservation is valid, by default, for 7 days.<br>.
     *
     * <b>This method returns an array with</b>:<br>
     * A reservation number (rno)<br>
     * Order status flag<br>
     *
     * <b>Order status can be</b>:<br>
     * {@link Flags::ACCEPTED}<br>
     * {@link Flags::PENDING}<br>
     * {@link Flags::DENIED}<br>
     *
     * <b>Please note</b>:<br>
     * Activation must be done with activate_reservation, i.e. you cannot
     * activate through Klarna Online.
     *
     * Gender is only required for Germany and Netherlands.<br>
     *
     * <b>Flags can be set to</b>:<br>
     * {@link Flags::NO_FLAG}<br>
     * {@link Flags::TEST_MODE}<br>
     * {@link Flags::RSRV_SENSITIVE_ORDER}<br>
     * {@link Flags::RSRV_PHONE_TRANSACTION}<br>
     * {@link Flags::RSRV_SEND_PHONE_PIN}<br>
     *
     * Some flags can be added to each other for multiple options.
     *
     * <b>Note</b>:<br>
     * Normal shipment type is assumed unless otherwise specified, you can do
     * this by calling:<br>
     * {@link Klarna::setShipmentInfo() setShipmentInfo('delay_adjust', ...)}
     * with either: {@link Flags::NORMAL_SHIPMENT NORMAL_SHIPMENT} or
     * {@link Flags::EXPRESS_SHIPMENT EXPRESS_SHIPMENT}<br>
     *
     * @example docs/examples/reserveAmount.php How to create a reservation.
     *
     * @param string $pno      Personal number, SSN, date of birth, etc.
     * @param int    $gender   {@link Flags::FEMALE} or
     *                         {@link Flags::MALE}, null for unspecified.
     * @param int    $amount   Amount to be reserved, including VAT.
     * @param int    $flags    Options which affect the behaviour.
     * @param int    $pclass   {@link PClass::getId() PClass ID}.
     * @param int    $encoding {@link Encoding PNO Encoding} constant.
     * @param bool   $clear    Whether customer info should be cleared after
     *                         this call.
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return array An array with reservation number and order
     *               status. [string, int]
     */
    public function reserveAmount(
        $pno,
        $gender,
        $amount,
        $flags = 0,
        $pclass = PClass::INVOICE,
        $encoding = null,
        $clear = true
    ) {
        $this->checkLocale();

        //Get the PNO/SSN encoding constant.
        if ($encoding === null) {
            $encoding = $this->getPNOEncoding();
        }

        $this->checkPNO($pno, $encoding);

        if ($gender === 'm') {
            $gender = Flags::MALE;
        } elseif ($gender === 'f') {
            $gender = Flags::FEMALE;
        }
        if ($gender !== null && strlen($gender) > 0) {
            $this->checkInt($gender, 'gender');
        }

        $this->checkInt($flags, 'flags');
        $this->checkInt($pclass, 'pclass');

        //Check so required information is set.
        $this->checkGoodslist();

        //Calculate automatically the amount from goodsList.
        if ($amount === -1) {
            $amount = (int) round($this->summarizeGoodsList());
        } else {
            $this->checkAmount($amount);
        }

        if ($amount < 0) {
            throw new \InvalidArgumentException(
                "Amount must be greater than zero, but was: {$amount}"
            );
        }

        //No addresses used for phone transactions
        if ($flags & Flags::RSRV_PHONE_TRANSACTION) {
            $billing = $shipping = '';
        } else {
            $billing = $this->assembleAddr($this->billing);
            $shipping = $this->assembleAddr($this->shipping);

            if (strlen($shipping['country']) > 0
                && ($shipping['country'] !== $this->country)
            ) {
                throw new \RuntimeException(
                    'Shipping address country must match the country set!'
                );
            }
        }

        //Assume normal shipment unless otherwise specified.
        if (!isset($this->shipInfo['delay_adjust'])) {
            $this->setShipmentInfo('delay_adjust', Flags::NORMAL_SHIPMENT);
        }

        $digestSecret = self::digest(
            "{$this->eid}:{$pno}:{$amount}:{$this->secret}"
        );

        $paramList = array(
            $pno,
            $gender,
            $amount,
            $this->reference,
            $this->reference_code,
            $this->orderid[0],
            $this->orderid[1],
            $shipping,
            $billing,
            $this->getClientIP(),
            $flags,
            $this->currency,
            $this->country,
            $this->language,
            $this->eid,
            $digestSecret,
            $encoding,
            $pclass,
            $this->goodsList,
            $this->comment,
            $this->shipInfo,
            $this->travelInfo,
            $this->incomeInfo,
            $this->bankInfo,
            $this->sid,
            $this->extraInfo,
        );

        self::printDebug('reserve_amount', $paramList);

        $result = $this->xmlrpcCall('reserve_amount', $paramList);

        if ($clear === true) {
            //Make sure any stored values that need to be unique between
            //purchases are cleared.
            $this->clear();
        }

        self::printDebug('reserve_amount result', $result);

        return $result;
    }

    /**
     * Extends the reservations expiration date.
     *
     * @example docs/examples/extendExpiryDate.php How to extend a reservations expiry date.
     *
     * @param string $rno Reservation number.
     *
     * @throws Exception\KlarnaException
     *
     * @return DateTime The new expiration date.
     */
    public function extendExpiryDate($rno)
    {
        $this->checkRNO($rno);

        $digestSecret = self::digest(
            self::colon($this->eid, $rno, $this->secret)
        );

        $paramList = array(
            $rno,
            $this->eid,
            $digestSecret,
        );

        self::printDebug('extend_expiry_date', $paramList);

        $result = $this->xmlrpcCall('extend_expiry_date', $paramList);

        // Default to server location as API does not include time zone info
        $timeZone = new \DateTimeZone('Europe/Stockholm');

        // $result = '20150525T103631';
        $date = \DateTime::createFromFormat('Ymd\THis', $result, $timeZone);
        if ($date === false) {
            throw new Exception\KlarnaException(
                "Could not parse result '{$result}' into date format 'Ymd\\This'"
            );
        }

        return $date;
    }

    /**
     * Extends the due date on the specified invoice.
     *
     * @param string $invNo   Invoice number.
     * @param int    $numDays Amount of days to extend the invoice with.
     * @param bool   $dryRun  Enabling dry run will only calculate cost.
     *
     * @throws KlarnaException
     *
     * @return array An array with cost as a float, and the new expiry date
     *               in the format YYYY-MM-DD.
     *               ['cost' => float, 'new_date' => string]
     */
    public function extendInvoiceDueDate($invNo, $numDays, $dryRun = false)
    {
        $this->checkInvNo($invNo);

        $this->checkInt($numDays, 'numDays');

        $digestSecret = self::digest(
            self::colon($this->eid, $invNo, $this->secret)
        );

        $paramList = array(
            $this->eid,
            $invNo,
            $digestSecret,
            array(
                'days' => $numDays,
                'calculate_only' => $dryRun === true
            )
        );

        self::printDebug('extend_invoice_due_date', $paramList);

        $result = $this->xmlrpcCall('extend_invoice_due_date', $paramList);

        $result['cost'] = floatval($result['cost'] / 100);

        return $result;
    }

    /**
     * Cancels a reservation.
     *
     * @example docs/examples/cancelReservation.php How to cancel a reservation.
     *
     * @param string $rno Reservation number.
     *
     * @throws Exception\KlarnaException
     *
     * @return bool True, if the cancellation was successful.
     */
    public function cancelReservation($rno)
    {
        $this->checkRNO($rno);

        $digestSecret = self::digest(
            self::colon($this->eid, $rno, $this->secret)
        );
        $paramList = array(
            $rno,
            $this->eid,
            $digestSecret,
        );

        self::printDebug('cancel_reservation', $paramList);

        $result = $this->xmlrpcCall('cancel_reservation', $paramList);

        return $result == 'ok';
    }

    /**
     * Update the reservation matching the given reservation number.
     *
     * @example docs/examples/update.php How to update a reservation.
     *
     * @param string $rno   Reservation number
     * @param bool   $clear clear set data after updating. Defaulted to true.
     *
     * @throws Exception\KlarnaException if no RNO is given, or if an error is received
     *                                   from Klarna Online.
     *
     * @return true if the update was successful
     */
    public function update($rno, $clear = true)
    {
        $rno = strval($rno);

        // All info that is sent in is part of the digest secret, in this order:
        // [
        //      proto_vsn, client_vsn, eid, rno, careof, street, zip, city,
        //      country, fname, lname, careof, street, zip, city, country,
        //      fname, lname, artno, qty, orderid1, orderid2
        // ].
        // The address part appears twice, that is one per address that
        // changes. If no value is sent in for an optional field, there
        // is no entry for this field in the digest secret. Shared secret
        // is added at the end of the digest secret.
        $digestArray = array(
            str_replace('.', ':', $this->proto),
            $this->version,
            $this->eid,
            $rno,
        );
        $digestArray = array_merge(
            $digestArray,
            $this->addressDigestPart($this->shipping)
        );
        $digestArray = array_merge(
            $digestArray,
            $this->addressDigestPart($this->billing)
        );
        if (is_array($this->goodsList) && $this->goodsList !== array()) {
            foreach ($this->goodsList as $goods) {
                if (strlen($goods['goods']['artno']) > 0) {
                    $digestArray[] = $goods['goods']['artno'];
                } else {
                    $digestArray[] = $goods['goods']['title'];
                }
                $digestArray[] = $goods['qty'];
            }
        }
        foreach ($this->orderid as $orderid) {
            $digestArray[] = $orderid;
        }
        $digestArray[] = $this->secret;

        $digestSecret = $this->digest(
            call_user_func_array(
                array('self', 'colon'),
                $digestArray
            )
        );

        $shipping = array();
        $billing = array();
        if ($this->shipping !== null && $this->shipping instanceof Address) {
            $shipping = $this->shipping->toArray();
        }
        if ($this->billing !== null && $this->billing instanceof Address) {
            $billing = $this->billing->toArray();
        }
        $paramList = array(
            $this->eid,
            $digestSecret,
            $rno,
            array(
                'goods_list' => $this->goodsList,
                'dlv_addr' => $shipping,
                'bill_addr' => $billing,
                'orderid1' => $this->orderid[0],
                'orderid2' => $this->orderid[1],
            ),
        );

        self::printDebug('update array', $paramList);

        $result = $this->xmlrpcCall('update', $paramList);

        self::printDebug('update result', $result);

        return $result === 'ok';
    }

    /**
     * Help function to sort the address for update digest.
     *
     * @param Address|null $address Address object or null
     *
     * @return array
     */
    private function addressDigestPart(Address $address = null)
    {
        if ($address === null) {
            return array();
        }

        $keyOrder = array(
            'careof', 'street', 'zip', 'city', 'country', 'fname', 'lname',
        );

        $holder = $address->toArray();
        $digest = array();

        foreach ($keyOrder as $key) {
            if ($holder[$key] != '') {
                $digest[] = $holder[$key];
            }
        }

        return $digest;
    }

    /**
     * Activate the reservation matching the given reservation number.
     * Optional information should be set in ActivateInfo.
     *
     * To perform a partial activation, use the addArtNo function to specify
     * which items in the reservation to include in the activation.
     *
     * @example docs/examples/activate.php How to activate a reservation.
     *
     * @param string $rno   Reservation number
     * @param string $ocr   optional OCR number to attach to the reservation when
     *                      activating. Overrides OCR specified in activateInfo.
     * @param string $flags optional flags to affect behaviour. If specified it
     *                      will overwrite any flag set in activateInfo.
     * @param bool   $clear clear set data after activating. Defaulted to true.
     *
     * @throws Exception\KlarnaException when the RNO is not specified, or if an error
     *                                   is received from Klarna Online.
     *
     * @return A string array with risk status and reservation number.
     */
    public function activate(
        $rno,
        $ocr = null,
        $flags = null,
        $clear = true
    ) {
        $this->checkRNO($rno);

        // Overwrite any OCR set on activateInfo if supplied here since this
        // method call is more specific.
        if ($ocr !== null) {
            $this->setActivateInfo('ocr', $ocr);
        }

        // If flags is specified set the flag supplied here to activateInfo.
        if ($flags !== null) {
            $this->setActivateInfo('flags', $flags);
        }

        //Assume normal shipment unless otherwise specified.
        if (!array_key_exists('delay_adjust', $this->shipInfo)) {
            $this->setShipmentInfo('delay_adjust', Flags::NORMAL_SHIPMENT);
        }

        // Append shipment info to activateInfo
        $this->activateInfo['shipment_info'] = $this->shipInfo;

        // Unlike other calls, if NO_FLAG is specified it should not be sent in
        // at all.
        if (array_key_exists('flags', $this->activateInfo)
            && $this->activateInfo['flags'] === Flags::NO_FLAG
        ) {
            unset($this->activateInfo['flags']);
        }

        // Build digest. Any field in activateInfo that is set is included in
        // the digest.
        $digestArray = array(
            str_replace('.', ':', $this->proto),
            $this->version,
            $this->eid,
            $rno,
        );

        $optionalDigestKeys = array(
            'bclass',
            'cust_no',
            'flags',
            'ocr',
            'orderid1',
            'orderid2',
            'reference',
            'reference_code',
        );

        foreach ($optionalDigestKeys as $key) {
            if (array_key_exists($key, $this->activateInfo)) {
                $digestArray[] = $this->activateInfo[$key];
            }
        }

        if (array_key_exists('delay_adjust', $this->activateInfo['shipment_info'])) {
            $digestArray[] = $this->activateInfo['shipment_info']['delay_adjust'];
        }

        // If there are any artnos added with addArtNo, add them to the digest
        // and to the activateInfo
        if (is_array($this->artNos)) {
            foreach ($this->artNos as $artNo) {
                $digestArray[] = $artNo['artno'];
                $digestArray[] = $artNo['qty'];
            }
            $this->setActivateInfo('artnos', $this->artNos);
        }

        $digestArray[] = $this->secret;
        $digestSecret = self::digest(
            call_user_func_array(
                array('self', 'colon'),
                $digestArray
            )
        );

        // Create the parameter list.
        $paramList = array(
            $this->eid,
            $digestSecret,
            $rno,
            $this->activateInfo,
        );

        self::printDebug('activate array', $paramList);

        $result = $this->xmlrpcCall('activate', $paramList);

        self::printDebug('activate result', $result);

        // Clear the state if specified.
        if ($clear) {
            $this->clear();
        }

        return $result;
    }

    /**
     * Splits a reservation due to for example outstanding articles.
     *
     * <b>For flags usage see</b>:<br>
     * {@link Klarna::reserveAmount()}<br>
     *
     * @example docs/examples/splitReservation.php How to split a reservation.
     *
     * @param string $rno    Reservation number.
     * @param int    $amount The amount to be subtracted from the reservation.
     * @param int    $flags  Options which affect the behaviour.
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return string A new reservation number.
     */
    public function splitReservation(
        $rno,
        $amount,
        $flags = Flags::NO_FLAG
    ) {
        //Check so required information is set.
        $this->checkRNO($rno);
        $this->checkAmount($amount);

        if ($amount <= 0) {
            throw new \InvalidArgumentException("Amount cannot be negative: {$amount}");
        }

        $digestSecret = self::digest(
            self::colon($this->eid, $rno, $amount, $this->secret)
        );
        $paramList = array(
            $rno,
            $amount,
            $this->orderid[0],
            $this->orderid[1],
            $flags,
            $this->eid,
            $digestSecret,
        );

        self::printDebug('split_reservation array', $paramList);

        $result = $this->xmlrpcCall('split_reservation', $paramList);

        self::printDebug('split_reservation result', $result);

        return $result;
    }

    /**
     * Reserves a specified number of OCR numbers.<br>
     * For the specified country or the {@link Klarna::setCountry() set country}.<br>.
     *
     * @example docs/examples/reserveOCR.php How to reserve OCRs.
     *
     * @param int $no      The number of OCR numbers to reserve.
     * @param int $country {@link Country} constant.
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return array An array of OCR numbers.
     */
    public function reserveOCR($no, $country = null)
    {
        $this->checkNo($no);
        if ($country === null) {
            if (!$this->country) {
                throw new \InvalidArgumentException('You must set country first!');
            }
            $country = $this->country;
        } else {
            $this->checkCountry($country);
        }

        $digestSecret = self::digest(
            self::colon($this->eid, $no, $this->secret)
        );
        $paramList = array(
            $no,
            $this->eid,
            $digestSecret,
            $country,
        );

        self::printDebug('reserve_ocr_nums array', $paramList);

        return $this->xmlrpcCall('reserve_ocr_nums', $paramList);
    }

    /**
     * Checks if the specified SSN/PNO has an part payment account with Klarna.
     *
     * @example docs/examples/hasAccount.php How to check for a part payment account.
     *
     * @param string $pno      Social security number, Personal number, ...
     * @param int    $encoding {@link Encoding PNO Encoding} constant.
     *
     * @throws Exception\KlarnaException
     *
     * @return bool True, if customer has an account.
     */
    public function hasAccount($pno, $encoding = null)
    {
        //Get the PNO/SSN encoding constant.
        if ($encoding === null) {
            $encoding = $this->getPNOEncoding();
        }

        $this->checkPNO($pno, $encoding);

        $digest = self::digest(
            self::colon($this->eid, $pno, $this->secret)
        );

        $paramList = array(
            $this->eid,
            $pno,
            $digest,
            $encoding,
        );

        self::printDebug('has_account', $paramList);

        $result = $this->xmlrpcCall('has_account', $paramList);

        return $result === 'true';
    }

    /**
     * Adds an article number and quantity to be used in
     * {@link Klarna::creditPart()}.
     *
     * @param int    $qty   Quantity of specified article.
     * @param string $artNo Article number.
     *
     * @throws Exception\KlarnaException
     */
    public function addArtNo($qty, $artNo)
    {
        $this->checkQty($qty);
        $this->checkArtNo($artNo);

        if (!is_array($this->artNos)) {
            $this->artNos = array();
        }

        $this->artNos[] = array('artno' => $artNo, 'qty' => $qty);
    }

    /**
     * Sends an activated invoice to the customer via e-mail. <br>
     * The email is sent in plain text format and contains a link to a
     * PDF-invoice.<br>.
     *
     * <b>Please note!</b><br>
     * Regular postal service is used if the customer has not entered his/her
     * e-mail address when making the purchase (charges may apply).<br>
     *
     * @example docs/examples/emailInvoice.php How to email an invoice.
     *
     * @param string $invNo Invoice number.
     *
     * @throws Exception\KlarnaException
     *
     * @return string Invoice number.
     */
    public function emailInvoice($invNo)
    {
        $this->checkInvNo($invNo);

        $digestSecret = self::digest(
            self::colon($this->eid, $invNo, $this->secret)
        );
        $paramList = array(
            $this->eid,
            $invNo,
            $digestSecret,
        );

        self::printDebug('email_invoice array', $paramList);

        return $this->xmlrpcCall('email_invoice', $paramList);
    }

    /**
     * Requests a postal send-out of an activated invoice to a customer by
     * Klarna (charges may apply).
     *
     * @example docs/examples/sendInvoice.php How to send an invoice.
     *
     * @param string $invNo Invoice number.
     *
     * @throws Exception\KlarnaException
     *
     * @return string Invoice number.
     */
    public function sendInvoice($invNo)
    {
        $this->checkInvNo($invNo);

        $digestSecret = self::digest(
            self::colon($this->eid, $invNo, $this->secret)
        );
        $paramList = array(
            $this->eid,
            $invNo,
            $digestSecret,
        );

        self::printDebug('send_invoice array', $paramList);

        return $this->xmlrpcCall('send_invoice', $paramList);
    }

    /**
     * Gives discounts on invoices.<br>
     * If you are using standard integration and the purchase is not yet
     * activated (you have not yet delivered the goods), <br>
     * just change the article list in our online interface Klarna Online.<br>.
     *
     * <b>Flags can be</b>:<br>
     * {@link Flags::INC_VAT}<br>
     * {@link Flags::NO_FLAG}, <b>NOT RECOMMENDED!</b><br>
     *
     * @param string $invNo       Invoice number.
     * @param int    $amount      The amount given as a discount.
     * @param float  $vat         VAT in percent, e.g. 22.2 for 22.2%.
     * @param int    $flags       If amount is
     *                            {@link Flags::INC_VAT including} or
     *                            {@link Flags::NO_FLAG excluding} VAT.
     * @param string $description Optional custom text to present as discount
     *                            in the invoice.
     *
     * @example docs/examples/returnAmount.php How to perform a return.
     *
     * @throws Exception\KlarnaException
     *
     * @return string Invoice number.
     */
    public function returnAmount(
        $invNo,
        $amount,
        $vat,
        $flags = Flags::INC_VAT,
        $description = ''
    ) {
        $this->checkInvNo($invNo);
        $this->checkAmount($amount);
        $this->checkVAT($vat);
        $this->checkInt($flags, 'flags');

        if ($description == null) {
            $description = '';
        }

        $digestSecret = self::digest(
            self::colon($this->eid, $invNo, $this->secret)
        );
        $paramList = array(
            $this->eid,
            $invNo,
            $amount,
            $vat,
            $digestSecret,
            $flags,
            $description,
        );

        self::printDebug('return_amount', $paramList);

        return $this->xmlrpcCall('return_amount', $paramList);
    }

    /**
     * Performs a complete refund on an invoice, part payment and mobile
     * purchase.
     *
     * @example docs/examples/creditInvoice.php How to credit an invoice.
     *
     * @param string $invNo  Invoice number.
     * @param string $credNo Credit number.
     *
     * @throws Exception\KlarnaException
     *
     * @return string Invoice number.
     */
    public function creditInvoice($invNo, $credNo = '')
    {
        $this->checkInvNo($invNo);
        $this->checkCredNo($credNo);

        $digestSecret = self::digest(
            self::colon($this->eid, $invNo, $this->secret)
        );
        $paramList = array(
            $this->eid,
            $invNo,
            $credNo,
            $digestSecret,
        );

        self::printDebug('credit_invoice', $paramList);

        return $this->xmlrpcCall('credit_invoice', $paramList);
    }

    /**
     * Performs a partial refund on an invoice, part payment or mobile purchase.
     *
     * <b>Note</b>:<br>
     * You need to call {@link Klarna::addArtNo()} first.<br>
     *
     * @example docs/examples/creditPart.php How to partially credit an invoice.
     *
     * @param string $invNo  Invoice number.
     * @param string $credNo Credit number.
     *
     * @see Klarna::addArtNo()
     *
     * @throws Exception\KlarnaException
     *
     * @return string Invoice number.
     */
    public function creditPart($invNo, $credNo = '')
    {
        $this->checkInvNo($invNo);
        $this->checkCredNo($credNo);

        if ($this->goodsList === null || empty($this->goodsList)) {
            $this->checkArtNos($this->artNos);
        }

        //function activate_part_digest
        $string = $this->eid.':'.$invNo.':';

        if ($this->artNos !== null && !empty($this->artNos)) {
            foreach ($this->artNos as $artNo) {
                $string .= $artNo['artno'].':'.$artNo['qty'].':';
            }
        }

        $digestSecret = self::digest($string.$this->secret);
        //end activate_part_digest

        $paramList = array(
            $this->eid,
            $invNo,
            $this->artNos,
            $credNo,
            $digestSecret,
        );

        if ($this->goodsList !== null && !empty($this->goodsList)) {
            $paramList[] = 0;
            $paramList[] = $this->goodsList;
        }

        $this->artNos = array();

        self::printDebug('credit_part', $paramList);

        return $this->xmlrpcCall('credit_part', $paramList);
    }

    /**
     * Returns the current order status for a specific reservation or invoice.
     * Use this when {@link Klarna::reserveAmount()} returns a {@link Flags::PENDING}
     * status.
     *
     * <b>Order status can be</b>:<br>
     * {@link Flags::ACCEPTED}<br>
     * {@link Flags::PENDING}<br>
     * {@link Flags::DENIED}<br>
     *
     * @example docs/examples/checkOrderStatus.php How to check a order status.
     *
     * @param string $id   Reservation number or invoice number.
     * @param int    $type 0 if $id is an invoice or reservation, 1 for order id
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return string The order status.
     */
    public function checkOrderStatus($id, $type = 0)
    {
        $this->checkArgument($id, 'id');

        $this->checkInt($type, 'type');
        if ($type !== 0 && $type !== 1) {
            throw new \InvalidArgumentException('Expected type to be 0 or 1');
        }

        $digestSecret = self::digest(
            self::colon($this->eid, $id, $this->secret)
        );
        $paramList = array(
            $this->eid,
            $digestSecret,
            $id,
            $type,
        );

        self::printDebug('check_order_status', $paramList);

        return $this->xmlrpcCall('check_order_status', $paramList);
    }

    /**
     * Get the PClasses from Klarna Online.<br>
     * You are only allowed to call this once, or once per update of PClasses
     * in KO.<br>.
     *
     * <b>Note</b>:<br>
     * You should store these in a DB of choice for later use.
     *
     * @example docs/examples/getPClasses.php How to get your estore PClasses.
     *
     * @param string|int $country  {@link Country Country} constant,
     *                             or two letter code.
     * @param mixed      $language {@link Language Language} constant,
     *                             or two letter code.
     * @param mixed      $currency {@link Currency Currency} constant,
     *                             or three letter code.
     *
     * @throws Exception\KlarnaException
     *
     * @return PClass[] A list of pclasses.
     */
    public function getPClasses($country = null, $language = null, $currency = null)
    {
        extract(
            $this->getLocale($country, $language, $currency),
            EXTR_OVERWRITE
        );

        $this->checkConfig();

        $digestSecret = self::digest(
            $this->eid.':'.$currency.':'.$this->secret
        );

        $paramList = array(
            $this->eid,
            $currency,
            $digestSecret,
            $country,
            $language,
        );

        self::printDebug('get_pclasses array', $paramList);

        $result = $this->xmlrpcCall('get_pclasses', $paramList);

        self::printDebug('get_pclasses result', $result);

        $pclasses = array();

        foreach ($result as $data) {
            $pclass = new PClass();
            $pclass->setEid($this->eid);
            $pclass->setId($data[0]);
            $pclass->setDescription($data[1]);
            $pclass->setMonths($data[2]);
            $pclass->setStartFee($data[3] / 100);
            $pclass->setInvoiceFee($data[4] / 100);
            $pclass->setInterestRate($data[5] / 100);
            $pclass->setMinAmount($data[6] / 100);
            $pclass->setCountry($data[7]);
            $pclass->setType($data[8]);
            $pclass->setExpire(strtotime($data[9]));

            $pclasses[] = $pclass;
        }

        return $pclasses;
    }

    /**
     * Returns the cheapest, per month, PClass related to the specified sum.
     *
     * <b>Note</b>: This choose the cheapest PClass for the current country.<br>
     * {@link Klarna::setCountry()}
     *
     * <b>Flags can be</b>:<br>
     * {@link Flags::CHECKOUT_PAGE}<br>
     * {@link Flags::PRODUCT_PAGE}<br>
     *
     * @param float    $sum      The product cost, or total sum of the cart.
     * @param int      $flags    Which type of page the info will be displayed on.
     * @param PClass[] $pclasses The list of pclasses to search in.
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return PClass or false if none was found.
     */
    public function getCheapestPClass($sum, $flags, $pclasses)
    {
        if (!is_numeric($sum)) {
            throw new \InvalidArgumentException("Sum has to be numeric: {$sum}");
        }

        if (!is_numeric($flags)
            || !in_array(
                $flags,
                array(
                    Flags::CHECKOUT_PAGE, Flags::PRODUCT_PAGE, )
            )
        ) {
            throw new \InvalidArgumentException(
                'Expected $flags to be '.Flags::CHECKOUT_PAGE.' or '.Flags::PRODUCT_PAGE
            );
        }

        $lowest_pp = $lowest = false;

        foreach ($pclasses as $pclass) {
            $lowest_payment = Calc::getLowestPaymentForAccount(
                $pclass->getCountry()
            );
            if ($pclass->getType() < 2 && $sum >= $pclass->getMinAmount()) {
                $minpay = Calc::calcMonthlyCost(
                    $sum,
                    $pclass,
                    $flags
                );

                if ($minpay < $lowest_pp || $lowest_pp === false) {
                    if ($pclass->getType() == PClass::ACCOUNT
                        || $minpay >= $lowest_payment
                    ) {
                        $lowest_pp = $minpay;
                        $lowest = $pclass;
                    }
                }
            }
        }

        return $lowest;
    }

    /**
     * Creates a XMLRPC call with specified XMLRPC method and parameters from array.
     *
     * @param string $method XMLRPC method.
     * @param array  $array  XMLRPC parameters.
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return mixed
     */
    protected function xmlrpcCall($method, $array)
    {
        $this->checkConfig();

        if (!isset($method) || !is_string($method)) {
            throw new \InvalidArgumentException('method has to be a string');
        }
        if ($array === null || count($array) === 0) {
            throw new \InvalidArgumentException('Parameter list cannot empty or null!');
        }
        if (self::$disableXMLRPC) {
            return true;
        }

        $encoder = new PhpXmlRpc\Encoder();

        try {
            //Create the XMLRPC message.
            $msg = new PhpXmlRpc\Request($method);
            $params = array_merge(
                array(
                    $this->proto,
                    $this->version,
                ),
                $array
            );

            foreach ($params as $p) {
                if (!$msg->addParam(
                    $encoder->encode($p, array('extension_api'))
                )
                ) {
                    throw new \RuntimeException(
                        'Failed to add parameters to XMLRPC message.',
                        50068
                    );
                }
            }

            if (self::$xmlrpcDebug) {
                $this->xmlrpc->setDebug(2);
            }

            $internalEncoding = PhpXmlRpc\PhpXmlRpc::$xmlrpc_internalencoding;
            PhpXmlRpc\PhpXmlRpc::$xmlrpc_internalencoding = $this->encoding;

            //Send the message.
            $xmlrpcresp = $this->xmlrpc->send(
                $msg,
                isset($this->config['timeout']) ? intval($this->config['timeout']) : 10
            );

            $status = $xmlrpcresp->faultCode();

            PhpXmlRpc\PhpXmlRpc::$xmlrpc_internalencoding = $internalEncoding;

            if ($status !== 0) {
                throw new Exception\KlarnaException($xmlrpcresp->faultString(), $status);
            }

            return $encoder->decode($xmlrpcresp->value());
        } catch (Exception\KlarnaException $e) {
            //Otherwise it is caught below, and re-thrown.
            throw $e;
        } catch (\Exception $e) {
            throw new Exception\KlarnaException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create a new CurlTransport.
     *
     * @return CurlTransport New CurlTransport instance
     */
    public function createTransport()
    {
        return new CurlTransport(
            new CurlHandle(),
            isset($this->config['timeout']) ? intval($this->config['timeout']) : 10
        );
    }

    /**
     * Perform a checkout service request.
     *
     * @example docs/examples/checkoutService.php How to use the checkout service.
     *
     * @param int|float $price    The total price for the checkout including VAT.
     * @param string    $currency ISO 4217 Currency Code
     * @param string    $locale   Specify what locale is used by the checkout.
     *                            ISO 639 language and ISO 3166-1 country separated
     *                            by underscore. Example: sv_SE
     * @param string    $country  (Optional) Specify what ISO 3166-1 country to use
     *                            for fetching payment methods. If not specified
     *                            the locale country will be used.
     *
     * @throws \RuntimeException If the curl extension is not loaded
     *
     * @return CheckoutServiceResponse Response with payment methods
     */
    public function checkoutService($price, $currency, $locale, $country = null)
    {
        $this->checkAmount($price);

        $params = array(
            'merchant_id' => $this->config['eid'],
            'total_price' => $price,
            'currency' => strtoupper($currency),
            'locale' => strtolower($locale),
        );

        if ($country !== null) {
            $params['country'] = $country;
        }

        return $this->createTransport()->send(
            new CheckoutServiceRequest($this->config, $params)
        );
    }

    /**
     * Removes all relevant order/customer data from the internal structure.
     */
    public function clear()
    {
        $this->goodsList = null;
        $this->comment = '';

        $this->billing = null;
        $this->shipping = null;

        $this->shipInfo = array();
        $this->extraInfo = array();
        $this->bankInfo = array();
        $this->incomeInfo = array();
        $this->activateInfo = array();

        $this->reference = '';
        $this->reference_code = '';

        $this->orderid[0] = '';
        $this->orderid[1] = '';

        $this->artNos = array();
    }

    /**
     * Implodes parameters with delimiter ':'.
     * Null and "" values are ignored by the colon function to
     * ensure there is not several colons in succession.
     *
     * @return string Colon separated string.
     */
    public static function colon()
    {
        $args = func_get_args();

        return implode(
            ':',
            array_filter(
                $args,
                array('self', 'filterDigest')
            )
        );
    }

    /**
     * Implodes parameters with delimiter '|'.
     *
     * @return string Pipe separated string.
     */
    public static function pipe()
    {
        $args = func_get_args();

        return implode('|', $args);
    }

    /**
     * Check if the value has a string length larger than 0.
     *
     * @param mixed $value The value to check.
     *
     * @return bool True if string length is larger than 0
     */
    public static function filterDigest($value)
    {
        return strlen(strval($value)) > 0;
    }

    /**
     * Creates a digest hash from the inputted string,
     * and the specified or the preferred hash algorithm.
     *
     * @param string $data Data to be hashed.
     * @param string $hash hash algoritm to use
     *
     * @throws Exception\KlarnaException
     *
     * @return string Base64 encoded hash.
     */
    public static function digest($data, $hash = null)
    {
        if ($hash === null) {
            $preferred = array(
                'sha512',
                'sha384',
                'sha256',
                'sha224',
                'md5',
            );

            $hashes = array_intersect($preferred, hash_algos());

            if (count($hashes) == 0) {
                throw new Exception\KlarnaException(
                    'No available hash algorithm supported!'
                );
            }
            $hash = array_shift($hashes);
        }
        self::printDebug('digest() using hash', $hash);

        return base64_encode(pack('H*', hash($hash, $data)));
    }

    /**
     * Prints debug information if debug is set to true.
     * $msg is used as header/footer in the output.
     *
     * If FirePHP is available it will be used instead of
     * dumping the debug info into the document.
     *
     * It uses print_r and encapsulates it in HTML/XML comments.
     * (<!-- -->)
     *
     * @param string $msg   Debug identifier, e.g. "my array".
     * @param mixed  $mixed Object, type, etc, to be debugged.
     */
    public static function printDebug($msg, $mixed)
    {
        if (self::$debug) {
            if (class_exists('FB', false)) {
                FB::send($mixed, $msg);
            } else {
                echo "\n<!-- {$msg}: \n";
                print_r($mixed);
                echo "\n end {$msg} -->\n";
            }
        }
    }

    /**
     * Checks/fixes so the invNo input is valid.
     *
     * @param string $invNo Invoice number.
     *
     * @throws \InvalidArgumentException
     */
    private function checkInvNo(&$invNo)
    {
        if (!isset($invNo)) {
            throw new \InvalidArgumentException('Invoice number must be set');
        }
        if (!is_string($invNo)) {
            $invNo = strval($invNo);
        }
        if (strlen($invNo) == 0) {
            throw new \InvalidArgumentException("Invoice number must be a string: {$invNo}");
        }
    }

    /**
     * Checks/fixes so the quantity input is valid.
     *
     * @param int $qty Quantity.
     *
     * @throws \InvalidArgumentException
     */
    private function checkQty(&$qty)
    {
        if (!isset($qty)) {
            throw new \InvalidArgumentException('Quantity must be set');
        }
        if (is_numeric($qty) && !is_int($qty)) {
            $qty = intval($qty);
        }
        if (!is_int($qty)) {
            throw new \InvalidArgumentException('Expected Quantity to be an integer');
        }
    }

    /**
     * Checks/fixes so the artNo input is valid.
     *
     * @param int|string $artNo Article number.
     *
     * @throws \InvalidArgumentException
     */
    private function checkArtNo(&$artNo)
    {
        if (is_numeric($artNo) && !is_string($artNo)) {
            //Convert artNo to string if integer.
            $artNo = strval($artNo);
        }
        if (!isset($artNo) || strlen($artNo) == 0 || (!is_string($artNo))) {
            throw new \InvalidArgumentException("artNo must be set and a string: {$artNo}");
        }
    }

    /**
     * Checks/fixes so the credNo input is valid.
     *
     * @param string $credNo Credit number.
     *
     * @throws \InvalidArgumentException
     */
    private function checkCredNo(&$credNo)
    {
        if (!isset($credNo)) {
            throw new \InvalidArgumentException('credNo is not set');
        }

        if ($credNo === false || $credNo === null) {
            $credNo = '';
        }
        if (!is_string($credNo)) {
            $credNo = strval($credNo);
            if (!is_string($credNo)) {
                throw new \InvalidArgumentException('Credit number is not a string');
            }
        }
    }

    /**
     * Checks so that artNos is an array and is not empty.
     *
     * @param array $artNos Array from {@link Klarna::addArtNo()}.
     *
     * @throws \InvalidArgumentException
     */
    private function checkArtNos(&$artNos)
    {
        if (!is_array($artNos)) {
            throw new \InvalidArgumentException('artNos is not an array');
        }
        if (empty($artNos)) {
            throw new \InvalidArgumentException('ArtNo array cannot empty!');
        }
    }

    /**
     * Checks/fixes so the integer input is valid.
     *
     * @param int    $int   {@link Flags flags} constant.
     * @param string $field Name of the field.
     *
     * @throws \InvalidArgumentException
     */
    private function checkInt(&$int, $field)
    {
        if (!isset($int)) {
            throw new \InvalidArgumentException("Expected field to be set: {$field}");
        }
        if (is_numeric($int) && !is_int($int)) {
            $int = intval($int);
        }
        if (!is_numeric($int) || !is_int($int)) {
            throw new \InvalidArgumentException(
                "Expected field to be an integer: {$field}"
            );
        }
    }

    /**
     * Checks/fixes so the VAT input is valid.
     *
     * @param float $vat VAT.
     *
     * @throws \InvalidArgumentException
     */
    private function checkVAT(&$vat)
    {
        if (!isset($vat)) {
            throw new \InvalidArgumentException('VAT must be set');
        }
        if (is_numeric($vat) && (!is_int($vat) || !is_float($vat))) {
            $vat = floatval($vat);
        }
        if (!is_numeric($vat) || (!is_int($vat) && !is_float($vat))) {
            throw new \InvalidArgumentException(
                "VAT must be an integer or float: {$vat}"
            );
        }
    }

    /**
     * Checks/fixes so the amount input is valid.
     *
     * @param int $amount Amount.
     *
     * @throws \InvalidArgumentException
     */
    private function checkAmount(&$amount)
    {
        if (!isset($amount)) {
            throw new \InvalidArgumentException('Amount must be set');
        }
        if (is_numeric($amount)) {
            $this->fixValue($amount);
        }
        if (is_numeric($amount) && !is_int($amount)) {
            $amount = intval($amount);
        }
        if (!is_numeric($amount) || !is_int($amount)) {
            throw new \InvalidArgumentException("amount must be an integer: {$amount}");
        }
    }

    /**
     * Checks/fixes so the price input is valid.
     *
     * @param int $price Price.
     *
     * @throws \InvalidArgumentException
     */
    private function checkPrice(&$price)
    {
        if (!isset($price)) {
            throw new \InvalidArgumentException('Price must be set');
        }
        if (is_numeric($price)) {
            $this->fixValue($price);
        }
        if (is_numeric($price) && !is_int($price)) {
            $price = intval($price);
        }
        if (!is_numeric($price) || !is_int($price)) {
            throw new \InvalidArgumentException("Price must be an integer: {$price}");
        }
    }

    /**
     * Multiplies value with 100 and rounds it.
     * This fixes value/price/amount inputs so that KO can handle them.
     *
     * @param float $value value
     */
    private function fixValue(&$value)
    {
        $value = round($value * 100);
    }

    /**
     * Checks/fixes so the discount input is valid.
     *
     * @param float $discount Discount amount.
     *
     * @throws \InvalidArgumentException
     */
    private function checkDiscount(&$discount)
    {
        if (!isset($discount)) {
            throw new \InvalidArgumentException('Discount must be set');
        }
        if (is_numeric($discount)
            && (!is_int($discount) || !is_float($discount))
        ) {
            $discount = floatval($discount);
        }

        if (!is_numeric($discount)
            || (!is_int($discount) && !is_float($discount))
        ) {
            throw new \InvalidArgumentException(
                "Discount must be an integer or float: {$discount}"
            );
        }
    }

    /**
     * Checks/fixes to the PNO/SSN input is valid.
     *
     * @param string $pno Personal number, social security  number, ...
     * @param int    $enc {@link Encoding PNO Encoding} constant.
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException         If pno is unsupported
     */
    private function checkPNO(&$pno, $enc)
    {
        if (!$pno) {
            throw new \InvalidArgumentException('PNO/SSN must be set');
        }

        if (!Encoding::checkPNO($pno)) {
            throw new \RuntimeException('PNO/SSN is not valid!');
        }
    }

    /**
     * Checks/fixes to the country input is valid.
     *
     * @param int $country {@link Country Country} constant.
     *
     * @throws \InvalidArgumentException
     */
    private function checkCountry(&$country)
    {
        if (!isset($country)) {
            throw new \InvalidArgumentException('Country must be set');
        }
        if (is_numeric($country) && !is_int($country)) {
            $country = intval($country);
        }
        if (!is_numeric($country) || !is_int($country)) {
            throw new \InvalidArgumentException(
                "Country must be an integer: {$country}"
            );
        }
    }

    /**
     * Checks/fixes to the language input is valid.
     *
     * @param int $language {@link Language Language} constant.
     *
     * @throws \InvalidArgumentException
     */
    private function checkLanguage(&$language)
    {
        if (!isset($language)) {
            throw new \InvalidArgumentException('Language must be set');
        }
        if (is_numeric($language) && !is_int($language)) {
            $language = intval($language);
        }
        if (!is_numeric($language) || !is_int($language)) {
            throw new \InvalidArgumentException('Language must be a integer');
        }
    }

    /**
     * Checks/fixes to the currency input is valid.
     *
     * @param int $currency {@link Currency Currency} constant.
     *
     * @throws \InvalidArgumentException
     */
    private function checkCurrency(&$currency)
    {
        if (!isset($currency)) {
            throw new \InvalidArgumentException('Currency must be set');
        }
        if (is_numeric($currency) && !is_int($currency)) {
            $currency = intval($currency);
        }
        if (!is_numeric($currency) || !is_int($currency)) {
            throw new \InvalidArgumentException('Currency must be a integer');
        }
    }

    /**
     * Checks/fixes so no/number is a valid input.
     *
     * @param int $no Number.
     *
     * @throws \InvalidArgumentException
     */
    private function checkNo(&$no)
    {
        if (!isset($no)) {
            throw new \InvalidArgumentException('number must be set');
        }
        if (is_numeric($no) && !is_int($no)) {
            $no = intval($no);
        }
        if (!is_numeric($no) || !is_int($no) || $no <= 0) {
            throw new \InvalidArgumentException(
                'number must be an integer and greater than 0'
            );
        }
    }

    /**
     * Checks/fixes so reservation number is a valid input.
     *
     * @param string $rno Reservation number.
     *
     * @throws \InvalidArgumentException
     */
    private function checkRNO(&$rno)
    {
        if (!is_string($rno)) {
            $rno = strval($rno);
        }
        if (strlen($rno) == 0) {
            throw new \InvalidArgumentException('RNO must be set');
        }
    }

    /**
     * Checks/fixes so that reference/refCode are valid.
     *
     * @param string $reference Reference string.
     * @param string $refCode   Reference code.
     *
     * @throws \InvalidArgumentException
     */
    private function checkRef(&$reference, &$refCode)
    {
        if (!is_string($reference)) {
            $reference = strval($reference);
            if (!is_string($reference)) {
                throw new \InvalidArgumentException('Reference must be a string');
            }
        }

        if (!is_string($refCode)) {
            $refCode = strval($refCode);
            if (!is_string($refCode)) {
                throw new \InvalidArgumentException('Reference code must be a string');
            }
        }
    }

    /**
     * Check so required argument is supplied.
     *
     * @param string $argument argument to check
     * @param string $name     name of argument
     *
     * @throws \InvalidArgumentException
     */
    private function checkArgument($argument, $name)
    {
        if (!is_string($argument)) {
            $argument = strval($argument);
        }

        if (strlen($argument) == 0) {
            throw new \InvalidArgumentException("{$name} must be set: {$argument}");
        }
    }

    /**
     * Check so Locale settings (country, currency, language) are set.
     *
     * @throws \RuntimeException If locale configurations are missing
     */
    private function checkLocale()
    {
        if (!is_int($this->country)
            || !is_int($this->language)
            || !is_int($this->currency)
        ) {
            throw new \RuntimeException('You must set country, language and currency!');
        }
    }

    /**
     * Checks whether a goods list is set.
     *
     * @throws \RuntimeException
     */
    private function checkGoodslist()
    {
        if (!is_array($this->goodsList) || empty($this->goodsList)) {
            throw new \RuntimeException('No articles in goods list');
        }
    }

    /**
     * Ensure the configuration is of the correct type.
     *
     * @param array|ArrayAccess|null $config an optional config to validate
     *
     * @throws \RuntimeException
     */
    private function checkConfig($config = null)
    {
        if ($config === null) {
            $config = $this->config;
        }
        if (!($config instanceof \ArrayAccess)
            && !is_array($config)
        ) {
            throw new \RuntimeException('Klarna instance not fully configured');
        }
    }
}
