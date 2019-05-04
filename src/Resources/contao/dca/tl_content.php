<?php

/**
 * Table tl_content
 */
$strName = 'tl_content';

// Palettes
$GLOBALS['TL_DCA'][$strName]['palettes']['content_stardate'] = '{type_legend},type;{calculate_legend},calculate;{invisible_legend:hide},invisible,start,stop;';

$GLOBALS['TL_DCA'][$strName]['fields']['calculate'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['calculate'],
    'default'                 => 'default',
    'exclude'                 => true,
    'filter'                  => true,
    'sorting'                 => true,
    'explanation'             => 'calculate_help',
    'inputType'               => 'select',
    'options'                 => array('default',
                                        'trekguide_f1', 
                                        'trekguide_f2', 
                                        'trekconnection', 
                                        'trekguide_11', 
                                        'startrek_tng2323', 
                                        'startrek_tng2322', 
                                        'startrek_tos2265'),
    'reference'               => &$GLOBALS['TL_LANG'][$strName],
    //'foreignKey'            => 'tl_user.name',
    //'options_callback'      => array('CLASS', 'METHOD'),
    'eval'                    => array('includeBlankOption'=>true, 'tl_class'=>'w50', 'helpwizard'=>true),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
