<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //menambahkan data ke table di database tanpa melalui input form (data default/bawaan)
       User::create([
        "name" => "Administrator",
        "email" => "admin@gmail.com",
        // hash = enkripsi password tersimpan berisi text acak agar tidak bisa diprediksi/dibaca oleh yang lain
        // hash -> bcrypt
        "password" => Hash::make("adminapotek"),
        "role" => "admin"
       ]);
       User::create([
        "name" => "Kasir Apotek",
        "email" => "kasir@gmail.com",
        "password" => Hash::make("kasirapotek"),
        "role" => "cashier"
       ]);
    }
}
