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

namespace BugBuster\StardateBundle\ContentElement;

use Contao\ContentElement;

class ContentStardate extends ContentElement
{
    protected $strTemplate = 'ce_stardate_default';

    protected function compile(): void
    {
        if (TL_MODE === 'BE') {
            $this->genBeOutput();
        } else {
            $this->genFeOutput();
        }
    }

    /**
     * Erzeugt die Ausgebe für das Backend.
     *
     * @return string
     */
    protected function genBeOutput()
    {
        $this->strTemplate = 'be_wildcard';
        $this->Template = new \BackendTemplate($this->strTemplate);
        $this->Template->title = $this->headline;
        $this->Template->wildcard = '### Content Stardate ###';
    }

    /**
     * Erzeugt die Ausgabe für das Frontend,.
     *
     * @return string
     */
    protected function genFeOutput()
    {
        switch ($this->calculate) {
            case 'trekguide_f1':
                $this->Template->stardateTag = '{{stardate::trekguide_f1|uncached}}';
                break;
            case 'trekguide_f2':
                $this->Template->stardateTag = '{{stardate::trekguide_f2|uncached}}';
                break;
            case 'trekconnection':
                $this->Template->stardateTag = '{{stardate::trekconnection|uncached}}';
                break;
            case 'trekguide_x11':
                $this->Template->stardateTag = '{{stardate::trekguide_x11|uncached}}';
                break;
            case 'startrek_tng2323':
                $this->Template->stardateTag = '{{stardate::startrek_tng2323|uncached}}';
                break;
            case 'startrek_tng2322':
                $this->Template->stardateTag = '{{stardate::startrek_tng2322|uncached}}';
                break;
            case 'startrek_tos2265':
                $this->Template->stardateTag = '{{stardate::startrek_tos2265|uncached}}';
                break;
            default:
                $this->Template->stardateTag = '00000.00';
                break;
        }
    }
}
