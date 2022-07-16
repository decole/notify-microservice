<?php


namespace App\Tests\unit\Application\Helper;


use App\Application\Helper\StringHelper;
use App\Tests\UnitTester;

class StringHelperCest
{
    public function sanitizeNull(UnitTester $I): void
    {
        $value = StringHelper::sanitize(null);

        $I->assertEquals(null, $value);
    }

    public function sanitizeEmptyString(UnitTester $I): void
    {
        $value = StringHelper::sanitize('');

        $I->assertEquals(null, $value);
    }

    public function sanitizeSimpleString(UnitTester $I): void
    {
        $string = 'lolypop';

        $value = StringHelper::sanitize($string);

        $I->assertEquals($string, $value);
    }

    public function sanitizeOwaspString(UnitTester $I): void
    {
        $string = "<b onmouseover=alert('Wufff!')>click me!</b>";

        $value = StringHelper::sanitize($string);

        $I->assertNotEquals($string, $value);
    }

    public function sanitizeOwaspExtendedString(UnitTester $I): void
    {
        $string = "<IMG SRC=j&#X41vascript:alert('test2')>";

        $value = StringHelper::sanitize($string);

        $I->assertNotEquals($string, $value);
    }

    public function sanitizeOwaspIlluminateString(UnitTester $I): void
    {
        $string = "<SCRIPT type=\"text/javascript\">var adr = '../evil.php?cakemonster=' + escape(document.cookie);</SCRIPT>";

        $value = StringHelper::sanitize($string);

        $I->assertNotEquals($string, $value);
    }

    public function sanitizeSqlInjectionString(UnitTester $I): void
    {
        $string = "1' or '1' = '1";

        $value = StringHelper::sanitize($string);

        $I->assertNotEquals($string, $value);
    }
}