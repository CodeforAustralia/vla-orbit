<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Laravel\Dusk\DuskServiceProvider;
use App\Role;
use App\User;
use Auth;
use URL;
use SimpleSAML_Auth_Simple;

const NO_SP = 0;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $adsf_rul = 'login.microsoftonline.com';
        $prev_url = URL::previous();

        if (!Auth::check() && \Request::is('login_vla')) {
            session(['login_vla' => true ]);
            $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
            $as->requireAuth();
            $attributes = $as->getAttributes();
            session(['login_vla_attributes' => $attributes ]);

            //Check if is a VLA USER
            if (isset($attributes['mail'][0]) && $attributes['mail'][0] != '') {
                $email = $attributes['mail'][0];
                $user = User::where('email', $email)->first();
                if ($user) { // User exists?
                    $user->setLoginDate();
                    Auth::login($user);
                } else { //Create that user!!!
                    $user = User::create([
                        'name'     => $attributes['name'][0],
                        'email'    => $attributes['mail'][0],
                        'password' => bcrypt(substr(str_shuffle(MD5(microtime())), 0, 16)),
                    ]);
                    //sign them in and Add role too
                    $user
                        ->roles()
                        ->attach(Role::where('name', 'VLA')->first());
                    $user->sp_id = NO_SP ; // No service provider set LH by default
                    $user->save();
                    $user->setLoginDate();
                    Auth::login($user);
                }
            }
        } elseif (strpos($prev_url, $adsf_rul) !== false && is_null(session('login_vla'))) {
            echo '
            <script>
                window.location.replace("/login_vla");
            </script>
            ';
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //Only run dusk in testing environments
        if ($this->app->environment('local', 'testing')) {
            //$this->app->register(\Staudenmeir\DuskUpdater\DuskServiceProvider::class);
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
