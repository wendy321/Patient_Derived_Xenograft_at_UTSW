<?php
class EscapeString
{
        public static function escape($data)
        {
               if(gettype($data)==="string"){
                   $data = trim($data);
               }
               $data = stripslashes($data);
               $data = htmlspecialchars($data,ENT_QUOTES);
               return $data;
        }
}
?>
