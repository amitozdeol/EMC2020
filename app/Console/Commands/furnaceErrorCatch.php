<?php

class furnaceErrorCatch  {


     public static function catchMissingData($variable){
        // $variable = $variables;
        //   try{
        //         if(! $variable->isEmpty()){
        //          return $variable;
        //           // dd($AQS1->toArray());
        //          }else{
        //           throw new Exception ('No entry has been found in the database for this field');
        // }      
            

        //     }catch(Exception $e){
        //     return $e->getMessage();
        //     }

        // if( is_array($variables) and !empty($variables)){
        //  return 'noo';
        // }else{
        //   return 'hello';
        // }

      if(isset($variable))
            return $variable;
       elseif (empty($variable) || is_null($variable))
             return  $variable = 'There is No Data for this field';
      else 
            return  $variable =  'There is No Data for this field';

     }

}

?>
