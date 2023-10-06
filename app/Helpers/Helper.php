<?php

/**
 * removeSpecialCharacters
 *
 * @param string $string
 * @return float
 */
function removeSpecialCharacters($string){
    $text = explode(',',$string);
 
    if(isset($text[1])){
        $existe = strpos($text[1],'.');
        if($existe != null && $existe > 0){
            $removeSign = str_replace( '$','',trim($string));
            $removePoint = str_replace(',','',$removeSign);
            $removeEat = $removePoint;
            
            $price = (float)$removePoint;
            
            return $price;
        }
    }

    $removeSign = str_replace( '$','',trim($string));
    $removePoint = str_replace('.','',$removeSign);
    $removeEat = str_replace(',','.',$removePoint);
    
    $price = (float)$removeEat;

    return $price;
}