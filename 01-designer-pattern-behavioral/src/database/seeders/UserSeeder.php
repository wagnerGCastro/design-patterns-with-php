<?php

use Phinx\Seed\AbstractSeed;
use Illuminate\Support\Str;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {

        // $data = [
        //     'uuid' => Str::uuid(),
        //     'first_name' =>  'Wagner',
        //     'last_name' =>  'Castro',
        //     'login' =>  'wagner.castro',
        //     'email' =>  'wagner.castro@test.com',
        //     'email_verified_at' => date('Y-m-d H:i:s'),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        // ];

        $faker = Faker\Factory::create();

        $data = [];
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'uuid' => Str::uuid(),
                'first_name'    => $faker->firstName,
                'last_name'     => $faker->lastName,
                'login'         => $faker->userName,
                'password'      => sha1($faker->password),
                'email'         => $faker->email,
                'created_at'    => date('Y-m-d H:i:s'),
            ];
        }

        $posts = $this->table('users');
        $posts->insert($data)->save();
    }
}
