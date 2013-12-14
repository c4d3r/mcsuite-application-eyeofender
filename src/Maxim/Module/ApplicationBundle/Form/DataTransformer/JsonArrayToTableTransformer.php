<?php
/**
 * Author: Maxim
 * Date: 12/12/13
 * Time: 20:15
 * Property of MCSuite
 */

namespace Maxim\Module\ApplicationBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
class JsonArrayToTableTransformer implements DataTransformerInterface
{
    public function transform($jsonarray)
    {
        var_dump($jsonarray);
        $output = "<div>";
        foreach($jsonarray as $row)
        {
            $output .= "<h4>" . $row[0] . "<h4>" . "<p>" . $row[1] . "</p>";
        }
        return $output;
    }

    public function reverseTransform($number)
    {
        return null;
    }
} 