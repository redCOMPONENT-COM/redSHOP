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
 * Currency Constants class.
 */
class Currency
{
    /**
     * Currency constant for Swedish Crowns (SEK).
     *
     * @var int
     */
    const SEK = 0;

    /**
     * Currency constant for Norwegian Crowns (NOK).
     *
     * @var int
     */
    const NOK = 1;

    /**
     * Currency constant for Euro.
     *
     * @var int
     */
    const EUR = 2;

    /**
     * Currency constant for Danish Crowns (DKK).
     *
     * @var int
     */
    const DKK = 3;

    /**
     * Converts a currency code, e.g. 'eur' to the Currency constant.
     *
     * @param string $val currency code
     *
     * @return int|null
     */
    public static function fromCode($val)
    {
        switch (strtolower($val)) {
            case 'dkk':
                return self::DKK;
            case 'eur':
            case 'euro':
                return self::EUR;
            case 'nok':
                return self::NOK;
            case 'sek':
                return self::SEK;
            default:
                return;
        }
    }

    /**
     * Converts a Currency constant to the respective language code.
     *
     * @param int $val Currency constant
     *
     * @return string|null
     */
    public static function getCode($val)
    {
        switch ($val) {
            case self::DKK:
                return 'dkk';
            case self::EUR:
                return 'eur';
            case self::NOK:
                return 'nok';
            case self::SEK:
                return 'sek';
            default:
                return;
        }
    }
}
