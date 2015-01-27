<?php

	class CustomerController extends BaseController{


		public function getAll(){
			try{
				$customer = Customers::select('id', 'name', 'email', 'phone')->get();
				
				return array(
							'message' => 'Success.',
							'errors' => '',
							'customer' => $customer,
							'statusCode' => 200,
							);
			}catch(Exception $e){
				
				return array(
							'message' => 'Unexpected Database Error.',
							'errors' => '',
							'statusCode' => 408,
							);
			}
		}

		public function getCustomerdet($id=NULL){
			$image = new Images;
			$customer = Customers::find($id);
			if($customer){
				$returnData = array(
									'id' => $customer->id,
									'name' => $customer->name,
									'postcode' => $customer->postcode,
									'street' => $customer->street,
									'city' => $customer->city,
									'country' => $customer->country,
									'email' => $customer->email,
									'phone' => $customer->phone,
									'image1' => $image->imgloc($customer->image1),
									'image2' => $image->imgloc($customer->image2)
									);
				return array(
							'message' => 'Success.',
							'errors' => '',
							'customer' => $returnData,
							'statusCode' => 200
							);
			}else{

				return array(
							'message' => 'Invalid Customer Detail Request.',
							'errors' => '',
							'statusCode' => 409,
							);
			}

		}

		public function postNewcustomer(){
			$validator = Validator::make(Input::all(),array(
				'customer_name' => 'required',
				'postcode' => 'required',
				'street' => 'required',
				'city' => 'required',
				'country' => 'required',
				'image1' => 'required|mimes:jpeg,jpg,png',
				'image2' => 'required|mimes:jpeg,jpg,png',
				'phone' => 'required',
				));
			if($validator->fails()){
				return array(
							'message' => 'Data Error.',
							'errors' => $validator->messages(),
							'statusCode' => 400,
							);
			}

			$image = new Images;
			$image1 = Input::file('image1');
			$image2 = Input::file('image2');
			if(isset($image1)){
				$img1 = $image->addimage($image1);
			}
			if(isset($image2)){
				$img2 = $image->addimage($image2);
			}

			if($img1 == 'Not valid file.' || $img1 == 'Not a valid image.' || $img2 == 'Not valid file.' || $img2 == 'Not a valid image.'){
				$imgName = 'image2';
				try{
					Images::find($img1['img'])->delete();
				}catch(Exception $e){
					$imgName = 'image1';
				}
				return array(
							'message' => 'Image Upload Error.',
							'errors' => [$imgName => 'Image file not valid.'],
							'statusCode' => 403,
							);		
			}else if($img1 == 'Image size out of limit.' || $img2 == 'Image size out of limit.'){
				$imgName = 'image2';
				try{
					Images::find($img1['img'])->delete();
				}catch(Exception $e){
					$imgName = 'image1';
				}
				return array(
							'message' => 'Image Upload Error.',
							'errors' => [$imgName=>'Image size out of limit.'],
							'statusCode' => 405,
							);		
			}else if($img1 == 'Not Uploaded.' || $img2 == 'Not Uploaded.'){
				$imgName = 'image2';
				try{
					Images::find($img1['img'])->delete();
				}catch(Exception $e){
					$imgName = 'image1';
				}
				return array(
							'message' => 'Unexpected Image Upload Error.',
							'errors' => '',
							'statusCode' => 406,
							);		
			}

			$imgId1 = $imgId2 = 1;
			if(isset($img1['message'])){
				// not uploaded
				$imgId1 = $img1['img'];
			}

			if(isset($img2['message'])){
				// not uploaded
				$imgId2 = $img2['img'];
			}

			try{
				$customer = new Customers;

				$customer->name = Input::get('customer_name');
				$customer->postcode = Input::get('postcode');
				$customer->street = Input::get('street');
				$customer->city = Input::get('city');
				$customer->country = Input::get('country');
				$customer->email = Input::get('email');
				$customer->phone = Input::get('phone');
				$customer->image1 = $imgId1;
				$customer->image2 = $imgId2;
				$customer->save();

				return array(
							'message' => 'Success',
							'errors' => '',
							'customer' => $customer->id,
							'statusCode' => 200,
							);
			}catch(Exception $e){

				return array(
							'message' => 'Unable to add Customer details.',
							'errors' => '',
							'statusCode' => 407,
							);
			}
		}
	}
?>