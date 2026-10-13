<?php
namespace Database\Seeders;
use App\Models\User;use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder{public function run():void{User::firstOrCreate(['email'=>env('DEMO_ADMIN_EMAIL','admin@example.com')],['name'=>'Demo Admin','password'=>env('DEMO_ADMIN_PASSWORD','ChangeMe123!'),'role'=>'admin']);User::firstOrCreate(['email'=>'user@example.com'],['name'=>'Demo User','password'=>'ChangeMe123!','role'=>'user']);}}