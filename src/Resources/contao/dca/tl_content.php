<?php

/**
 * Table tl_content
 */
$strName = 'tl_content';

// Palettes, old: content_stardate
$GLOBALS['TL_DCA'][$strName]['palettes']['stardate'] = '{type_legend},type;{calculateStardate_legend},calculateStardate;{template_legend:hide},customTpl;{invisible_legend:hide},invisible,start,stop;';

// old: calculate
$GLOBALS['TL_DCA'][$strName]['fields']['calculateStardate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['calculateStardate'],
    'default'                 => 'default',
    'exclude'                 => true,
    'filter'                  => true,
    'sorting'                 => true,
    'explanation'             => 'calculateStardate_help',
    'inputType'               => 'select',
    'options'                 => array('default',
                                        'trekguide_f1', 
                                        'trekguide_f2', 
                                        'trekconnection', 
                                        'trekguide_x11', 
                                        'startrek_tng2323', 
                                        'startrek_tng2322', 
                                        'startrek_tos2265'),
    'reference'               => &$GLOBALS['TL_LANG'][$strName],
    //'foreignKey'            => 'tl_user.name',
    //'options_callback'      => array('CLASS', 'METHOD'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50', 'helpwizard'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
