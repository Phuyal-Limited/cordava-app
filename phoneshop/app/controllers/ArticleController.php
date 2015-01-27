<?php


	class ArticleController extends BaseController{


		public function postStore(){
			$phones = Input::get('article');
			foreach ($phones as $eachPhone) {
				$validator = Validator::make($eachPhone,array(
					'article_name' => 'required',
					'manufacturer' => 'required',
					'model' => 'required',
					'imei' => 'required',
					'article_type' => 'required',
					'image' => 'required|mimes:jpeg,jpg,png'
					));
				if($validator->fails()){
					return array(
								'message' => 'Data Error.',
								'errors' => $validator->messages(),
								'statusCode' => 400,
								);
				}	
			}
			
			$articleData = array();
			$articleIds = array();
			foreach ($phones as $eachPhone) {
				
				$image = new Images;
				if(isset($eachPhone['image'])){
					$img = $eachPhone['image'];
					$imgData = $image->addimage($img);
				}else{
					return array(
								'message' => 'Data Error.',
								'errors' => ['image'=>'Article Image Required.'],
								'statusCode' => 400,
								);	
				}
				if($imgData == 'Not valid file.' || $imgData == 'Not a valid image.'){
					return array(
								'message' => 'Image Upload Error.',
								'errors' => ['image'=>'Image file not valid.'],
								'statusCode' => 403,
								);		
				}else if($imgData == 'Image size out of limit.'){
					return array(
								'message' => 'Image Upload Error.',
								'errors' => ['image'=>'Image size out of limit.'],
								'statusCode' => 405,
								);		
				}else if($imgData == 'Not Uploaded.'){
					return array(
								'message' => 'Unexpected Image Upload Error.',
								'errors' => '',
								'statusCode' => 406,
								);		
				}

				$imgId = 1;
				if(isset($imgData['message'])){
					// not uploaded
					$imgId = $imgData['img'];
				}

				try{
					$article = new Articles;

					$article->name = $eachPhone['article_name'];
					$article->manufacturer = $eachPhone['manufacturer'];
					$article->model = $eachPhone['model'];
					$article->imei = $eachPhone['imei'];
					$article->images_id = $imgId;
					$article->stock = 1;//Input::get('stock');
					$article->type = $eachPhone['article_type'];
					$article->save();

					array_push($articleData, array('id'=>$article->id, 'tax'=> $eachPhone['tax'], 'price'=>$eachPhone['price']));
					array_push($articleIds, $article->id);
				}catch(Exception $e){
					if($articleIds != array()){
						DB::table('articles')->whereIn('id', $articleIds)->delete();
					}

					return array(
								'message' => 'Unable to add Article details.',
								'errors' => '',
								'statusCode' => 407
								);
				}
			}
			$returnData = array('articleIds' => $articleIds, 'articleData' => $articleData);
			return array(
						'message' => 'Success.',
						'errors' => '',
						'article' => $returnData,
						'statusCode' => 200,
						);
		}

		public function getAll(){
			try{
				$articles = Articles::select('id', 'name', 'stock')->where('stock', '!=', 0)->get();
				
				return array(
							'message' => 'Success.',
							'errors' => '',
							'article' => $articles,
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

		public function getArticledet($id=NULL){
			$image = new Images;
			$article = Articles::find($id);
			if($article){
				$returnData = array(
									'id' => $article->id,
									'name' => $article->name,
									'manufacturer' => $article->manufacturer,
									'model' => $article->model,
									'imei' => $article->imei,
									'type' => $article->type,
									'stock' => $article->stock,
									'image' => $image->imgloc($article->images_id),
									);
				return array(
							'message' => 'Success.',
							'errors' => '',
							'article' => $returnData,
							'statusCode' => 200
							);
			}else{

				return array(
							'message' => 'Invalid Article Detail Request.',
							'errors' => '',
							'statusCode' => 409,
							);
			}

		}

		public function postSell(){
			// try{
			// 	$others = Input::get('others');
			// 	$phones = Input::get('phones');
			// 	$count = count($phones);
			// }catch(Exception $e){
			// 	return array(
			// 				'message' => 'Data Error.',
			// 				'errors' => '',
			// 				'statusCode' => 400,
			// 				);
			// }
			
			// $checkphone = Articles::where('stock', '!=', 0)->lists('id');
			
			// foreach ($phones as $eachPhone) {
			// 	if(!in_array($eachPhone['id'], $checkphone)){

			// 		return array(
			// 				'message' => 'One of the article not valid.',
			// 				'errors' => '',
			// 				'statusCode' => 0,
			// 				);
			// 	}
			// }
			
			// $sellPhone = array();
			// $sellOther = array();
			// foreach ($phones as $eachPhone) {
			// 	$artcile = Articles::find($eachPhone['id']);

			// 	$article->stock = 0;
			// 	$article->save();

			// 	array_push($sellPhone, $article->id);
			// }

			// $othersData = array();
			// try{
			// 	foreach ($others as $eachOther) {
			// 		$newArticle = new Articles;

			// 		$newArticle->name = $eachOther['name'];
			// 		$newArticle->stock = 0;
			// 		$newArticle->type = 'other';
			// 		$newArticle->save();

			// 		$otherArticle = array(
			// 							'id' => $newArticle->id,
			// 							'price' => $eachOther['price'],
			// 							'qty' => $eachOther['qty'],
			// 							);

			// 		array_push($sellOther, $newArticle->id);
			// 		array_push($othersData, $otherArticle);
			// 	}
			// }catch(Exception $e){
			// 	DB::table('articles')->whereIn('id', $sellPhone)->update(array('stock'=>1));

			// 	return array(
			// 				'message' => 'Data Add Error.',
			// 				'errors' => '',
			// 				'statusCode' => 0,
			// 				);
			// }
			// $returnData = array(
			// 					'phones'=>$phones,
			// 					'others'=>$othersData,
			// 					'phoneIds' => $sellPhone,
			// 					'otherIds' => $sellOther
			// 					);

			// return array(
			// 			'message' => 'Success',
			// 			'errors' => '',
			// 			'article' => $returnData,
			// 			'statusCode' => 200,
			// 			);
		}
	}
?>