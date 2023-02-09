<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAccountTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_admin_can_create_other_admin()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $this->actingAs($user,'api');

        $response= $this->post('/api/create-account',[
            
            'name' => 'User name 1',
            'email' => 'user@12gmail.com',
            'password' => 'abcd12AA',
            'password_confirmation' => 'abcd12AA',
            'is_admin' => True,
               
        ]);
        $this->assertCount(2, User::all());
        //dd($response);

        $user= User::latest()->first();
        $this->assertEquals('1', $user->is_admin);
        //dd($user);
        $this->actingAs($user,'api');

        $response= $this->post('/api/create-election',[
            'data' => [
                'type' => 'Election',
                'attributes' => [
                    'name' => 'Testing Elec',
                ]
            ]
        ]);
        //dd($response);

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
    public function test_admin_can_delete_non_admin_account()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $user2= User::factory(2)->create();
        $this->actingAs($user,'api');
        $this->assertCount(3, User::all());

        $response= $this->get('/api/delete-account/'.$user2->first()->id);
        $this->assertCount(2, User::all());
        
        $response= $this->get('/api/delete-account/'.$user2->last()->id);
        $this->assertCount(1, User::all());

    }
    public function test_admin_can_not_delete_admin_account()
    {
        $this->withoutExceptionHandling(); 
        
        $user= User::factory()->makeAdmin()->create();
        $user2= User::factory(2)->makeAdmin()->create();
        $this->actingAs($user,'api');
        $this->assertCount(3, User::all());

        $response= $this->get('/api/delete-account/'.$user2->first()->id);
        $this->assertCount(3, User::all());

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "code" => 401,
                    "message" => "Unauthorized",
                    "detail" => "You can not delete an admin"
                ]
            ]);
        
        $response= $this->get('/api/delete-account/'.$user2->last()->id);
        $this->assertCount(3, User::all());

        $response->assertStatus(401)
            ->assertJson([
                'errors' => [
                    "code" => 401,
                    "message" => "Unauthorized",
                    "detail" => "You can not delete an admin"
                ]
            ]);

    }
    public function test_admin_can_see_list_of_non_admin_account(){

        $user= User::factory()->makeAdmin()->create();
        $r_user = User::factory(2)->create();
        
        $this->actingAs($user,'api');

        $response= $this->get('/api/list-account');
        //dd($response);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'data' => [
                            'type' => 'users',
                            'user_id' => $r_user->first()->id,
                            'attributes' => [
                                'name' => $r_user->first()->name,
                            ]
                        ]
                    ],
                    [
                        'data' => [
                            'type' => 'users',
                            'user_id' => $r_user->last()->id,
                            'attributes' => [
                                'name' => $r_user->last()->name,
                            ]
                        ]
                    ]
                ],
                'links' => [
                    'self' => url('/listAccount'),
                ]
            ]);
    }
}
