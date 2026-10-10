<?php
require '/var/www/main_shrz_usr/data/www/main.sharzhukov.com/vendor/autoload.php';
$app = require '/var/www/main_shrz_usr/data/www/main.sharzhukov.com/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Page;

// ─── КОНФИГ ───
$C = [
    'name'           => "Sharzhukov's Laboratory",
    'legal_form'     => 'a sole proprietor established in the Netherlands',
    'kvk'            => null,
    'vat'            => null,
    'address'        => 'Netherlands',
    'contact_email'  => 'admin@sharzhukov.com',
    'privacy_email'  => 'faq@sharzhukov.com',
    'dpa_name'       => 'Autoriteit Persoonsgegevens',
    'dpa_url'        => 'https://autoriteitpersoonsgegevens.nl',
    'effective_date' => '11 October 2026',
    'version'        => '1.0',
];

// ─── Предвычисление всех условных строк (heredoc не умеет тернарники) ───
$name          = $C['name'];
$legalForm     = $C['legal_form'];
$address       = $C['address'];
$contactEmail  = $C['contact_email'];
$privacyEmail  = $C['privacy_email'];
$dpaName       = $C['dpa_name'];
$dpaUrl        = $C['dpa_url'];
$effDate       = $C['effective_date'];
$version       = $C['version'];

$kvkLi = $C['kvk'] ? "<li><strong>KvK number:</strong> {$C['kvk']}</li>" : '';
$vatLi = $C['vat'] ? "<li><strong>VAT number:</strong> {$C['vat']}</li>" : '';
$kvkLiPrivacy = $kvkLi;
$vatLiPrivacy = $vatLi;

$privacyMailLink = '<a href="mailto:' . $privacyEmail . '">' . $privacyEmail . '</a>';
$contactMailLink = '<a href="mailto:' . $contactEmail . '">' . $contactEmail . '</a>';

// ═══════════════ 1. PRIVACY POLICY ═══════════════
$privacyContent = <<<HTML
<p><em>Last updated: {$effDate} · Version {$version}</em></p>

<p>This Privacy Policy explains how <strong>{$name}</strong> ("we", "us", "our"), {$legalForm}, collects, uses, stores, and protects your personal data when you use our websites and services. We are the <strong>data controller</strong> within the meaning of the EU General Data Protection Regulation (GDPR, Regulation (EU) 2016/679) and the Dutch GDPR Implementation Act (UAVG).</p>

<h2>1. Who we are (Controller)</h2>
<ul>
    <li><strong>Controller:</strong> {$name}</li>
    <li><strong>Address:</strong> {$address}</li>
    {$kvkLiPrivacy}
    {$vatLiPrivacy}
    <li><strong>Contact:</strong> {$contactMailLink}</li>
    <li><strong>Privacy contact:</strong> {$privacyMailLink}</li>
</ul>

<h2>2. What data we collect</h2>

<h3>2.1 Data you provide directly</h3>
<ul>
    <li><strong>Account data:</strong> username, email address, password (stored as a one-way hash), display name, optional avatar image, locale, time zone.</li>
    <li><strong>Authentication data:</strong> two-factor authentication secrets, WebAuthn passkeys (public key + metadata only — we never see your private key), recovery codes (hashed).</li>
    <li><strong>Content you submit:</strong> news posts, comments, wiki pages and revisions, user projects, short links, contact messages, newsletter subscription email.</li>
    <li><strong>Communication data:</strong> messages you send us via contact forms or email.</li>
</ul>

<h3>2.2 Data collected automatically</h3>
<ul>
    <li><strong>Technical data:</strong> IP address, user agent (browser and OS), referrer, requested URL, HTTP status, timestamp.</li>
    <li><strong>Session data:</strong> session identifier, CSRF token, last activity time.</li>
    <li><strong>Security data:</strong> login attempts, failed authentication events, rate-limit counters.</li>
    <li><strong>Cookie consent records:</strong> your consent choices, timestamp, IP address, user agent, and locale — stored as a legal audit trail as required by the GDPR.</li>
</ul>

<h3>2.3 Data we do NOT collect</h3>
<p>We do not collect special categories of personal data (Art. 9 GDPR). We do not perform automated decision-making or profiling. We do not sell your personal data.</p>

<h2>3. Why we process your data (Legal bases)</h2>
<p>Under Art. 6 GDPR, we process your personal data on the following legal bases:</p>

<table>
    <thead>
        <tr><th>Purpose</th><th>Legal basis</th></tr>
    </thead>
    <tbody>
        <tr><td>Creating and managing your account; providing the service</td><td>Performance of a contract — Art. 6(1)(b)</td></tr>
        <tr><td>Authentication, password reset, two-factor, passkeys</td><td>Performance of a contract — Art. 6(1)(b)</td></tr>
        <tr><td>Publishing content you choose to publish</td><td>Performance of a contract — Art. 6(1)(b)</td></tr>
        <tr><td>Security, abuse prevention, rate limiting, audit logs</td><td>Legitimate interest — Art. 6(1)(f)</td></tr>
        <tr><td>Transactional email (verification, password reset, backup alerts)</td><td>Performance of a contract — Art. 6(1)(b)</td></tr>
        <tr><td>Newsletter (double opt-in)</td><td>Consent — Art. 6(1)(a)</td></tr>
        <tr><td>Analytics cookies</td><td>Consent — Art. 6(1)(a)</td></tr>
        <tr><td>Marketing cookies</td><td>Consent — Art. 6(1)(a)</td></tr>
        <tr><td>Cookie consent audit trail</td><td>Legal obligation — Art. 6(1)(c)</td></tr>
    </tbody>
</table>

<h2>4. How long we keep your data (Retention)</h2>
<ul>
    <li><strong>Account data:</strong> until you delete your account, then up to 30 days in backups for recovery purposes; afterwards permanently erased.</li>
    <li><strong>Content:</strong> until you delete it or delete your account (deleted content enters a 30-day soft-delete window, then is purged from backups).</li>
    <li><strong>Server logs:</strong> up to 30 days, then automatically rotated and deleted.</li>
    <li><strong>Security events (login attempts):</strong> up to 90 days.</li>
    <li><strong>Cookie consent records:</strong> 3 years (statute of limitations for GDPR complaints).</li>
    <li><strong>Newsletter subscription:</strong> until you unsubscribe, plus 30 days in backups.</li>
    <li><strong>Database backups:</strong> 7 daily, 16 daily, 8 weekly, and 4 monthly — oldest rotated out automatically.</li>
</ul>

<h2>5. Who we share your data with (Processors and recipients)</h2>
<p>We use a small number of trusted service providers ("processors"). We have Data Processing Agreements (DPAs) in place with each of them.</p>

<table>
    <thead>
        <tr><th>Provider</th><th>Purpose</th><th>Location</th></tr>
    </thead>
    <tbody>
        <tr><td>Aeza</td><td>Server hosting</td><td>Netherlands (EU)</td></tr>
        <tr><td>Cloudflare</td><td>DNS, CDN, DDoS protection</td><td>EU / USA (SCC)</td></tr>
        <tr><td>Resend</td><td>Transactional and newsletter email delivery</td><td>USA (SCC)</td></tr>
        <tr><td>GitHub</td><td>Private source-code hosting (no user data)</td><td>USA (SCC)</td></tr>
    </tbody>
</table>

<p>We do not sell, rent, or trade your personal data. We disclose data to law enforcement only when legally required and after review.</p>

<h2>6. International transfers</h2>
<p>Some of our processors (Cloudflare, Resend, GitHub) are based in the United States. Where personal data is transferred outside the EEA, we rely on the <strong>EU Standard Contractual Clauses</strong> (Commission Implementing Decision (EU) 2021/914) and, where applicable, the EU–US Data Privacy Framework. You can request a copy of the safeguards we use by emailing {$privacyMailLink}.</p>

<h2>7. Your rights under GDPR</h2>
<p>You have the following rights:</p>
<ul>
    <li><strong>Right of access</strong> (Art. 15) — obtain a copy of your personal data.</li>
    <li><strong>Right to rectification</strong> (Art. 16) — correct inaccurate data.</li>
    <li><strong>Right to erasure</strong> ("right to be forgotten", Art. 17) — delete your data.</li>
    <li><strong>Right to restriction</strong> (Art. 18) — limit processing.</li>
    <li><strong>Right to data portability</strong> (Art. 20) — receive your data in a machine-readable format.</li>
    <li><strong>Right to object</strong> (Art. 21) — object to processing based on legitimate interest.</li>
    <li><strong>Right to withdraw consent</strong> (Art. 7(3)) — at any time, without affecting prior processing.</li>
    <li><strong>Right to lodge a complaint</strong> with a supervisory authority.</li>
</ul>

<p>To exercise any of these rights, email {$privacyMailLink}. We will respond within <strong>30 days</strong> as required by Art. 12(3) GDPR. We may ask you to verify your identity before acting on your request.</p>

<h3>Supervisory authority</h3>
<p>You have the right to lodge a complaint with the Dutch Data Protection Authority:</p>
<ul>
    <li><strong>{$dpaName}</strong></li>
    <li><a href="{$dpaUrl}" rel="noopener" target="_blank">{$dpaUrl}</a></li>
</ul>

<h2>8. Security</h2>
<p>We implement appropriate technical and organisational measures (Art. 32 GDPR), including:</p>
<ul>
    <li>HTTPS/TLS encryption for all traffic (HSTS enabled).</li>
    <li>Password hashing with bcrypt/argon2.</li>
    <li>Two-factor authentication (TOTP) and WebAuthn passkeys.</li>
    <li>Rate limiting and brute-force protection.</li>
    <li>Regular automated backups, encrypted in transit.</li>
    <li>Principle of least privilege on the server.</li>
</ul>
<p>In case of a personal data breach affecting your rights, we will notify the {$dpaName} within 72 hours and inform affected users without undue delay, as required by Art. 33–34 GDPR.</p>

<h2>9. Children</h2>
<p>Our services are not directed to children under 16. If we become aware that we have collected personal data from a child under 16 without parental consent, we will delete it promptly.</p>

<h2>10. Cookies</h2>
<p>We use cookies and similar technologies. See our <a href="/cookies">Cookie Policy</a> for details on categories, purposes, and how to manage them.</p>

<h2>11. Changes to this Policy</h2>
<p>We may update this Policy from time to time. Material changes will be announced on this page and, where required, by email. The "Last updated" date at the top indicates the latest version.</p>

<h2>12. Contact</h2>
<p>For any privacy-related question or request:</p>
<ul>
    <li>Email: {$privacyMailLink}</li>
    <li>General: {$contactMailLink}</li>
</ul>
HTML;

// ═══════════════ 2. COOKIE POLICY ═══════════════
$cookiesContent = <<<HTML
<p><em>Last updated: {$effDate} · Version {$version}</em></p>

<p>This Cookie Policy explains how <strong>{$name}</strong> uses cookies and similar technologies (localStorage, sessionStorage) on its websites. It should be read together with our <a href="/privacy">Privacy Policy</a>.</p>

<p>Under EU and Dutch law, we may only store or access non-essential cookies on your device after obtaining your <strong>prior consent</strong> (Art. 11.7a Dutch Telecommunicatiewet and Art. 5(3) ePrivacy Directive).</p>

<h2>1. What are cookies?</h2>
<p>Cookies are small text files placed on your device by a website you visit. They allow the site to recognise your browser, keep you logged in, remember your preferences, and — if you consent — measure usage.</p>

<h2>2. Categories of cookies we use</h2>

<h3>2.1 Strictly necessary (always active)</h3>
<p>These cookies are required for the website to function. They cannot be disabled. They do not require consent.</p>

<table>
    <thead>
        <tr><th>Name</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr>
    </thead>
    <tbody>
        <tr><td>sharzhukov_session</td><td>{$name}</td><td>User authentication and session state</td><td>12 hours</td></tr>
        <tr><td>XSRF-TOKEN</td><td>{$name}</td><td>CSRF protection</td><td>Session</td></tr>
        <tr><td>sharzhukov_cookie_consent</td><td>{$name}</td><td>Stores your cookie preferences</td><td>1 year</td></tr>
        <tr><td>__cf_bm</td><td>Cloudflare</td><td>Bot management and security</td><td>30 minutes</td></tr>
        <tr><td>cf_clearance</td><td>Cloudflare</td><td>Anti-DDoS challenge clearance</td><td>30 minutes – 1 year</td></tr>
    </tbody>
</table>

<h3>2.2 Analytics (require consent)</h3>
<p>These help us understand how visitors use the site so we can improve it. They are <strong>only set after you accept analytics cookies</strong>.</p>

<table>
    <thead>
        <tr><th>Name</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr>
    </thead>
    <tbody>
        <tr><td colspan="4"><em>Currently no analytics cookies are actively used. If we enable Google Analytics or a self-hosted alternative (e.g. Plausible), it will appear here and only load after consent.</em></td></tr>
    </tbody>
</table>

<h3>2.3 Marketing (require consent)</h3>
<p>These would be used to show you relevant content or ads on third-party platforms. They are <strong>only set after you accept marketing cookies</strong>.</p>

<table>
    <thead>
        <tr><th>Name</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr>
    </thead>
    <tbody>
        <tr><td colspan="4"><em>Currently no marketing cookies are in use.</em></td></tr>
    </tbody>
</table>

<h2>3. How to manage your consent</h2>
<p>When you first visit the site, a banner allows you to:</p>
<ul>
    <li><strong>Accept all</strong> — enable necessary + analytics + marketing;</li>
    <li><strong>Reject all</strong> — enable only necessary;</li>
    <li><strong>Preferences</strong> — choose each category individually.</li>
</ul>
<p>You can <strong>change or withdraw your consent at any time</strong> by clicking the "Cookie settings" link in the footer of any page. Withdrawal does not affect the lawfulness of processing based on consent before its withdrawal.</p>

<p>Most browsers also allow you to block or delete cookies through their settings. Please note that blocking necessary cookies will break core functionality.</p>

<h2>4. Legal basis</h2>
<p>Processing of data via necessary cookies is based on our legitimate interest in providing a functional and secure website (Art. 6(1)(f) GDPR). Processing via analytics and marketing cookies is based on your consent (Art. 6(1)(a) GDPR).</p>

<h2>5. Changes</h2>
<p>We may update this Cookie Policy. The "Last updated" date above reflects the latest version. Material changes will be communicated via the consent banner.</p>

<h2>6. Contact</h2>
<p>Questions about cookies? Email {$privacyMailLink}.</p>
HTML;

// ═══════════════ 3. TERMS OF SERVICE ═══════════════
$termsContent = <<<HTML
<p><em>Last updated: {$effDate} · Version {$version}</em></p>

<p>These Terms of Service ("Terms") govern your access to and use of the websites and services provided by <strong>{$name}</strong> ("we", "us", "our"). By creating an account or using the services, you agree to these Terms. If you do not agree, do not use the services.</p>

<h2>1. Who we are</h2>
<ul>
    <li><strong>Service provider:</strong> {$name}, {$legalForm}</li>
    <li><strong>Address:</strong> {$address}</li>
    {$kvkLi}
    {$vatLi}
    <li><strong>Contact:</strong> {$contactMailLink}</li>
</ul>

<h2>2. Eligibility</h2>
<p>You must be at least 16 years old to create an account. If you are under 18, you confirm that you have permission from a parent or legal guardian. We do not knowingly allow registration from users in jurisdictions subject to EU sanctions, and we may block access from regions restricted for security reasons.</p>

<h2>3. Your account</h2>
<ul>
    <li>You are responsible for maintaining the confidentiality of your credentials and passkeys.</li>
    <li>You agree to provide accurate registration information and to keep it up to date.</li>
    <li>You are responsible for all activity that occurs under your account.</li>
    <li>Notify us immediately at {$contactMailLink} if you suspect unauthorised access.</li>
    <li>We may suspend or terminate accounts that violate these Terms.</li>
</ul>

<h2>4. Acceptable use</h2>
<p>You agree <strong>not</strong> to:</p>
<ul>
    <li>Violate any applicable law or the rights of others;</li>
    <li>Post content that is unlawful, defamatory, hateful, sexually explicit, or infringes intellectual property;</li>
    <li>Upload malware, conduct phishing, or attempt to compromise the security of the services;</li>
    <li>Use automated tools to scrape, overload, or reverse-engineer the services;</li>
    <li>Impersonate another person or misrepresent your affiliation;</li>
    <li>Use the short-link service to distribute spam, malware, or content that violates these Terms;</li>
    <li>Attempt to access accounts or data that are not yours.</li>
</ul>

<h2>5. Your content</h2>
<p>You retain ownership of content you create and upload ("Your Content"). By publishing Your Content, you grant us a <strong>non-exclusive, worldwide, royalty-free licence</strong> to host, store, reproduce, and display it solely for the purpose of operating and improving the services.</p>
<p>You represent and warrant that you own or have all necessary rights to Your Content, and that it does not infringe the rights of any third party.</p>
<p>We may remove content that violates these Terms or applicable law, without prior notice.</p>

<h2>6. Intellectual property</h2>
<p>The services, including their source code (where published), design, and branding, are owned by us or our licensors. Open-source components are governed by their respective licences. You may not use our trademarks without prior written permission.</p>

<h2>7. Third-party services</h2>
<p>The services rely on third-party providers (hosting, CDN, email delivery — see <a href="/privacy">Privacy Policy</a> for details). We are not responsible for outages or acts of third parties beyond our reasonable control.</p>

<h2>8. Availability and changes</h2>
<p>The services are provided on an "as is" and "as available" basis. We may modify, suspend, or discontinue any part of the services at any time, with reasonable notice where practical. We do not guarantee continuous, error-free, or secure availability.</p>

<h2>9. Disclaimer of warranties</h2>
<p>To the maximum extent permitted by law, the services are provided <strong>without warranties of any kind</strong>, express or implied, including but not limited to merchantability, fitness for a particular purpose, and non-infringement. Nothing in these Terms excludes mandatory consumer rights under Dutch or EU law.</p>

<h2>10. Limitation of liability</h2>
<p>To the maximum extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits, data, or goodwill. Our total aggregate liability for any claim arising from or relating to these Terms is limited to <strong>€100</strong> or the amount you paid us in the 12 months preceding the claim, whichever is greater.</p>
<p>Nothing in these Terms limits liability that cannot be limited under applicable law (including liability for intent or gross negligence).</p>

<h2>11. Indemnification</h2>
<p>You agree to indemnify and hold us harmless from any claims, damages, or expenses arising out of your use of the services or breach of these Terms.</p>

<h2>12. Termination</h2>
<p>You may delete your account at any time from your <a href="https://account-main.sharzhukov.com/dashboard">account dashboard</a>. We may suspend or terminate your access for material breach of these Terms, illegal activity, or as required by law.</p>
<p>On termination, sections that by their nature should survive (intellectual property, disclaimers, liability, governing law) will remain in effect.</p>

<h2>13. Governing law and jurisdiction</h2>
<p>These Terms are governed by the laws of the <strong>Netherlands</strong>, excluding its conflict-of-law rules and the UN Convention on Contracts for the International Sale of Goods.</p>
<p>Any dispute arising out of or relating to these Terms shall be submitted to the exclusive jurisdiction of the competent court in <strong>Amsterdam, the Netherlands</strong>, unless mandatory consumer protection law provides otherwise.</p>
<p>If you are a consumer residing in the EU, you may also use the European Commission's <a href="https://ec.europa.eu/consumers/odr" rel="noopener" target="_blank">Online Dispute Resolution platform</a>.</p>

<h2>14. Changes to the Terms</h2>
<p>We may revise these Terms from time to time. Material changes will be announced on this page and, where required, by email at least 14 days before they take effect. Continued use after the effective date constitutes acceptance.</p>

<h2>15. Contact</h2>
<p>Questions about these Terms? Email {$contactMailLink}.</p>
HTML;

// ═══════════════ SAVE ═══════════════
$pages = [
    'privacy' => [
        'title'            => 'Privacy Policy',
        'hero_eyebrow'     => '// LEGAL · GDPR',
        'hero_title'       => 'Privacy Policy',
        'hero_subtitle'    => 'How we collect, use, and protect your personal data under the GDPR.',
        'content'          => $privacyContent,
        'meta_description' => "Privacy Policy of {$name} — GDPR-compliant notice under Art. 13 GDPR. Data controller, retention, rights, and contact.",
    ],
    'cookies' => [
        'title'            => 'Cookie Policy',
        'hero_eyebrow'     => '// LEGAL · COOKIES',
        'hero_title'       => 'Cookie Policy',
        'hero_subtitle'    => 'Which cookies we use, why, and how to manage your consent.',
        'content'          => $cookiesContent,
        'meta_description' => "Cookie Policy of {$name} — categories, purposes, durations, and how to manage or withdraw consent.",
    ],
    'terms' => [
        'title'            => 'Terms of Service',
        'hero_eyebrow'     => '// LEGAL · TERMS',
        'hero_title'       => 'Terms of Service',
        'hero_subtitle'    => 'Rules for using our services — governed by Dutch law.',
        'content'          => $termsContent,
        'meta_description' => "Terms of Service of {$name} — eligibility, acceptable use, liability, governing law (Netherlands).",
    ],
];

foreach ($pages as $slug => $data) {
    $page = Page::updateOrCreate(
        ['slug' => $slug],
        array_merge($data, ['is_published' => true])
    );
    echo "✓ {$slug} — id={$page->id}, " . strlen($data['content']) . " bytes content\n";
}

echo "\n=== DONE ===\n";
