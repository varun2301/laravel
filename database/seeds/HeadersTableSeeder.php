<?php

use Illuminate\Database\Seeder;

class HeadersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(DB::table('headers')->get()->count() == 0){

            DB::table('headers')->insert([

                [
                    'header_name' => 'Dashboard',
                    'header_slug' => 'dashboard',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Milestones',
                    'header_slug' => 'milestones',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Tasks',
                    'header_slug' => 'tasks',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Bugs',
                    'header_slug' => 'bugs',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Calendar',
                    'header_slug' => 'calendar',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Documents',
                    'header_slug' => 'documents',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Timesheet',
                    'header_slug' => 'timesheet',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Forums',
                    'header_slug' => 'forums',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Pages',
                    'header_slug' => 'pages',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Users',
                    'header_slug' => 'users',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Reports',
                    'header_slug' => 'reports',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'header_name' => 'Logs',
                    'header_slug' => 'logs',
                    'description' => '',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]

            ]);

        } else { echo "\e[31mTable is not empty, therefore NOT "; }

    }
}
