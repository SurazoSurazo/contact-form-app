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
        $categoryIds = Category::pluck('id');
        $tagIds = Tag::pluck('id');

        for ($i = 0; $i < 20; $i++) {
            $contact = Contact::create([
                'category_id' => $faker->randomElement($categoryIds),
                'first_name' => $faker->lastName(),
                'last_name' => $faker->firstName(),
                'gender' => $faker->numberBetween(1, 3),
                'email' => $faker->unique()->safeEmail(),
                'tel' => '0'.$faker->numerify('##########'),
                'address' => $faker->prefecture().$faker->city().$faker->streetAddress(),
                'building' => $faker->optional()->secondaryAddress(),
                'detail' => $faker->realText(80),
            ]);

            $contact->tags()->attach(
                $faker->randomElements($tagIds->all(), $faker->numberBetween(1, 3))
            );
        }
    }
}
