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
                'content' => '
    <p>Customers take full responsibility for ordering items and all associated substitutions. Information regarding spare parts and substitutions is for reference purposes only.</p>
    <div class="legal-alert mt-3">
        <strong>Substitution Policy:</strong> If a customer orders a discontinued part, AutoSpareLink reserves the right to supply an <strong>OEM substitution</strong> part number.
    </div>
    <br>
    <p>By placing an order, the customer confirms full responsibility for the correctness and suitability of the part numbers selected.</p>
',
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