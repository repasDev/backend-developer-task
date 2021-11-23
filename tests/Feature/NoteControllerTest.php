<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NoteControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function an_authorized_user_can_read_all_the_notes()
    {
        //Given
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPrivateTestNote();

        //When
        $response = $this->actingAs($user)
            ->get('api/notes');

        //Assert
        $response->assertSee($note->title);
    }

    /** @test */
    public function an_unauthorized_user_cannot_read_all_the_notes()
    {
        //Given

        //When
        $response = $this->get('api/notes');

        //Assert
        $response->assertUnauthorized();
    }

    /** @test */
    public function an_unauthorized_user_can_read_public_notes()
    {
        //Given
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPublicTestNote();

        //When
        $response = $this->get('api/public-notes');

        //Assert
        $response->assertSee($note->title);
    }

    /** @test */
    public function an_authorized_user_can_read_single_note()
    {
        //Given
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPrivateTestNote();

        //When
        $response = $this->actingAs($user)
            ->get('api/notes/' . $note->id);

        //Assert
        $response->assertSee($note->title)
            ->assertSee($note->description);
    }

    /** @test */
    public function an_unauthorized_user_can_read_a_public_note()
    {
        //Given
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPublicTestNote();

        //When
        $response = $this->get('api/public-notes/' . $note->id);

        //Assert
        $response->assertSee($note->title)
            ->assertSee($note->description);
    }

    /** @test */
    public function an_unauthorized_user_cannot_read_a_private_note()
    {
        //Given
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPrivateTestNote();

        //When
        $response = $this->get('api/public-notes/' . $note->id);

        //Assert
        $response->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_create_a_new_note()
    {
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->makePrivateTestNote();

        $this->actingAs($user)
            ->post('api/notes', $note->toArray());
        //It gets stored in the database
        $this->assertEquals(1, Note::all()->count());
    }

    /** @test */
    public function unauthenticated_users_cannot_create_a_new_note()
    {
        $note = $this->makePrivateTestNote();

        $response = $this->post('api/notes', $note->toArray());
        //It gets stored in the database
        $this->assertEquals(0, Note::all()->count());
        $response->assertUnauthorized();
    }

    /** @test */
    public function authorized_user_can_update_note(){

        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPrivateTestNote();
        $note->title = "New Title";

        //When the user hit's the endpoint to update the task
        $this->actingAs($user)
            ->put('api/notes/'.$note->id, $note->toArray());
        //The task should be updated in the database.
        $this->assertDatabaseHas('notes',['id'=> $note->id , 'title' => 'New Title']);

    }

    /** @test */
    public function unauthorized_user_can_update_note(){
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPublicTestNote();
        $note->title = "New Title";

        //When the user hit's the endpoint to update the task
        $this->put('api/notes/'.$note->id, $note->toArray());
        //The task should be updated in the database.
        $this->assertDatabaseMissing('notes',['id'=> $note->id , 'title' => 'New Title']);

    }

    /** @test */
    public function authorized_user_can_delete_note(){
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPublicTestNote();

        $this->actingAs($user)
            ->delete('api/notes/'.$note->id);
        //The task should be deleted from the database.
        $this->assertDatabaseMissing('notes',['id'=> $note->id]);

    }

    /** @test */
    public function unauthorized_user_cannot_delete_note(){
        $user = User::factory()->has(Folder::factory())->create();
        $note = $this->createPublicTestNote();

        $this->delete('api/notes/'.$note->id);
        //The task should be deleted from the database.
        $this->assertDatabaseHas('notes',['id'=> $note->id]);

    }
    public function createPrivateTestNote()
    {
        $note = Note::factory()->state(function (array $attributes) {
            return [
                'private' => 1,
                'folder_id' => 1,
                'user_id' => 1,
                'type' => 'text'
            ];
        })->create();
        return $note;
    }

    public function createPublicTestNote()
    {
        $note = Note::factory()->state(function (array $attributes) {
            return [
                'private' => 0,
                'folder_id' => 1,
                'user_id' => 1,
                'type' => 'text'
            ];
        })->create();
        return $note;
    }


    public function makePrivateTestNote()
    {
        $note = Note::factory()->state(function (array $attributes) {
            return [
                'private' => 1,
                'folder_id' => 1,
                'user_id' => 1,
                'type' => 'text'
            ];
        })->make();
        return $note;
    }
}
