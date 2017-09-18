<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
/** User Auth on boot **/
use App\Role;
use App\User;
use Auth;
use URL;
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

        $adsf_rul = 'fs.vla.vic.gov.au';
        $prev_url = URL::previous();
        
        if ( !Auth::check() && \Request::is('login_vla') ) 
        {
            session(['login_vla' => true ]);
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
        elseif( strpos( $prev_url, $adsf_rul ) !== false && is_null( session('login_vla') ) )
        {
            //dd( $prev_url );
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
        //
    }
}
