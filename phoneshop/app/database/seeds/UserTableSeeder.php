<?php
	
	class UserTableSeeder extends Seeder{

		public function run()
	       {
	         //insert some dummy records
	         DB::table('users')->insert(array(
	             array('name'=>'Sample','email'=>'sample@sample.com','password'=>Hash::make('sample')),
	          ));
	       }
	}
?>