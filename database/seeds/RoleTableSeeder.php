<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Teamwork\Role::create([
          'id'            => 1,
          'name'          => 'Root',
          'description'   => 'This account has full access and control to the entire app.'
      ]);
      Teamwork\Role::create([
          'id'            => 2,
          'name'          => 'Researcher',
          'description'   => 'Full access to the administrator dashboard.'
      ]);
      Teamwork\Role::create([
          'id'            => 3,
          'name'          => 'Participant',
          'description'   => 'Limited access.'
      ]);
      Teamwork\Role::create([
          'id'            => 4,
          'name'          => 'Group',
          'description'   => 'Group response access.'
      ]);
    }
}
