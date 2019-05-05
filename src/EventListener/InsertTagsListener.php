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
     * Replaces the FAQ insert tags.
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
        if (!isset($elements[1])) {
            $elements[1] = 'trekconnection'; // Default Angabe
        }

        if (!\in_array($key, $supportedTags, true)) {
            return false;
        }

        $this->framework->initialize();

        return $this->generateReplacement($elements[1]);
    }

    /**
     * @return string|false
     */
    private function generateReplacement(string $calc_method)
    {
        switch ($calc_method) {
            case 'trekguide_f1':
                return $this->calculateStardateTrekguide_f1();
            case 'trekguide_f2':
                return $this->calculateStardateTrekguide_f2();
            case 'trekconnection':
                return $this->calculateStardateTrekconnection();
            case 'trekguide_x11':
                return $this->calculateStardateTrekguideX11();
            case 'startrek_tng2323':
                return $this->calculateStardateTng2323();
            case 'startrek_tng2322':
                return $this->calculateStardateTng2322();
            case 'startrek_tos2265':
                return $this->calculateStardateTos2265();
        }

        return false;
    }

    private function calculateStardateTrekguide_f1()
    {
        // http://trekguide.com/Stardates.htm#Today (1)
        // fictional Stardate that corresponds to "today's" date
        // YYMM.DD (YY = YYYY-1900)
        $Year = date('Y') - 1900;

        return $Year.date('m').'.'.date('d');
    }

    private function calculateStardateTrekguide_f2()
    {
        // http://trekguide.com/Stardates.htm#Today (3)
        // fictional Stardate
        // Sternzeit 00000.0 beginnt am 14.07.1946, um 18:00 Uhr.
        $date1 = mktime(18, 0, 0, 7, 14, 1946);
        $date2 = time();
        $diffdays = (($date2 - $date1) / 86400);
        $strDate = round($diffdays / 365.25 * 1000, 1);

        return number_format($strDate, 1, '.', ''); // 3 to 3.0
    }

    private function calculateStardateTrekconnection()
    {
        // Javascript made by Heath Coop of TrekConnection.com
        // adapted in php
        $SDYear = 40000 + ((date('Y') - 1987) * 1000);
        $YearStartTime = mktime(0, 0, 0, 1, 1, (int) date('Y'));
        [$usec, $sec] = explode(' ', microtime());
        $NowTime = ((float) $usec + (float) $sec);
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

    private function calculateStardateTrekguideX11()
    {
        // Stardate format in Star Trek XI
        // dates may be expressed in YYYY.xx format, where YYYY is the actual four-digit year,
        // and .xx represents the fraction of the year to two decimal places (i.e., hundredths of a year).
        // For example, January 1, 1999, would correspond to Stardate 1999.00,
        // while July 2, 1999, would correspond to Stardate 1999.50 (half-way through the year 1999).
        $SDYear = (int) date('Y');
        $YearStartTime = mktime(0, 0, 0, 1, 1, (int) date('Y'));
        $YearEndTime = mktime(0, 0, 0, 1, 1, (int) date('Y') + 1);
        [$usec, $sec] = explode(' ', microtime());
        $NowTime = ((float) $usec + (float) $sec);
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

    private function calculateStardateTng2323()
    {
        // nach  http://www.lcars.org.uk/Stardate.htm
        // Stardate 00000.0 began on January 01, 2323, at 00:00 hours.
        $SDYear = (int) date('Y') - 2323;
        $YearStartTime = mktime(0, 0, 0, 1, 1, (int) date('Y'));
        $YearEndTime = mktime(0, 0, 0, 1, 1, (int) date('Y') + 1);
        $DaysInYear = ($YearEndTime - $YearStartTime) / 86400;
        [$usec, $sec] = explode(' ', microtime());
        $NowTime = ((float) $usec + (float) $sec);
        $Days = ($NowTime - $YearStartTime) / 86400;
        $MinutesPart = (int) date('i') / 60;
        $HoursPart = ((int) date('H') + $MinutesPart) / 24;
        $SDMonth = round((floor($Days) + $HoursPart) / $DaysInYear * 1000, 2);

        return $SDYear.$SDMonth;
    }

    private function calculateStardateTng2322()
    {
        // nach http://trekguide.com/Stardates.htm#TNG
        // Stardate 00000.0 began on May 25, 2322, at 00:00 hours.
        $SDBegin = mktime(0, 0, 0, 5, 25, 2322); // May 25, 2322 00:00:00
        [$usec, $sec] = explode(' ', microtime());
        $NowTime = ((float) $usec + (float) $sec);
        $DiffTime = $NowTime - $SDBegin;
        $SDYear = $DiffTime / (60 * 60 * 24 * 365.2422);
        $SDYear = round((floor($SDYear * 100000) / 100) + 0.11, 2); // 0.11 kleine Korrektur noetig zum Javascript Original, damit beide gleich

        return number_format($SDYear, 2, '.', ''); // 3.4 to 3.40
    }

    private function calculateStardateTos2265()
    {
        // http://trekguide.com/Stardates.htm#TOS
        // Stardate 0000.0 began on May 1, 2265 00:00:00
        $SDBegin = mktime(0, 0, 0, 5, 1, 2265); // May 1, 2265 00:00:00
        [$usec, $sec] = explode(' ', microtime());
        $NowTime = ((float) $usec + (float) $sec);
        $DiffTime = $NowTime - $SDBegin;
        $SDYear = $DiffTime / (60 * 60 * 24 * 365.2422);
        $SDYear = $SDYear * 2.7113654892;
        $SDYear = round((floor($SDYear * 100000) / 100) + 0.31, 2); // 0.31 kleine Korrektur noetig zum Javascript Original, damit beide gleich

        return number_format($SDYear, 2, '.', ''); // 3.4 to 3.40
    }
}
