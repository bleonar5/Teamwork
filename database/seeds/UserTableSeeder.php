<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $role_researcher  = Teamwork\Role::where('name', 'researcher')->first();

      $researcher = new Teamwork\User();
      $researcher->name = 'SkillsLabAdmin';
      $researcher->email = 'skillslab@hks.harvard.edu';
      $researcher->password = bcrypt('skillslab');
      $researcher->role_id = 2;
      $researcher->participant_id = 0;
      $researcher->group_id = 1;
      $researcher->save();
    }
}
