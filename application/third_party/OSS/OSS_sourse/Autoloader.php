<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
//spl_autoload_register("Autoloader::autoload");

Autoloader::autoload1('a');

class Autoloader
{	
	private static $autoloadPathArray = array(
		"Core",
		"Http",
		"Model",
		"Result"
	);
	
	public static function autoload1($className)
	{
//		foreach (self::$autoloadPathArray as $path) {
//			$file = dirname(__DIR__).DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$className.".php";
//			$file = str_replace('\\', DIRECTORY_SEPARATOR, $file);echo $file;
//			if(is_file($file)){
//				include_once $file;
//				break;
//			}
//		}

		$path = APPPATH .'third_party/OSS/';
		$file_arr = self::getFiles($path,true);
		//var_dump($file_arr);
		foreach($file_arr as $k=>$v){
			if(is_array($v)){


				//var_dump($v);

				foreach($v as $p){
					//var_dump($p);
					$ex = explode('.',$p);
					if(isset($ex[1])){
						//echo APPPATH . 'third_party/OSS/' . $k . '/' . $p . '<br>';
						require_once APPPATH . 'third_party/OSS/' . $k . '/' . $p;
					}

				}



				//echo '<br>';
				foreach($v as $item){
					//echo APPPATH . 'third_party/OSS/' . $k . '/' . $item . '222<br>';
					//$ex = explode('.',$item);
//					if($item != 'LICENSE') {
//						echo APPPATH . 'third_party/OSS/' . $k . '/' . $item . '<br>';
//						require_once APPPATH . 'third_party/OSS/' . $k . '/' . $item;
//					}
				}
			}else{
				if(isset(explode('.',$v)[1]) && explode('.',$v)[1] == 'php'){
					//echo $v;
					//echo APPPATH .'third_party/OSS/'.$v.'<br>';
					//require_once APPPATH .'third_party/OSS/'.$v;
					require_once APPPATH . 'third_party/OSS/OssClient.php';
				}
			}

		}





	}
	
	public static function addAutoloadPath($path)
	{
		array_push(self::$autoloadPathArray, $path);
	}

	/**
	 *获取某个目录下所有文件
	 *@param $path文件路径
	 *@param $child 是否包含对应的目录
	 */
	public  static function getFiles($path,$child=false){
		$files=array();
		if(!$child){
			if(is_dir($path)){
				$dp = dir($path);
			}else{
				return null;
			}
			while ($file = $dp ->read()){
				if($file !="." && $file !=".." && is_file($path.$file)){
					$files[] = $file;
				}
			}
			$dp->close();
		}else{
			self::scanfiles($files,$path);
		}
		return $files;
	}
	/**
	 *@param $files 结果
	 *@param $path 路径
	 *@param $childDir 子目录名称
	 */
	public static function scanfiles(&$files,$path,$childDir=false){
		$dp = dir($path);
		while ($file = $dp ->read()){
			if($file !="." && $file !=".."){
				if(is_file($path.$file)){//当前为文件
					$files[]= $file;
				}else{//当前为目录
					self::scanfiles($files[$file],$path.$file.DIRECTORY_SEPARATOR,$file);
				}
			}
		}
		$dp->close();
	}

}

?>