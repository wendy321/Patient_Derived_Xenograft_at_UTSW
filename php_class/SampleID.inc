<?php

class SampleID
{
    /* Generate a new Sample ID:
     * This is a function to generate a new sample id based on inputs of patient id, patholigical status, and sample class.
     * The format of sample_id is 6-digit patient_id, 2-digit patholigical_status, 2-digit sample_class, and 2-digit auto-increment.
     * @param object $db database
     * @param string $patientId patient_id
     * @param string or integer $specimenType patholigical_status
     * @param string or integer $sampleClass sample_class
     * @param string or integer $sampleSource sample_source (patient or pdx)
     * @return string sample_id
     * Author: Shin-Yi Lin at UTSW edited on May, 14, 2018
     * */
    public static function generateSampleID($db,$patientId,$specimenType,$sampleClass,$sampleSource)
    {
        $specimenType_Int=$sampleClass_Int=$sampleSource_Int=null;
        if(gettype($specimenType)==="string"){
            $specimenType_Int=(int)$specimenType;
        }elseif(gettype($specimenType)==="integer"){
            $specimenType_Int=$specimenType;
            if($specimenType>=0 && $specimenType<=9){
                $specimenType="0".((string)$specimenType);
            }
        }else{
            return gettype($specimenType);
        }

        if(gettype($sampleClass)==="string"){
            $sampleClass_Int=(int)$sampleClass;
        }elseif(gettype($sampleClass)==="integer"){
            $sampleClass_Int=$sampleClass;
            if($sampleClass>=0 && $sampleClass<=9){
                $sampleClass="0".((string)$sampleClass);
            }
        }else{
            return gettype($sampleClass);
        }

        if(gettype($sampleSource)==="string"){
            $sampleSource_Int=(int)$sampleSource;
        }elseif(gettype($sampleSource)==="integer"){
            $sampleSource_Int=$sampleSource;
            if($sampleSource>=0 && $sampleSource<=9){
                $sampleSource="0".((string)$sampleSource);
            }
        }else{
            return gettype($sampleSource);
        }

        $pidLen=7;
        $finalSampleId=$maxSampleIdDb=$finalPid=null;
        if($patientId===null)
        {
            $sql = "SELECT max(Sample_ID) FROM Sample WHERE Patient_ID IS NULL AND Specimen_Type=? AND Sample_Class=? AND Sample_Source=?";
            $result = $db->prepare($sql);
            $result->bind_param('iii', $specimenType_Int,$sampleClass_Int,$sampleSource_Int);
            $result->execute();
            $result->bind_result($maxSampleIdDb);
            $result->fetch();
            $result->close();
        }else
        {
            $sql = "SELECT max(Sample_ID) FROM Sample WHERE Patient_ID=? AND Specimen_Type=? AND Sample_Class=? AND Sample_Source=?";
            $result = $db->prepare($sql);
            $result->bind_param('siii', $patientId, $specimenType_Int,$sampleClass_Int,$sampleSource_Int);
            $result->execute();
            $result->bind_result($maxSampleIdDb);
            $result->fetch();
            $result->close();
        }


        if($maxSampleIdDb===null){
            if($patientId===null)
            {
                $finalPid=str_repeat("Z",$pidLen);
                $finalSampleId=$finalPid.$specimenType.$sampleClass.$sampleSource."00";
            }else
            {
                $finalPid=$patientId;
                $finalSampleId=$finalPid.$specimenType.$sampleClass.$sampleSource."00";
            }
        }else{
            $last2Digit=(int)substr($maxSampleIdDb,-2,2);
            if($last2Digit<99)
            {
                $last2Digit+=1;
                if($last2Digit<10)
                {
                    $last2Digit="0".(string)$last2Digit;
                }
            }else
            {
                $last2Digit="00";
            }

            if($patientId===null)
            {
                $finalSampleId=substr($maxSampleIdDb,0,-2).$last2Digit;
            }else
            {
                $finalPid=$patientId;
                $finalSampleId=$finalPid.$specimenType.$sampleClass.$sampleSource.$last2Digit;
            }
        }

        return $finalSampleId;

    }

    /* Get the converted Sample ID for web UI displaying:
     * This is a function to get the sample_id by sample_uuid. The sample_id will be displayed on web UI.
     * If a temporary patient_id (which has not linked to Clinical System patient yet) is in sample_id,
     * it'll be converted to openSpecimen_patient_id. But if openSpecimen_patient_id is null, it'll be
     * converted to local_patient_id.
     * @param object $db database
     * @param string $sampleUuid sample uuid
     * @return string a converted sample_id
     * Author: Shin-Yi Lin at UTSW edited on May, 14, 2018
     * */
    public static function getConvertedSampleID($db,$sampleUuid){
        $pidLen=7;
        $finalSampleId=null;
        $sampleId=$patientId=$localPid=$openSpecimenPid=null;
        $sql="SELECT Sample_ID,Patient_ID FROM Sample WHERE UUID=? AND isDelete=0";
        if ($result = $db->prepare($sql)) {
            $result->bind_param('s', $sampleUuid);
            $result->execute();
            $result->bind_result($sampleId,$patientId);
            $result->fetch();
            $result->close();
        }

        //case 1: $sampleid!==null && $pid!==null
        if($sampleId!==null && $patientId!==null){
            if(preg_match("/^[A-Z]{".$pidLen."}$/",$patientId)){
                $sql="SELECT Local_Patient_ID, OpenSpecimen_Patient_ID FROM Patient WHERE Patient_ID=? AND isDelete=0";
                if ($result = $db->prepare($sql)) {
                    $result->bind_param('s', $patientId);
                    $result->execute();
                    $result->bind_result($localPid,$openSpecimenPid);
                    $result->fetch();
                    $result->close();

                    // open specimen patient id has higher priority than local patient id
                    if($openSpecimenPid!==null){
                        $finalsampleid=substr_replace($sampleId,$openSpecimenPid,0,$pidLen);
                    }elseif ($localPid!==null){
                        $finalsampleid=substr_replace($sampleId,$localPid,0,$pidLen);
                    }else{
                        $finalsampleid=$sampleId;
                    }
                }
            }else{
                $finalsampleid=$sampleId;
            }
        }

        // case 2: $sampleid!==null && $pid===null (patient is deleted, but sample is not deleted.)
        if($sampleId!==null && $patientId===null){
            $finalsampleid=$sampleId;
        }

        // other cases: $sampleid===null (It's impossible to happen by the system logic)
        return $finalsampleid;
    }

    /* Generate a Sample ID with changed Patient_ID or changed Specimen_Type:
     * @param object $db database
     * @param string $inPatientId input patient_id from frontend
     * @param string $inSpecimenType input specimen_type from frontend
     * @return string a converted sample_id
     * Author: Shin-Yi Lin at UTSW edited on April, 1, 2019
     * */
    public static function generateSampleIDWiChangePidSpecimenType($db,$inPatientId,$inSpecimenType,$uuid){
        $dbSamClass=$dbSamSrc=null;
        $sql = "SELECT Sample_Class, Sample_Source FROM Sample WHERE UUID = ?";
        if ($result = $db->prepare($sql)) {
            $result->bind_param('s', $uuid);
            $result->execute();
            $result->bind_result($dbSamClass,$dbSamSrc);
            $result->fetch();
            $result->close();
        }
        return SampleID::generateSampleID($db,$inPatientId,$inSpecimenType,$dbSamClass,$dbSamSrc);
    }
}