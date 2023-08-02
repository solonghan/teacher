<?php

namespace App\Helpers\Hasher;

use Illuminate\Contracts\Hashing\Hasher;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Hashing\AbstractHasher;

class MD5Hasher extends AbstractHasher implements HasherContract
{
    public function check($value, $hashedValue, array $options = [])
    {

        return $this->make($value) === $hashedValue;
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }

    public function make($value, array $options = [])
    {
        return md5($value);
    }
}
