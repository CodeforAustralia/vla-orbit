<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Login with user and password')
                    //->clickLink('Login') //This line is replaced with the below one as is returning an error (but atually is working...)
                    ->click('a[href="/login"]')
                    ->assertPathIs('/login')
                    ->pause(1000)
                    ->value('#email', '')
                    ->value('#password', '')
                    ->click('button[type="submit"]') //Click the submit button on the page
                    ->assertSee("Test") //User name
                    ->assertPathIs('/');
        });
    }
}
