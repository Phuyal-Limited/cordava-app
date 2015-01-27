<?php

	class TransactionController extends BaseController{


		public function postStore($customerId){

			$validator = Validator::make(Input::all(),array(
				'bill_number' => 'required',
				'transaction_type' => 'required',
				));
			if($validator->fails()){
				return array(
							'message' => 'Data Error.',
							'errors' => $validator->messages(),
							'statusCode' => 400,
							);
			}

			try{
				$transaction = new Transactions;

				$transaction->customers_id = $customerId;
				$transaction->user_id = Auth::user()->id;
				$transaction->bill_number = Input::get('bill_number');
				$transaction->type = Input::get('transaction_type');
				$transaction->signature = 0;
				$transaction->save();

				return array(
							'message' => 'Success.',
							'errors' => '',
							'transaction' => $transaction->id,
							'statusCode' => 200,
							);
			}catch(Exception $e){

				return array(
							'message' => 'Unable to add Transaction details.',
							'errors' => '',
							'statusCode' => 407,
							);
			}

		}

		public function postBuy(){
			$customer = new CustomerController;
			$article = new ArticleController;
			$transaction = new TransactionController;
			$purchase = new PurchaseController;

			$articleData = $article->postStore();
			if(!isset($articleData['article'])){
				return $articleData;
			}
			$customerData = $customer->postNewcustomer();
			if(!isset($customerData['customer'])){
				DB::table('articles')->whereIn('id', $articleData['article']['articleIds'])->delete();

				return $customerData;
			}
			$transactionData = $transaction->postStore($customerData['customer']);
			if(!isset($transactionData['transaction'])){
				DB::table('articles')->whereIn('id', $articleData['article']['articleIds'])->delete();

				DB::table('customers')->where('id', $customerData['customer'])->delete();

				return $transactionData;
			}
			$purchaseData = $purchase->postPurchase($transactionData['transaction'], $articleData['article']['articleData']);
			if(!isset($purchaseData['purchase'])){
				DB::table('articles')->whereIn('id', $articleData['article']['articleIds'])->delete();

				DB::table('customers')->where('id', $customerData['customer'])->delete();

				$delTransaction = Transactions::find($transactionData['transaction']);
				$delTransaction->delete();

				return $purchaseData;
			}

			return array(
						'message' => 'Success.',
						'errors' => '',
						'statusCode' => 200,
						);
		}

		public function postSell(){
			// $customer = new CustomerController;
			// $article = new ArticleController;
			// $transaction = new TransactionController;
			// $purchase = new PurchaseController;

			// $articleData = $article->postSell();
			// if(!isset($articleData['article'])){
			// 	return $articleData;
			// }
			// $customerData = $customer->postNewcustomer();
			// if(!isset($customerData['customer'])){
			// 	DB::table('articles')->whereIn('id', $articleData['otherIds'])->update(array('stock'=>1));
			// 	DB::table('articles')->whereIn('id', $articleData['otherIds'])->delete();

			// 	return $customerData;
			// }
			// $transactionData = $transaction->postStore($customerData['customer']);
			// if(!isset($transactionData['transaction'])){
			// 	DB::table('articles')->whereIn('id', $articleData['otherIds'])->update(array('stock'=>1));
			// 	DB::table('articles')->whereIn('id', $articleData['otherIds'])->delete();

			// 	$delCustomer = Customers::find($customerData['customer']);
			// 	$delCustomer->delete();

			// 	return $transactionData;
			// }
			// $purchaseData = $purchase->postSell($transactionData['transaction'], $articleData['article']);
			// if(!isset($purchaseData['purchase'])){
			// 	DB::table('articles')->whereIn('id', $articleData['otherIds'])->update(array('stock'=>1));
			// 	DB::table('articles')->whereIn('id', $articleData['otherIds'])->delete();

			// 	$delCustomer = Customers::find($customerData['customer']);
			// 	$delCustomer->delete();

			// 	$delTransaction = Transactions::find($transactionData['transaction']);
			// 	$delTransaction->delete();

			// 	return $purchaseData;
			// }

			// return array(
			// 			'message' => 'Success.',
			// 			'errors' => '',
			// 			'statusCode' => 200,
			// 			);
		}
	}
?>