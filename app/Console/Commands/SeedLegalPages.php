<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class SeedLegalPages extends Command
{
    protected $signature = 'pages:seed-legal';
    protected $description = 'Seed privacy/cookies/terms pages';

    public function handle(): int
    {
        $privacy = '<h2>1. Introduction</h2><p>Sharzhukov\'s Laboratory ("we", "us") is committed to protecting your privacy. This policy explains how we collect, use, and safeguard your personal data in accordance with the EU General Data Protection Regulation (GDPR).</p>'
            . '<h2>2. Data We Collect</h2><ul>'
            . '<li><strong>Account data:</strong> name, username, email address, hashed password.</li>'
            . '<li><strong>Profile data:</strong> avatar, bio, social links, hardware specifications (optional).</li>'
            . '<li><strong>Usage data:</strong> IP address, user agent, session information.</li>'
            . '<li><strong>Content:</strong> projects, comments, wiki contributions you submit.</li></ul>'
            . '<h2>3. How We Use Your Data</h2><ul>'
            . '<li>To provide and maintain our services.</li>'
            . '<li>To authenticate you across our ecosystem (single sign-on).</li>'
            . '<li>To send transactional emails (verification, password reset, security alerts).</li>'
            . '<li>To moderate content and prevent abuse.</li></ul>'
            . '<h2>4. Data Storage</h2><p>All data is stored on servers located in the Netherlands (EU). We do not transfer your data outside the European Economic Area.</p>'
            . '<h2>5. Your Rights</h2><p>Under GDPR, you have the right to:</p><ul>'
            . '<li>Access your personal data.</li>'
            . '<li>Request correction of inaccurate data.</li>'
            . '<li>Request deletion ("right to be forgotten").</li>'
            . '<li>Export your data in a portable format.</li>'
            . '<li>Object to processing.</li></ul>'
            . '<p>You can exercise these rights from your <a href="https://account-main.sharzhukov.com/security">account settings</a>.</p>'
            . '<h2>6. Data Retention</h2><p>We retain your data while your account is active. Deleted accounts are permanently removed within 30 days.</p>'
            . '<h2>7. Contact</h2><p>For privacy inquiries, contact us via <a href="https://main.sharzhukov.com/contact">our contact form</a>.</p>';

        $cookies = '<h2>1. What Are Cookies</h2><p>Cookies are small text files stored on your device when you visit our websites. They help us provide a functional and secure experience.</p>'
            . '<h2>2. Categories We Use</h2>'
            . '<h3>Necessary (always active)</h3><p>Essential for the website to function. These include session cookies for authentication, CSRF protection, and cookie consent storage. These cannot be disabled.</p>'
            . '<h3>Analytics (optional)</h3><p>Help us understand how visitors use our sites — pages viewed, time spent, referring sources. Used only if you explicitly opt in.</p>'
            . '<h3>Marketing (optional)</h3><p>Used to show relevant content and measure campaign effectiveness. Used only if you explicitly opt in.</p>'
            . '<h2>3. Managing Your Preferences</h2><p>You can change your cookie preferences at any time by clicking the "Cookie Preferences" link in our footer or by clearing your browser cookies and reloading the site.</p>'
            . '<h2>4. Third-Party Services</h2><ul>'
            . '<li><strong>Cloudflare</strong> — CDN and DDoS protection.</li>'
            . '<li><strong>Resend</strong> — transactional email delivery.</li></ul>';

        $terms = '<h2>1. Acceptance of Terms</h2><p>By accessing or using Sharzhukov\'s Laboratory services ("Services"), you agree to these Terms.</p>'
            . '<h2>2. Eligibility</h2><p>You must be at least 16 years old and not located in a restricted region (Russia, Belarus) to use our Services.</p>'
            . '<h2>3. Your Account</h2><p>You are responsible for maintaining the security of your password and all activities that occur under your account.</p>'
            . '<h2>4. Acceptable Use</h2><p>You agree not to violate any laws, post harmful content, or attempt to gain unauthorized access to our systems.</p>'
            . '<h2>5. User Content</h2><p>You retain ownership of content you submit. By submitting, you grant us a non-exclusive license to display it within our Services.</p>'
            . '<h2>6. Open Source</h2><p>Our source code is licensed under MIT or similar permissive licenses.</p>'
            . '<h2>7. Termination</h2><p>We may suspend your access at any time for violation of these Terms.</p>'
            . '<h2>8. Disclaimer</h2><p>The Services are provided "as is" without warranty of any kind.</p>';

        $pages = [
            ['slug' => 'privacy', 'title' => 'Privacy Policy', 'hero_eyebrow' => '// LEGAL', 'hero_title' => 'Privacy Policy', 'hero_subtitle' => 'How we collect, use, and protect your data.', 'meta_description' => 'Privacy Policy for Sharzhukov\'s Laboratory.', 'content' => $privacy, 'is_published' => true],
            ['slug' => 'cookies', 'title' => 'Cookie Policy', 'hero_eyebrow' => '// LEGAL', 'hero_title' => 'Cookie Policy', 'hero_subtitle' => 'What cookies we use and why.', 'meta_description' => 'Cookie Policy for Sharzhukov\'s Laboratory.', 'content' => $cookies, 'is_published' => true],
            ['slug' => 'terms', 'title' => 'Terms of Service', 'hero_eyebrow' => '// LEGAL', 'hero_title' => 'Terms of Service', 'hero_subtitle' => 'Rules for using our services.', 'meta_description' => 'Terms of Service for Sharzhukov\'s Laboratory.', 'content' => $terms, 'is_published' => true],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(['slug' => $data['slug']], $data);
            $this->info("✓ {$data['slug']}");
        }

        $this->info('Total pages: ' . Page::count());
        return 0;
    }
}
