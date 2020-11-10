<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionType;

class SubscriptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subs = file_get_contents(storage_path() . '/json/subscription_type.json');
        $subs = json_decode($subs);
        foreach ($subs as $sub) {
            SubscriptionType::create([
                'sub_title' => $sub->sub_title,
                'sub_description' => $sub->sub_description,
                'sub_description' => $sub->sub_description,
            ]);
        }
    }
}
