<?php

namespace App\Services;

use App\Models\Link;

class SlugGenerator
{
    /**
     * Slug - A short, human-friendly identifier replacing long URLs
     * Generate a unique slug consisting of allowed characters.
     */
    public function generate(int $length = 6): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_-';
        $slug = '';

        do {
            $slug = '';
            for ($i = 0; $i < $length; $i++) {
                $slug .= $characters[random_int(0, strlen($characters) - 1)];
            }
        } while (Link::where('slug', $slug)->exists());

        return $slug;
    }
}
