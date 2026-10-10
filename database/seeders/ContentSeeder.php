<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Faq;
use App\Models\EcosystemLink;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // Products
        $products = [
            ['name' => 'Account', 'tagline' => 'Single sign-on and user center', 'url' => 'https://account-main.sharzhukov.com', 'icon' => 'user-circle', 'sort_order' => 1],
            ['name' => 'Wiki', 'tagline' => 'Documentation for our projects', 'url' => 'https://wiki-main.sharzhukov.com', 'icon' => 'book-open', 'sort_order' => 2],
            ['name' => 'Go', 'tagline' => 'Short links, QR codes, bio pages', 'url' => 'https://go-main.sharzhukov.com', 'icon' => 'link', 'sort_order' => 3],
        ];
        foreach ($products as $p) {
            Product::updateOrCreate(['name' => $p['name']], $p);
        }

        // FAQs
        $faqs = [
            ['question' => 'Is everything really open source?', 'answer' => '<p>Yes. Every project is MIT or similarly permissive. Code is public on GitHub.</p>', 'sort_order' => 1],
            ['question' => 'Where are your servers located?', 'answer' => '<p>Netherlands, EU. We are GDPR-compliant by design.</p>', 'sort_order' => 2],
            ['question' => 'Can I contribute?', 'answer' => '<p>Absolutely. Pick any repository on GitHub, open an issue or PR.</p>', 'sort_order' => 3],
            ['question' => 'How do you make money?', 'answer' => '<p>We don\'t, yet. It\'s a passion project.</p>', 'sort_order' => 4],
            ['question' => 'Do you accept donations?', 'answer' => '<p>Not at the moment. Use, star, share, and contribute — that\'s support enough.</p>', 'sort_order' => 5],
        ];
        foreach ($faqs as $f) {
            Faq::updateOrCreate(['question' => $f['question']], $f);
        }

        // Ecosystem links
        $links = [
            ['group' => 'products', 'label' => 'All projects', 'url' => 'https://main.sharzhukov.com/projects', 'sort_order' => 1],
            ['group' => 'products', 'label' => 'News', 'url' => 'https://main.sharzhukov.com/news', 'sort_order' => 2],
            ['group' => 'products', 'label' => 'Wiki', 'url' => 'https://wiki-main.sharzhukov.com', 'sort_order' => 3, 'is_external' => true],
            ['group' => 'products', 'label' => 'Short links', 'url' => 'https://go-main.sharzhukov.com', 'sort_order' => 4, 'is_external' => true],

            ['group' => 'community', 'label' => 'Discord', 'url' => 'https://discord.gg/sharzhukov', 'sort_order' => 1, 'is_external' => true],
            ['group' => 'community', 'label' => 'Telegram', 'url' => 'https://t.me/sharzhukov', 'sort_order' => 2, 'is_external' => true],
            ['group' => 'community', 'label' => 'GitHub', 'url' => 'https://github.com/SharzhukovLab', 'sort_order' => 3, 'is_external' => true],

            ['group' => 'resources', 'label' => 'Documentation', 'url' => 'https://wiki-main.sharzhukov.com', 'sort_order' => 1, 'is_external' => true],
            ['group' => 'resources', 'label' => 'Blog', 'url' => 'https://main.sharzhukov.com/news', 'sort_order' => 2],
            ['group' => 'resources', 'label' => 'Sign up', 'url' => 'https://account-main.sharzhukov.com/register', 'sort_order' => 3, 'is_external' => true],
            ['group' => 'resources', 'label' => 'Sign in', 'url' => 'https://account-main.sharzhukov.com/login', 'sort_order' => 4, 'is_external' => true],

            ['group' => 'legal', 'label' => 'Privacy', 'url' => 'https://main.sharzhukov.com/privacy', 'sort_order' => 1, 'is_external' => true],
            ['group' => 'legal', 'label' => 'Cookies', 'url' => 'https://main.sharzhukov.com/cookies', 'sort_order' => 2, 'is_external' => true],
            ['group' => 'legal', 'label' => 'Terms', 'url' => 'https://main.sharzhukov.com/terms', 'sort_order' => 3, 'is_external' => true],
        ];
        foreach ($links as $l) {
            EcosystemLink::updateOrCreate(['group' => $l['group'], 'label' => $l['label']], $l);
        }

        echo "Content seeded: " . Product::count() . " products, " . Faq::count() . " faqs, " . EcosystemLink::count() . " links\n";
    }
}