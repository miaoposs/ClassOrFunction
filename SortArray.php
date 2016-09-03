<?php 

	//php中函数不要与内置函数同名（不区分大小写）
	//SortArray(1,10,0);

	/**
	 * 选择调用什么排序方法进行排序
	 * 0 => 冒泡  1=> 插入排序 2 => 选择排序 3 => 快速排序
	 * @param $choose     排序的种类
	 * @param $param      如果为数字则表示自动生成的数组长度，为数组则是需要排序的数组
	 * @param $order      排序的方式，0表示从小到大，非零表示从大到小
	 * @return            返回排好序的array      
	 */
	function SortArray($choose,$param,$order=0)
	{
		if (!empty($param)) {
			if (!is_numeric($param)) {
				if (!is_array($param)) {
					die('您的第二个参数类型有误，必须为数组或不小于1的数字');
				}else{
					$array = $param; 
				}
			}else if($param < 1){
				die('您请求的数组长度不符合要求，需要时不小于1的数字');
			}else{
				$array = CreateArray($param);
			}
		}else{
			die('您调用的函数需要第二位参数');
		}
		
		echo '<pre>','排序之前：<br />';
		PrintArray($array);
		switch ($choose) {
			case 0:
				$ret = BubblingSort($array);
				break;

			case 1:
				$ret = InsertionSort($array);
				break;

			case 2:
				$ret = SelectionSort($array);
				break;

			case 3:
				$ret = QuickSort($array,0,count($array));
				//$ret1 = BubblingSort($array);
				break;
			
			default:
				die('您的参数无效，请确保值在0~3之间');
				break;
		}

		if ($order !== 0) {
			$ret = array_reverse($ret);
		}

		echo '<pre>','排序之后：<br />';
		PrintArray($ret);
	}



	
	/**
	 *产生随机数组，长度自定义
	 * @param $length     生成的数组的长度
	 * @return            返回自动生成的array      
	 */
	function CreateArray($length)
	{
		$array = array();
		for ($i=0; $i < $length; $i++) { 
			$array[] = mt_rand(0,100);
		}
		return $array;
	}

	/**
	 * 打印数组
	 * @param $array      需要打印的数组 
	 */
	function PrintArray($array)
	{
		$length = count($array);
		for ($i=0; $i < $length; $i++) { 
			echo $array[$i],' ';
		}
		echo '<br />';
	}

	/**
	 *冒泡排序,直接上无序数组
	 * @param $array      需要排序的数组
	 * @return            返回排序好的array  
	 */

	function BubblingSort($array)
	{
		if (!is_array($array)) {
			die('第一个参数必须为数组');
		}

		$arr_length = count($array);

		if ($arr_length <= 1) {
			return $array;
		}

		for ($i=0; $i < $arr_length; $i++) { 
			for ($j=0; $j < $arr_length-$i-1; $j++) { 
				if ($array[$j] > $array[$j+1]) {
					$temp = $array[$j+1];
					$array[$j+1] = $array[$j];
					$array[$j] = $temp;
				}
			}
		}
		return $array;
	}


	

	/**
	 * 插入排序
	 * @param $array      需要排序的数组
	 * @return            返回排序好的array 
	 */
	function InsertionSort($array)
	{
		if (!is_array($array)) {
			die('第一个参数必须为数组');
		}

		$arr_length = count($array);

		if ($arr_length <= 1) {
			return $array;
		}

		for ($i=1; $i < $arr_length; $i++) {
			//0~$j表示当前的有序区间
			for ($j=0; $j < $i; $j++) { 
			 	if ($array[$i] < $array[$j]) {
			 		$temp = $array[$i];
			 		//将$array[$i]插入有序数组里面
			 		for ($k=$i; $k > $j; $k--) { 
			 			$array[$k] = $array[$k-1];
			 		}
			 		$array[$j] = $temp;
			 	}
			} 
		}

		return $array;
	}


	

	/**
	 * 选择排序
	 * @param $array      需要排序的数组
	 * @return            返回排序好的array 
	 */
	function SelectionSort($array)
	{
		if (!is_array($array)) {
			die('第一个参数必须为数组');
		}

		$arr_length = count($array);

		if ($arr_length <= 1) {
			return $array;
		}

		for ($i=0; $i < $arr_length-1; $i++) {
			$temp = $array[$i];
			$index = $i;
			//找出最小值
			for ($j=$i+1; $j < $arr_length; $j++) { 
				if ($array[$j] < $temp) {
					$temp = $array[$j];
					$index = $j;
				}
			}
			$array[$index] = $array[$i];
			$array[$i] = $temp;
		}

		return $array;
	}

	


	/**
	 * 快速排序,在数组中如果有相同值时可能会出现问题
	 * @param $array      需要排序的数组,由于php数组默认是值传递，这里强制使用引用传递
	 * @param $begin      当前快速排序区间的首位下标
	 * @param $end        当前排序区间的末尾+1下标
	 * @return            返回排序好的array 
	 */
	function QuickSort(&$array,$begin,$end)
	{
		//static $count = 0;
		//$count++;
		if($end-1 <= $begin){
			return $array;
		}

		$handler = $array[$begin];
		$index = $begin;
		$i = $begin;
		$j = $end;

		while ($i != $j) {
			for (; $j > $i; ) { 
				$j--;
				if ($array[$j] < $handler) {
					$temp = $array[$i];
					$array[$i] = $array[$j];
					$array[$j] = $temp;
					$index = $j;
					break;
				}
			}
			if ($j == $i) {
				break;
			}
			for (; $i < $j; ) {
				$i++;
				if ($array[$i] > $handler) {
					$temp = $array[$j];
					$array[$j] = $array[$i];
					$array[$i] = $temp;
					$index = $i;
					break;
				}
				
			}
		}

		//$index = array_search($handler,$array);      //当数组有重复数据时，这种寻找下标的做法很危险

		QuickSort($array,$begin,$index);
		QuickSort($array,$index+1,$end);

		return $array;
	}