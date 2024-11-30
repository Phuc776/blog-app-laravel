<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

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
    public function boot(ResponseFactory $response)
    {
        $response->macro('success', function ($status = 200, $message = '', $data) use ($response) {
            $format = [
                'status' => $status,
                'message' => $message,
                'data' => $data,
            ];

            return $response->make($format);
        });

        $response->macro('error', function ($status = 500, string $message, $errors = []) use ($response) {
            $format = [
                'status' => $status,
                'message' => $message,
                'errors' => $errors,
            ];

            return $response->make($format);
        });
    }   
}
