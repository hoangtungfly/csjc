<?php

namespace common\lib;

use PHPExcel_Cell;
use PHPExcel_IOFactory;


require_once(__DIR__.'/PHPExcel.php');

class DPHPExcel {
    
    public $objReader;
    public $objPHPExcel;
    public $worksheet;
    public $highestRow;
    public $highestColumn;
    public $highestColumnIndex;
    
    public function __construct($filename,$readWrite = true) {
        $inputFileType = PHPExcel_IOFactory::identify($filename);
        $this->objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $this->objReader->setReadDataOnly($readWrite);
        $this->objPHPExcel = $this->objReader->load($filename);
        if(!$readWrite) {
            $this->objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
            $this->objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
            $this->objWrite = PHPExcel_IOFactory::createWriter($this->objPHPExcel, "Excel2007");
        }
    }
    
    public function readExcel($sheet = 0,$rowIndex = 1) {
        $this->worksheet = $this->objPHPExcel->setActiveSheetIndex($sheet);
        $this->highestRow = $this->worksheet->getHighestRow();
        $this->getHighestColumn();
        $results = array();
        for ($row = $rowIndex; $row <= $this->highestRow;  $row++) {
            for($col = 0; $col < $this->highestColumnIndex; $col++) {
                $results[$row][$col] = $this->worksheet->getCellByColumnAndRow($col, $row)->getValue();
            }
        }
        return $results;
    }
    
    public function getHighestColumn() {
        $this->highestColumn = $this->worksheet->getHighestColumn();
        $this->highestColumnIndex = PHPExcel_Cell::columnIndexFromString($this->highestColumn);
    }
    
    public function inputDataDetail($col, $row, $value) {
        if(is_array($value)) {
            $a = $this->worksheet->getCellByColumnAndRow($col, $row)->setValue($value[0]);
            if(isset($value['bold'])) {
                $this->worksheet->getStyleByColumnAndRow($col, $row)->getFont()->setBold($value['bold']);
            }
            if(isset($value['align'])) {
                $this->worksheet->getStyleByColumnAndRow($col, $row)->getAlignment()->setHorizontal($value['align']);
            }
            if(isset($value['type'])) {
                $this->worksheet->getStyleByColumnAndRow($col, $row)->getNumberFormat()->setFormatCode($value['type']);
            }
        } else {
            $this->worksheet->getCellByColumnAndRow($col, $row)->setValue($value);
        }
    }
    
    public function inputData($data,$sheet = 0,$begin_row = 3,$begin_col = 0) {
        if($data) {
            $this->worksheet = $this->objPHPExcel->setActiveSheetIndex($sheet);
            $this->highestRow = $this->worksheet->getHighestRow();
            $this->getHighestColumn();
            foreach($data as $k => $rowObj) {
                $row = $k + $begin_row;
                foreach($rowObj as $j => $value) {
                    $col = $j + $begin_col;
                    $this->inputDataDetail($col, $row, $value);
                }
            }
        }
    }
    
    public function writeExcel($data,$sheet = 0,$begin_row = 3,$begin_col = 0, $filename = 'file.xlsx') {
        $this->inputData($data, $sheet, $begin_row, $begin_col);
        if($filename) {
            $link = 'files/test.xlsx';
            $this->objWrite->save($link);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            echo file_get_contents($link);
            ob_end_flush();
            die();
        }
    }
    
    public function actionExport() {
//        $id = $this->getParam('id');
//        ob_start();
//        if ($id) {
//            $org_feed_log = OrgFeedLogs::findOne(['feed_log_id' => $id]);
//            if ($org_feed_log) {
//                $list_opp = OppFeedLogs::findAll(['feed_log_id'=>$org_feed_log->feed_log_id]);
//                $count_log_listing = Yii::$app->db
//                        ->createCommand("SELECT count(*) FROM opp_feed_logs WHERE (listing_log != '[]' AND listing_log != '' AND listing_log IS NOT NULL) AND feed_log_id=:feed_log_id", 
//                            [
//                                ':feed_log_id'=>$id
//                            ])
//                        ->queryScalar();
//                
//                $count_log_target = Yii::$app->db
//                        ->createCommand("SELECT count(*) FROM opp_feed_logs WHERE (targeting_log != '[]' AND targeting_log != '' AND targeting_log IS NOT NULL) AND feed_log_id=:feed_log_id", 
//                            [
//                                ':feed_log_id'=>$id
//                            ])
//                        ->queryScalar();
//                
//                $count_log_steps = Yii::$app->db
//                        ->createCommand("SELECT count(*) FROM opp_feed_logs WHERE (steps_log != '[]' AND steps_log != '' AND steps_log IS NOT NULL) AND feed_log_id=:feed_log_id", 
//                            [
//                                ':feed_log_id'=>$id
//                            ])
//                        ->queryScalar();
//                
//                $feed_method = OrgFeedLogEnum::$method;
//                $data = ['ID', 'Method', 'Create time(UTC)', 'Listing xml', 'Steps xml', 'Target xml', "Result"];
//                $listing_log = $org_feed_log->listing_log;
//                if ($listing_log) {
//                    
//                    $listing_log = str_ireplace(array("\r", "\n", '\r', '\n'), '', $listing_log);
//                    $listing_logs = Json::decode($listing_log);
//                    $listing_logs = flatten($listing_logs);
//                    
//                    $listing_log = $listing_logs ? implode("\n >>", $listing_logs) : '';
//                    $listing_log = $listing_log ? ">>$listing_log" : "Valid";
//                    $listing_log = $listing_log == "Valid" && (!$list_opp || ($list_opp && $count_log_listing)) ? 'Invalid' : $listing_log;
//                }
//                $steps_log = $org_feed_log->steps_log;
//                if($steps_log) {
//                    $steps_log = str_ireplace(array("\r","\n",'\r','\n'),'', $steps_log);
//                    $steps_logs = Json::decode($steps_log);
//                    $steps_logs = flatten($steps_logs);
//                    
//                    $steps_log = $steps_logs ? implode("\n >>", $steps_logs) : '';
//                    $steps_log = $steps_log ? ">>$steps_log" : "Valid";
//                    $steps_log = $steps_log == "Valid" && (!$list_opp || ($list_opp && $count_log_target)) ? 'Invalid' : $steps_log;
//                }
//                
//                $target_log = $org_feed_log->targeting_log;
//                if($target_log) {
//                    $target_log = str_ireplace(array("\r","\n",'\r','\n'),'', $target_log);
//                    $target_logs = Json::decode($target_log);
//                    $target_logs = flatten($target_logs);
//                    
//                    $target_log = $target_logs ? implode("\n >>", $target_logs) : '';
//                    $target_log = $target_log ? ">>$target_log" : "Valid";
//                    $target_log = $target_log == "Valid" && (!$list_opp || ($list_opp && $count_log_steps)) ? 'Invalid' : $target_log;
//                }
//                
//                
//                $countTotal = $org_feed_log->count_opps ? count(Json::decode($org_feed_log->count_opps)) : 0;
//
//                $success = [];
//                $success_arr = $org_feed_log->count_success ? Json::decode($org_feed_log->count_success) : [];
//                if(isset($success_arr['listing'])) {
//                    $success = array_merge($success, $success_arr['listing']);
//                }
//                if(isset($success_arr['steps'])) {
//                    $success = array_merge($success, $success_arr['steps']);
//                }
//                if(isset($success_arr['targeting'])) {
//                    $success = array_merge($success, $success_arr['targeting']);
//                }
//                $countSuccess = count(array_unique($success));
//                if(!$countSuccess){
//                    $target_log = $target_log == 'Valid' ? '' : $target_log;
//                    $steps_log = $steps_log == 'Valid' ? '' : $steps_log;
//                    $listing_log = $listing_log == 'Valid' ? '' : $listing_log;
//                }
//                $file_name = 'feed_logs_'.$org_feed_log->org_id.'.csv';
//                $fh = fopen($file_name, 'w+');
//                fwrite($fh, "sep=\t" . "\r\n");
//                fputcsv($fh, ["'--------------XML FILE VALIDATION--------------"], "\t");
//                fputcsv($fh, $data, "\t");
//                fputcsv($fh, array("#". $org_feed_log->feed_log_id, $feed_method[$org_feed_log->type], date('d-M-Y H:i:s',$org_feed_log->created_time), "$listing_log", "$steps_log", "$target_log", "$countSuccess/$countTotal successful"), "\t");
//                if($list_opp) {
//                    fputcsv($fh, [], "\t");
//                    fputcsv($fh, [], "\t");
//                    fputcsv($fh, ["'--------------OPPORTUNITY VALIDATION--------------"], "\t");
//                    $data_opp = ['Reference Id', 'Listing logs','Steps logs', 'Targeting logs'];
//                    fputcsv($fh, $data_opp, "\t");
//                    foreach($list_opp as $key=>$opp) {
//                        $listing_opp = $opp->listing_log;
//                        if($listing_opp) {
//                            $listing_opps = Json::decode($listing_opp, true);
//                            $listing_opps = flatten($listing_opps);
//                            $listing_opp = $listing_opps ? implode("\n >>", $listing_opps) : 'Valid';
//                        }
//                        //steps
//                        $steps_opp = $opp->steps_log;
//                        if($steps_opp) {
//                            $steps_opps = Json::decode($steps_opp);
//                            $steps_opps = flatten($steps_opps);
//                            $steps_opp = $steps_opps ? implode("\n >>", $steps_opps) : 'Valid';
//                        }
//                        //target
//                        $target_opp = $opp->targeting_log;
//                        if($target_opp) {
//                            $target_opps = Json::decode($target_opp);
//                            $target_opps = flatten($target_opps);
//                            $target_opp = $target_opps ? implode("\n >>", $target_opps) : 'Valid';
//                        }
//                        fputcsv($fh, array($opp->refer_id, "$listing_opp", "$steps_opp", "$target_opp"), "\t");
//                    }
//                }
//                fclose($fh);
//                $data_csv = file_get_contents($file_name);
//                $file_name_export = 'feed_history_log_'.$org_feed_log->org_id . '-'. date('Y-M-d H:i:s', $org_feed_log->created_time).'.csv';
//                header('Content-Type: application/csv');
//                header('Content-Disposition: attachement; filename="'.$file_name_export.'"');
//                echo $data_csv;
//                //unlink($file_name);
//                ob_end_flush();
//                Yii::$app->end();
//                
//            } else {
//               return $this->redirect($this->createUrl('index'));
//            }
//        } else {
//            return $this->redirect($this->createUrl('index'));
//        }
        
    }
}