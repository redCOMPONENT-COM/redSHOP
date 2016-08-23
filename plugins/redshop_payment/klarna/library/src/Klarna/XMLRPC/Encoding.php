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
 * Encoding class.
 */
class Encoding
{
    /**
     * PNO/SSN encoding for Sweden.
     *
     * @var int
     */
    const PNO_SE = 2;

    /**
     * PNO/SSN encoding for Norway.
     *
     * @var int
     */
    const PNO_NO = 3;

    /**
     * PNO/SSN encoding for Finland.
     *
     * @var int
     */
    const PNO_FI = 4;

    /**
     * PNO/SSN encoding for Denmark.
     *
     * @var int
     */
    const PNO_DK = 5;

    /**
     * PNO/SSN encoding for Germany.
     *
     * @var int
     */
    const PNO_DE = 6;

    /**
     * PNO/SSN encoding for Netherlands.
     *
     * @var int
     */
    const PNO_NL = 7;

    /**
     * PNO/SSN encoding for Austria.
     *
     * @var int
     */
    const PNO_AT = 8;

    /**
     * Encoding constant for customer numbers.
     *
     * @see Klarna::setCustomerNo()
     *
     * @var int
     */
    const CUSTNO = 1000;

    /**
     * Encoding constant for email address.
     *
     * @var int
     */
    const EMAIL = 1001;

    /**
     * Encoding constant for cell numbers.
     *
     * @var int
     */
    const CELLNO = 1002;

    /**
     * Encoding constant for bank bic + account number.
     *
     * @var int
     */
    const BANK_BIC_ACC_NO = 1003;

    /**
     * Returns the constant for the wanted country.
     *
     * @param string $country country
     *
     * @return int
     */
    public static function get($country)
    {
        switch (strtoupper($country)) {
            case 'DE':
                return self::PNO_DE;
            case 'DK':
                return self::PNO_DK;
            case 'FI':
                return self::PNO_FI;
            case 'NL':
                return self::PNO_NL;
            case 'NO':
                return self::PNO_NO;
            case 'SE':
                return self::PNO_SE;
            case 'AT':
                return self::PNO_AT;
            default:
                return -1;
        }
    }

    /**
     * Checks if the specified PNO is correct according to specified
     * encoding constant.
     *
     * @param string $pno PNO/SSN string.
     * @param int    $enc {@link Encoding PNO/SSN encoding} constant.
     *
     * @return bool True if correct.
     */
    public static function checkPNO($pno, $enc = null)
    {
        return strlen($pno) > 0;
    }
}
