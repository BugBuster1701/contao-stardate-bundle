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

namespace BugBuster\StardateBundle\Runonce;

use Contao\Database;
use Contao\StringUtil;

/**
 * Class CompileCommands.
 *
 * Call over the hook sqlCompileCommands
 */
class CompileCommands
{
    /**
     * Hook Call sqlCompileCommands.
     *
     * @param array $definition Array of SQL statements
     *
     * @return array Array of SQL statements
     */
    public function runMigration(array $definition)
    {
        $this->runMigration200($definition);

        return $this->manipulateSqlCommands($definition);
    }

    /**
     * Delete the ALTER TABLE command.
     *
     * @param array $return Array of SQL statements
     *
     * @return array Array of SQL statements
     */
    public function manipulateSqlCommands($return)
    {
        if (isset($return['ALTER_CHANGE'])) {
            if (\is_array($return['ALTER_CHANGE'])) {
                $return['ALTER_CHANGE'] = array_filter(
                    $return['ALTER_CHANGE'],
                    static fn ($sql) => !str_contains($sql, 'ALTER TABLE tl_content CHANGE calculate calculateStardate'),
                );

                if (empty($return['ALTER_CHANGE'])) {
                    unset($return['ALTER_CHANGE']);
                }
            }
        }

        return $return;
    }

    /**
     * Run the migration to version 2.0.0.
     *
     * @param array $definition Array of SQL statements
     */
    public function runMigration200(array $definition): void
    {
        $migration = false;

        if (Database::getInstance()->tableExists('tl_content')) {
            if (
                Database::getInstance()->fieldExists('calculate', 'tl_content')
            && !Database::getInstance()->fieldExists('calculateStardate', 'tl_content')
            ) {
                Database::getInstance()->execute("ALTER TABLE tl_content CHANGE calculate calculatestardate VARCHAR(255) DEFAULT '' NOT NULL");
                Database::getInstance()->prepare("UPDATE `tl_content` SET `type`='stardate' WHERE `type`=?")
                    ->execute('content_stardate')
                ;
                $migration = true;
            }
        }

        if (true === $migration) {
            // Protokoll
            $strText = 'Stardate-Bundle has been migrated';
            Database::getInstance()->prepare('INSERT INTO `tl_log` (tstamp, source, action, username, text, func, browser) VALUES(?, ?, ?, ?, ?, ?, ?)')
                ->execute(time(), 'BE', 'CONFIGURATION', '', StringUtil::specialchars($strText), 'Stardate Bundle Migration', '127.0.0.1', 'NoBrowser')
            ;
        }
    }
}
