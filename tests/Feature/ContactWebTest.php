<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_and_thanks_pages_are_displayed(): void
    {
        $category = Category::factory()->create(['content' => '商品のお届けについて']);
        $tag = Tag::factory()->create(['name' => '質問']);

        $this->get('/')
            ->assertOk()
            ->assertViewIs('contact.index')
            ->assertViewHas('categories')
            ->assertViewHas('tags')
            ->assertSee($category->content)
            ->assertSee($tag->name);

        $this->get('/thanks')->assertOk()->assertViewIs('contact.thanks');
    }

    public function test_contact_confirm_displays_valid_input(): void
    {
        $category = Category::factory()->create(['content' => 'その他']);
        $tag = Tag::factory()->create(['name' => '要望']);

        $this->post('/contacts/confirm', $this->contactData($category->id, [$tag->id]))
            ->assertOk()
            ->assertViewIs('contact.confirm')
            ->assertSee('山田')
            ->assertSee('test@example.com')
            ->assertSee('その他')
            ->assertSee('要望');
    }

    public function test_contact_store_saves_contact_and_tags(): void
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $this->post('/contacts', $this->contactData($category->id, [$tag->id]))
            ->assertRedirect('/thanks');

        $this->assertDatabaseHas('contacts', ['email' => 'test@example.com']);
        $this->assertDatabaseCount('contact_tag', 1);
    }

    public function test_admin_requires_authentication_and_authenticated_user_can_view(): void
    {
        $this->get('/admin')->assertRedirect('/login');

        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin')->assertOk()->assertViewIs('admin.index');
    }

    public function test_admin_search_paginates_and_detail_delete_work(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['content' => '商品トラブル']);
        Contact::factory()->count(8)->create(['category_id' => $category->id, 'first_name' => 'Sato']);
        $contact = Contact::first();

        $this->actingAs($user)
            ->get('/admin?keyword=Sato&category_id='.$category->id)
            ->assertOk()
            ->assertSee('商品トラブル')
            ->assertSee('Pagination Navigation');

        $this->actingAs($user)->get("/admin/contacts/{$contact->id}")
            ->assertOk()
            ->assertViewIs('admin.show');

        $this->actingAs($user)->delete("/admin/contacts/{$contact->id}")
            ->assertRedirect('/admin');

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function test_authenticated_user_can_manage_tags(): void
    {
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => '質問']);

        $this->actingAs($user)->post('/admin/tags', ['name' => '重要'])
            ->assertRedirect('/admin');
        $this->assertDatabaseHas('tags', ['name' => '重要']);

        $this->actingAs($user)->get("/admin/tags/{$tag->id}/edit")
            ->assertOk()
            ->assertViewIs('admin.tags.edit');

        $this->actingAs($user)->put("/admin/tags/{$tag->id}", ['name' => '更新済み'])
            ->assertRedirect('/admin');
        $this->assertDatabaseHas('tags', ['name' => '更新済み']);

        $this->actingAs($user)->delete("/admin/tags/{$tag->id}")
            ->assertRedirect('/admin');
        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    public function test_guest_is_redirected_from_tag_management(): void
    {
        $tag = Tag::factory()->create();

        $this->post('/admin/tags', ['name' => '重要'])->assertRedirect('/login');
        $this->get("/admin/tags/{$tag->id}/edit")->assertRedirect('/login');
        $this->put("/admin/tags/{$tag->id}", ['name' => '更新'])->assertRedirect('/login');
        $this->delete("/admin/tags/{$tag->id}")->assertRedirect('/login');
    }

    public function test_authenticated_user_can_download_filtered_csv(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['content' => 'その他']);
        Contact::factory()->create([
            'category_id' => $category->id,
            'first_name' => 'Csv',
            'last_name' => 'Target',
            'email' => 'csv@example.com',
        ]);
        Contact::factory()->create();

        $response = $this->actingAs($user)->get('/contacts/export?keyword=Csv&category_id='.$category->id);

        $response->assertOk();
        $response->assertHeader('content-disposition', 'attachment; filename=contacts.csv');
        $this->assertStringContainsString('csv@example.com', $response->streamedContent());
    }

    private function contactData(int $categoryId, array $tagIds = []): array
    {
        return [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷ビル301',
            'category_id' => $categoryId,
            'detail' => '商品について確認したいです。',
            'tag_ids' => $tagIds,
        ];
    }
}
