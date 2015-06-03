<?php

namespace Omnipay\Barzahlen;

class Hasher {

    const HASH_ALGORITHM = 'sha512';
    const SEPARATOR = ';';

    /**
     * Hash an array with the given key.
     *
     * @param $hashArray
     * @param $key
     *
     * @return string
     */
    public static function fromArray($hashArray, $key)
    {
        $hashArray = self::onlyHashableKeys($hashArray);

        $hashArray[] = $key;

        $hashString = implode(self::SEPARATOR, $hashArray);

        return hash(self::HASH_ALGORITHM, $hashString);
    }

    /**
     * Get an array with only the keys relevant for hashing.
     *
     * @param       $array
     * @param array $ignoreKeys
     *
     * @return array
     */
    protected static function onlyHashableKeys($array, $ignoreKeys = []) {
        $ignoreKeys = array_merge(['hash', 'due_date'], $ignoreKeys);

        return array_diff_key($array, array_flip($ignoreKeys));
    }
}
