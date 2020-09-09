<?php

namespace App\Tests\Service;

use App\Service\ImageUploader;
use PHPUnit\Framework\TestCase;

class ImageUploaderTest extends TestCase
{
    public function testGetRandomFileName()
    {
        $imageUploader = new ImageUploader();

        $randomFileName = $imageUploader->getRandomFileName('jpg');

        $this->assertIsString($randomFileName);
        $this->assertEquals(16, strlen($randomFileName));
        $this->assertStringEndsWith('.jpg', $randomFileName);
    }
}
