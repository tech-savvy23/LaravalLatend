<?php

use Illuminate\Database\Seeder;
use Bitfumes\Multiauth\Model\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $models        = ['Auditor', 'Contractor', 'Customer', 'Report'];
        $tasks         = ['Create', 'Read', 'Update', 'Delete'];
        foreach ($tasks as $task) {
            foreach ($models as $model) {
                $name       = "{$task}{$model}";
                $permission = factory(Permission::class)->create(['name' => $name]);
            }
        }
    }
}
