<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CookieConsentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ─── SEO ───
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// ─── Public pages ───
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
// ─── Products (open source, from GitHub + curated) ───
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

Route::get('/u/{username}', [UserController::class, 'show'])->name('users.show');


// ─── API / forms ───
Route::post('/api/cookie-consent', [CookieConsentController::class, 'store'])->name('cookie-consent.store');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

// ── Health check (uptime monitoring) ──
Route::get('/health', \App\Http\Controllers\HealthController::class)->name('health');
// ── Notifications ──
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationsController::class, 'index'])->name('notifications.index');
    Route::get('/api/notifications/recent', [\App\Http\Controllers\NotificationsController::class, 'recent'])->name('notifications.recent');
    Route::get('/api/notifications/unread-count', [\App\Http\Controllers\NotificationsController::class, 'unreadCount'])->name('notifications.unread');
    Route::post('/api/notifications/{id}/read', [\App\Http\Controllers\NotificationsController::class, 'markRead'])->name('notifications.read');
    Route::post('/api/notifications/mark-all-read', [\App\Http\Controllers\NotificationsController::class, 'markAllRead'])->name('notifications.mark-all');
    Route::delete('/api/notifications/{id}', [\App\Http\Controllers\NotificationsController::class, 'destroy'])->name('notifications.destroy');
});



// ─── Follows (must be BEFORE catch-all) ───
Route::middleware('auth')->group(function () {
    Route::post('/u/{user:username}/follow',   [\App\Http\Controllers\FollowController::class, 'store'])->name('follows.store');
    Route::delete('/u/{user:username}/follow', [\App\Http\Controllers\FollowController::class, 'destroy'])->name('follows.destroy');
});

// ─── Activity Feed ───
Route::middleware('auth')->group(function () {
    Route::get('/feed', [\App\Http\Controllers\FeedController::class, 'index'])->name('feed.index');
});
// ─── Reactions ───
Route::get('/api/reactions/{type}/{id}', [\App\Http\Controllers\ReactionsController::class, 'index'])
    ->whereIn('type', ['news', 'project'])
    ->name('reactions.index');

Route::middleware('auth')->group(function () {
    Route::post('/api/reactions/toggle', [\App\Http\Controllers\ReactionsController::class, 'toggle'])->name('reactions.toggle');
});
// ─── Comments (public read, auth write) ───
Route::get('/api/comments/{type}/{id}', [\App\Http\Controllers\CommentsController::class, 'index'])
    ->whereIn('type', ['news', 'project'])
    ->name('comments.index');

Route::middleware('auth')->group(function () {
    Route::post('/api/comments', [\App\Http\Controllers\CommentsController::class, 'store'])->name('comments.store');
    Route::delete('/api/comments/{comment}', [\App\Http\Controllers\CommentsController::class, 'destroy'])->name('comments.destroy');

    // Модерация (сервер сам проверит isModerator)
    Route::post('/api/comments/{comment}/approve', [\App\Http\Controllers\CommentsController::class, 'approve'])->name('comments.approve');
    Route::post('/api/comments/{comment}/reject',  [\App\Http\Controllers\CommentsController::class, 'reject'])->name('comments.reject');
    Route::post('/api/comments/{comment}/spam',    [\App\Http\Controllers\CommentsController::class, 'spam'])->name('comments.spam');
});
// ─── Catch-all for CMS pages (MUST BE LAST!) ───
Route::get('/{slug}', [PageController::class, 'show'])
    ->name('pages.show')
    ->where('slug', '[a-z0-9-]+');
