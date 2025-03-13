<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class MenuSeederDefault extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (!Menu::where('title', 'Dashboard')->first()) {
            $dashboard = Menu::create(['title' => 'Dashboard', 'code' => 'dashboard']);
        }

        if (!Menu::where('title', 'Setting')->first()) {
            $setting = Menu::create(['title' => 'Setting', 'code' => 'setting']);

            Menu::create(['title' => 'Roles', 'code' => 'setting.roles', 'parent_id' => $setting->id]);
            Menu::create(['title' => 'Permissions', 'code' => 'setting.permissions', 'parent_id' => $setting->id]);
        }

        if (!Menu::where('title', 'Master')->first()) {
            $master = Menu::create(['title' => 'Master', 'code' => 'master']);

            Menu::create(['title' => 'Branch', 'code' => 'master.products', 'parent_id' => $master->id]);
        }

        if (!Menu::where('title', 'Asuransi')->first()) {
            $master = Menu::where('title', 'Master')->first();

            $asuransi = Menu::create(['title' => 'Asuranasi', 'code' => 'master.asuransi', 'parent_id' => $master->id]);
            Menu::create(['title' => 'Vendor Asuranasi', 'code' => 'master.asuransi.vendor', 'parent_id' => $asuransi->id]);
        }

        if (!Permission::where('name', 'menu:master')->first()) {
            Permission::create(['name' => 'menu:master', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'menu:master.branch')->first()) {
            Permission::create(['name' => 'menu:master.branch', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'view:master.branch-create')->first()) {
            Permission::create(['name' => 'view:master.branch-create', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'view:master.branch-update')->first()) {
            Permission::create(['name' => 'view:master.branch-update', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'filter:master.branch-name')->first()) {
            Permission::create(['name' => 'filter:master.branch-name', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'filter:master.branch-code')->first()) {
            Permission::create(['name' => 'filter:master.branch-code', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'menu:master.asuransi')->first()) {
            Permission::create(['name' => 'menu:master.asuransi', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'menu:master.asuransi.vendor')->first()) {
            Permission::create(['name' => 'menu:master.asuransi.vendor', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'view:master.asuransi.vendor-create')->first()) {
            Permission::create(['name' => 'view:master.asuransi.vendor-create', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'view:master.asuransi.vendor-update')->first()) {
            Permission::create(['name' => 'view:master.asuransi.vendor-update', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'filter:master.asuransi.vendor-name')->first()) {
            Permission::create(['name' => 'filter:master.asuransi.vendor-name', 'guard_name' => 'api']);
        }

        if (!Permission::where('name', 'filter:master.asuransi.vendor-code')->first()) {
            Permission::create(['name' => 'filter:master.asuransi.vendor-code', 'guard_name' => 'api']);
        }


    }
}
