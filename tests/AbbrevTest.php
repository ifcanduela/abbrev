<?php

use ifcanduela\abbrev\Abbrev;

class AbbrevTest extends PHPUnit\Framework\TestCase
{
    public function testMatch()
    {
        $words = [['ape', 'aperture', 'apprentice'], 'albino', 'append'];
        $abbrev = new Abbrev($words);

        $this->assertFalse($abbrev->match('an'));
        $this->assertEquals('albino', $abbrev->match('al'));
    }

    public function testSuggest()
    {
        $words = [['ape', 'aperture', 'apprentice'], 'albino', 'append'];
        $abbrev = new Abbrev($words);

        $this->assertEquals([
                'ape',
                'aperture',
                'append',
                'apprentice',
            ], $abbrev->suggest('ap'));

        $this->assertEquals([
                'append',
                'apprentice',
            ], $abbrev->suggest('app'));
    }

    public function testAbbreviate()
    {
        $words = [['ape', 'aperture', 'apprentice'], 'albino', 'append'];
        $abbrev = new Abbrev($words);

        $expected = [
            'al'         => 'albino',
            'alb'        => 'albino',
            'albi'       => 'albino',
            'albin'      => 'albino',
            'albino'     => 'albino',
            'ape'        => 'ape',
            'aper'       => 'aperture',
            'apert'      => 'aperture',
            'apertu'     => 'aperture',
            'apertur'    => 'aperture',
            'aperture'   => 'aperture',
            'appe'       => 'append',
            'appen'      => 'append',
            'append'     => 'append',
            'appr'       => 'apprentice',
            'appre'      => 'apprentice',
            'appren'     => 'apprentice',
            'apprent'    => 'apprentice',
            'apprenti'   => 'apprentice',
            'apprentic'  => 'apprentice',
            'apprentice' => 'apprentice',
        ];

        $this->assertEquals($expected, $abbrev->abbreviate());
    }

    public function testMixedCase()
    {
        $words = [['ape', 'aperture', 'apprentice'], 'Albino', 'append'];
        $abbrev = new Abbrev($words);

        $this->assertEquals('ape', $abbrev->match('APE'));
        $this->assertEquals('apprentice', $abbrev->match('appR'));
        $this->assertEquals('Albino', $abbrev->match('al'));
    }
}
