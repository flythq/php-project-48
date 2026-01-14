<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;
use function Differ\Utils\getFileContent;

class DifferTest extends TestCase
{
    private $diff;
    public function setUp(): void
    {
        $this->diff = getFileContent('diff.txt');
    }
    public function testDiff()
    {
        $this->assertEquals($this->diff, genDiff('file1.json', 'file2.json'));
    }
}
