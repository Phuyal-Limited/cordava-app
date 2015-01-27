<?php 
	use Illuminate\Database\Eloquent\SoftDeletingTrait;
	class Images extends Eloquent{
		use SoftDeletingTrait;
		protected $table = 'images';
		protected $fillable = array('path', 'description');
		protected $dates = ['deleted_at'];


		public function imgloc($id=NULL){
			if(!$id){
			
				return false;
			}else if($id == 1){
                return URL::to('assets/images/not_available.jpg');
            }else{
            	$image = $this->where('id', $id)->first();
            	return $image->path;
            }
		}

		public function addimage($image){
			if ($image->isValid()){

				$size = $image->getSize();
				$ext = $image->getClientOriginalExtension();
				$type = $image->getMimeType();
				if (!(($ext == "jpg" || $ext == "jpeg" || $ext == "png") && ($type == "image/jpeg" || $type == "image/jpg" || $type == "image/png"))){
				    return 'Not a valid image.';
				}
				if(!($size < 2120000)){
					return 'Image size out of limit.';	
				}

				$path = public_path() . '/images';
				if(!file_exists($path)){
	                mkdir($path, 0777, true);
	            }

	            try {
					$lastimage = DB::table('images')->select('id')->orderBy('id', 'DESC')->take(1)->get();
					$imgID = 2;
					if($lastimage){
						$imgID = $lastimage[0]->id + 1;
					}

					$uploadImage = $imgID.'.'.$ext;
					$destinationPath = $path.'/'.$uploadImage;
					$img = new Images;
		    		
					$img->path = $destinationPath;
		            $img->save();


	                $image->move($path,$uploadImage);
	             	
	            } catch(Exception $e) {
	             
	                // Handle your error here.
	                // You might want to log $e->getMessage() as that will tell you why the file failed to move.
	                $error['error'] = $e->getMessage();
	                return 'Not Uploaded.';
	            }

				return array(
							'message' => 'Success',
							'img' => $img->id,
							);
			}else{

				return 'Not valid file.';
			}
		}
	}
?>