<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\WorkBit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class WorkBitTest extends TestCase
{
    use RefreshDatabase;

    protected string $table;

    protected User $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->table = (new WorkBit())->getTable();
        $this->company = User::factory()->company()->create()->refresh();
        Passport::actingAs($this->company);
    }

    protected function tearDown(): void
    {
        unset($this->company);
        parent::tearDown();
    }

    /**
     * @return array
     */
    protected function createWorkBitResource() {
        $workBit = WorkBit::factory()->make();
        return [
            'type' => 'work-bits',
            'attributes' => [
                'title' => $workBit->title,
                'description' => $workBit->description,
                'date' => $workBit->date,
            ]
        ];
    }

    /**
     * Test that work bits can be listed
     *
     * @return void
     */
    public function testWorkBitsCanBeListed()
    {
        $workBits = WorkBit::factory()->count(5)->create();

        // Testing as regular user
        $user = User::factory()->create();

        $this->actingAs($user)->jsonApi()
            ->expects('work-bits')
            ->get(route('api:v1:work-bits.index'))
            ->assertFetchedMany($workBits);
    }

    /**
     * Test that work bits can be read
     *
     * @return void
     */
    public function testWorkBitsCanBeRead()
    {
        $workBit = WorkBit::factory()->create()->refresh();

        // Testing as regular user
        $user = User::factory()->create();

        $this->actingAs($user)->jsonApi()
            ->expects('work-bits')
            ->get(route('api:v1:work-bits.read', $workBit->getRouteKey()))
            ->assertFetchedOne($workBit);
    }

    /**
     * Test that work bits can be created
     *
     * @return void
     */
    public function testWorkBitsCanBeCreated()
    {
        $data = $this->createWorkBitResource();

        $this->jsonApi()
            ->withData($data)
            ->post(route('api:v1:work-bits.create'))
            ->assertCreated();

        $this->assertDatabaseHas($this->table, $data['attributes']);
    }

    /**
     * Test that work bits can be updated
     *
     * @return void
     */
    public function testWorkBitsCanBeUpdated()
    {
        $workBit = WorkBit::factory()->create()->refresh();

        $data = ['id' => (string) $workBit->getKey()] + $this->createWorkBitResource();

        $this->actingAs($workBit->author)->jsonApi()
            ->withData($data)
            ->patch(route('api:v1:work-bits.update', $workBit->getRouteKey()))->dump()
            ->assertStatus(200);

        $this->assertDatabaseHas($this->table, [ $workBit->getKeyName() => $data['id'] ] + $data['attributes']);
    }

    /**
     * Test that work bits can be deleted softly
     *
     * @return void
     */
    public function testWorkBitsCanBeDeletedSoftly()
    {
        $workBit = WorkBit::factory()->create()->refresh();

        $this->actingAs($workBit->author)->jsonApi()
            ->delete(route('api:v1:work-bits.delete', $workBit->getRouteKey()))->dump()
            ->assertDeleted();

        $this->assertSoftDeleted($workBit);
    }

    /**
     * Test that work bits cannot be created by regular account
     *
     * @return void
     */
    public function testRegularUserCannotCreateWorkBits()
    {
        $data = $this->createWorkBitResource();

        $this->actingAs(User::factory()->create());

        $this->jsonApi()
            ->withData($data)
            ->post(route('api:v1:work-bits.create'))
            ->assertForbidden();

        $this->assertDatabaseMissing($this->table, $data['attributes']);
    }


}
