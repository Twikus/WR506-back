<?php

namespace App\Services;

use Symfony\Component\String\Slugger\AsciiSlugger;

class SlugifyService
{
    private $slugger;

    public function __construct()
    {
        $this->slugger = new AsciiSlugger();
    }

    public function slugify(string $phrase): string
    {
        return $this->slugger->slug($phrase)->lower()->toString();
    }
}
