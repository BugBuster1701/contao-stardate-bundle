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

namespace BugBuster\StardateBundle\Runonce;

class CompileCommands
{
    /**
     * Hook Call
     *
     * @param  array $definition Array of SQL statements
     * @return array             Array of SQL statements
     */
    public function runMigration(array $definition)
    {
        $this->runMigration200($definition);
        log_message(sprintf('[%s] %s','GetFromDbHook::runMigration',print_r($definition,true)),'stardate_debug.log');
        $definition = $this->manipulateSqlCommands($definition);
        log_message(sprintf('[%s] %s','GetFromDbHook::manipulatSqlCommands',print_r($definition,true)),'stardate_debug.log');
        return $definition;
    }

    /**
     * Delete the ALTER TABLE command
     * 
     * @param  array $return Array of SQL statements
     * @return array         Array of SQL statements
     */
    public function manipulateSqlCommands($return)
    {
        if (is_array($return['ALTER_CHANGE'])) 
        {
            $return['ALTER_CHANGE'] = array_filter(
                $return['ALTER_CHANGE'],
                function ($sql) {
                    return strpos($sql, 'ALTER TABLE tl_content CHANGE calculate calculateStardate') === false;
                }
            );

            if (empty($return['ALTER_CHANGE'])) {
                unset($return['ALTER_CHANGE']);
            }
        }
        return $return;
    }

    /**
     * Run the migration to version 2.0.0
     *
     * @param array $definition Array of SQL statements
     * @return void
     */
    public function runMigration200(array $definition)
    {
        $migration = false;
        if (\Contao\Database::getInstance()->tableExists('tl_content'))
        {
            if (\Contao\Database::getInstance()->fieldExists('calculate', 'tl_content')
            && !\Contao\Database::getInstance()->fieldExists('calculateStardate', 'tl_content'))
            {
                //\Contao\Database::getInstance()->execute("ALTER TABLE `tl_content` ADD `calculateStardate` varchar(255) NOT NULL default ''");
                \Contao\Database::getInstance()->execute("ALTER TABLE tl_content CHANGE calculate calculatestardate VARCHAR(255) DEFAULT '' NOT NULL");
                \Contao\Database::getInstance()->prepare("UPDATE `tl_content` SET `type`='stardate' WHERE `type`=?")
                                                ->execute('content_stardate');
                $migration = true;
            }
        }

        if ($migration === true)
        {
            //Protokoll
            $strText = 'Stardate-Bundle has been migrated';
            \Contao\Database::getInstance()->prepare("INSERT INTO `tl_log` (tstamp, source, action, username, text, func, browser) VALUES(?, ?, ?, ?, ?, ?, ?)")
                            ->execute(time(), 'BE', 'CONFIGURATION', '', specialchars($strText), 'Stardate Bundle Migration', '127.0.0.1', 'NoBrowser');
        }
    }
}
