<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
/**
 * To run the test follow this command
 * php artisan dusk tests/Browser/ReferralTest.php
 */
class ReferralTest extends DuskTestCase
{
	/**
	 * A Dusk test example.
	 *
	 * @return void
	 */
	public function testReferralScreen()
    {
        $this->browse(function (Browser $browser) {

            //Login
            $browser->loginAs(User::find(28))
                    ->visit('/')
                    ->assertSee("Christian");

            //Go to first step of referrals
            $browser->clickLink('New Referral')
                    ->assertSee('Search')
                    ->pause(1000)
                    ->select2('#single', 999, 5)
                    ->select2('#single-prepend-text', 3000, 5)
                    ->clickLink('Next');

            //Go to second step of referrals
            $browser->assertSee('Eligibility')
                    ->pause(1000)
                    ->click('.mt-checkbox.mt-checkbox-outline')
                    ->clickLink('Next');

            //Go to third step of referrals
            $browser->assertSee('Questions')
                    ->value('#form_answers .form-control:not(.hidden)', 5)
                    ->pause(1000)
                    ->press('View Matches');

            //Go to last step of referrals
            $browser->assertSee('Tech for non-tech!');
        });
    }
}
