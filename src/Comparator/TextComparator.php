<?php
namespace Bulkdozer\Comparator;

interface TextComparator
{
    /**
     * @param $string1
     * @param $string2
     * @return boolean
     */
    public function isSimilar($string1, $string2);
}