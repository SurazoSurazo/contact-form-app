<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_contacts_index_api_returns_json_with_meta_and_validates(): void
    {
        $category = Category::factory()->create();
        Contact::factory()->count(3)->create(['category_id' => $category->id, 'first_name' => '田中']);

        $this->getJson('/api/v1/contacts?keyword=田中&per_page=2')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta' => ['current_page', 'last_page', 'per_page', 'total']])
            ->assertJsonPath('meta.per_page', 2);

        $this->getJson('/api/v1/contacts?gender=0')->assertStatus(422);
    }

    public function test_contact_api_crud(): void
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $payload = $this->contactData($category->id, [$tag->id]);

        $createdId = $this->postJson('/api/v1/contacts', $payload)
            ->assertCreated()
            ->assertJsonPath('data.first_name', '山田')
            ->json('data.id');

        $this->getJson("/api/v1/contacts/{$createdId}")
            ->assertOk()
            ->assertJsonPath('data.category.id', $category->id);

        $this->putJson("/api/v1/contacts/{$createdId}", array_merge($payload, ['first_name' => '鈴木']))
            ->assertOk()
            ->assertJsonPath('data.first_name', '鈴木');

        $this->deleteJson("/api/v1/contacts/{$createdId}")->assertNoContent();
        $this->getJson("/api/v1/contacts/{$createdId}")->assertNotFound();
    }

    public function test_contact_api_store_validation_returns_422(): void
    {
        $this->postJson('/api/v1/contacts', [])->assertStatus(422);
    }

    public function test_contact_api_update_validation_and_not_found_responses(): void
    {
        $category = Category::factory()->create();
        $contact = Contact::factory()->create(['category_id' => $category->id]);

        $this->putJson("/api/v1/contacts/{$contact->id}", [])->assertStatus(422);
        $this->putJson('/api/v1/contacts/999999', $this->contactData($category->id))->assertNotFound();
        $this->deleteJson('/api/v1/contacts/999999')->assertNotFound();
        $this->getJson('/api/v1/contacts/999999')->assertNotFound();
    }

    private function contactData(int $categoryId, array $tagIds = []): array
    {
        return [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'api@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷ビル301',
            'category_id' => $categoryId,
            'detail' => 'APIから作成します。',
            'tag_ids' => $tagIds,
        ];
    }
}
