<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Document;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // صلاحية إدارة المستخدمين
        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin');
        });

        // Badge عدد الـ Temp-Out المنتهية
        View::composer('*', function ($view) {
            if (auth()->check()) {

                $expiredBacCount = Document::where('type', 'Bac')
                    ->where('status', 'Temp_Out')
                    ->whereHas('movements', function ($q) {
                        $q->where('action_type', 'Sortie')
                          ->whereNotNull('deadline')
                          ->where('deadline', '<', now());
                    })
                    ->count();

                $view->with('expiredBacCount', $expiredBacCount);
            }
        });
    }
}