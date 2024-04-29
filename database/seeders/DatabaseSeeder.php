<?php

namespace Database\Seeders;

use App\Enums\TokenEnum;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use function Database\Seeders\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        function Ability($name): void
        {
            $Model = new \App\Models\Ability();
            $Model->name = $name;
            $Model->save();
        }

        $ablities = TokenEnum::cases();
        foreach ($ablities as $ability) {
            Ability($ability->value);
        }

        function Role($name): void
        {
            $Model = new \App\Models\Role();
            $Model->name = $name;
            $Model->save();
        }

        Role("user");
        Role("moderator");
        Role("admin");

        Role::find(1)->role_abilities()->sync([1, 2]);
        Role::find(2)->role_abilities()->sync([1, 2, 3, 4, 5, 6]);
        Role::find(3)->role_abilities()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        function Location($address): void
        {
            $Model = new \App\Models\Location();
            $Model->address = $address;
            $Model->save();
        }

        Location("Munkebjergvej 130");
        Location("Risingsvej 130");

        function User($first_name, $last_name, $phone_number, $email, $password, $location_id, $role_id): void
        {
            $Model = new \App\Models\User();
            $Model->first_name = $first_name;
            $Model->last_name = $last_name;
            $Model->phone_number = $phone_number;
            $Model->email = $email;
            $Model->email_verified_at = now();
            $Model->password = Hash::make($password);
            $Model->location_id = $location_id;
            $Model->role_id = $role_id;
            $Model->save();
        }

        User('John', 'Doe', '1111111111', 'JohnDoe@gmail.com', 'Password', 1, 1);
        User('Jane', 'Doe', '1222222222', 'JaneDoe@gmail.com', 'Password', 1, 2);
        User('Jack', 'Doe', '1233333333', 'JackDoe@gmail.com', 'Password', 1, 3);
        User('John', 'Smith', '1234444444', 'JohnSmith@gmail.com', 'Password', 2, 1);
        User('Jane', 'Smith', '1234555555', 'JaneSmith@gmail.com', 'Password', 2, 2);
        User('Jack', 'Smith', '1234566666', 'JackSmith@gmail.com', 'Password', 2, 3);

        function Category($name): void
        {
            $Model = new \App\Models\Category();
            $Model->name = $name;
            $Model->save();
        }

        Category('Office Supplies');
        Category('Drinks');

        function Unit($abbreviation, $name): void
        {
            $Model = new \App\Models\Unit();
            $Model->abbreviation = $abbreviation;
            $Model->name = $name;
            $Model->save();
        }

        Unit('g','Gram');
        Unit('kg','Kilogram');
        Unit('l','Liter');


        function Product($name, $barcode, $content, $unit_id, $category_id): void
        {
            $Model = new \App\Models\Product();
            $Model->name = $name;
            $Model->barcode = $barcode;
            $Model->category_id = $category_id;
            $Model->content = $content;
            $Model->save();
        }

        Product('Mountain Dew Citrus Blast 50 cl', 5741000135525, 0.5, 3, 2);
        Product('Office Kuglepen, 50 Stk./ 1 Pk', 7392265460013, 0.5, 3, 1);

        Product::find(1)->location_products()->sync([
            1 => ['stock' => 124, 'shelf_amount' => 22],
            2 => ['stock' => 65, 'shelf_amount' => 1]
        ]);
        Product::find(2)->location_products()->sync([
            1 => ['stock' => 4, 'shelf_amount' => 2],
            2 => ['stock' => 0, 'shelf_amount' => 1]
        ]);
    }
}
