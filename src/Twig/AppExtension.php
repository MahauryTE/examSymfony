<?php

namespace App\Twig;


use App\Entity\Fight;
use phpDocumentor\Reflection\Types\Boolean;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('show_date', [$this, 'showDate'])
        ];
    }

    public function getFunctions()
    {
        return [
        ];
    }

    public function showDate(\DateTime $date)
    {
        return $date->format('d/m/Y');
    }
}