<?php

/*
 * This file is part of a BugBuster Contao Bundle
 *
 * @copyright  Glen Langer 2019 <http://contao.ninja>
 * @author     Glen Langer (BugBuster)
 * @package    Contao Stardate Bundle
 * @license    LGPL-3.0-or-later
 * @see        https://github.com/BugBuster1701/contao-stardate-bundle
 */

namespace BugBuster\StardateBundle\Tests;

use BugBuster\StardateBundle\BugBusterStardateBundle;
use PHPUnit\Framework\TestCase;

class BugBusterStardateBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new BugBusterStardateBundle();

        $this->assertInstanceOf('BugBuster\StardateBundle\BugBusterStardateBundle', $bundle);
    }
}
