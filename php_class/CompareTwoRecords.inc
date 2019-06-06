<?php
class CompareTwoRecords
{
    public static function compareTwoPatients($patient1,$patient2,$db){
        $result_arr = [];

        $sql = "SELECT Patient_ID, Local_Patient_ID, Data_Contributor_Clinical_Trial_Group, Data_Contributor_Center, 
                Age_At_Enrollment_In_Days, Age_At_First_Visit_In_Days, Relapsed_At_Enrollment, Relapsed_At_First_Visit,
                Age_At_Diagnosis_In_Days, Year_Of_Diagnosis, Dysgenetic_Gonad, Sex, Race, Ethnic, Vital_Status, COG_Stage,
                FIGO_Stage, AJCC_Stage, IGCCCG_RiskGroup FROM Patient WHERE Patient_ID IN (?,?)";
        if ($result = $db->prepare($sql)) {
            $pid=$localPid=$contriClinical=$contriCenter=$ageEnrollDay=$ageFirstVisitDay=
                $relapseEnroll=$relapseFirstVisit=$ageDiagDay=$yrDiag=$dysGonad=$sex=$race=$ethnic=$vitalStatus=
                $cog=$figo=$ajcc=$igcccg=null;
            $result->bind_param('ss',$patient1,$patient2);
            $result->execute();
            $result->bind_result($pid,$localPid,$contriClinical,$contriCenter,$ageEnrollDay,$ageFirstVisitDay,
                $relapseEnroll,$relapseFirstVisit,$ageDiagDay,$yrDiag,$dysGonad,$sex,$race,$ethnic,$vitalStatus,
                $cog,$figo,$ajcc,$igcccg);
            while($result->fetch()){
                $result_arr[$pid]=array("localPid"=>$localPid, "contriClinical"=>$contriClinical, "contriCenter"=>$contriCenter,
                        "ageEnrollDay"=>$ageEnrollDay,"ageFirstVisitDay"=>$ageFirstVisitDay,"relapseEnroll"=>$relapseEnroll,
                        "relapseFirstVisit"=>$relapseFirstVisit,"ageDiagDay"=>$ageDiagDay,"yrDiag"=>$yrDiag,
                        "dysGonad"=>$dysGonad,"sex"=>$sex,"race"=>$race,"ethnic"=>$ethnic,"vitalStatus"=>$vitalStatus,
                        "cog"=>$cog,"figo"=>$figo,"ajcc"=>$ajcc,"igcccg"=>$igcccg);
            }
            $result->close();
        }

        if(sizeof($result_arr) > 0){
            foreach($result_arr[$patient1] as $k => $v){
                $val1=$result_arr[$patient1][$k];
                $val2=$result_arr[$patient2][$k];
                if($val1!=null && $val2!=null && $val1!=$val2)
                    return array('different',null);

                if($val1!=null && $val2==null)
                    $result_arr["combined"][$k]=$val1;
                else
                    $result_arr["combined"][$k]=$val2;
            }
            return array('same',$result_arr["combined"]);
        }else{
            return array('nodata',null);
        }
    }

    public static function compareTwoLocalPatients($patient1,$firstIsLocal,$patient2,$secondIsLocal,$db){
        // both are local patient id, then compare them directly
        if($firstIsLocal && $secondIsLocal){
            return array('same',null);
        }
        // first is local, second is system, then get local of system and compare them
        elseif($firstIsLocal && !$secondIsLocal){
            $localpatientid1=$patient1;
            $localpatientid2=null;
            // get local patient id of the select system patient id
            $sql = "SELECT Local_Patient_ID FROM Patient WHERE Patient_ID=?";
            if ($result = $db->prepare($sql)) {
                $result->bind_param('s',$patient2);
                $result->execute();
                $result->bind_result($localpatientid2);
                $result->fetch();
                $result->close();
            }

            // if no local patient is found by the select system patient id
            if($localpatientid2==null){
                // check whether the input local patient id is used by other patient
                $patientid1=null;
                $sql = "SELECT Patient_ID FROM Patient WHERE Local_Patient_ID=?";
                if ($result = $db->prepare($sql)) {
                    $result->bind_param('s',$localpatientid1);
                    $result->execute();
                    $result->bind_result($patientid1);
                    $result->fetch();
                    $result->close();
                }
                if($patientid1==null){
                    return array('localnotexist',null);
                }else{
                    return array('localexist',null);
                }
            }// if local patient is found by the select system patient id
            else{
                // compare two local patient ids
                if($localpatientid1!=$localpatientid2){
                    return array('different',$localpatientid2);
                }else{
                    return array('same',null);
                }
            }
        }
        // first is system, second is local, then get local of system and compare them
        elseif(!$firstIsLocal && $secondIsLocal){
            return array('same',null);
        }
        // both are system
        else{
            $arr=CompareTwoRecords::compareTwoPatients($patient1,$patient2,$db);
            $result=$arr[0];
            $localPid=$arr[1]["localPid"];
            if($result=="same"){
                return array($result,$localPid);
            }else{
                return $arr;
            }
        }
    }
}