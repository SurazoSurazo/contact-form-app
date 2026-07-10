<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_contacts(): void
    {
        $category = Category::factory()->create();
        Contact::factory()->count(2)->create(['category_id' => $category->id]);

        $this->assertCount(2, $category->contacts);
    }

    public function test_contact_belongs_to_category_and_syncs_tags(): void
    {
        $category = Category::factory()->create();
        $contact = Contact::factory()->create(['category_id' => $category->id]);
        $tags = Tag::factory()->count(2)->create();

        $contact->tags()->sync($tags->pluck('id'));

        $this->assertTrue($contact->category->is($category));
        $this->assertCount(2, $contact->fresh()->tags);
    }

    public function test_tag_belongs_to_many_contacts(): void
    {
        $tag = Tag::factory()->create();
        $contacts = Contact::factory()->count(2)->create();
        $tag->contacts()->attach($contacts->pluck('id'));

        $this->assertCount(2, $tag->contacts);
    }
}
