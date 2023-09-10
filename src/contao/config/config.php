<?php

declare(strict_types=1);

/*
 * This file is part of a BugBuster Contao Bundle.
 *
 * @copyright  Glen Langer 2023 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao Stardate Bundle
 * @license LGPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/BugBuster1701/contao-stardate-bundle
 */

// Register hooks
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = [
    'bugbuster.stardatebundle.insert_tags',
    'onReplaceInsertTags',
];

$GLOBALS['TL_HOOKS']['sqlCompileCommands'][] = [
    'BugBuster\StardateBundle\Runonce\CompileCommands',
    'runMigration',
];
