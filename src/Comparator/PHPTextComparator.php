<?php
namespace Comparator;

use Bulkdozer\Comparator\TextComparator;

class PHPTextComparator implements TextComparator
{
    const MINIMUM_SIMILARITY = 95.0;

    public function isSimilar($string1, $string2)
    {
        return ($this->getTextSimilarity($string1,$string2) >= static::MINIMUM_SIMILARITY);
    }

    protected function getTextSimilarity($string1, $string2)
    {
        similar_text($string1, $string2, $percent);

        return ($percent);
    }
}