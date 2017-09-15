<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
/** User Auth on boot **/
use App\Role;
use App\User;
use Auth;
use SimpleSAML_Auth_Simple;

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

        if ( !Auth::check() && \Request::is('login_vla') ) {
            $as = new SimpleSAML_Auth_Simple(env('SIMPLESML_SP'));
            $as->requireAuth();
            $attributes = $as->getAttributes();        

            //Check if is a VLA USER
            if (isset($attributes['mail'][0]) && $attributes['mail'][0] != '') {
                $email = $attributes['mail'][0];
                $user = User::where('email',$email)->first();
                if ($user) { // User exists?                
                    Auth::login($user);
                } else { //Create that user!!!                
                    $user = User::create([
                      'name'     => $attributes['name'][0],
                      'email'    => $attributes['mail'][0],
                      'password' => bcrypt('123344adsdsaasdasd'),
                    ]);                
                    //sign them in and Add role too
                    $user
                       ->roles()
                       ->attach(Role::where('name', 'VLA')->first());
                    $user->sp_id = 112 ; // No service provider
                    $user->save();
                    Auth::login($user);
                }
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
