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

/**
 * PClass object used for part payment.
 *
 * PClasses are used in conjunction with Calc to determine part payment costs.
 *
 * @ignore Do not show in PHPDoc.
 */
class PClass
{
    /**
     * Invoice type/identifier, used for invoice purchases.
     *
     * @var int
     */
    const INVOICE = -1;

    /**
     * Campaign type pclass.
     *
     * @var int
     */
    const CAMPAIGN = 0;

    /**
     * Account type pclass.
     *
     * @var int
     */
    const ACCOUNT = 1;

    /**
     * Special campaign type pclass.<br>
     * "Buy now, pay in x month"<br>.
     *
     * @var int
     */
    const SPECIAL = 2;

    /**
     * Fixed campaign type pclass.
     *
     * @var int
     */
    const FIXED = 3;

    /**
     * Delayed campaign type pclass.<br>
     * "Pay in X months"<br>.
     *
     * @var int
     */
    const DELAY = 4;

    /**
     * Klarna Mobile type pclass.
     *
     * @var int
     */
    const MOBILE = 5;

    /**
     * The description for this PClass.
     * HTML entities for special characters.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var string
     */
    protected $description;

    /**
     * Number of months for this PClass.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var int
     */
    protected $months;

    /**
     * PClass starting fee.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var float
     */
    protected $startFee;

    /**
     * PClass invoice/handling fee.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var float
     */
    protected $invoiceFee;

    /**
     * PClass interest rate.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var float
     */
    protected $interestRate;

    /**
     * PClass minimum amount for purchase/product.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var float
     */
    protected $minAmount;

    /**
     * PClass country.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @see Country
     *
     * @var int
     */
    protected $country;

    /**
     * PClass ID.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var int
     */
    protected $id;

    /**
     * PClass type.
     *
     * @see self::CAMPAIGN
     * @see self::ACCOUNT
     * @see self::SPECIAL
     * @see self::FIXED
     * @see self::DELAY
     * @see self::MOBILE
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var int
     */
    protected $type;

    /**
     * Expire date / valid until date as unix timestamp.<br>
     * Compare it with e.g. $_SERVER['REQUEST_TIME'].<br>.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var int
     */
    protected $expire;

    /**
     * Merchant ID / Estore ID.
     *
     * @ignore Do not show in PHPDoc.
     *
     * @var int
     */
    protected $eid;

    /**
     * Returns an associative array mirroring this PClass.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'eid' => $this->eid,
            'id' => $this->id,
            'description' => $this->description,
            'months' => $this->months,
            'startfee' => $this->startFee,
            'invoicefee' => $this->invoiceFee,
            'interestrate' => $this->interestRate,
            'minamount' => $this->minAmount,
            'country' => $this->country,
            'type' => $this->type,
            'expire' => $this->expire,
        );
    }

    /**
     * Sets the descriptiton, converts to HTML entities.
     *
     * @param string $description PClass description.
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Sets the number of months.
     *
     * @param int $months Number of months.
     */
    public function setMonths($months)
    {
        $this->months = intval($months);
    }

    /**
     * Sets the starting fee.
     *
     * @param float $startFee Starting fee.
     */
    public function setStartFee($startFee)
    {
        $this->startFee = floatval($startFee);
    }

    /**
     * Sets the invoicing/handling fee.
     *
     * @param float $invoiceFee Invoicing fee.
     */
    public function setInvoiceFee($invoiceFee)
    {
        $this->invoiceFee = floatval($invoiceFee);
    }

    /**
     * Sets the interest rate.
     *
     * @param float $interestRate Interest rate.
     */
    public function setInterestRate($interestRate)
    {
        $this->interestRate = floatval($interestRate);
    }

    /**
     * Sets the Minimum amount to use this PClass.
     *
     * @param float $minAmount Minimum amount.
     */
    public function setMinAmount($minAmount)
    {
        $this->minAmount = floatval($minAmount);
    }

    /**
     * Sets the country for this PClass.
     *
     * @param int $country {@link Country} constant.
     *
     * @see Country
     */
    public function setCountry($country)
    {
        $this->country = intval($country);
    }

    /**
     * Sets the ID for this pclass.
     *
     * @param int $id PClass identifier.
     */
    public function setId($id)
    {
        $this->id = intval($id);
    }

    /**
     * Sets the type for this pclass.
     *
     * @param int $type PClass type identifier.
     *
     * @see self::CAMPAIGN
     * @see self::ACCOUNT
     * @see self::SPECIAL
     * @see self::FIXED
     * @see self::DELAY
     * @see self::MOBILE
     */
    public function setType($type)
    {
        $this->type = intval($type);
    }

    /**
     * Returns the ID for this PClass.
     *
     * @return int PClass identifier.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns this PClass's type.
     *
     * @see self::CAMPAIGN
     * @see self::ACCOUNT
     * @see self::SPECIAL
     * @see self::FIXED
     * @see self::DELAY
     * @see self::MOBILE
     *
     * @return int PClass type identifier.
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the Merchant ID or Estore ID connected to this PClass.
     *
     * @return int
     */
    public function getEid()
    {
        return $this->eid;
    }

    /**
     * Merchant ID or Estore ID connected to this PClass.
     *
     * @param int $eid Merchant ID.
     */
    public function setEid($eid)
    {
        $this->eid = intval($eid);
    }

    /**
     * Checks whether this PClass is valid.
     *
     * @param int $now Unix timestamp
     *
     * @return bool
     */
    public function isValid($now = null)
    {
        if ($this->expire == null
            || $this->expire == '-'
            || $this->expire <= 0
        ) {
            //No expire, or unset? assume valid.
            return true;
        }

        if ($now === null || !is_numeric($now)) {
            $now = time();
        }

        //If now is before expire, it is still valid.
        return ($now > $this->expire) ? false : true;
    }

    /**
     * Returns the valid until/expire date unix timestamp or false if there's
     * no expiry.
     *
     * @return int|bool
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Sets the valid until/expire date unix timestamp.
     *
     * @param int|bool $expire unix timestamp for expire or false if no expiry.
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;
    }

    /**
     * Returns the description for this PClass.
     *
     * <b>Note</b>:<br>
     * Encoded with HTML entities.
     *
     * @return string PClass description.
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the number of months for this PClass.
     *
     * @return int Number of months.
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * Returns the starting fee for this PClass.
     *
     * @return float Starting fee.
     */
    public function getStartFee()
    {
        return $this->startFee;
    }

    /**
     * Returns the invoicing/handling fee for this PClass.
     *
     * @return float Invoicing fee.
     */
    public function getInvoiceFee()
    {
        return $this->invoiceFee;
    }

    /**
     * Returns the interest rate for this PClass.
     *
     * @return float Interest rate.
     */
    public function getInterestRate()
    {
        return $this->interestRate;
    }

    /**
     * Returns the minimum order/product amount for which this PClass is allowed.
     *
     * @return float Minimum amount to use this PClass.
     */
    public function getMinAmount()
    {
        return $this->minAmount;
    }

    /**
     * Returns the country related to this PClass.
     *
     * @see Country
     *
     * @return int {@link Country} constant.
     */
    public function getCountry()
    {
        return $this->country;
    }
}
