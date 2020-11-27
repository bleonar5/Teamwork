<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      $group = new Teamwork\Group();
      $group->group_number = 'RESEARCHER GROUP';
      $group->save();
    }
}
