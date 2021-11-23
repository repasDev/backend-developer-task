<?php

namespace Tests\Feature;

use App\Models\Folder;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FolderControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function an_authorized_user_can_read_all_the_folders()
    {
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        //When
        $response = $this->actingAs($user)
            ->get('api/folders');

        //Assert
        $response->assertSee($folder->title);
    }

    /** @test */
    public function an_unauthorized_user_cannot_read_all_the_folders()
    {
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        //When
        $response = $this->get('api/folders');
        //Assert
        $response->assertUnauthorized();
    }

    /** @test */
    public function an_authorized_user_can_read_a_folder()
    {
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        $note = Note::factory()->create(['user_id' => "1", 'folder_id' => "1", 'type' => 'text']);
        //When
        $response = $this->actingAs($user)
            ->get('api/folders/'.$folder->id);
        //Assert
        $response->assertSee($note->title);
    }

    /** @test */
    public function an_unauthorized_user_cannot_read_a_folder()
    {
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        $note = Note::factory()->create(['user_id' => "1", 'folder_id' => "1", 'type' => 'text']);
        //When
        $response = $this->get('api/folders/'.$folder->id);

        //Assert
        $response->assertUnauthorized();
    }

    /** @test */
    public function authenticated_users_can_create_a_new_folder()
    {
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        //When
        $this->actingAs($user)
            ->post('api/notes', $folder->toArray());
        //Assert
        $this->assertEquals(1, Folder::all()->count());
    }

    /** @test */
    public function unauthenticated_users_can_create_a_new_folder()
    {
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        //When
        $response = $this->post('api/notes', $folder->toArray());
        //Assert
        $response->assertUnauthorized();
    }

    /** @test */
    public function authorized_user_can_update_folder(){
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        $folder->title = "New Title";
        //When
        $response = $this->actingAs($user)
            ->put('api/folders/'.$folder->id, $folder->toArray());
        //Assert
        $response->assertExactJson($folder->jsonSerialize());

    }

    /** @test */
    public function unauthorized_user_cannot_update_folder(){
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        $folder->title = "New Title";
        //When
        $response = $this->put('api/folders/'.$folder->id, $folder->toArray());
        //Assert
        $response->assertUnauthorized();
    }

    /** @test */
    public function authorized_user_can_delete_folder(){
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        //When
        $this->actingAs($user)
            ->delete('api/folders/'.$folder->id);
        //Assert
        $this->assertDatabaseMissing('folders',['id'=> $folder->id]);

    }

    /** @test */
    public function unauthorized_user_cannot_delete_folder(){
        //Given
        $user = User::factory()->create();
        $folder = Folder::factory()->create(['user_id' => "1"]);
        //When
        $this->delete('api/folders/'.$folder->id);
        //Assert
        $this->assertDatabaseHas('folders',['id'=> $folder->id]);

    }

}

