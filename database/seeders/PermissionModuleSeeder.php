<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $getPer = file_get_contents(storage_path() . '/json/permission_module.json');
        $getPer = json_decode($getPer);
        foreach ($getPer as $per) {
            Permission::create([
                'permission_module_name' => $per->name
            ]);
        }
    }
}
