<?php 
	use Illuminate\Database\Eloquent\SoftDeletingTrait;
	class Articles extends Eloquent{
		use SoftDeletingTrait;
		protected $table = 'articles';
		protected $fillable = array('name', 'manufacturer', 'model', 'imei', 'images_id', 'type', 'stock');
		protected $dates = ['deleted_at'];

		public function purchasesales(){
			return $this->hasMany('PurchaseSale');
		}
	}
?>