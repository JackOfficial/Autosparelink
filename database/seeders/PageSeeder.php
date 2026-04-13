<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Terms & Conditions',
                'slug'  => 'terms-and-conditions',
                'content' => '<h3>1. Introduction</h3><p>Welcome to AutoSpareLink...</p>',
            ],
            [
                'title' => 'Privacy Policies',
                'slug'  => 'policies',
                'content' => '<h3>Data Protection</h3><p>We value your privacy...</p>',
            ],
            [
                'title' => 'Frequently Asked Questions',
                'slug'  => 'faqs',
                // For the FAQ, we seed an empty JSON array so the Alpine.js builder works immediately
                'content' => json_encode([
                    [
                        'cat' => 'General',
                        'q'   => 'Welcome to our FAQ!',
                        'a'   => 'You can edit this content from the admin panel.'
                    ]
                ]),
            ],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}