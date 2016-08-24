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
 * Calc provides methods to calculate part payment functions.
 *
 * All rates are yearly rates, but they are calculated monthly. So
 * a rate of 9 % is used 0.75% monthly. The first is the one we specify
 * to the customers, and the second one is the one added each month to
 * the account. The IRR uses the same notation.
 *
 * The APR is however calculated by taking the monthly rate and raising
 * it to the 12 power. This is according to the EU law, and will give
 * very large numbers if the $pval is small compared to the $fee and
 * the amount of months you repay is small as well.
 *
 * All functions work in discrete mode, and the time interval is the
 * mythical evenly divided month. There is no way to calculate APR in
 * days without using integrals and other hairy math. So don't try.
 * The amount of days between actual purchase and the first bill can
 * of course vary between 28 and 61 days, but all calculations in this
 * class assume this time is exactly and that is ok since this will only
 * overestimate the APR and all examples in EU law uses whole months as well.
 */
class Calc
{
    /**
     * This constant tells the irr function when to stop.
     * If the calculation error is lower than this the calculation is done.
     *
     * @var float
     */
    protected static $accuracy = 0.01;

    /**
     * Calculates the midpoint between two points. Used by divide and conquer.
     *
     * @param float $a point a
     * @param float $b point b
     *
     * @return float
     */
    private static function midpoint($a, $b)
    {
        return ($a + $b) / 2;
    }

    /**
     * Net Present Value.
     *
     * Calculates the difference between the initial loan to the customer
     * and the individual payments adjusted for the inverse of the interest
     * rate. The variable we are searching for is $rate and if $pval,
     * $payarray and $rate is perfectly balanced this function returns 0.0.
     *
     * @param float $pval       initial loan to customer (in any currency)
     * @param array $payarray   array of monthly payments from the customer
     * @param float $rate       interest rate per year in %
     * @param int   $fromdayone count interest from the first day? yes(1)/no(0)
     *
     * @return float
     */
    private static function npv($pval, $payarray, $rate, $fromdayone)
    {
        $month = $fromdayone;
        foreach ($payarray as $payment) {
            $pval -= $payment / pow(1 + $rate / (12 * 100.0), $month++);
        }

        return $pval;
    }

    /**
     * This function uses divide and conquer to numerically find the IRR,
     * Internal Rate of Return. It starts of by trying a low of 0% and a
     * high of 100%. If this isn't enough it will double the interval up
     * to 1000000%. Note that this is insanely high, and if you try to convert
     * an IRR that high to an APR you will get even more insane values,
     * so feed this function good data.
     *
     * Return values: float irr  if it was possible to find a rate that gets
     *                           npv closer to 0 than $accuracy.
     *                int -1     The sum of the payarray is less than the lent
     *                           amount, $pval. Hellooooooo. Impossible.
     *                int -2     the IRR is way to high, giving up.
     *
     * This algorithm works in logarithmic time no matter what inputs you give
     * and it will come to a good answer within ~30 steps.
     *
     * @param float $pval       initial loan to customer (in any currency)
     * @param array $payarray   array of monthly payments from the customer
     * @param int   $fromdayone count interest from the first day? yes(1)/no(0)
     *
     * @return float
     */
    private static function irr($pval, $payarray, $fromdayone)
    {
        $low = 0.0;
        $high = 100.0;
        $lowval = self::npv($pval, $payarray, $low, $fromdayone);
        $highval = self::npv($pval, $payarray, $high, $fromdayone);

        // The sum of $payarray is smaller than $pval, impossible!
        if ($lowval > 0.0) {
            return -1;
        }

        // Standard divide and conquer.
        do {
            $mid = self::midpoint($low, $high);
            $midval = self::npv($pval, $payarray, $mid, $fromdayone);
            if (abs($midval) < self::$accuracy) {
                //we are close enough
                return $mid;
            }

            if ($highval < 0.0) {
                // we are not in range, so double it
                $low = $high;
                $lowval = $highval;
                $high *= 2;
                $highval = self::npv($pval, $payarray, $high, $fromdayone);
            } elseif ($midval >= 0.0) {
                // irr is between low and mid
                $high = $mid;
                $highval = $midval;
            } else {
                // irr is between mid and high
                $low = $mid;
                $lowval = $midval;
            }
        } while ($high < 1000000);
        // bad input, insanely high interest. APR will be INSANER!
        return -2;
    }

    /**
     * IRR is not the same thing as APR, Annual Percentage Rate. The
     * IRR is per time period, i.e. 1 month, and the APR is per year,
     * and note that that you need to raise to the power of 12, not
     * mutliply by 12.
     *
     * This function turns an IRR into an APR.
     *
     * If you feed it a value of 100%, yes the APR will be millions!
     * If you feed it a value of   9%, it will be 9.3806%.
     * That is the nature of this math and you can check the wiki
     * page for APR for more info.
     *
     * @param float $irr Internal Rate of Return, expressed yearly, in %
     *
     * @return float Annual Percentage Rate, in %
     */
    private static function irr2apr($irr)
    {
        return 100 * (pow(1 + $irr / (12 * 100.0), 12) - 1);
    }

    /**
     * This is a simplified model of how our paccengine works if
     * a client always pays their bills. It adds interest and fees
     * and checks minimum payments. It will run until the value
     * of the account reaches 0, and return an array of all the
     * individual payments. Months is the amount of months to run
     * the simulation. Important! Don't feed it too few months or
     * the whole loan won't be paid off, but the other functions
     * should handle this correctly.
     *
     * Giving it too many months has no bad effects, or negative
     * amount of months which means run forever, but it will stop
     * as soon as the account is paid in full.
     *
     * Depending if the account is a base account or not, the
     * payment has to be 1/24 of the capital amount.
     *
     * The payment has to be at least $minpay, unless the capital
     * amount + interest + fee is less than $minpay; in that case
     * that amount is paid and the function returns since the client
     * no longer owes any money.
     *
     * @param float $pval    initial loan to customer (in any currency)
     * @param float $rate    interest rate per year in %
     * @param float $fee     monthly invoice fee
     * @param float $minpay  minimum monthly payment allowed for this country.
     * @param float $payment payment the client to pay each month
     * @param int   $months  amount of months to run (-1 => infinity)
     * @param bool  $base    is it a base account?
     *
     * @return array An array of monthly payments for the customer.
     */
    private static function fulpacc(
        $pval,
        $rate,
        $fee,
        $minpay,
        $payment,
        $months,
        $base
    ) {
        $bal = $pval;
        $payarray = array();
        while (($months != 0) && ($bal > self::$accuracy)) {
            $interest = $bal * $rate / (100.0 * 12);
            $newbal = $bal + $interest + $fee;

            if ($minpay >= $newbal || $payment >= $newbal) {
                $payarray[] = $newbal;

                return $payarray;
            }

            $newpay = max($payment, $minpay);
            if ($base) {
                $newpay = max($newpay, $bal / 24.0 + $fee + $interest);
            }

            $bal = $newbal - $newpay;
            $payarray[] = $newpay;
            $months -= 1;
        }

        return $payarray;
    }

    /**
     * Calculates how much you have to pay each month if you want to
     * pay exactly the same amount each month. The interesting input
     * is the amount of $months.
     *
     * It does not include the fee so add that later.
     *
     * Return value: monthly payment.
     *
     * @param float $pval   principal value
     * @param int   $months months to pay of in
     * @param float $rate   interest rate in % as before
     *
     * @return float monthly payment
     */
    private static function annuity($pval, $months, $rate)
    {
        if ($months == 0) {
            return $pval;
        }

        if ($rate == 0) {
            return $pval / $months;
        }

        $p = $rate / (100.0 * 12);

        return $pval * $p / (1 - pow((1 + $p), -$months));
    }

    /**
     * Calculate the APR for an annuity given the following inputs.
     *
     * If you give it bad inputs, it will return negative values.
     *
     * @param float $pval   principal value
     * @param int   $months months to pay off in
     * @param float $rate   interest rate in % as before
     * @param float $fee    monthly fee
     * @param float $minpay minimum payment per month
     *
     * @return float APR in %
     */
    private static function aprAnnuity($pval, $months, $rate, $fee, $minpay)
    {
        $payment = self::annuity($pval, $months, $rate) + $fee;
        if ($payment < 0) {
            return $payment;
        }
        $payarray = self::fulpacc(
            $pval,
            $rate,
            $fee,
            $minpay,
            $payment,
            $months,
            false
        );
        $apr = self::irr2apr(self::irr($pval, $payarray, 1));

        return $apr;
    }

    /**
     * Grabs the array of all monthly payments for specified PClass.
     *
     * <b>Flags can be either</b>:<br>
     * {@link Flags::CHECKOUT_PAGE}<br>
     * {@link Flags::PRODUCT_PAGE}<br>
     *
     * @param float  $sum    The sum for the order/product.
     * @param PClass $pclass PClass used to calculate the APR.
     * @param int    $flags  Checkout or Product page.
     *
     * @throws Exception\KlarnaException
     *
     * @return array An array of monthly payments.
     */
    private static function getPayArray($sum, $pclass, $flags)
    {
        $monthsfee = 0;
        if ($flags === Flags::CHECKOUT_PAGE) {
            $monthsfee = $pclass->getInvoiceFee();
        }
        $startfee = 0;
        if ($flags === Flags::CHECKOUT_PAGE) {
            $startfee = $pclass->getStartFee();
        }

        //Include start fee in sum
        $sum += $startfee;

        $base = ($pclass->getType() === PClass::ACCOUNT);
        $lowest = self::getLowestPaymentForAccount($pclass->getCountry());

        if ($flags == Flags::CHECKOUT_PAGE) {
            $minpay = ($pclass->getType() === PClass::ACCOUNT) ? $lowest : 0;
        } else {
            $minpay = 0;
        }

        $payment = self::annuity(
            $sum,
            $pclass->getMonths(),
            $pclass->getInterestRate()
        );

        //Add monthly fee
        $payment += $monthsfee;

        return  self::fulpacc(
            $sum,
            $pclass->getInterestRate(),
            $monthsfee,
            $minpay,
            $payment,
            $pclass->getMonths(),
            $base
        );
    }

    /**
     * Calculates APR for the specified values.<br>
     * Result is rounded with two decimals.<br>.
     *
     * <b>Flags can be either</b>:<br>
     * {@link Flags::CHECKOUT_PAGE}<br>
     * {@link Flags::PRODUCT_PAGE}<br>
     *
     * @param float  $sum    The sum for the order/product.
     * @param PClass $pclass PClass used to calculate the APR.
     * @param int    $flags  Checkout or Product page.
     * @param int    $free   Number of free months.
     *
     * @throws \InvalidArgumentException
     * @throws Exception\KlarnaException
     *
     * @return float APR in %
     */
    public static function calcApr($sum, PClass $pclass, $flags, $free = 0)
    {
        if (!is_numeric($sum)) {
            throw new \InvalidArgumentException('sum must be numeric');
        }
        if (is_numeric($sum) && (!is_int($sum) || !is_float($sum))) {
            $sum = floatval($sum);
        }

        if (!is_numeric($free)) {
            throw new \InvalidArgumentException('free must be an integer');
        }

        if (is_numeric($free) && !is_int($free)) {
            $free = intval($free);
        }

        if ($free < 0) {
            throw new \InvalidArgumentException(
                'Number of free months must be positive or zero!'
            );
        }

        if (is_numeric($flags) && !is_int($flags)) {
            $flags = intval($flags);
        }

        if (!is_numeric($flags)
            || !in_array(
                $flags,
                array(
                    Flags::CHECKOUT_PAGE, Flags::PRODUCT_PAGE,
                )
            )
        ) {
            throw new \InvalidArgumentException(
                'Expected $flags to be '.Flags::CHECKOUT_PAGE.' or '.Flags::PRODUCT_PAGE
            );
        }

        $monthsfee = 0;
        if ($flags === Flags::CHECKOUT_PAGE) {
            $monthsfee = $pclass->getInvoiceFee();
        }
        $startfee = 0;
        if ($flags === Flags::CHECKOUT_PAGE) {
            $startfee = $pclass->getStartFee();
        }

        //Include start fee in sum
        $sum += $startfee;

        $lowest = self::getLowestPaymentForAccount($pclass->getCountry());

        if ($flags == Flags::CHECKOUT_PAGE) {
            $minpay = ($pclass->getType() === PClass::ACCOUNT) ? $lowest : 0;
        } else {
            $minpay = 0;
        }

        //add monthly fee
        $payment = self::annuity(
            $sum,
            $pclass->getMonths(),
            $pclass->getInterestRate()
        ) + $monthsfee;

        $type = $pclass->getType();
        switch ($type) {
            case PClass::CAMPAIGN:
            case PClass::ACCOUNT:
                return round(
                    self::aprAnnuity(
                        $sum,
                        $pclass->getMonths(),
                        $pclass->getInterestRate(),
                        $pclass->getInvoiceFee(),
                        $minpay
                    ),
                    2
                );
            case PClass::SPECIAL:
                throw new \RuntimeException(
                    'Method is not available for SPECIAL pclasses'
                );
            case PClass::FIXED:
                throw new \RuntimeException(
                    'Method is not available for FIXED pclasses'
                );
            default:
                throw new \RuntimeException(
                    'Unknown PClass type! ('.$type.')'
                );
        }
    }

    /**
     * Calculates the total credit purchase cost.<br>
     * The result is rounded up, depending on the pclass country.<br>.
     *
     * <b>Flags can be either</b>:<br>
     * {@link Flags::CHECKOUT_PAGE}<br>
     * {@link Flags::PRODUCT_PAGE}<br>
     *
     * @param float  $sum    The sum for the order/product.
     * @param PClass $pclass PClass used to calculate total credit cost.
     * @param int    $flags  Checkout or Product page.
     *
     * @throws \InvalidArgumentException
     *
     * @return float Total credit purchase cost.
     */
    public static function totalCreditPurchaseCost($sum, PClass $pclass, $flags)
    {
        if (!is_numeric($sum)) {
            throw new \InvalidArgumentException('Expected $sum to be numeric');
        }

        if (is_numeric($sum) && (!is_int($sum) || !is_float($sum))) {
            $sum = floatval($sum);
        }

        if (is_numeric($flags) && !is_int($flags)) {
            $flags = intval($flags);
        }

        if (!is_numeric($flags)
            || !in_array(
                $flags,
                array(
                    Flags::CHECKOUT_PAGE, Flags::PRODUCT_PAGE,
                )
            )
        ) {
            throw new \InvalidArgumentException(
                'Expected $flags to be '.Flags::CHECKOUT_PAGE.' or '.Flags::PRODUCT_PAGE
            );
        }

        $payarr = self::getPayArray($sum, $pclass, $flags);

        $credit_cost = 0;
        foreach ($payarr as $pay) {
            $credit_cost += $pay;
        }

        return self::pRound($credit_cost, $pclass->getCountry());
    }

    /**
     * Calculates the monthly cost for the specified pclass.
     * The result is rounded up to the correct value depending on the
     * pclass country.<br>.
     *
     * Example:<br>
     * <ul>
     *     <li>In product view, round monthly cost with max 0.5 or 0.1
     *  depending on currency.<br>
     *     <ul>
     *         <li>10.50 SEK rounds to 11 SEK</li>
     *         <li>10.49 SEK rounds to 10 SEK</li>
     *         <li> 8.55 EUR rounds to 8.6 EUR</li>
     *         <li> 8.54 EUR rounds to 8.5 EUR</li>
     *     </ul></li>
     *     <li>
     *         In checkout, round the monthly cost to have 2 decimals.<br>
     *         For example 10.57 SEK/per månad
     *     </li>
     * </ul>
     *
     * <b>Flags can be either</b>:<br>
     * {@link Flags::CHECKOUT_PAGE}<br>
     * {@link Flags::PRODUCT_PAGE}<br>
     *
     * @param int    $sum    The sum for the order/product.
     * @param PClass $pclass PClass used to calculate monthly cost.
     * @param int    $flags  Checkout or product page.
     *
     * @throws \InvalidArgumentException
     *
     * @return float The monthly cost.
     */
    public static function calcMonthlyCost($sum, PClass $pclass, $flags)
    {
        if (!is_numeric($sum)) {
            throw new \InvalidArgumentException('Expected $sum to be numeric');
        }

        if (is_numeric($sum) && (!is_int($sum) || !is_float($sum))) {
            $sum = floatval($sum);
        }

        if (!($pclass instanceof PClass)) {
            throw new \InvalidArgumentException('Expected $pclass to be a PClass');
        }

        if (is_numeric($flags) && !is_int($flags)) {
            $flags = intval($flags);
        }

        if (!is_numeric($flags)
            || !in_array(
                $flags,
                array(
                    Flags::CHECKOUT_PAGE, Flags::PRODUCT_PAGE,
                )
            )
        ) {
            throw new \InvalidArgumentException(
                'Expected $flags to be '.Flags::CHECKOUT_PAGE.' or '.Flags::PRODUCT_PAGE
            );
        }

        $payarr = self::getPayArray($sum, $pclass, $flags);
        $value = 0;
        if (isset($payarr[0])) {
            $value = $payarr[0];
        }

        if (Flags::CHECKOUT_PAGE == $flags) {
            return round($value, 2);
        }

        return self::pRound($value, $pclass->getCountry());
    }

    /**
     * Returns the lowest monthly payment for Klarna Account.
     *
     * @param int $country Country constant.
     *
     * @throws Exception\KlarnaException
     *
     * @return int|float Lowest monthly payment.
     */
    public static function getLowestPaymentForAccount($country)
    {
        $country = Country::getCode($country);

        switch (strtoupper($country)) {
            case 'SE':
                return 50.0;
            case 'NO':
                return 95.0;
            case 'FI':
                return 8.95;
            case 'DK':
                return 89.0;
            case 'DE':
            case 'AT':
                return 6.95;
            case 'NL':
                return 5.0;
            default:
                throw new \RuntimeException("Invalid country {{$country}}");
        }
    }

    /**
     * Rounds a value depending on the specified country.
     *
     * @param int|float $value   The value to be rounded.
     * @param int       $country Country constant.
     *
     * @return float|int
     */
    public static function pRound($value, $country)
    {
        $multiply = 1; //Round to closest integer
        $country = Country::getCode($country);
        switch ($country) {
            case 'FI':
            case 'DE':
            case 'NL':
            case 'AT':
                $multiply = 10; //Round to closest decimal
                break;
        }

        return floor(($value * $multiply) + 0.5) / $multiply;
    }
}
