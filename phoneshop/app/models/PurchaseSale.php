<?php 
	use Illuminate\Database\Eloquent\SoftDeletingTrait;
	class PurchaseSale extends Eloquent{
		use SoftDeletingTrait;
		protected $table = 'purchase-sale';
		protected $fillable = array('transactions_id', 'articles_id', 'tax', 'qty', 'price');
		protected $dates = ['deleted_at'];

		public function articles(){
			return $this->belongsTo('Articles');
		}

		public function transactions(){
			return $this->belongsTo('Transactions');
		}
	}
?>