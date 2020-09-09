<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSlugify()
    {
        $slugger = new Slugger();

        $this->assertEquals('seven', $slugger->slugify('Seven'));
        $this->assertEquals('rrrrrrr-', $slugger->slugify('RRRrrrr !!!'));
        $this->assertEquals('2012', $slugger->slugify('2012'));
        $this->assertEquals('star-wars-episode-iii-la-revanche-des-sith', $slugger->slugify('Star Wars: Episode III - La revanche des Sith'));
    }
}
