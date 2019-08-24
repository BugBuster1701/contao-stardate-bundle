<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2019 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao Stardate Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-stardate-bundle
 */

namespace BugBuster\StardateBundle\EventListener;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\StringUtil;

/**
 * Listener for replace insert tags
 * to calculate the stardate
 * 
 * Usage for actualy stardate:
 *     {{stardate::trekguide_f1|uncached}}
 *     {{stardate::trekguide_f2|uncached}}
 *     {{stardate::trekconnection|uncached}}
 *     {{stardate::trekguide_x11|uncached}}
 *     {{stardate::startrek_tng2323|uncached}}
 *     {{stardate::startrek_tng2322|uncached}}
 *     {{stardate::startrek_tos2265|uncached}}
 * 
 * Usage with parameter for specially stardate:
 *     {{stardate::trekguide_f1::'2019-08-20 13:37'::'Y-m-d H:i'|uncached}}
 *     {{stardate::trekguide_f2::<datetime>::<format>|uncached}}
 *     {{stardate::trekconnection::<datetime>::<format>|uncached}}
 *     {{stardate::trekguide_x11::<datetime>::<format>|uncached}}
 *     {{stardate::startrek_tng2323::<datetime>::<format>|uncached}}
 *     {{stardate::startrek_tng2322::<datetime>::<format>|uncached}}
 *     {{stardate::startrek_tos2265::<datetime>::<format>|uncached}}
 * 
 */
class InsertTagsListener
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Replaces the Stardate insert tags.
     *
     * @return string|false
     */
    public function onReplaceInsertTags(string $tag, bool $useCache, $cacheValue, array $flags)
    {
        static $supportedTags = [
            'stardate',
        ];

        $elements = StringUtil::trimsplit('::', $tag);
        $key = strtolower($elements[0]);

        if (!\in_array($key, $supportedTags, true)) {
            return false;
        }

        if (!isset($elements[1])) {
            $elements[1] = 'trekconnection'; // Default Angabe
        }

        if (!isset($elements[2])) {
            $elements[2] = '';
        }
        
        if (!isset($elements[3])) {
            $elements[3] = '';
        }

        $this->framework->initialize();

        return $this->generateReplacement($elements[1], $elements[2], $elements[3]);
    }

    /**
     * @return string|false
     */
    private function generateReplacement(string $calc_method, string $datetime, string $datetimeformat)
    {
        $date = \DateTime::createFromFormat('0.u00 U', microtime());

        if (!empty($datetime) && !empty($datetimeformat)) {
            $date = \DateTime::createFromFormat($datetimeformat, $datetime);
            if (false === $date) {
                \System::loadLanguageFile('tl_stardate_event');
                return sprintf($GLOBALS['TL_LANG']['tl_stardate_event']['error_datetime'], $datetimeformat, $datetime);
            }
        }
        
        switch ($calc_method) {
            case 'trekguide_f1':
                return $this->calculateStardateTrekguide_f1($date);
            case 'trekguide_f2':
                return $this->calculateStardateTrekguide_f2($date);
            case 'trekconnection':
                return $this->calculateStardateTrekconnection($date);
            case 'trekguide_x11':
                return $this->calculateStardateTrekguideX11($date);
            case 'startrek_tng2323':
                return $this->calculateStardateTng2323($date);
            case 'startrek_tng2322':
                return $this->calculateStardateTng2322($date);
            case 'startrek_tos2265':
                return $this->calculateStardateTos2265($date);
        }

        return false;
    }

    private function calculateStardateTrekguide_f1(\DateTimeInterface $datetime)
    {
        // http://trekguide.com/Stardates.htm#Today (1)
        // fictional Stardate that corresponds to "today's" date
        // YYMM.DD (YY = YYYY-1900)
        $Year = $datetime->format('Y') - 1900;

        return $Year.$datetime->format('m').'.'.$datetime->format('d');
    }

    private function calculateStardateTrekguide_f2(\DateTimeInterface $datetime)
    {
        // http://trekguide.com/Stardates.htm#Today (3)
        // fictional Stardate
        // Sternzeit 00000.0 beginnt am 14.07.1946, um 18:00 Uhr.
        $date1 = mktime(18, 0, 0, 7, 14, 1946);
        $date2 = $datetime->getTimestamp(); //time();
        $diffdays = (($date2 - $date1) / 86400);
        $strDate = round($diffdays / 365.25 * 1000, 1);

        return number_format($strDate, 1, '.', ''); // 3 to 3.0
    }

    private function calculateStardateTrekconnection(\DateTimeInterface $datetime)
    {
        // Javascript made by Heath Coop of TrekConnection.com
        // adapted in php
        $SDYear = 40000 + (($datetime->format('Y') - 1987) * 1000);
        $YearStartTime = mktime(0, 0, 0, 1, 1, (int) $datetime->format('Y'));
        //[$usec, $sec] = explode(' ', microtime());
        //$NowTime = ((float) $usec + (float) $sec);
        $NowTime = (float) $datetime->format('U.u');
        $Days = ($NowTime - $YearStartTime) / 86400;
        if ($Days >= 183) {
            $SDYear = $SDYear + 1000;
            $SDDays = ($Days * (1000 / 365)) - 500;
        }
        if ($Days < 183) {
            $SDDays = 500 + ($Days * (1000 / 365));
        }
        $strDate = round($SDYear + $SDDays, 1);

        return number_format($strDate, 1, '.', ''); // 3 to 3.0
    }

    private function calculateStardateTrekguideX11(\DateTimeInterface $datetime)
    {
        // Stardate format in Star Trek XI
        // dates may be expressed in YYYY.xx format, where YYYY is the actual four-digit year,
        // and .xx represents the fraction of the year to two decimal places (i.e., hundredths of a year).
        // For example, January 1, 1999, would correspond to Stardate 1999.00,
        // while July 2, 1999, would correspond to Stardate 1999.50 (half-way through the year 1999).
        $SDYear = (int) $datetime->format('Y');
        $YearStartTime = mktime(0, 0, 0, 1, 1, (int) $datetime->format('Y'));
        $YearEndTime = mktime(0, 0, 0, 1, 1, (int) $datetime->format('Y') + 1);
        //[$usec, $sec] = explode(' ', microtime());
        //$NowTime = ((float) $usec + (float) $sec);
        $NowTime = (float) $datetime->format('U.u');
        $Days = ($NowTime - $YearStartTime) / 86400;
        $DaysH = round($Days * 100 / (($YearEndTime - $YearStartTime) / 86400), 0);
        if ($DaysH < 10) {
            return $SDYear.'.0'.$DaysH;
        }
        if (100 === $DaysH) {
            return $SDYear.'.99';
        }

        return $SDYear.'.'.$DaysH;
    }

    private function calculateStardateTng2323(\DateTimeInterface $datetime)
    {
        // nach  http://www.lcars.org.uk/Stardate.htm
        // Stardate 00000.0 began on January 01, 2323, at 00:00 hours.
        $SDYear = (int) $datetime->format('Y') - 2323;
        $YearStartTime = mktime(0, 0, 0, 1, 1, (int) $datetime->format('Y'));
        $YearEndTime = mktime(0, 0, 0, 1, 1, (int) $datetime->format('Y') + 1);
        $DaysInYear = ($YearEndTime - $YearStartTime) / 86400;
        //[$usec, $sec] = explode(' ', microtime());
        //$NowTime = ((float) $usec + (float) $sec);
        $NowTime = (float) $datetime->format('U.u');
        $Days = ($NowTime - $YearStartTime) / 86400;
        $MinutesPart = (int) $datetime->format('i') / 60;
        $HoursPart = ((int) $datetime->format('H') + $MinutesPart) / 24;
        $SDMonth = round((floor($Days) + $HoursPart) / $DaysInYear * 1000, 2);
        $YM = $SDYear.$SDMonth;
        
        return number_format((float)$YM, 2, '.', '');
    }

    private function calculateStardateTng2322(\DateTimeInterface $datetime)
    {
        // nach http://trekguide.com/Stardates.htm#TNG
        // Stardate 00000.0 began on May 25, 2322, at 00:00 hours.
        $SDBegin = mktime(0, 0, 0, 5, 25, 2322); // May 25, 2322 00:00:00
        //[$usec, $sec] = explode(' ', microtime());
        //$NowTime = ((float) $usec + (float) $sec);
        $NowTime = (float) $datetime->format('U.u');
        $DiffTime = $NowTime - $SDBegin;
        $SDYear = $DiffTime / (60 * 60 * 24 * 365.2422);
        $SDYear = round((floor($SDYear * 100000) / 100) + 0.11, 2); // 0.11 kleine Korrektur noetig zum Javascript Original, damit beide gleich

        return number_format($SDYear, 2, '.', ''); // 3.4 to 3.40
    }

    private function calculateStardateTos2265(\DateTimeInterface $datetime)
    {
        // http://trekguide.com/Stardates.htm#TOS
        // Stardate 0000.0 began on May 1, 2265 00:00:00
        $SDBegin = mktime(0, 0, 0, 5, 1, 2265); // May 1, 2265 00:00:00
        //[$usec, $sec] = explode(' ', microtime());
        //$NowTime = ((float) $usec + (float) $sec);
        $NowTime = (float) $datetime->format('U.u');
        $DiffTime = $NowTime - $SDBegin;
        $SDYear = $DiffTime / (60 * 60 * 24 * 365.2422);
        $SDYear = $SDYear * 2.7113654892;
        $SDYear = round((floor($SDYear * 100000) / 100) + 0.31, 2); // 0.31 kleine Korrektur noetig zum Javascript Original, damit beide gleich

        return number_format($SDYear, 2, '.', ''); // 3.4 to 3.40
    }
}
