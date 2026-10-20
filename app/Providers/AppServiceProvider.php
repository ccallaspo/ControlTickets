<?php

namespace App\Providers;

use App\Models\Cotizacion;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Document;
use App\Models\Followup;
use App\Models\Note;
use App\Observers\CotizacionObserver;
use App\Observers\CourseObserver;
use App\Observers\CustomerObserver;
use App\Observers\DocumentObserver;
use App\Observers\FollowupObserver;
use App\Observers\NoteObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(\App\Services\TicketUpdateNotifier::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Cotizacion::observe(CotizacionObserver::class);
        Followup::observe(FollowupObserver::class);
        Document::observe(DocumentObserver::class);
        Note::observe(NoteObserver::class);
        Course::observe(CourseObserver::class);
        Customer::observe(CustomerObserver::class);
    }
}
