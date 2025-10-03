<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle.
 *
 * @copyright  Glen Langer 2025 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao Stardate Bundle
 * @license LGPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/BugBuster1701/contao-stardate-bundle
 */

namespace BugBuster\StardateBundle\Controller\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StardateController extends AbstractContentElementController
{
    /**
     * Render the content element.
     */
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        if (
            System::getContainer()->get('contao.routing.scope_matcher')
                ->isBackendRequest(System::getContainer()->get('request_stack')
                    ->getCurrentRequest() ?? Request::create(''))
        ) {
            /** @phpstan-ignore property.notFound */
            $calc = $GLOBALS['TL_LANG']['tl_content'][$model->calculateStardate];
            $template->set('wildcard', '=/\= '.$calc.' =/\=');

            return $template->getResponse();
        }

        /** @phpstan-ignore property.notFound */
        switch ($model->calculateStardate) {
            case 'trekguide_f1':
                $template->set('stardateTag', '{{fragment::{{stardate::trekguide_f1}}}}');
                break;
            case 'trekguide_f2':
                $template->set('stardateTag', '{{fragment::{{stardate::trekguide_f2}}}}');
                break;
            case 'trekconnection':
                $template->set('stardateTag', '{{fragment::{{stardate::trekconnection}}}}');
                break;
            case 'trekguide_x11':
                $template->set('stardateTag', '{{fragment::{{stardate::trekguide_x11}}}}');
                break;
            case 'startrek_tng2323':
                $template->set('stardateTag', '{{fragment::{{stardate::startrek_tng2323}}}}');
                break;
            case 'startrek_tng2322':
                $template->set('stardateTag', '{{fragment::{{stardate::startrek_tng2322}}}}');
                break;
            case 'startrek_tos2265':
                $template->set('stardateTag', '{{fragment::{{stardate::startrek_tos2265}}}}');
                break;
            case 'startrek_beyond':
                $template->set('stardateTag', '{{fragment::{{stardate::startrek_beyond}}}}');
                break;

            default:
                $template->set('stardateTag', '00000.00');
                break;
        }

        return $template->getResponse();
    }
}
