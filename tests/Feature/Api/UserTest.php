<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create()->refresh();
        Passport::actingAs($this->user);
    }

    protected function tearDown(): void
    {
        unset($this->user);
        parent::tearDown();
    }

    /**
     * Test that users cannot be listed
     *
     * @return void
     */
    public function testUsersCannotBeListed()
    {
        $this->assertApiRejection(
            $this->jsonApi()
                ->get('/v1/users')
        );
    }

    /**
     * Test that user cannot create a new user
     *
     * @return void
     */
    public function testUserCannotCreateUsers()
    {
        $user = User::factory()->make();
        $data = [
            'type' => 'users',
            'attributes' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];

        $this->assertApiRejection(
            $this->jsonApi()
                ->withData($data)
                ->post('/v1/users')
        );
    }

    /**
     * Test that user cannot update itself
     *
     * @return void
     */
    public function testUserCannotUpdateSelf()
    {
        $user = User::factory()->make();
        $data = [
            'type' => 'users',
            'attributes' => [
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];

        $this->assertApiRejection(
            $this->jsonApi()
                ->withData($data)
                ->patch(route('api:v1:users.read', $this->user->getRouteKey()))
        );
    }

    /**
     * Test that user cannot delete itself
     *
     * @return void
     */
    public function testUserCannotDeleteSelf()
    {
        $this->assertApiRejection(
            $this->jsonApi()
                ->delete(route('api:v1:users.read', $this->user->getRouteKey()))
        );
    }

    /**
     * Test if user can access it's own resource
     *
     * @return void
     */
    public function testUserCanReadSelf()
    {
        $this->jsonApi()
            ->expects('users')
            ->get(route('api:v1:users.read', $this->user->getRouteKey()))
            ->assertFetchedOne($this->user);
    }

    /**
     * Test if user can access it's own resource via /users/me
     *
     * @return void
     */
    public function testUserCanReadSelfViaMe()
    {
        $this->jsonApi()
            ->expects('users')
            ->get(route('api:v1:users.read', 'me'))
            ->assertFetchedOne($this->user);
    }

    /**
     * Test that user cannot access other user resource
     *
     * @return void
     */
    public function testUserCannotReadOthers()
    {
        $this->jsonApi()
            ->expects('users')
            ->get(route('api:v1:users.read', User::factory()->create()->getRouteKey()))
            ->assertErrors(403, [[]]);
    }
}
