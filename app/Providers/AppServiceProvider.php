<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
/** User Auth on boot **/
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
        //
        Schema::defaultStringLength(191);

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
                Auth::login($user);
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
