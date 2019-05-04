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
use Contao\StringUtil;

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
    private function genBeOutput()
    {
        $this->strTemplate = 'be_wildcard';
        $this->Template = new \BackendTemplate($this->strTemplate);
        $this->Template->title = $this->headline;
        $this->Template->wildcard = '### ContentProduct ###';
    }

    /**
     * Erzeugt die Ausgebe für das Frontend.
     *
     * @return string
     */
    private function genFeOutput()
    {
        if ('' !== $this->productproperties) {
            $this->Template->arrProperties = StringUtil::deserialize($this->productproperties, true);
        }
    }
}
