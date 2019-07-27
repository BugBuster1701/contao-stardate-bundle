<?php

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright Glen Langer 2019 <http://contao.ninja>
 * @author Glen Langer (BugBuster)
 * @package Contao Stardate Bundle
 * @license LGPL-3.0-or-later
 * @see https://github.com/BugBuster1701/contao-stardate-bundle
 */

// Register hooks
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array(
    'bugbuster.stardatebundle.insert_tags',
    'onReplaceInsertTags'
);

$GLOBALS['TL_HOOKS']['sqlCompileCommands'][] = array(
    'BugBuster\StardateBundle\Runonce\CompileCommands', 
    'runMigration'
);
