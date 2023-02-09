<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\Nominee;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        if ($this->command->confirm('Do you want to refresh the database?')) {
            $this->command->call('migrate:refresh');
            $this->command->info('Database was refreshed');
        }

        //User
        User::factory()->makeAdmin()->create([
                'name' => 'Gourav',
                'email' => 'gs@12.com',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
        User::factory()->create([
            'name' => 'GUser',
            'email' => 'gs@12u.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        User::factory(9)->create();

        //Election
        Election::factory(1)->create();
        Election::factory(3)->startElection()->create();
        Election::factory(2)->stopElection()->create();
        $ele= Election::all();

        //Nominee
        $NomCount = 18 ;

        $nom = Nominee::factory($NomCount)->make()->each(function($nom) use ($ele) {
            $nom->election_id = $ele->random()->id;
            $nom->save();
        });

        //Voter
        $VoterCount = 25 ;
        $howManyMin = 6;
        $howManyMax = 8;
        //dd(Election::where('id','>',2)->get());

        Election::where('id','>',2)->get()->each(function (Election $ele) use($howManyMin, $howManyMax) {
            $take = random_int($howManyMin, $howManyMax);

            User::isNotAdmin()->get()->random($take)->each(function (User $user) use($ele) {
                //dd($ele->nominees()->get()->random()->id);
                $voter = new Voter();
                $voter->election_id = $ele->id;
                $voter->nominee_id = $ele->nominees()->get()->random()->id;
                $voter->user_id = $user->id;
                $voter->save();
            });
        });
    }
}
