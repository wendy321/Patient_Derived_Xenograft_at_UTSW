<?php
class PatientID
{
    public static function generatePatientID($db,$isUnlinkedPid)
    {

        $pidLen=7;
        $maxPidNum=9999999;
        $sql = ($isUnlinkedPid)?"SELECT max(Patient_ID) FROM Patient WHERE Patient_ID REGEXP '^[A-Z]{".$pidLen."}$'":
            "SELECT max(Patient_ID) FROM Patient WHERE Patient_ID REGEXP '^[A-Z]{1}[0-9]{".($pidLen-1)."}$'";

        $maxPid=null;
        if ($result = $db->prepare($sql)) {
            $result->execute();
            $result->bind_result($maxPid);
            $result->fetch();
            $result->close();
        }

        $finalPid="";
        if($maxPid===null){
            $finalPid = ($isUnlinkedPid)?str_repeat("A",$pidLen):"A".str_repeat("0",$pidLen-1);
        }else{
            if($isUnlinkedPid){
                // The max unlinked Patient_Id is "ZZZZZZY"
                // If Patient_Id = "ZZZZZZZ", it means the patient is marked as deleted and `Patient`.`isDelete` = 1
                // So the Sample_ID format becomes "ZZZZZZZ********"
                if($maxPid === str_repeat("Z",$pidLen-1)."Y"){
                    $finalPid=str_repeat("A",$pidLen);
                }else{
                    $carry=true;
                    $postFixPid="";
                    for($i=$pidLen-1;$i>=0 && $carry==true;$i--){
                        $char=substr($maxPid,$i,1);
                        if($char==="Z"){
                            $char="A";
                        }else{
                            $char=chr(ord($char)+1);
                            $carry=false;
                        }
                        $postFixPid=$char.$postFixPid;
                    }

                    $len=strlen($postFixPid);
                    $start=$pidLen-$len;
                    $finalPid=substr_replace($maxPid,$postFixPid,$start,$len);
                }
            }else{
                $carry=false;
                $firstChar=substr($maxPid,0,1);
                $res=substr($maxPid,1,$pidLen-1);

                $resNum=intval($res);
                if($resNum >= 0 && $resNum < $maxPidNum){
                    $resNum+=1;
                }else if($resNum == $maxPidNum){
                    $carry=true;
                    $resNum=0;
                }
                $resultRes=str_pad(strval($resNum),$pidLen-1,'0',STR_PAD_LEFT);

                $ascii=ord($firstChar);
                if($carry == true){
                    if($ascii >= 65 && $ascii <= 89){
                        $ascii+=1;
                    }else if($ascii == 90){
                        $ascii=65;
                    }
                }
                $resultFirstChar=chr($ascii);

                $finalPid=$resultFirstChar.$resultRes;
            }
        }

        return $finalPid;
    }

}