<?php

class StardateRunonce extends Controller
{
	public function __construct()
	{
	    parent::__construct();
	    $this->import('Database');
	}
	public function run()
	{
        $migration = false;
        if ($this->Database->tableExists('tl_content'))
        {
            if ($this->Database->fieldExists('calculate', 'tl_content')
            && !$this->Database->fieldExists('calculateStardate', 'tl_content'))
            {
                $this->Database->execute("ALTER TABLE `tl_content` ADD `calculateStardate` varchar(255) NOT NULL default ''");
                $migration = true;
            }
        }

        if ($migration === true)
        {
            $objOld = $this->Database->execute("SELECT `id`, `calculate` FROM `tl_content` WHERE `calculate` != ''");
            while ($objOld->next())
            {
                $this->Database->prepare("UPDATE `tl_content` SET `calculateStardate`=? WHERE `id`=?")
                                ->execute($objOld->calculate, $objOld->id);
                //Protokoll
                $strText = 'Stardate-Bundle "'.$objOld->calculate.'" (id:'.$objOld->id.') has been migrated';
                $this->Database->prepare("INSERT INTO `tl_log` (tstamp, source, action, username, text, func, browser) VALUES(?, ?, ?, ?, ?, ?, ?)")
                                ->execute(time(), 'BE', 'CONFIGURATION', '', specialchars($strText), 'Stardate Bundle Migration', '127.0.0.1', 'NoBrowser');
            }
        }
    }
}

$objStardateRunonce = new StardateRunonce();
$objStardateRunonce->run();
