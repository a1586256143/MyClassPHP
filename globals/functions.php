<?php
function maps($item , $item2){
	if($item == $item2){
		return $item;
	}else{
		$patten = '/\{([\w\_]+)\}/';
		if(preg_match($patten , $item2 , $match)){
			$_GET[$match[1]] = $item;
		}
		return $item;
	}
}

function getkey_maps($item , $item2){
	if($item == $item2){
		return $item;
	}else{
		return $item2;
	}
}