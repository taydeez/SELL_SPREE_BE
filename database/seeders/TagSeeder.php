<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    private const TAGS = [
        // eBook
        'Personal Finance', 'Self Improvement', 'Productivity', 'Business', 'Marketing',
        'Copywriting', 'SEO', 'Entrepreneurship', 'Health & Wellness', 'Mindset',

        // Music
        'Afrobeats', 'Hip Hop', 'Gospel', 'R&B', 'Amapiano',
        'Instrumental', 'Beat Pack', 'Sample Pack', 'Lofi', 'Trap',

        // Video
        'Tutorial', 'Masterclass', 'Documentary', 'Short Film', 'Vlog',
        'Comedy', 'Motivational', 'Behind the Scenes', 'Recap', 'Animation',

        // Code
        'Laravel', 'Vue', 'Nuxt', 'React', 'Node.js',
        'Python', 'API', 'Starter Kit', 'Dashboard', 'Boilerplate',
        'WordPress', 'Shopify', 'Mobile App', 'CLI Tool', 'Chrome Extension',

        // Tickets
        'Conference', 'Workshop', 'Webinar', 'Meetup', 'Concert',
        'Bootcamp', 'Hackathon', 'Networking', 'Comedy Show', 'Seminar',
    ];

    public function run(): void
    {
        foreach (self::TAGS as $name) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            );
        }

        $this->command->info('Tags seeded (' . Tag::count() . ' total).');

        // Attach 1–4 random tags to every existing product
        $tags = Tag::all();

        if ($tags->isEmpty() || Product::count() === 0) {
            return;
        }

        Product::each(function (Product $product) use ($tags) {
            $product->tags()->syncWithoutDetaching(
                $tags->random(fake()->numberBetween(1, 4))->pluck('id')->all()
            );
        });

        $this->command->info('Tags attached to products.');
    }
}
