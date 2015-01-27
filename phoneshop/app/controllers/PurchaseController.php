<?php

	class PurchaseController extends BaseController{

		public function postPurchase($transactionId, $articleData){
			foreach ($articleData as $eachArticle) {
			
				$validator = Validator::make($eachArticle,array(
					'tax' => 'required',
					'price' => 'required',
					));
				if($validator->fails()){
					return array(
								'message' => 'Data Error.',
								'errors' => $validator->messages(),
								'statusCode' => 400,
								);
				}
			}

			$purchaseIds = array();
			foreach ($articleData as $eachArticle) {
				
				try{
					$tax = 0;
					$price = $eachArticle['price'];
					
					if($eachArticle['tax']){
						$price = $price/1.19;
						$tax = 0.19*$price;
					}
					$purchase = new PurchaseSale;

					$purchase->transactions_id = $transactionId;
					$purchase->articles_id = $eachArticle['id'];
					$purchase->tax = $tax;
					$purchase->qty = 1;
					$purchase->price = $price;
					$purchase->save();


					array_push($purchaseIds, $purchase->id);
				}catch(Exception $e){
					if($purchaseIds != array()){
						DB::table('purchase-sale')->whereIn('id', $purchaseIds)->delete();
					}

					return array(
								'message' => 'Unable to add Purchase details.',
								'errors' => '',
								'statusCode' => 407,
								);
				}
			}
			return array(
						'message' => 'Success.',
						'errors' => '',
						'purchase' => $purchaseIds,
						'statusCode' => 200,
						);
		}

		public function postSell($transaction, $article){
			// $validator = Validator::make(Input::all(),array(
			// 	'qty' => 'required|integer',
			// 	'price' => 'required',
			// 	));
			// if($validator->fails()){
			// 	return array(
			// 				'message' => 'Data Error.',
			// 				'errors' => $validator->messages(),
			// 				'statusCode' => 400,
			// 				);
			// }
			
			// foreach ($article['phones'] as $eachPhone) {
			// 	$purchase = new PurchaseSale;

			// 	$purchase->transactions_id = $transactionId;
			// 	$purchase->articles_id = $eachPhone['id'];
			// 	$purchase->tax = 39;
			// 	$purchase->qty = Input::get('qty');
			// 	$purchase->price = Input::get('price');
			// 	$purchase->save();
			// }
			// try{
			// 	$purchase = new PurchaseSale;

			// 	$purchase->transactions_id = $transactionId;
			// 	$purchase->articles_id = $articleId;
			// 	$purchase->tax = 39;//Input::get('tax');
			// 	$purchase->qty = Input::get('qty');
			// 	$purchase->price = Input::get('price');
			// 	$purchase->save();

			// 	return array(
			// 				'message' => 'Success.',
			// 				'errors' => '',
			// 				'purchase' => $purchase->id,
			// 				'statusCode' => 200,
			// 				);
			// }catch(Exception $e){

			// 	return array(
			// 				'message' => 'Unable to add Purchase details.',
			// 				'errors' => '',
			// 				'statusCode' => 407,
			// 				);
			// }	
		}
	}
?>