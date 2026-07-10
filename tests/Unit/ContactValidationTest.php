<?php

namespace Tests\Unit;

use App\Http\Requests\Api\V1\IndexContactRequest as ApiIndexContactRequest;
use App\Http\Requests\Api\V1\StoreContactRequest as ApiStoreContactRequest;
use App\Http\Requests\ExportContactRequest;
use App\Http\Requests\IndexContactRequest;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ContactValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_store_validation_accepts_valid_data_with_tags(): void
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $data = $this->contactData($category->id, [$tag->id]);

        $validator = Validator::make($data, (new StoreContactRequest)->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_contact_store_validation_rejects_invalid_tel(): void
    {
        $category = Category::factory()->create();
        $data = $this->contactData($category->id, ['tel' => '090-1234-5678']);

        $validator = Validator::make($data, (new StoreContactRequest)->rules());

        $this->assertFalse($validator->passes());
    }

    public function test_admin_search_validation_rejects_invalid_gender(): void
    {
        $validator = Validator::make(['gender' => 9], (new IndexContactRequest)->rules());

        $this->assertFalse($validator->passes());
    }

    public function test_export_validation_accepts_filters_and_rejects_invalid_values(): void
    {
        $category = Category::factory()->create();

        $valid = Validator::make([
            'keyword' => '山田',
            'gender' => 0,
            'category_id' => $category->id,
            'date' => now()->toDateString(),
        ], (new ExportContactRequest)->rules());

        $invalid = Validator::make([
            'gender' => 9,
            'category_id' => 999,
        ], (new ExportContactRequest)->rules());

        $this->assertTrue($valid->passes());
        $this->assertFalse($invalid->passes());
    }

    public function test_api_index_validation_uses_api_filter_rules(): void
    {
        $category = Category::factory()->create();

        $valid = Validator::make([
            'keyword' => '田中',
            'gender' => 1,
            'category_id' => $category->id,
            'date' => now()->toDateString(),
            'per_page' => 100,
            'page' => 1,
        ], (new ApiIndexContactRequest)->rules());

        $invalid = Validator::make([
            'gender' => 0,
            'per_page' => 101,
            'page' => 0,
        ], (new ApiIndexContactRequest)->rules());

        $this->assertTrue($valid->passes());
        $this->assertFalse($invalid->passes());
    }

    public function test_api_store_validation_accepts_required_fields_and_rejects_invalid_values(): void
    {
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $valid = Validator::make($this->contactData($category->id, [$tag->id]), (new ApiStoreContactRequest)->rules());
        $invalid = Validator::make($this->contactData($category->id, [
            'gender' => 9,
            'tel' => '090-1234-5678',
            'tag_ids' => [999],
        ]), (new ApiStoreContactRequest)->rules());

        $this->assertTrue($valid->passes());
        $this->assertFalse($invalid->passes());
    }

    public function test_tag_validation_requires_unique_name_and_update_ignores_itself(): void
    {
        $tag = Tag::factory()->create(['name' => '質問']);

        $storeValidator = Validator::make(['name' => '質問'], (new StoreTagRequest)->rules());
        $this->assertFalse($storeValidator->passes());

        $request = UpdateTagRequest::create("/admin/tags/{$tag->id}", 'PUT', ['name' => '質問']);
        $request->setRouteResolver(fn () => new class($tag)
        {
            public function __construct(private Tag $tag) {}

            public function parameter(string $name)
            {
                return $name === 'tag' ? $this->tag : null;
            }
        });

        $updateValidator = Validator::make(['name' => '質問'], $request->rules());
        $this->assertTrue($updateValidator->passes());

        $otherTag = Tag::factory()->create(['name' => '要望']);
        $duplicateRequest = UpdateTagRequest::create("/admin/tags/{$tag->id}", 'PUT', ['name' => $otherTag->name]);
        $duplicateRequest->setRouteResolver(fn () => new class($tag)
        {
            public function __construct(private Tag $tag) {}

            public function parameter(string $name)
            {
                return $name === 'tag' ? $this->tag : null;
            }
        });

        $duplicateValidator = Validator::make(['name' => $otherTag->name], $duplicateRequest->rules());
        $this->assertFalse($duplicateValidator->passes());
    }

    private function contactData(int $categoryId, array $overrides = []): array
    {
        $data = [
            'first_name' => '山田',
            'last_name' => '太郎',
            'gender' => 1,
            'email' => 'test@example.com',
            'tel' => '09012345678',
            'address' => '東京都渋谷区1-1-1',
            'building' => '渋谷ビル301',
            'category_id' => $categoryId,
            'detail' => '商品について確認したいです。',
            'tag_ids' => [],
        ];

        if (array_key_exists(0, $overrides)) {
            $data['tag_ids'] = $overrides;

            return $data;
        }

        return array_merge($data, $overrides);
    }
}
