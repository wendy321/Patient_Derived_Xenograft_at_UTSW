<?php
class ChangeHistory
{
    private $db;

    function __construct($db) {
        $this->db = $db;
    }

    public function recordChangeHistory($table,$primarykey,$primarykeyid,$fieldname,$newvalue,$account)
    {
        // only one id
        if(strpos($primarykeyid,",") == false){
            $sql="SELECT ".$fieldname." FROM ".$table." WHERE ".$primarykey." = '".$primarykeyid."'";
            $oldvalue=null;
            if($result = $this->db->prepare($sql)){
                $result->execute();
                $result->bind_result($oldvalue);
                $result->fetch();
                $result->close();
                $oldvalue=($oldvalue==null)?$oldvalue:(string)$oldvalue;
                $newvalue=($newvalue==null)?$newvalue:(string)$newvalue;
                if($oldvalue==$newvalue){
                    return false;
                }else{
                    $sql="INSERT INTO ChangeHistory (TableName,Primary_Key,Field_Name,From_Value,To_Value,Account,".
                        "ChangeTime) VALUES (?,?,?,?,?,?,NOW())";
                    if($result = $this->db->prepare($sql)){
                        $result->bind_param("ssssss",$table,$primarykeyid,$fieldname,$oldvalue,$newvalue,$account);
                        $result->execute();
                        $result->close();
                    }
                    return true;
                }
            }
            return false;
        }// more than one id
        else{
            $primarykeyids_arr = explode(",",$primarykeyid);
            $sql="SELECT ".$fieldname." FROM ".$table." WHERE ".$primarykey." = ".$primarykeyids_arr[0];
            $oldvalue=null;
            if($result = $this->db->prepare($sql)){
                $result->execute();
                $result->bind_result($oldvalue);
                $result->fetch();
                $result->close();

                if($oldvalue!=$newvalue || ($oldvalue!==null && $newvalue!==null && (string)$oldvalue!==$newvalue)){
                    //insert change history
                    for($i=0;$i<count($primarykeyids_arr);$i++){
                        $sql="INSERT INTO ChangeHistory (TableName,Primary_Key,Field_Name,From_Value,To_Value,Account,".
                            "ChangeTime) VALUES (?,?,?,?,?,?,NOW())";

                        if($result = $this->db->prepare($sql)){
                            $result->bind_param("ssssss",$table,str_replace("\"","",$primarykeyids_arr[$i]),$fieldname,$oldvalue,$newvalue,$account);
                            $result->execute();
                            $result->close();
                        }
                    }
                    return true;
                }else{
                    return false;
                }
            }
            return false;
        }
    }
}