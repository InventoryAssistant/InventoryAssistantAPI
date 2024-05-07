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

        Category('Kontorartikler');
        Category('Drikkevare');
        Category('Slik');

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
        Unit('stk','styk');


        function Product($name, $barcode, $content, $unit_id, $category_id): void
        {
            $Model = new \App\Models\Product();
            $Model->name = $name;
            $Model->barcode = $barcode;
            $Model->category_id = $category_id;
            $Model->content = $content;
            $Model->unit_id = $unit_id;
            $Model->save();
        }

        Product('Mountain Dew Citrus Blast', 5741000135525, 0.5, 3, 2);
        Product('Office Kuglepen', 7392265460013, 0.5, 4, 1);
        Product('Mountain Dew Citrus Blast', 5741000145500, 1.5, 3, 2);
        Product('Pepsi', 5741000124109, 1.5, 3, 2);
        Product('Honning Snitter', 5709364674777, 144, 1, 3);
        Product('Hit Mix', 577454062231, 375, 1, 3);
        Product('Chokolade Stænger', 5712873288816, 200, 1, 3);
        Product('Snøfler Træstammer', 5709364571274, 100, 1, 3);
        Product('Coca Cola', 57095882, 0.25, 3, 2);
        Product('Coca Cola', 57113098, 1, 3, 2);
        Product('Coca Cola', 57013596, 1.25, 3, 2);
        Product('Coca Cola', 57045795, 1.5, 3, 2);
        Product('Coca Cola', 57089058, 2, 3, 2);
        Product('Pringles Texas BBQ Sauce', 5053990161966, 165, 1, 3);
        Product('Pringles Paprika', 5053990161669, 165, 1, 3);


        Product::find(1)->location_products()->sync([
            1 => ['stock' => 124, 'shelf_amount' => 22],
            2 => ['stock' => 65, 'shelf_amount' => 1]
        ]);
        Product::find(2)->location_products()->sync([
            1 => ['stock' => 4, 'shelf_amount' => 2],
            2 => ['stock' => 0, 'shelf_amount' => 1]
        ]);
        Product::find(3)->location_products()->sync([
            1 => ['stock' => 2, 'shelf_amount' => 22],
            2 => ['stock' => 5, 'shelf_amount' => 32]
        ]);
        Product::find(4)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 22],
            2 => ['stock' => 4, 'shelf_amount' => 14]
        ]);
        Product::find(5)->location_products()->sync([
            1 => ['stock' => 12, 'shelf_amount' => 242],
            2 => ['stock' => 41, 'shelf_amount' => 4]
        ]);
        Product::find(6)->location_products()->sync([
            1 => ['stock' => 21, 'shelf_amount' => 2],
            2 => ['stock' => 54, 'shelf_amount' => 114]
        ]);
        Product::find(7)->location_products()->sync([
            1 => ['stock' => 12, 'shelf_amount' => 23],
            2 => ['stock' => 43, 'shelf_amount' => 11]
        ]);
        Product::find(8)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(9)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(10)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(11)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(12)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(13)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(14)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
        Product::find(15)->location_products()->sync([
            1 => ['stock' => 1, 'shelf_amount' => 1],
            2 => ['stock' => 2, 'shelf_amount' => 2]
        ]);
    }
}
