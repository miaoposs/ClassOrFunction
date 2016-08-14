<?php

	/**
	*文件上传demo,支持多文件上传,支持中文文件名,暂不支持目录检测，即存在同名文件会直接覆盖
	*/
	class FileUpload
	{
		public $errorInfo = '';
		private $fileFormat = array('gif','png','jpeg','doc','docx','xls','txt','rar','zip');


		/**
		*文件上传方法，能兼容<input name="file[]">以及多个input组成的两种多文件上传方式
		*
		*@param1  $key       string|array   require   文件上传表单中input的name，$_FILE的一级下标
		*@param2  $path      string         require   文件保存的路径
		*@param3  $fileName  string|array   optional  文件名，当此参数为空时，将以源文件名保存,数组只支持索引数组，多文件上传时
		*                                             文件名数量不必与上传文件数一样
		*/
		public function Upload($key, $path, $fileName='')
		{
			//检查是否上传了文件
			if (!empty($_FILES)) {

				if (!is_array($key)) {
					$key = array($key);
				}
				$i = -1;
				foreach ($key as $value) {
					//由于多文件上传两种形式的操作filename的方式不一样，$i是多个input上传时fielname的下标
					$i++;
					$file = $_FILES["{$value}"];
					$error = $file['error'];
		 			if (!is_array($error)) {
		 				$error = array($error);
		 			}

		 			foreach ($error as $index => $row) {
		 				switch ($row) {
		 					case 1:
		 						$this->errorInfo = '文件大小超过php配置文件限定上限';
		 						break;

		 					case 2:
		 						$this->errorInfo = '文件大小超过表单限定上限';
		 						break;

		 					case 3:
		 						$this->errorInfo = '文件只有部分被上传';
		 						break;

		 					case 4:
		 						$this->errorInfo = '文件没有被上传';
		 						break;

		 					case 6:
		 						$this->errorInfo = '找不到临时文件夹';
		 						break;

		 					case 7:
		 						$this->errorInfo = '文件写入失败';
		 						break;

		 					case 0:
		 						$temp = '';
		 						$name = is_array($file['name']) ? $file['name']["{$index}"] : $file['name'];
		 						$tmp_name = is_array($file['tmp_name']) ? $file['tmp_name']["{$index}"] : $file['tmp_name'];
		 						$extension = substr($name, strrpos($name, '.')+1);
		 						if (!in_array($extension, $this->fileFormat)) {
		 							$this->errorInfo = '文件格式不被支持';
		 						}

		 						if (is_array($fileName)) {
		 							$index = $i > $index ? $i : $index;
		 							if (isset($fileName["{$index}"])) {
		 								$temp = $fileName["{$index}"].'.'.$extension;
		 							}else {
		 								$temp = $name;
		 							}
		 						}elseif (empty($fileName)) {
		 							$temp = $name;
		 						}else{
		 							$temp = $fileName.'.'.$extension;
		 						}

		 						if(!move_uploaded_file($tmp_name, iconv('utf-8', 'gb2312', $path.$temp))) {
									$this->errorInfo = '文件上传失败';
								}

		 						break;
		 					
		 					default:
		 						$this->errorInfo = '出现了不能识别的文件上传错误标识';
		 						break;
		 				}
		 			}
				}

		 	}else{
		 		$errorInfo = '您未选择上传文件';
		 	}
		}
	}

?>