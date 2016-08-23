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
 * Country Constants class.
 */
class Country
{
    /**
     * Country constant for Austria (AT).<br>
     * ISO3166_AT.
     *
     * @var int
     */
    const AT = 15;

    /**
     * Country constant for Denmark (DK).<br>
     * ISO3166_DK.
     *
     * @var int
     */
    const DK = 59;

    /**
     * Country constant for Finland (FI).<br>
     * ISO3166_FI.
     *
     * @var int
     */
    const FI = 73;

    /**
     * Country constant for Germany (DE).<br>
     * ISO3166_DE.
     *
     * @var int
     */
    const DE = 81;

    /**
     * Country constant for Netherlands (NL).<br>
     * ISO3166_NL.
     *
     * @var int
     */
    const NL = 154;

    /**
     * Country constant for Norway (NO).<br>
     * ISO3166_NO.
     *
     * @var int
     */
    const NO = 164;

    /**
     * Country constant for Sweden (SE).<br>
     * ISO3166_SE.
     *
     * @var int
     */
    const SE = 209;

    /**
     * Converts a country code, e.g. 'de' or 'deu' to the Country constant.
     *
     * @param string $val country code iso-alpha-2 or iso-alpha-3
     *
     * @return int|null
     */
    public static function fromCode($val)
    {
        $val = strtoupper($val);
        if (strlen($val) === 3) {
            if (self::$tlcFlip === array()) {
                self::$tlcFlip = array_flip(self::$tlcMap);
            }
            if (!array_key_exists($val, self::$tlcFlip)) {
                return;
            }
            $val = self::$tlcFlip[$val];
        }
        if (array_key_exists($val, self::$countries)) {
            return self::$countries[$val];
        }

        return;
    }

    /**
     * Converts a Country constant to the respective country code.
     *
     * @param int  $val    Country constant
     * @param bool $alpha3 Whether to return a ISO-3166-1 alpha-3 code
     *
     * @return string|null
     */
    public static function getCode($val, $alpha3 = false)
    {
        if (self::$countryFlip === array()) {
            self::$countryFlip = array_flip(self::$countries);
        }
        if (!array_key_exists($val, self::$countryFlip)) {
            return;
        }
        $result = self::$countryFlip[$val];
        if ($alpha3) {
            return self::$tlcMap[$result];
        }

        return $result;
    }

    /**
     * Checks country against currency and returns true if they match.
     *
     * @param int $country  {@link Country}
     * @param int $language {@link Language}
     *
     * @deprecated Do not use.
     *
     * @return bool
     */
    public static function checkLanguage($country, $language)
    {
        switch ($country) {
            case self::AT:
            case self::DE:
                return $language === Language::DE;
            case self::NL:
                return $language === Language::NL;
            case self::FI:
                return $language === Language::FI;
            case self::DK:
                return $language === Language::DA;
            case self::NO:
                return $language === Language::NB;
            case self::SE:
                return $language === Language::SV;
            default:
                //Country not yet supported by Klarna.
                return false;
        }
    }
    /**
     * Checks country against language and returns true if they match.
     *
     * @param int $country  {@link Country}
     * @param int $currency {@link Currency}
     *
     * @deprecated Do not use.
     *
     * @return bool
     */
    public static function checkCurrency($country, $currency)
    {
        switch ($country) {
            case self::AT:
            case self::DE:
            case self::NL:
            case self::FI:
                return $currency === Currency::EUR;
            case self::DK:
                return $currency === Currency::DKK;
            case self::NO:
                return $currency === Currency::NOK;
            case self::SE:
                return $currency === Currency::SEK;
            default:
                //Country not yet supported by Klarna.
                return false;
        }
    }
    /**
     * Get language for supplied country. Defaults to English.
     *
     * @param int $country Country constant
     *
     * @deprecated Do not use.
     *
     * @return int
     */
    public static function getLanguage($country)
    {
        switch ($country) {
            case self::AT:
            case self::DE:
                return Language::DE;
            case self::NL:
                return Language::NL;
            case self::FI:
                return Language::FI;
            case self::DK:
                return Language::DA;
            case self::NO:
                return Language::NB;
            case self::SE:
                return Language::SV;
            default:
                return Language::EN;
        }
    }
    /**
     * Get currency for supplied country.
     *
     * @param int $country Country constant
     *
     * @deprecated Do not use.
     *
     * @return int|false
     */
    public static function getCurrency($country)
    {
        switch ($country) {
            case self::AT:
            case self::DE:
            case self::NL:
            case self::FI:
                return Currency::EUR;
            case self::DK:
                return Currency::DKK;
            case self::NO:
                return Currency::NOK;
            case self::SE:
                return Currency::SEK;
            default:
                return false;
        }
    }

    private static $tlcFlip = array();

    /**
     * Cache for the flipped country array.
     *
     * @var array
     */
    private static $countryFlip = array();

    /**
     * Array containing all countries and their KRED Code.
     *
     * @var array
     */
    private static $countries = array(
        'AF' => 1,   //     AFGHANISTAN
        'AX' => 2,   //     ÅLAND ISLANDS
        'AL' => 3,   //     ALBANIA
        'DZ' => 4,   //     ALGERIA
        'AS' => 5,   //     AMERICAN SAMOA
        'AD' => 6,   //     ANDORRA
        'AO' => 7,   //     ANGOLA
        'AI' => 8,   //     ANGUILLA
        'AQ' => 9,   //     ANTARCTICA
        'AG' => 10,  //     ANTIGUA AND BARBUDA
        'AR' => 11,  //     ARGENTINA
        'AM' => 12,  //     ARMENIA
        'AW' => 13,  //     ARUBA
        'AU' => 14,  //     AUSTRALIA
        'AT' => 15,  //     AUSTRIA
        'AZ' => 16,  //     AZERBAIJAN
        'BS' => 17,  //     BAHAMAS
        'BH' => 18,  //     BAHRAIN
        'BD' => 19,  //     BANGLADESH
        'BB' => 20,  //     BARBADOS
        'BY' => 21,  //     BELARUS
        'BE' => 22,  //     BELGIUM
        'BZ' => 23,  //     BELIZE
        'BJ' => 24,  //     BENIN
        'BM' => 25,  //     BERMUDA
        'BT' => 26,  //     BHUTAN
        'BO' => 27,  //     BOLIVIA
        'BA' => 28,  //     BOSNIA AND HERZEGOVINA
        'BW' => 29,  //     BOTSWANA
        'BV' => 30,  //     BOUVET ISLAND
        'BR' => 31,  //     BRAZIL
        'IO' => 32,  //     BRITISH INDIAN OCEAN TERRITORY
        'BN' => 33,  //     BRUNEI DARUSSALAM
        'BG' => 34,  //     BULGARIA
        'BF' => 35,  //     BURKINA FASO
        'BI' => 36,  //     BURUNDI
        'KH' => 37,  //     CAMBODIA
        'CM' => 38,  //     CAMEROON
        'CA' => 39,  //     CANADA
        'CV' => 40,  //     CAPE VERDE
        'KY' => 41,  //     CAYMAN ISLANDS
        'CF' => 42,  //     CENTRAL AFRICAN REPUBLIC
        'TD' => 43,  //     CHAD
        'CL' => 44,  //     CHILE
        'CN' => 45,  //     CHINA
        'CX' => 46,  //     CHRISTMAS ISLAND
        'CC' => 47,  //     COCOS (KEELING) ISLANDS
        'CO' => 48,  //     COLOMBIA
        'KM' => 49,  //     COMOROS
        'CG' => 50,  //     CONGO
        'CD' => 51,  //     CONGO, THE DEMOCRATIC REPUBLIC OF THE
        'CK' => 52,  //     COOK ISLANDS
        'CR' => 53,  //     COSTA RICA
        'CI' => 54,  //     COTE D'IVOIRE
        'HR' => 55,  //     CROATIA
        'CU' => 56,  //     CUBA
        'CY' => 57,  //     CYPRUS
        'CZ' => 58,  //     CZECH REPUBLIC
        'DK' => 59,  //     DENMARK
        'DJ' => 60,  //     DJIBOUTI
        'DM' => 61,  //     DOMINICA
        'DO' => 62,  //     DOMINICAN REPUBLIC
        'EC' => 63,  //     ECUADOR
        'EG' => 64,  //     EGYPT
        'SV' => 65,  //     EL SALVADOR
        'GQ' => 66,  //     EQUATORIAL GUINEA
        'ER' => 67,  //     ERITREA
        'EE' => 68,  //     ESTONIA
        'ET' => 69,  //     ETHIOPIA
        'FK' => 70,  //     FALKLAND ISLANDS (MALVINAS)
        'FO' => 71,  //     FAROE ISLANDS
        'FJ' => 72,  //     FIJI
        'FI' => 73,  //     FINLAND
        'FR' => 74,  //     FRANCE
        'GF' => 75,  //     FRENCH GUIANA
        'PF' => 76,  //     FRENCH POLYNESIA
        'TF' => 77,  //     FRENCH SOUTHERN TERRITORIES
        'GA' => 78,  //     GABON
        'GM' => 79,  //     GAMBIA
        'GE' => 80,  //     GEORGIA
        'DE' => 81,  //     GERMANY
        'GH' => 82,  //     GHANA
        'GI' => 83,  //     GIBRALTAR
        'GR' => 84,  //     GREECE
        'GL' => 85,  //     GREENLAND
        'GD' => 86,  //     GRENADA
        'GP' => 87,  //     GUADELOUPE
        'GU' => 88,  //     GUAM
        'GT' => 89,  //     GUATEMALA
        'GG' => 90,  //     GUERNSEY
        'GN' => 91,  //     GUINEA
        'GW' => 92,  //     GUINEA-BISSAU
        'GY' => 93,  //     GUYANA
        'HT' => 94,  //     HAITI
        'HM' => 95,  //     HEARD ISLAND AND MCDONALD ISLANDS
        'VA' => 96,  //     HOLY SEE (VATICAN CITY STATE)
        'HN' => 97,  //     HONDURAS
        'HK' => 98,  //     HONG KONG
        'HU' => 99,  //     HUNGARY
        'IS' => 100, //     ICELAND
        'IN' => 101, //     INDIA
        'ID' => 102, //     INDONESIA
        'IR' => 103, //     IRAN, ISLAMIC REPUBLIC OF
        'IQ' => 104, //     IRAQ
        'IE' => 105, //     IRELAND
        'IM' => 106, //     ISLE OF MAN
        'IL' => 107, //     ISRAEL
        'IT' => 108, //     ITALY
        'JM' => 109, //     JAMAICA
        'JP' => 110, //     JAPAN
        'JE' => 111, //     JERSEY
        'JO' => 112, //     JORDAN
        'KZ' => 113, //     KAZAKHSTAN
        'KE' => 114, //     KENYA
        'KI' => 115, //     KIRIBATI
        'KP' => 116, //     KOREA, DEMOCRATIC PEOPLE'S REPUBLIC OF
        'KR' => 117, //     KOREA, REPUBLIC OF
        'KW' => 118, //     KUWAIT
        'KG' => 119, //     KYRGYZSTAN
        'LA' => 120, //     LAO PEOPLE'S DEMOCRATIC REPUBLIC
        'LV' => 121, //     LATVIA
        'LB' => 122, //     LEBANON
        'LS' => 123, //     LESOTHO
        'LR' => 124, //     LIBERIA
        'LY' => 125, //     LIBYAN ARAB JAMAHIRIYA
        'LI' => 126, //     LIECHTENSTEIN
        'LT' => 127, //     LITHUANIA
        'LU' => 128, //     LUXEMBOURG
        'MO' => 129, //     MACAO
        'MK' => 130, //     MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF
        'MG' => 131, //     MADAGASCAR
        'MW' => 132, //     MALAWI
        'MY' => 133, //     MALAYSIA
        'MV' => 134, //     MALDIVES
        'ML' => 135, //     MALI
        'MT' => 136, //     MALTA
        'MH' => 137, //     MARSHALL ISLANDS
        'MQ' => 138, //     MARTINIQUE
        'MR' => 139, //     MAURITANIA
        'MU' => 140, //     MAURITIUS
        'YT' => 141, //     MAYOTTE
        'MX' => 142, //     MEXICO
        'FM' => 143, //     MICRONESIA     FEDERATED STATES OF
        'MD' => 144, //     MOLDOVA, REPUBLIC OF
        'MC' => 145, //     MONACO
        'MN' => 146, //     MONGOLIA
        'MS' => 147, //     MONTSERRAT
        'MA' => 148, //     MOROCCO
        'MZ' => 149, //     MOZAMBIQUE
        'MM' => 150, //     MYANMAR
        'NA' => 151, //     NAMIBIA
        'NR' => 152, //     NAURU
        'NP' => 153, //     NEPAL
        'NL' => 154, //     NETHERLANDS
        'AN' => 155, //     NETHERLANDS ANTILLES
        'NC' => 156, //     NEW CALEDONIA
        'NZ' => 157, //     NEW ZEALAND
        'NI' => 158, //     NICARAGUA
        'NE' => 159, //     NIGER
        'NG' => 160, //     NIGERIA
        'NU' => 161, //     NIUE
        'NF' => 162, //     NORFOLK ISLAND
        'MP' => 163, //     NORTHERN MARIANA ISLANDS
        'NO' => 164, //     NORWAY
        'OM' => 165, //     OMAN
        'PK' => 166, //     PAKISTAN
        'PW' => 167, //     PALAU
        'PS' => 168, //     PALESTINIAN TERRITORY OCCUPIED
        'PA' => 169, //     PANAMA
        'PG' => 170, //     PAPUA NEW GUINEA
        'PY' => 171, //     PARAGUAY
        'PE' => 172, //     PERU
        'PH' => 173, //     PHILIPPINES
        'PN' => 174, //     PITCAIRN
        'PL' => 175, //     POLAND
        'PT' => 176, //     PORTUGAL
        'PR' => 177, //     PUERTO RICO
        'QA' => 178, //     QATAR
        'RE' => 179, //     REUNION
        'RO' => 180, //     ROMANIA
        'RU' => 181, //     RUSSIAN FEDERATION
        'RW' => 182, //     RWANDA
        'SH' => 183, //     SAINT HELENA
        'KN' => 184, //     SAINT KITTS AND NEVIS
        'LC' => 185, //     SAINT LUCIA
        'PM' => 186, //     SAINT PIERRE AND MIQUELON
        'VC' => 187, //     SAINT VINCENT AND THE GRENADINES
        'WS' => 188, //     SAMOA
        'SM' => 189, //     SAN MARINO
        'ST' => 190, //     SAO TOME AND PRINCIPE
        'SA' => 191, //     SAUDI ARABIA
        'SN' => 192, //     SENEGAL
        'CS' => 193, //     SERBIA AND MONTENEGRO
        'SC' => 194, //     SEYCHELLES
        'SL' => 195, //     SIERRA LEONE
        'SG' => 196, //     SINGAPORE
        'SK' => 197, //     SLOVAKIA
        'SI' => 198, //     SLOVENIA
        'SB' => 199, //     SOLOMON ISLANDS
        'SO' => 200, //     SOMALIA
        'ZA' => 201, //     SOUTH AFRICA
        'GS' => 202, //     SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS
        'ES' => 203, //     SPAIN
        'LK' => 204, //     SRI LANKA
        'SD' => 205, //     SUDAN
        'SR' => 206, //     SURINAME
        'SJ' => 207, //     SVALBARD AND JAN MAYEN
        'SZ' => 208, //     SWAZILAND
        'SE' => 209, //     SWEDEN
        'CH' => 210, //     SWITZERLAND
        'SY' => 211, //     SYRIAN ARAB REPUBLIC
        'TW' => 212, //     TAIWAN PROVINCE OF CHINA
        'TJ' => 213, //     TAJIKISTAN
        'TZ' => 214, //     TANZANIA, UNITED REPUBLIC OF
        'TH' => 215, //     THAILAND
        'TL' => 216, //     TIMOR-LESTE
        'TG' => 217, //     TOGO
        'TK' => 218, //     TOKELAU
        'TO' => 219, //     TONGA
        'TT' => 220, //     TRINIDAD AND TOBAGO
        'TN' => 221, //     TUNISIA
        'TR' => 222, //     TURKEY
        'TM' => 223, //     TURKMENISTAN
        'TC' => 224, //     TURKS AND CAICOS ISLANDS
        'TV' => 225, //     TUVALU
        'UG' => 226, //     UGANDA
        'UA' => 227, //     UKRAINE
        'AE' => 228, //     UNITED ARAB EMIRATES
        'GB' => 229, //     UNITED KINGDOM
        'US' => 230, //     UNITED STATES
        'UM' => 231, //     UNITED STATES MINOR OUTLYING ISLANDS
        'UY' => 232, //     URUGUAY
        'UZ' => 233, //     UZBEKISTAN
        'VU' => 234, //     VANUATU
        'VE' => 235, //     VENEZUELA
        'VN' => 236, //     VIET NAM
        'VG' => 237, //     VIRGIN ISLANDS, BRITISH
        'VI' => 238, //     VIRGIN ISLANDS, US
        'WF' => 239, //     WALLIS AND FUTUNA
        'EH' => 240, //     WESTERN SAHARA
        'YE' => 241, //     YEMEN
        'ZM' => 242, //     ZAMBIA
        'ZW' => 243,  //     ZIMBABWE
    );

    private static $tlcMap = array(
        'AF' => 'AFG',
        'AX' => 'ALA',
        'AL' => 'ALB',
        'DZ' => 'DZA',
        'AS' => 'ASM',
        'AD' => 'AND',
        'AO' => 'AGO',
        'AI' => 'AIA',
        'AQ' => 'ATA',
        'AG' => 'ATG',
        'AR' => 'ARG',
        'AM' => 'ARM',
        'AW' => 'ABW',
        'AU' => 'AUS',
        'AT' => 'AUT',
        'AZ' => 'AZE',
        'BS' => 'BHS',
        'BH' => 'BHR',
        'BD' => 'BGD',
        'BB' => 'BRB',
        'BY' => 'BLR',
        'BE' => 'BEL',
        'BZ' => 'BLZ',
        'BJ' => 'BEN',
        'BM' => 'BMU',
        'BT' => 'BTN',
        'BO' => 'BOL',
        'BQ' => 'BES',
        'BA' => 'BIH',
        'BW' => 'BWA',
        'BV' => 'BVT',
        'BR' => 'BRA',
        'IO' => 'IOT',
        'BN' => 'BRN',
        'BG' => 'BGR',
        'BF' => 'BFA',
        'BI' => 'BDI',
        'KH' => 'KHM',
        'CM' => 'CMR',
        'CA' => 'CAN',
        'CV' => 'CPV',
        'KY' => 'CYM',
        'CF' => 'CAF',
        'TD' => 'TCD',
        'CL' => 'CHL',
        'CN' => 'CHN',
        'CX' => 'CXR',
        'CC' => 'CCK',
        'CO' => 'COL',
        'KM' => 'COM',
        'CG' => 'COG',
        'CD' => 'COD',
        'CK' => 'COK',
        'CR' => 'CRI',
        'CI' => 'CIV',
        'HR' => 'HRV',
        'CU' => 'CUB',
        'CW' => 'CUW',
        'CY' => 'CYP',
        'CZ' => 'CZE',
        'DK' => 'DNK',
        'DJ' => 'DJI',
        'DM' => 'DMA',
        'DO' => 'DOM',
        'EC' => 'ECU',
        'EG' => 'EGY',
        'SV' => 'SLV',
        'GQ' => 'GNQ',
        'ER' => 'ERI',
        'EE' => 'EST',
        'ET' => 'ETH',
        'FK' => 'FLK',
        'FO' => 'FRO',
        'FJ' => 'FJI',
        'FI' => 'FIN',
        'FR' => 'FRA',
        'GF' => 'GUF',
        'PF' => 'PYF',
        'TF' => 'ATF',
        'GA' => 'GAB',
        'GM' => 'GMB',
        'GE' => 'GEO',
        'DE' => 'DEU',
        'GH' => 'GHA',
        'GI' => 'GIB',
        'GR' => 'GRC',
        'GL' => 'GRL',
        'GD' => 'GRD',
        'GP' => 'GLP',
        'GU' => 'GUM',
        'GT' => 'GTM',
        'GG' => 'GGY',
        'GN' => 'GIN',
        'GW' => 'GNB',
        'GY' => 'GUY',
        'HT' => 'HTI',
        'HM' => 'HMD',
        'VA' => 'VAT',
        'HN' => 'HND',
        'HK' => 'HKG',
        'HU' => 'HUN',
        'IS' => 'ISL',
        'IN' => 'IND',
        'ID' => 'IDN',
        'IR' => 'IRN',
        'IQ' => 'IRQ',
        'IE' => 'IRL',
        'IM' => 'IMN',
        'IL' => 'ISR',
        'IT' => 'ITA',
        'JM' => 'JAM',
        'JP' => 'JPN',
        'JE' => 'JEY',
        'JO' => 'JOR',
        'KZ' => 'KAZ',
        'KE' => 'KEN',
        'KI' => 'KIR',
        'KP' => 'PRK',
        'KR' => 'KOR',
        'KW' => 'KWT',
        'KG' => 'KGZ',
        'LA' => 'LAO',
        'LV' => 'LVA',
        'LB' => 'LBN',
        'LS' => 'LSO',
        'LR' => 'LBR',
        'LY' => 'LBY',
        'LI' => 'LIE',
        'LT' => 'LTU',
        'LU' => 'LUX',
        'MO' => 'MAC',
        'MK' => 'MKD',
        'MG' => 'MDG',
        'MW' => 'MWI',
        'MY' => 'MYS',
        'MV' => 'MDV',
        'ML' => 'MLI',
        'MT' => 'MLT',
        'MH' => 'MHL',
        'MQ' => 'MTQ',
        'MR' => 'MRT',
        'MU' => 'MUS',
        'YT' => 'MYT',
        'MX' => 'MEX',
        'FM' => 'FSM',
        'MD' => 'MDA',
        'MC' => 'MCO',
        'MN' => 'MNG',
        'ME' => 'MNE',
        'MS' => 'MSR',
        'MA' => 'MAR',
        'MZ' => 'MOZ',
        'MM' => 'MMR',
        'NA' => 'NAM',
        'NR' => 'NRU',
        'NP' => 'NPL',
        'NL' => 'NLD',
        'NC' => 'NCL',
        'NZ' => 'NZL',
        'NI' => 'NIC',
        'NE' => 'NER',
        'NG' => 'NGA',
        'NU' => 'NIU',
        'NF' => 'NFK',
        'MP' => 'MNP',
        'NO' => 'NOR',
        'OM' => 'OMN',
        'PK' => 'PAK',
        'PW' => 'PLW',
        'PS' => 'PSE',
        'PA' => 'PAN',
        'PG' => 'PNG',
        'PY' => 'PRY',
        'PE' => 'PER',
        'PH' => 'PHL',
        'PN' => 'PCN',
        'PL' => 'POL',
        'PT' => 'PRT',
        'PR' => 'PRI',
        'QA' => 'QAT',
        'RE' => 'REU',
        'RO' => 'ROU',
        'RU' => 'RUS',
        'RW' => 'RWA',
        'BL' => 'BLM',
        'SH' => 'SHN',
        'KN' => 'KNA',
        'LC' => 'LCA',
        'MF' => 'MAF',
        'PM' => 'SPM',
        'VC' => 'VCT',
        'WS' => 'WSM',
        'SM' => 'SMR',
        'ST' => 'STP',
        'SA' => 'SAU',
        'SN' => 'SEN',
        'RS' => 'SRB',
        'SC' => 'SYC',
        'SL' => 'SLE',
        'SG' => 'SGP',
        'SX' => 'SXM',
        'SK' => 'SVK',
        'SI' => 'SVN',
        'SB' => 'SLB',
        'SO' => 'SOM',
        'ZA' => 'ZAF',
        'GS' => 'SGS',
        'SS' => 'SSD',
        'ES' => 'ESP',
        'LK' => 'LKA',
        'SD' => 'SDN',
        'SR' => 'SUR',
        'SJ' => 'SJM',
        'SZ' => 'SWZ',
        'SE' => 'SWE',
        'CH' => 'CHE',
        'SY' => 'SYR',
        'TW' => 'TWN',
        'TJ' => 'TJK',
        'TZ' => 'TZA',
        'TH' => 'THA',
        'TL' => 'TLS',
        'TG' => 'TGO',
        'TK' => 'TKL',
        'TO' => 'TON',
        'TT' => 'TTO',
        'TN' => 'TUN',
        'TR' => 'TUR',
        'TM' => 'TKM',
        'TC' => 'TCA',
        'TV' => 'TUV',
        'UG' => 'UGA',
        'UA' => 'UKR',
        'AE' => 'ARE',
        'GB' => 'GBR',
        'US' => 'USA',
        'UM' => 'UMI',
        'UY' => 'URY',
        'UZ' => 'UZB',
        'VU' => 'VUT',
        'VE' => 'VEN',
        'VN' => 'VNM',
        'VG' => 'VGB',
        'VI' => 'VIR',
        'WF' => 'WLF',
        'EH' => 'ESH',
        'YE' => 'YEM',
        'ZM' => 'ZMB',
        'ZW' => 'ZWE',
    );
}
