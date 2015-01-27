<?php 
	use Illuminate\Database\Eloquent\SoftDeletingTrait;
	class Transactions extends Eloquent{
		use SoftDeletingTrait;
		protected $table = 'transactions';
		protected $fillable = array('customers_id', 'user_id', 'bill_number', 'type', 'signature');
		protected $dates = ['deleted_at'];

		public function customers(){
			return $this->belongsTo('Customers');
		}

		public function purchasesales(){
			return $this->hasMany('PurchaseSale');
		}
	}
?>