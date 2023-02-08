<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ResumExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ResumExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('myExerpt', [$this, 'excerption']),
        ];
    }

    public function excerption($text, $maxLength = 200, $elipsis = '...')
    {

        if(strlen($text) <= $maxLength) // si inferieur je retourne le texte en entier
            return $text;
        $resume = substr($text, 0, $maxLength- strlen($elipsis) )
                        .$elipsis;

        return $resume;
    }

//    public function getFunctions(): array
//    {
//        return [
//            new TwigFunction('function_name', [ResumExtensionRuntime::class, 'doSomething']),
//        ];
//    }
}
