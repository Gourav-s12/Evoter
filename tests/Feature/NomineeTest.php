<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Nominee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NomineeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_add_nominee_in_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();

        $response= $this->post('/api/election/'.$ele->id.'/create-nominee',[
            'data' => [
                'type' => 'Nominee',
                'attributes' => [
                    'name' => 'Testing Nom 1',
                    'description' => 'Hello this mag is from Nom 1',
                ]
            ]
        ]);

        $ele= Election::first();
        $this->assertCount(1, Election::all());

        $nom= Nominee::first();

        $this->assertCount(1, Nominee::all());
        $this->assertEquals('Testing Nom 1', $nom->name);
        //dd($response);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'nominee',
                            'nom_id' => $nom->id,
                            'attributes' => [
                                'name' => 'Testing Nom 1',
                                'description' => 'Hello this mag is from Nom 1',
                            ]
                        ]
                    ]
                ],
                'nominee_count' => 1,
            ]);
    }
    public function test_a_admin_can_delete_a_nominee()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        //dd($ele);
        $nom = Nominee::factory()->create(['election_id' => $ele->id]);

        $response= $this->get('/api/delete-nominee/'.$nom->id);

        $this->assertCount(0, Nominee::all());
        $this->assertCount(1, Election::all());

        $response->assertStatus(201)
            ->assertJson([
                "data" => [
                    "code" => 201,
                    "title" => "Nominee deleted",
                    "message" => "successful"
                ]
            ]);

    }
    public function test_a_any_user_can_list_all_nominee_in_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        //dd($ele);
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);

        $response= $this->get('/api/election/'.$ele->id.'/nominee');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'nominee',
                            'nom_id' => $nom->first()->id,
                            'attributes' => [
                                'name' => $nom->first()->name,
                                'description' => $nom->first()->description,
                            ]
                        ]
                    ],
                    [
                        'data' => [
                            'type' => 'nominee',
                            'nom_id' => $nom->last()->id,
                            'attributes' => [
                                'name' => $nom->last()->name,
                                'description' => $nom->last()->description,
                            ]
                        ]
                    ]
                ],
                'nominee_count' => 2,
            ]);
    }
    public function test_error_on_delete_non_exist_nominee()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');

        $response= $this->get('/api/delete-nominee/1')
                ->assertStatus(404);

        $response->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "code" => 404,
                    "title" => "Nominee",
                    "detail" => "Unable to locate the given information. (Not Found)",
                  ]
            ]);

    }
    public function test_error_on_create_nominee_on_non_exist_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/election/1/create-nominee',[
            'data' => [
                'type' => 'Nominee',
                'attributes' => [
                    'name' => 'Testing Nom 1',
                    'description' => 'Hello this mag is from Nom 1',
                ]
            ]
        ]);

        $response->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "code" => 404,
                    "title" => "Election",
                    "detail" => "Unable to locate the given information. (Not Found)",
                  ]
            ]);

    }
}
