<?php

namespace Albertarni\TicketingPortalClient;

use Illuminate\Support\ServiceProvider;
use Config;
use Route;
use Request;
use Session;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['config']->package('albertarni/ticketing-portal-client', __DIR__.'/config');
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        Route::get('redirect-back-to-ticketing-portal', function() {
            $signer       = App::make('SignerInterfaceImplementation');
            $api_token    = $this->app['config']->get('ticketing-portal-client::config.apiToken');
            $project_id   = $this->app['config']->get('ticketing-portal-client::config.projectId');
            $redirect_url = Session::pull('redirect_url');

            $sign_request            = new SignRequest($api_token, $redirect_url);
            $data['sign_project_id'] = $project_id;
            $data['sign_email']      = $signer->helpdeskEmail();
            $data['sign_first_name'] = $signer->helpdeskFirstname();
            $data['sign_last_name']  = $signer->helpdeskLastname();
            $data['sign_token']      = $sign_request->makeHash($data);

            $url = $sign_request->getUrl($data);

            return Redirect::away($url);
        });

        Route::filter('sign_request', function() {
            if (Request::has('sign_token')) {
                $redirect_url = Request::get('redirect_url');
                $apiToken     = $this->app['config']->get('ticketing-portal-client::config.apiToken');
                $sign_request = new SignRequest($apiToken);
                if ($sign_request->validateHash(Request::all())) {
                    Session::put('redirect_url', $redirect_url);
                }
            }
        });
    }
}
