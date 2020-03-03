<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\Builder;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('paginate', function ($query, int $perPage) {
            $paginator = $query->simplePaginate($perPage);

            return response()->json(
                array_merge(
                    $paginator->toArray(),
                    ['has_more_pages' => $paginator->hasMorePages()]
                )
            );
        });
    }
}
