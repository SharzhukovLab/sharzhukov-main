<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact', [
            'categories' => ContactMessage::categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $categories = array_keys(ContactMessage::categories());

        $data = $request->validate([
            'category'      => ['required', 'in:' . implode(',', $categories)],
            'role_interest' => ['nullable', 'string', 'max:255'],
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'subject'       => ['required', 'string', 'max:255'],
            'message'       => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $data['ip_address'] = $request->ip();
        $data['user_agent'] = substr((string) $request->userAgent(), 0, 255);
        $data['user_id']    = $request->user()?->id;
        $data['status']     = 'new';

        $msg = ContactMessage::create($data);

        // Notify admin
        try {
            Mail::raw(
                "New contact message #{$msg->id}\n\n"
                . "Category: {$msg->category}\n"
                . "Role: " . ($msg->role_interest ?: '—') . "\n"
                . "Name: {$msg->name}\n"
                . "Email: {$msg->email}\n"
                . "Subject: {$msg->subject}\n\n"
                . "{$msg->message}\n",
                function ($m) use ($msg) {
                    $m->to('admin@sharzhukov.com')
                      ->replyTo($msg->email, $msg->name)
                      ->subject('[Contact] ' . $msg->subject);
                }
            );
        } catch (\Throwable $e) {
            logger()->warning('[contact] mail failed', ['error' => $e->getMessage()]);
        }

        return redirect()->route('contact')
            ->with('contact_success', "Thanks, {$msg->name}! We got your message and will reply to {$msg->email} soon.");
    }
}
