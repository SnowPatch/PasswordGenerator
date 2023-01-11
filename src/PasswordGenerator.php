<?php

namespace SnowPatch;

class PasswordGenerator 
{
    private $excludes = [];

    private $keyspaces = [
        'lowercase' => 'abcdefghijklmnopqrstuvwxyz',
        'uppercase' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        'numbers' => '0123456789',
        'symbols' => '_.:!?#%&@$-',
    ];

    public function withoutNumbers() 
    {
        $this->excludes[] = 'numbers';
        return $this;
    }

    public function withoutSymbols() 
    {
        $this->excludes[] = 'symbols';
        return $this;
    }

    private function getKeyspace() 
    {
        $keyspace = '';

        foreach ($this->keyspaces as $name => $characters) {

            if (in_array($name, $this->excludes)) {
                continue;
            }

            $keyspace .= $characters;

        }

        return $keyspace;
    }

    public function generate(int $length = 16) 
    {
        $keyspace = $this->getKeyspace();
        $max = mb_strlen($keyspace, 'UTF-8') - 1;

        if (!$length) {
            throw new \Exception('Passwords must be at least 1 character in length');
        }

        if (!$keyspace) {
            throw new \Exception('Unable to generate passwords with an empty keyspace');
        }

        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $keyspace[random_int(0, $max)];
        }

        return $password;
    }
}
