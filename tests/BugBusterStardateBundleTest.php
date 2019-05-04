<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
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
