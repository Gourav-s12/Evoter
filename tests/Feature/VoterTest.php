<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Nominee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_vote_a_nominee()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        $ele2 = Election::factory()->create();
        
        $nom2 = Nominee::factory(3)->create(['election_id' => $ele2->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());

        $response= $this->get('/api/start-election/'.$ele->id);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'message' => 'successful',
                ]
            ]);
    }
    public function test_admin_can_not_vote_a_nominee()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        $ele2 = Election::factory()->create();
        
        $nom2 = Nominee::factory(3)->create(['election_id' => $ele2->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());
        $response= $this->get('/api/start-election/'.$ele->id);

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'Unauthorized',
                    'detail' => 'Admin can not vote in Election',
                ]
            ]);
    }
    public function test_user_can_not_vote_nominee_twice_in_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        $ele2 = Election::factory()->create();
        
        $nom2 = Nominee::factory(3)->create(['election_id' => $ele2->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());
        $response= $this->get('/api/start-election/'.$ele->id);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->first()->id,
                ]
            ]
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'Unauthorized',
                    'detail' => 'You already voted in this election',
                ]
            ]);
    }
    public function test_user_can_not_vote_a_nominee_in_closed_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        $ele2 = Election::factory()->create();
        
        $nom2 = Nominee::factory(3)->create(['election_id' => $ele2->id]);

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'Unauthorized',
                    'detail' => 'This election is not open to vote',
                ]
            ]);
    }
    
    public function test_user_can_see_vote_result_in_ended_election()
    {
        $this->withoutExceptionHandling(); 
        
        $userA= User::factory()->makeAdmin()->create();
        $this->actingAs($userA,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);

        $response= $this->get('/api/start-election/'.$ele->id);
        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->first()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');
        
        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');
        
        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $this->actingAs($userA,'api');
        $response= $this->get('/api/stop-election/'.$ele->id);

        $this->actingAs($user,'api');
        $response= $this->get('/api/result/'.$ele->id);
        //dd($response);

        $ele= Election::find($ele->id);
        //$nom= Nominee::all();

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    "type" => "election_result",
                    "ele_id" => $ele->id,
                    "attributes" => [
                        "name" => $ele->name,
                        "start" => $ele->start->diffForHumans(),
                        "end" => $ele->end != null ? $ele->end->diffForHumans() : null,
                        "total_voters_count" => "4",
                        "nominees" => [
                            'data' => [
                                [
                                    'data' => [
                                        'type' => 'nominee',
                                        'nom_id' => $nom->first()->id,
                                        'attributes' => [
                                            'name' => $nom->first()->name,
                                            'description' => $nom->first()->description,
                                        ],
                                        "voters_count" => "1",
                                    ]
                                ],
                                [
                                    'data' => [
                                        'type' => 'nominee',
                                        'nom_id' => $nom->last()->id,
                                        'attributes' => [
                                            'name' => $nom->last()->name,
                                            'description' => $nom->last()->description,
                                        ],
                                        "voters_count" => "3",
                                    ]
                                ]
                            ],
                            'nominee_count' => 2,
                        ]
                    ]
                ]
            ]);
    }
    public function test_admin_can_see_vote_result_in_any_election()
    {
        $this->withoutExceptionHandling(); 
        
        $userA= User::factory()->makeAdmin()->create();
        $this->actingAs($userA,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);

        $response= $this->get('/api/start-election/'.$ele->id);
        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->first()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');
        
        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');
        
        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $this->actingAs($userA,'api');
        $response= $this->get('/api/result/'.$ele->id);
        //dd($response);

        $ele= Election::find($ele->id);
        //$nom= Nominee::all();

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    "type" => "election_result",
                    "ele_id" => $ele->id,
                    "attributes" => [
                        "name" => $ele->name,
                        "start" => $ele->start->diffForHumans(),
                        "end" => $ele->end != null ? $ele->end->diffForHumans() : null,
                        "total_voters_count" => "4",
                        "nominees" => [
                            'data' => [
                                [
                                    'data' => [
                                        'type' => 'nominee',
                                        'nom_id' => $nom->first()->id,
                                        'attributes' => [
                                            'name' => $nom->first()->name,
                                            'description' => $nom->first()->description,
                                        ],
                                        "voters_count" => "1",
                                    ]
                                ],
                                [
                                    'data' => [
                                        'type' => 'nominee',
                                        'nom_id' => $nom->last()->id,
                                        'attributes' => [
                                            'name' => $nom->last()->name,
                                            'description' => $nom->last()->description,
                                        ],
                                        "voters_count" => "3",
                                    ]
                                ]
                            ],
                            'nominee_count' => 2,
                        ]
                    ]
                ]
            ]);
    }
    public function test_user_can_not_see_vote_result_in_ongoing_election()
    {
        $this->withoutExceptionHandling(); 
        
        $userA= User::factory()->makeAdmin()->create();
        $this->actingAs($userA,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);

        $response= $this->get('/api/start-election/'.$ele->id);
        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->first()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');
        
        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $user= User::factory()->create();
        $this->actingAs($user,'api');
        
        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        $response= $this->get('/api/result/'.$ele->id);
        //dd($response);

        $ele= Election::find($ele->id);
        //$nom= Nominee::all();

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "code" => 401,
                    "message" => "Unauthorized",
                    "detail" => "This election has not ended , you can not see the result now"
                ]
            ]);
    }
    public function test_user_Check_can_vote_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());

        $response= $this->get('/api/start-election/'.$ele->id);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->get('/api/can-vote/'.$ele->id);

        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'title' => 'You can vote in the Election',
                ]
            ]);
    }
    public function test_admin_check_can_not_vote_a_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());
        $response= $this->get('/api/start-election/'.$ele->id);
        
        $response= $this->get('/api/can-vote/'.$ele->id);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'Unauthorized',
                    'detail' => 'Admin can not vote in Election',
                ]
            ]);
    }
    public function test_user_check_can_not_vote_nominee_twice_in_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        // dd(Election::with('nominees')->get());
        // dd(Nominee::where('id',2)->with('election')->get());
        $response= $this->get('/api/start-election/'.$ele->id);

        $user= User::factory()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/vote',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'election_id' => $ele->id,
                    'nominee_id' => $nom->last()->id,
                ]
            ]
        ]);

        
        $response= $this->get('/api/can-vote/'.$ele->id);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'Unauthorized',
                    'detail' => 'You already voted in this election',
                ]
            ]);
    }
    public function test_user_check_can_not_vote_a_nominee_in_closed_election()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->create();
        $this->actingAs($user,'api');
        $ele = Election::factory()->create();
        
        $nom = Nominee::factory(2)->create(['election_id' => $ele->id]);
        $ele2 = Election::factory()->create();
        
        $nom2 = Nominee::factory(3)->create(['election_id' => $ele2->id]);

        
        $response= $this->get('/api/can-vote/'.$ele->id);

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => 'Unauthorized',
                    'detail' => 'This election is not open to vote',
                ]
            ]);
    }
}
