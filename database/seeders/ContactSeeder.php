<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ja_JP');
        $categories = Category::pluck('id', 'content');
        $tagIds = Tag::pluck('id');
        $details = [
            '商品のお届けについて' => '注文した商品がまだ届いていません。発送状況をご確認いただけますでしょうか。',
            '商品の交換について' => '届いた商品のサイズが合わなかったため、交換を希望しております。',
            '商品トラブル' => '購入した商品に傷がありました。交換または返品の対応をお願いいたします。',
            'ショップへのお問い合わせ' => '営業時間と店舗での受け取りについて教えてください。',
            'その他' => 'サービスについて確認したい点がありますので、ご回答をお願いいたします。',
        ];

        for ($i = 0; $i < 20; $i++) {
            $categoryContent = $faker->randomElement(array_keys($details));

            $contact = Contact::create([
                'category_id' => $categories[$categoryContent],
                'first_name' => $faker->lastName(),
                'last_name' => $faker->firstName(),
                'gender' => $faker->numberBetween(1, 3),
                'email' => $faker->unique()->safeEmail(),
                'tel' => '0'.$faker->numerify('##########'),
                'address' => $faker->prefecture().$faker->city().$faker->streetAddress(),
                'building' => $faker->optional()->secondaryAddress(),
                'detail' => $details[$categoryContent],
            ]);

            $contact->tags()->attach(
                $faker->randomElements($tagIds->all(), $faker->numberBetween(1, 3))
            );
        }
    }
}
