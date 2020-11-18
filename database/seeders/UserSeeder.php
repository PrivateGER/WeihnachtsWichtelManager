<?php

namespace Database\Seeders;

use App\Models\Benutzer;
use App\Models\Gift;
use App\Models\Relationships;
use App\Models\Wish;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $relations = [
            "Kirsten" => ["Jonas", "Heike"],
            "Heike" => ["Michael", "Janosch"],
            "Udo" => ["Jonas", "Kirsten"],
            "Janosch" => ["Heike", "Hussi"],
            "Hussi" => ["Marita", "Janosch"],
            "Marita" => ["Kirsten", "Michael"],
            "Jonas" => ["Udo", "Hussi"],
            "Michael" => ["Marita", "Udo"]
        ];

        $userMap = [];

        $userID = 1;
        foreach ($relations as $key => $user) {
            Benutzer::create([
                "name" => $key,
                "token" => sha1(random_bytes(64))
            ]);

            $userMap[$key] = $userID;

            $userID++;
        }

        foreach ($relations as $key => $user) {
            foreach ($user as $receiver) {
                Relationships::create([
                    "from" => $userMap[$key],
                    "to" => $userMap[$receiver]
                ]);
            }
        }
    }
}
