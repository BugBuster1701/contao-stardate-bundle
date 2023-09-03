<?php

declare(strict_types=1);

namespace BugBuster\StardateBundle\Controller\ContentElement;

use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\ContentModel;
use Contao\System;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StardateController extends AbstractContentElementController
{
    protected function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        if (System::getContainer()->get('contao.routing.scope_matcher')
	        ->isBackendRequest(System::getContainer()->get('request_stack')
        	->getCurrentRequest() ?? Request::create(''))) 
        {
            $calc = $GLOBALS['TL_LANG']['tl_content'][$model->calculateStardate];
            $template->set('wildcard', '=/\= '.$calc.' =/\=');

            return $template->getResponse();
        }

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
            default:
                $template->set('stardateTag', '00000.00');
                break;
        }

        return $template->getResponse();
    }
}