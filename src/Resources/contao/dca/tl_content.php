<?php

/**
 * Table tl_content
 */
$strName = 'tl_content';

// Palettes
$GLOBALS['TL_DCA'][$strName]['palettes']['content_stardate'] = '{type_legend},type,headline;{description_legend},productdescription,productproperties,productprice;{invisible_legend:hide},invisible,start,stop;';

// Fields
$GLOBALS['TL_DCA'][$strName]['fields']['productdescription'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['productdescription'],
    'exclude'                 => true,
    'inputType'               => 'textarea',
    'eval'                    => array('rte' => 'tinyMCE'),
    'sql'                     => "text NOT NULL"
);
$GLOBALS['TL_DCA'][$strName]['fields']['productproperties'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['productproperties'],
    'exclude'                 => true,
    'inputType'               => 'listWizard',
    'sql'                     => "text NOT NULL"
);
$GLOBALS['TL_DCA'][$strName]['fields']['productprice'] = array
(
    'label'                   => &$GLOBALS['TL_LANG'][$strName]['productprice'],
    'exclude'                 => true,
    'inputType'               => 'text',
    'eval'                    => array('tl_class' => 'w50'),
    'sql'                     => "varchar(255) NOT NULL default ''"
);
