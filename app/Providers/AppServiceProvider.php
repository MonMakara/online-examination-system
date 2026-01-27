<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Pagination\Paginator::useTailwind();

        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory)->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.key')
                )
            );
        });

        // Share active exams count with student sidebar
        view()->composer('layouts.partials.sidebars.student', function ($view) {
            $activeExamsCount = 0;

            if (auth()->check() && auth()->user()->role === 'student') {
                $studentId = auth()->id();
                $now = now();

                $enrolledClassIds = \Illuminate\Support\Facades\DB::table('class_student')
                    ->where('student_id', $studentId)
                    ->pluck('class_id');

                $activeExamsCount = \App\Models\Exam::whereIn('class_id', $enrolledClassIds)
                    ->whereDoesntHave('results', function ($query) use ($studentId) {
                        $query->where('user_id', $studentId);
                    })
                    ->where(function ($query) use ($now) {
                        $query->whereNull('closed_at')
                            ->orWhere('closed_at', '>', $now);
                    })
                    ->count();
            }

            $view->with('activeExamsCount', $activeExamsCount);
        });
    }
}
