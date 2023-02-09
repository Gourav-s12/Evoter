<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Nominee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ElectionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    // public function test_example()
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user, 'api');
    //     $response = $this->get('/api/test');
    //     //dd($response);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'message' => '5'
    //     ]);
    // }
    public function test_a_admin_can_create_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/create-election',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'name' => 'Testing Elec',
                ]
            ]
        ]);

        $ele= Election::first();

        $this->assertCount(1, Election::all());
        $this->assertEquals('Testing Elec', $ele->name);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'type' => 'election',
                    'ele_id' => $ele->id,
                    'attributes' => [
                        'name' => 'Testing Elec',
                    ]
                ],
                'links' => [
                    'self' => url('/Election/'.$ele->id),
                ]
            ]);
    }
    public function test_a_admin_can_retrieve_all_election()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->makeAdmin()->create();
        $this->actingAs($user, 'api');
        $ele = Election::factory(2)->create();

        $response = $this->get('/api/election-all');

        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'election',
                        'ele_id' => $ele->last()->id,
                        'attributes' => [
                            'name' => $ele->last()->name,
                        ]
                    ]
                ],
                [
                    'data' => [
                        'type' => 'election',
                        'ele_id' => $ele->first()->id,
                        'attributes' => [
                            'name' => $ele->first()->name,
                        ]
                    ]
                ]
            ],
            'links' => [
                'self' => url('/ElectionAll'),
            ]
        ]);
    }
    public function test_a_non_admin_can_not_create_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/create-election',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'name' => 'Testing Elec',
                ]
            ]
        ]);

        $this->assertCount(0, Election::all());

        $response->assertStatus(401)
            ->assertJson([
                "message" => "Unauthorized",
            ]);
    }
    /**
    * @expectedException CustomException
    */
    public function test_a_admin_can_delete_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        //dd($ele);

        $response= $this->get('/api/delete-election/'.$ele->id);

        $this->assertCount(0, Election::all());

        $response->assertStatus(201)
            ->assertJson([
                "data" => [
                    "code" => 201,
                    "title" => "Election deleted",
                    "message" => "successful"
                ]
            ]);
    }
    public function test_error_delete_a_non_exist_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        //dd($ele);

        $response= $this->get('/api/delete-election/1');

        $this->assertCount(0, Election::all());

        $response->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "code" => 404,
                    "title" => "Election",
                    "detail" => "Unable to locate the given information. (Not Found)",
                  ]
            ]);

    }
    public function test_a_admin_can_start_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();

        $response= $this->get('/api/start-election/'.$ele->id);

        $ele= Election::first();

        $this->assertCount(1, Election::all());
        //dd($response);
        
        $this->assertEquals(now()->startOfSecond(), $ele->start);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'election',
                    'ele_id' => $ele->id,
                    'attributes' => [
                        'name' => $ele->name,
                        'start' => $ele->start->diffForHumans(),
                        'end' => $ele->end,
                    ]
                ],
                'links' => [
                    'self' => url('/Election/'.$ele->id),
                ]
            ]);
    }
    public function test_a_admin_can_stop_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();

        $response= $this->get('/api/start-election/'.$ele->id);

        $this->assertCount(1, Election::all());
        $ele= Election::first();
        $this->assertEquals(now()->startOfSecond(), $ele->start);

        sleep(2);
        $response= $this->get('/api/stop-election/'.$ele->id);

        $ele= Election::first();
        $this->assertEquals(now()->startOfSecond(), $ele->end);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'election',
                    'ele_id' => $ele->id,
                    'attributes' => [
                        'name' => $ele->name,
                        'start' => $ele->start->diffForHumans(),
                        'end' => $ele->end->diffForHumans(),
                    ]
                ],
                'links' => [
                    'self' => url('/Election/'.$ele->id),
                ]
            ]);
    }
    public function test_error_start_a_non_exist_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');

        $response= $this->get('/api/start-election/1');

        $this->assertCount(0, Election::all());

        $response->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "code" => 404,
                    "title" => "Election",
                    "detail" => "Unable to locate the given information. (Not Found)",
                  ]
            ]);

    }
    public function test_error_stop_a_non_exist_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');

        $response= $this->get('/api/stop-election/1');

        $this->assertCount(0, Election::all());

        $response->assertStatus(404)
            ->assertJson([
                "errors" => [
                    "code" => 404,
                    "title" => "Election",
                    "detail" => "Unable to locate the given information. (Not Found)",
                  ]
            ]);

    }
    public function test_a_user_can_retrieve_all_open_election()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->makeAdmin()->create();
        $this->actingAs($user, 'api');
        $ele = Election::factory(2)->create();
        $response= $this->get('/api/start-election/'.$ele->last()->id);

        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->get('/api/election-open');
        //dd($response);
        $ele= Election::find($ele->last()->id);

        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'election',
                        'ele_id' => $ele->id,
                        'attributes' => [
                            'name' => $ele->name,
                            'start' => optional($ele->start)->diffForHumans(),
                            'end' => optional($ele->end)->diffForHumans(),
                        ]
                    ]
                ]
            ],
            'links' => [
                'self' => url('/ElectionAll'),
            ]
        ]);
    }
    public function test_a_user_can_retrieve_all_closed_election()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->makeAdmin()->create();
        $this->actingAs($user, 'api');
        $ele = Election::factory(2)->create();
        $response= $this->get('/api/start-election/'.$ele->last()->id);
        $response= $this->get('/api/stop-election/'.$ele->last()->id);

        $user = User::factory()->create();
        $this->actingAs($user, 'api');

        $response = $this->get('/api/election-close');
        //dd($response);
        $ele= Election::find($ele->last()->id);

        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'election',
                        'ele_id' => $ele->id,
                        'attributes' => [
                            'name' => $ele->name,
                            'start' => optional($ele->start)->diffForHumans(),
                            'end' => optional($ele->end)->diffForHumans(),
                        ]
                    ]
                ]
            ],
            'links' => [
                'self' => url('/ElectionAll'),
            ]
        ]);
    }
    public function test_a_admin_can_restart_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();

        $response= $this->get('/api/start-election/'.$ele->id);
        $response= $this->get('/api/stop-election/'.$ele->id);
        $response= $this->get('/api/start-election/'.$ele->id);

        $ele= Election::first();
        //dd($ele);

        $this->assertCount(1, Election::all());
        //dd($response);
        
        $this->assertEquals(now()->startOfSecond(), $ele->start);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'type' => 'election',
                    'ele_id' => $ele->id,
                    'attributes' => [
                        'name' => $ele->name,
                        'start' => $ele->start->diffForHumans(),
                        'end' => null,
                    ]
                ],
                'links' => [
                    'self' => url('/Election/'.$ele->id),
                ]
            ]);
    }
    public function test_a_user_can_retrieve_all_open_election_with_marked_as_voted()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->makeAdmin()->create();
        $this->actingAs($user, 'api');
        $ele = Election::factory(2)->create();
        $nom = Nominee::factory(2)->create(['election_id' => $ele->first()->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());
        $response= $this->get('/api/start-election/'.$ele->first()->id);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->first()->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $response = $this->get('/api/election-open');
        // dd($response);
        $ele= Election::find($ele->first()->id);

        $response->assertStatus(200)
        ->assertJson([
            'data' => [
                [
                    'data' => [
                        'type' => 'election',
                        'ele_id' => $ele->id,
                        'attributes' => [
                            'name' => $ele->name,
                            'start' => optional($ele->start)->diffForHumans(),
                            'end' => optional($ele->end)->diffForHumans(),
                            'voted' => true
                        ]
                    ]
                ]
            ],
            'links' => [
                'self' => url('/ElectionAll'),
            ]
        ]);
    }
}
