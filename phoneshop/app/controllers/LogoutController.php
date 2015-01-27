<?php

	class LogoutController extends BaseController {

		public function getIndex(){			 
			Auth::logout();
			return array(
						'message' => 'Success.',
						'errors' => '',
						'statusCode' => 200,
						);
		}
	}

?>