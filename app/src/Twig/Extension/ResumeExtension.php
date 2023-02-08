<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ResumeExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ResumeExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('myExerpt', [$this, 'exerption']),
        ];
    }

    public function exerption($text, $maxLength = 200, $elipsis = '...')
    {
        if (strlen($text) <= $maxLength) // Si inférieur je retourne le text en entier
            return $text;   

        $resume = substr($text, 0, $maxLength - strlen($elipsis)).$elipsis;

        return $resume;
    }

    // public function getFunctions(): array
    // {
    //     return [
    //         new TwigFunction('function_name', [ResumeExtensionRuntime::class, 'doSomething']),
    //     ];
    // }


}
