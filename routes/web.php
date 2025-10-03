
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureTwoFactorEnabled;
use App\Domain\Contacts\Controllers\ContactController;
use App\Domain\Messaging\Controllers\InboxController;
use App\Domain\Messaging\Controllers\MessageController;
use App\Domain\Messaging\Controllers\ConversationController;
use App\Domain\Reports\Controllers\ReportController;
use App\Domain\Team\Controllers\RolePermissionController;
use App\Domain\Messaging\Controllers\ChannelSettingsController;
use App\Integrations\OAuth\MetaConnectController;
use App\Integrations\OAuth\TikTokConnectController;
use App\Integrations\OAuth\WhatsAppConnectController;

// Redirect root to dashboard
Route::redirect('/', '/dashboard');

// Authenticated + verified users
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

    // Security settings
    Route::view('/settings/security', 'settings.security')->name('settings.security');

    // Inbox
    Route::prefix('inbox')->name('inbox.')->group(function () {
        Route::get('/', [InboxController::class, 'index'])->name('index');
        Route::get('/conversations/{conversation}', [InboxController::class, 'show'])->name('conversation');
        Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store'])->name('message.store');
        Route::post('/conversations/{conversation}/assign', [ConversationController::class, 'assign'])
            ->name('conversation.assign')->middleware('role:Admin');
        Route::patch('/conversations/{conversation}/status', [ConversationController::class, 'updateStatus'])->name('conversation.status');
    });

    // Contacts
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [ContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [ContactController::class, 'show'])->name('show');
    });

    // Reports (Admin only)
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    });

    // Settings (Admin only)
    Route::prefix('settings')->middleware(['role:Admin'])->group(function () {

        // Roles & Permissions
        Route::get('/roles', [RolePermissionController::class, 'index'])->name('settings.roles');
        Route::post('/roles', [RolePermissionController::class, 'storeRole'])->name('settings.roles.store');
        Route::delete('/roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('settings.roles.destroy');
        Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'syncRolePermissions'])->name('settings.roles.permissions.sync');
        Route::post('/users/{user}/roles', [RolePermissionController::class, 'syncUserRoles'])->name('settings.users.roles.sync');

        // Channel Settings
        Route::get('channels', [ChannelSettingsController::class, 'index'])->name('settings.channels');
        Route::post('channels', [ChannelSettingsController::class, 'store'])->name('settings.channels.store');
        Route::patch('channels/{channelAccount}', [ChannelSettingsController::class, 'update'])->name('settings.channels.update');
        Route::post('channels/{channelAccount}/toggle', [ChannelSettingsController::class, 'toggle'])->name('settings.channels.toggle');
        Route::delete('channels/{channelAccount}', [ChannelSettingsController::class, 'destroy'])->name('settings.channels.destroy');

        // OAuth
        Route::get('connect/meta/start', [MetaConnectController::class, 'start'])->name('oauth.meta.start');
        Route::get('connect/meta/callback', [MetaConnectController::class, 'callback'])->name('oauth.meta.callback');
        Route::get('connect/tiktok/start', [TikTokConnectController::class, 'start'])->name('oauth.tiktok.start');
        Route::get('connect/tiktok/callback', [TikTokConnectController::class, 'callback'])->name('oauth.tiktok.callback');
        Route::get('connect/whatsapp', [WhatsAppConnectController::class, 'index'])->name('oauth.whatsapp.index');
        Route::post('connect/whatsapp', [WhatsAppConnectController::class, 'connect'])->name('oauth.whatsapp.connect');
    });
});

// Admin routes with 2FA
Route::middleware(['auth', 'verified', EnsureTwoFactorEnabled::class])
    ->prefix('admin')
    ->name('admin.')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::view('/users', 'admin.users.index')->name('users.index');
        Route::view('/roles', 'admin.roles.index')->name('roles.index');
    });
