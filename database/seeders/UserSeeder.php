<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->name = "Hasbi";
        $user->email = "231210013@student.mercubuana-yogya.ac.id";
        $user->password = bcrypt("1234");
        $user->phone = "082135277434";
        $user->alamat = "Yogyakarta";
        $user->role = "admin";
        $user->save();
    }
    public function down()
     {
        Schema::dropIfExists('users');
    }
}
