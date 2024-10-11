<?php
/** 
 * 
 * @package Thaidate
 * @version 2.1.1
 * @author Vee W.
 * @license http://opensource.org/licenses/MIT
 * 
 */

namespace Rundiz\Thaidate;

/**
 * Format date in Thai locale.
 */
class Thaidate
{
    /**
     * Use Buddhist Era? (พ.ศ.)
     * @var boolean Set to true to use or false not to use.
     */
    public $buddhist_era = true;

    /**
     * Locale to use in setlocale() function. Some server support different locales, with .UTF8, or without .UTF8. Detect and use at your own.
     * @var string|array See more about `$locale` parameter of `setlocale()` function at http://php.net/manual/en/function.setlocale.php .
     */
    public $locale = 'th';

    public $month_long = array('มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
    public $month_short = array('ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.');

    public $day_long = array('อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์');
    public $day_short = array('อา.', 'จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.');


    /**
     * Thai date() function.
     * 
     * @param string $format The format as same as PHP date function format. See http://php.net/manual/en/function.date.php
     * @param int $timestamp The optional timestamp is an integer Unix timestamp.
     * @return string Return the formatted date/time string.
     * @throws \InvalidArgumentException Throw the exception if invalid argument type is specify.
     */
    public function date($format, $timestamp = '')
    {
        if (!is_string($format)) {
            throw new \InvalidArgumentException('The argument $format must be string.');
        }

        if (!is_numeric($timestamp)) {
            $timestamp = time();
        }

        // if use Buddhist era, convert the year.
        if ($this->buddhist_era === true) {
            if (mb_strpos($format, 'o') !== false) {
                $year = (date('o', $timestamp)+543);
                $format = str_replace('o', $year, $format);
            } elseif (mb_strpos($format, 'Y') !== false) {
                $year = (date('Y', $timestamp)+543);
                $format = str_replace('Y', $year, $format);
            } elseif (mb_strpos($format, 'y') !== false) {
                $year = (date('y', $timestamp)+43);
                $format = str_replace('y', $year, $format);
            }
            unset($year);
        }

        // replace format that will be convert month into name (F, M) to Thai.
        if (mb_strpos($format, 'F') !== false || mb_strpos($format, 'M') !== false) {
            $month_num = (date('n', $timestamp)-1);
            if (mb_strpos($format, 'F') !== false && array_key_exists($month_num, $this->month_long)) {
                $format = str_replace('F', $this->month_long[$month_num], $format);
            } elseif (mb_strpos($format, 'M') !== false && array_key_exists($month_num, $this->month_short)) {
                $format = str_replace('M', $this->month_short[$month_num], $format);
            }
            unset($month_num);
        }

        // replace format that will be convert day into name (D, l) to Thai.
        if (mb_strpos($format, 'l') !== false || mb_strpos($format, 'D') !== false) {
            $day_num = date('w', $timestamp);
            if (mb_strpos($format, 'l') !== false && array_key_exists($day_num, $this->day_long)) {
                $format = str_replace('l', $this->day_long[$day_num], $format);
            } elseif (mb_strpos($format, 'D') !== false && array_key_exists($day_num, $this->day_short)) {
                $format = str_replace('D', $this->day_short[$day_num], $format);
            }
            unset($day_num);
        }

        return date($format, $timestamp);
    }// date


    /**
     * Thai date use `\IntlDateFormatter()` class.
     * 
     * @since 2.1.0
     * @see https://www.php.net/manual/en/class.intldateformatter.php
     * @param string $format The format or pattern as **same** as ICU format. See https://unicode-org.github.io/icu/userguide/format_parse/datetime/
     * @param int $timestamp The optional timestamp is an integer Unix timestamp.
     * @return string Return the formatted date/time string.
     * @throws \InvalidArgumentException Throw the exception if invalid argument type is specify.
     */
    public function intlDate($format, $timestamp = '')
    {
        if (!is_string($format)) {
            throw new \InvalidArgumentException('The argument $format must be string.');
        }

        if (!is_numeric($timestamp)) {
            $timestamp = time();
        }

        if ($this->buddhist_era === true) {
            $calendar = \IntlDateFormatter::TRADITIONAL;
        } else {
            $calendar = null;
        }
        $locale = $this->locale;
        if (is_array($this->locale)) {
            $localeVals = array_values($locale);
            $locale = array_shift($localeVals);
            unset($localeVals);
        } elseif (!is_scalar($this->locale)) {
            $locale = 'th';
        }
        $IntlDateFormatter = new \IntlDateFormatter($locale, \IntlDateFormatter::FULL, \IntlDateFormatter::FULL, null, $calendar);
        $IntlDateFormatter->setPattern($format);
        return $IntlDateFormatter->format($timestamp);
    }// intlDate


    /**
     * Thai date use strftime() function.
     * 
     * Function `strftime()` is deprecated since PHP 8.1. This method will be here to keep it working from old projects to new.<br>
     * However, please validate the result that it really is correct once PHP removed this function in version 9.0.<br>
     * Use other method instead of this is recommended.
     * 
     * @param string $format The format as same as PHP date function format. See http://php.net/manual/en/function.strftime.php
     * @param int $timestamp The optional timestamp is an integer Unix timestamp.
     * @return string Return the formatted date/time string.<br>
     *              This method will be show the notice if function `strftime()` is deprecated or removed from currently running PHP version.
     * @throws \InvalidArgumentException Throw the exception if invalid argument type is specify.
     */
    public function strftime($format, $timestamp = '')
    {
        if (!function_exists('strftime') || version_compare(PHP_VERSION, '8.1', '>=')) {
            // if function `strftime` is not exists or deprecated (since PHP 8.1).
            // notice the developers to upgrade their code.
            // this method can keep running with new version of PHP but need more attention about format/pattern.
            // so, use notice instead of warning, error, deprecated level.
            trigger_error(
                'Function `strftime()` is deprecated 
                    and method `\Rundiz\Thaidate\Thaidate::strftime()` is using replacement which may return incorrect result.
                    Please update your code to use `\Rundiz\Thaidate\Thaidate::intlDate()` instead.', 
                E_USER_NOTICE
            );

            if (class_exists('\IntlDateFormatter')) {
                return $this->intlDate($this->strftimeFormatToIntlDatePattern($format), $timestamp);
            }
            // if IntlDateFormatter is not exists then let it run and error occur.
        }

        if (!is_string($format)) {
            throw new \InvalidArgumentException('The argument $format must be string.');
        }

        if (!is_numeric($timestamp)) {
            $timestamp = time();
        }

        setlocale(LC_TIME, $this->locale);

        // if use Buddhist era, convert the year (y, Y).
        if ($this->buddhist_era === true) {
            if (mb_strpos($format, '%Y') !== false) {
                $year = (@strftime('%Y', $timestamp)+543);
                $format = str_replace('%Y', $year, $format);
            } elseif (mb_strpos($format, '%y') !== false) {
                $year = (@strftime('%y', $timestamp)+43);
                $format = str_replace('%y', $year, $format);
            }
            unset($year);
        }

        return @strftime($format, $timestamp);
    }// strftime


    /**
     * Thai date convert format of strftime to intlDate
     * 
     * @param string $format strftime format
     * @return string Return format pattern of intlDate
     */
    public function strftimeFormatToIntlDatePattern($format)
    {
        $format = str_replace('%Y', 'yyyy', $format);
        $format = str_replace('%y', 'yy', $format);
        $format = str_replace('%m', 'MM', $format);
        $format = str_replace('%d', 'dd', $format);
        $format = str_replace('%H', 'HH', $format);
        $format = str_replace('%i', 'mm', $format);
        $format = str_replace('%s', 'ss', $format);
        $format = str_replace('%A', 'EEEE', $format);
        $format = str_replace('%B', 'MMMM', $format);
        $format = str_replace('%a', 'EEE', $format);
        $format = str_replace('%b', 'MMM', $format);
        $format = str_replace('%p', 'a', $format);
        $format = str_replace('%c', 'yyyy-MM-dd HH:mm:ss', $format); // ISO8601.
        return $format;
    }// strftimeFormatToIntlDatePattern


    /**
     * Convert Arabic numbers to Thai numbers.
     * 
     * @param string $string The input string containing Arabic numbers.
     * @return string The string with Arabic numbers converted to Thai numbers.
     */
    public function toThaiNumber($string)
    {
        $arabicNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $thaiNumbers = ['๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙'];
        
        return str_replace($arabicNumbers, $thaiNumbers, $string);
    }
}
