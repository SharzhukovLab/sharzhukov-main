<x-layouts.public
    title="Contact — Sharzhukov's Laboratory"
    description="Get in touch with Sharzhukov's Laboratory — general messages, bug reports, partnership, and more.">

    <section class="brutal-grid-bg" style="padding:60px 24px 40px;border-bottom:3px solid var(--brutal-border);">
        <div style="max-width:820px;margin:0 auto;">
            <span class="brutal-marker">// CONTACT</span>
            <h1 class="brutal-hero-title" style="margin:20px 0 12px;font-size:clamp(2rem,4vw,3rem);">Get in touch.</h1>
            <p style="color:var(--lab-fg-muted);font-size:17px;margin:0;line-height:1.6;max-width:600px;">
                Pick what your message is about — we'll route it to the right person.
            </p>
        </div>
    </section>

    <article style="max-width:820px;margin:0 auto;padding:48px 24px 80px;">

        @if(session('contact_success'))
            <div style="border:2px solid #16a34a;background:#f0fdf4;padding:20px 24px;margin-bottom:32px;box-shadow:4px 4px 0 #16a34a;">
                <strong style="font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:#16a34a;">// SENT</strong>
                <p style="margin:8px 0 0;color:#0f172a;">{{ session('contact_success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" style="display:flex;flex-direction:column;gap:24px;">
            @csrf

            {{-- Category radio-cards --}}
            <div>
                <label style="display:block;font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--lab-fg-muted);margin-bottom:12px;">
                    What is this about?
                </label>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px;">
                    @foreach($categories as $key => $cat)
                        <label style="cursor:pointer;position:relative;">
                            <input type="radio" name="category" value="{{ $key }}" required
                                   style="position:absolute;opacity:0;pointer-events:none;"
                                   @if(old('category', 'general') === $key) checked @endif
                                   onchange="document.querySelectorAll('.cat-card').forEach(c=>c.classList.remove('cat-card-active'));this.closest('label').querySelector('.cat-card').classList.add('cat-card-active');">
                            <div class="cat-card @if(old('category', 'general') === $key) cat-card-active @endif"
                                 style="border:2px solid var(--brutal-border);padding:16px 18px;background:var(--lab-bg-elevated);box-shadow:3px 3px 0 var(--brutal-shadow);transition:all .15s ease;">
                                <div style="font-family:var(--font-sans);font-weight:900;font-size:14px;letter-spacing:-0.01em;margin-bottom:4px;">
                                    {{ $cat['label'] }}
                                </div>
                                <div style="font-size:13px;color:var(--lab-fg-muted);line-height:1.4;">
                                    {{ $cat['desc'] }}
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('category') <p style="color:#dc2626;font-size:13px;margin:8px 0 0;">{{ $message }}</p> @enderror
            </div>

            {{-- Role (only for join_team) --}}
            <div id="role-field" style="display:none;">
                <label style="display:block;font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--lab-fg-muted);margin-bottom:8px;">
                    Role you're interested in
                </label>
                <input type="text" name="role_interest" value="{{ old('role_interest') }}"
                       placeholder="Developer, Designer, Editor, Writer..."
                       style="width:100%;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);padding:12px 14px;font-family:var(--font-mono);font-size:13px;">
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;">
                <div>
                    <label style="display:block;font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--lab-fg-muted);margin-bottom:8px;">Your name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required
                           style="width:100%;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);padding:12px 14px;font-family:var(--font-mono);font-size:13px;">
                    @error('name') <p style="color:#dc2626;font-size:12px;margin:6px 0 0;">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label style="display:block;font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--lab-fg-muted);margin-bottom:8px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required
                           style="width:100%;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);padding:12px 14px;font-family:var(--font-mono);font-size:13px;">
                    @error('email') <p style="color:#dc2626;font-size:12px;margin:6px 0 0;">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label style="display:block;font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--lab-fg-muted);margin-bottom:8px;">Subject</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required maxlength="255"
                       style="width:100%;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);padding:12px 14px;font-family:var(--font-mono);font-size:13px;">
                @error('subject') <p style="color:#dc2626;font-size:12px;margin:6px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label style="display:block;font-family:var(--font-mono);font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:var(--lab-fg-muted);margin-bottom:8px;">Message</label>
                <textarea name="message" rows="8" required minlength="10" maxlength="3000"
                          placeholder="Tell us what's on your mind..."
                          style="width:100%;border:2px solid var(--brutal-border);background:var(--lab-bg-elevated);padding:12px 14px;font-family:var(--font-mono);font-size:13px;resize:vertical;">{{ old('message') }}</textarea>
                <p style="font-family:var(--font-mono);font-size:11px;color:var(--lab-fg-muted);margin:6px 0 0;">Min 10 characters, max 3000.</p>
                @error('message') <p style="color:#dc2626;font-size:12px;margin:6px 0 0;">{{ $message }}</p> @enderror
            </div>

            <div>
                <button type="submit" class="lab-btn lab-btn--primary" style="padding:14px 28px;font-size:14px;">
                    Send message →
                </button>
            </div>
        </form>
    </article>

    <style>
        .cat-card-active {
            border-color: var(--brutal-accent) !important;
            background: rgba(37,99,235,0.06) !important;
            transform: translate(-2px,-2px);
            box-shadow: 5px 5px 0 var(--brutal-shadow) !important;
        }
    </style>

    <script>
        (function(){
            var cats = document.querySelectorAll('input[name="category"]');
            var roleField = document.getElementById('role-field');
            function toggleRole() {
                var checked = document.querySelector('input[name="category"]:checked');
                if (checked && checked.value === 'join_team') {
                    roleField.style.display = 'block';
                } else {
                    roleField.style.display = 'none';
                }
            }
            cats.forEach(c => c.addEventListener('change', toggleRole));
            toggleRole();
        })();
    </script>
</x-layouts.public>
