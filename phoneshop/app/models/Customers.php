<?php 
	use Illuminate\Database\Eloquent\SoftDeletingTrait;
	class Customers extends Eloquent{
		use SoftDeletingTrait;
		protected $table = 'customers';
		protected $fillable = array('name', 'postcode', 'street', 'city', 'country', 'email', 'phone', 'image1', 'image2');
		protected $dates = ['deleted_at'];

		public function transactions(){
			return $this->hasMany('Transactions');
		}
	}
?>