<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2019..2021 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao Stardate Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-stardate-bundle
 */

namespace BugBuster\StardateBundle\ContentElement;

use Contao\ContentElement;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

class ContentStardate extends ContentElement
{
    protected $strTemplate = 'ce_stardate_default';

    protected function compile(): void
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')
                ->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))) {
            $this->genBeOutput();

            return;
        }
        $this->genFeOutput();
    }

    /**
     * Erzeugt die Ausgebe für das Backend.
     *
     * @return string
     */
    protected function genBeOutput()
    {
        $this->strTemplate = 'be_wildcard';
        $this->Template = new \Contao\BackendTemplate($this->strTemplate);
        $calc = $GLOBALS['TL_LANG']['tl_content'][$this->calculateStardate];
        $this->Template->wildcard = '=/\= '.$calc.' =/\=';
    }

    /**
     * Erzeugt die Ausgabe für das Frontend,.
     *
     * @return string
     */
    protected function genFeOutput()
    {
        switch ($this->calculateStardate) {
            case 'trekguide_f1':
                $this->Template->stardateTag = '{{fragment::stardate::trekguide_f1}}';
                break;
            case 'trekguide_f2':
                $this->Template->stardateTag = '{{fragment::stardate::trekguide_f2}}';
                break;
            case 'trekconnection':
                $this->Template->stardateTag = '{{fragment::stardate::trekconnection}}';
                break;
            case 'trekguide_x11':
                $this->Template->stardateTag = '{{fragment::stardate::trekguide_x11}}';
                break;
            case 'startrek_tng2323':
                $this->Template->stardateTag = '{{fragment::stardate::startrek_tng2323}}';
                break;
            case 'startrek_tng2322':
                $this->Template->stardateTag = '{{fragment::stardate::startrek_tng2322}}';
                break;
            case 'startrek_tos2265':
                $this->Template->stardateTag = '{{fragment::stardate::startrek_tos2265}}';
                break;
            default:
                $this->Template->stardateTag = '00000.00';
                break;
        }
    }
}
