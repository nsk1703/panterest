<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/*
 * Permet de pluraliser un mot selon l'etat du compteur.
 */
class AppExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'pluralize']),
        ];
    }

    public function pluralize(int $count, string $singular, ?string $plural = null): string
    {
        $plural = $plural ?? $singular.'s';

        $str = $count === 1 ?$singular : $plural;
        return "$count $str";
    }
}
