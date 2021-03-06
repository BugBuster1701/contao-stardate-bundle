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

namespace BugBuster\StardateBundle\EventListener;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

class System
{
    private $requestStack;
    private $scopeMatcher;

    public function __construct(RequestStack $requestStack, ScopeMatcher $scopeMatcher)
    {
        $this->requestStack = $requestStack;
        $this->scopeMatcher = $scopeMatcher;
    }

    public function isBackend()
    {
        //composer install/update has no request, but the initializeSystem hook is called!
        if (null === $this->requestStack->getCurrentRequest()) {
            return false;
        }

        return $this->scopeMatcher->isBackendRequest($this->requestStack->getCurrentRequest());
    }

    public function isFrontend()
    {
        //composer install/update has no request, but the initializeSystem hook is called!
        if (null === $this->requestStack->getCurrentRequest()) {
            return false;
        }

        return $this->scopeMatcher->isFrontendRequest($this->requestStack->getCurrentRequest());
    }

    public function initializeSystem(): void
    {
        if ($this->isFrontend()) { // Früher TL_MODE == 'FE'
            // Pfad ggf. anpassen
            // Alle Dateien in /src/Ressources/public werden unter /web/bundles/bundle-name
            // als Symlink veröffentlicht nach composer install/update
            //$GLOBALS['TL_CSS'][] = 'bundles/stardatebundle/css/dummy.css|static';
            //$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/stardatebundle/js/dummy.js|static';
        }

        array_insert($GLOBALS['TL_CTE']['texts'], 2, [
            'stardate' => 'BugBuster\StardateBundle\ContentElement\ContentStardate',
        ]);
    }
}
