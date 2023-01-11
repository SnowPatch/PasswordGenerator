<?php

use PHPUnit\Framework\TestCase;
use SnowPatch\PasswordGenerator;

final class PasswordGeneratorTest extends TestCase 
{
    public function testPasswordGenerationMethodExists() 
    {
        $class = new PasswordGenerator();
        $exists = method_exists($class, 'generate');

        $this->assertTrue($exists, 'The generate() method does not exist');
    }

    public function testPasswordGeneration() 
    {
        $passgen = new PasswordGenerator();
        $password = $passgen->generate();

        $this->assertNotNull($password, 'Generated password is null');
        $this->assertNotEmpty($password, 'Generated password is empty');
    }

    public function testPasswordNotDuplicates() 
    {
        $passgen = new PasswordGenerator();

        $one = $passgen->generate();
        $two = $passgen->generate();

        $this->assertNotEquals($one, $two, 'Generated passwords are duplicates');
    }

    public function testPasswordLength() 
    {
        $passgen = new PasswordGenerator();

        $short = $passgen->generate(4);
        $medium = $passgen->generate(22);
        $long = $passgen->generate(160);

        $this->assertEquals(4, mb_strlen($short, 'UTF-8'), 'Password length does not meet expectations');
        $this->assertEquals(22, mb_strlen($medium, 'UTF-8'), 'Password length does not meet expectations');
        $this->assertEquals(160, mb_strlen($long, 'UTF-8'), 'Password length does not meet expectations');
    }

    public function testZeroLengthPasswordsThrowingException() 
    {
        $this->expectException(Exception::class);

        $passgen = new PasswordGenerator();
        $password = $passgen->generate(0);
    }

    public function testPasswordStrength() {

        $passgen = new PasswordGenerator();
        $password = $passgen->generate(160);

        $contains_lowercase = (bool)preg_match('/[a-z]/', $password);
        $contains_uppercase = (bool)preg_match('/[A-Z]/', $password);
        $contains_numbers = (bool)preg_match('/[0-9]/', $password);
        $contains_symbols = (bool)preg_match('/[_.:!?#%&@$-]/', $password);

        $this->assertTrue($contains_lowercase, 'Password does not contain lowercase characters');
        $this->assertTrue($contains_uppercase, 'Password does not contain uppercase characters');
        $this->assertTrue($contains_numbers, 'Password does not contain numbers');
        $this->assertTrue($contains_symbols, 'Password does not contain symbols');

    }

    public function testPasswordWithoutNumbers() 
    {
        $passgen = new PasswordGenerator();
        $password = $passgen->withoutNumbers()->generate(160);

        $contains_lowercase = (bool)preg_match('/[a-z]/', $password);
        $contains_uppercase = (bool)preg_match('/[A-Z]/', $password);
        $contains_numbers = (bool)preg_match('/[0-9]/', $password);
        $contains_symbols = (bool)preg_match('/[_.:!?#%&@$-]/', $password);

        $this->assertTrue($contains_lowercase, 'Password no longer contains lowercase characters');
        $this->assertTrue($contains_uppercase, 'Password no longer contains uppercase characters');
        $this->assertFalse($contains_numbers, 'Password still contains numbers');
        $this->assertTrue($contains_symbols, 'Password no longer contains symbols');
    }

    public function testPasswordWithoutSymbols() 
    {
        $passgen = new PasswordGenerator();
        $password = $passgen->withoutSymbols()->generate(160);

        $contains_lowercase = (bool)preg_match('/[a-z]/', $password);
        $contains_uppercase = (bool)preg_match('/[A-Z]/', $password);
        $contains_numbers = (bool)preg_match('/[0-9]/', $password);
        $contains_symbols = (bool)preg_match('/[_.:!?#%&@$-]/', $password);

        $this->assertTrue($contains_lowercase, 'Password no longer contains lowercase characters');
        $this->assertTrue($contains_uppercase, 'Password no longer contains uppercase characters');
        $this->assertTrue($contains_numbers, 'Password no longer contains numbers');
        $this->assertFalse($contains_symbols, 'Password still contains symbols');
    }

    public function testPasswordWithoutNumbersAndSymbols() 
    {
        $passgen = new PasswordGenerator();
        $password = $passgen->withoutNumbers()->withoutSymbols()->generate(160);

        $contains_lowercase = (bool)preg_match('/[a-z]/', $password);
        $contains_uppercase = (bool)preg_match('/[A-Z]/', $password);
        $contains_numbers = (bool)preg_match('/[0-9]/', $password);
        $contains_symbols = (bool)preg_match('/[_.:!?#%&@$-]/', $password);

        $this->assertTrue($contains_lowercase, 'Password no longer contains lowercase characters');
        $this->assertTrue($contains_uppercase, 'Password no longer contains uppercase characters');
        $this->assertFalse($contains_numbers, 'Password still contains numbers');
        $this->assertFalse($contains_symbols, 'Password still contains symbols');
    }
}
