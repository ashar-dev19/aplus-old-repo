<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\components;

/**
 * Description of Statuslabel
 *
 * @author Analyst
 */
class CustomFormatter extends \yii\i18n\Formatter{
    //put your code here
    
    /**
     * 
     * @param type $status [] 
     * @return type
     */
    
    public function asStatuslabel($status = null) {
        
        if($status == '1' )   $statuslabel = "<span class='badge badge-success'>Active</span>";
         
		else if($status == '0')
            $statuslabel = "<span class='badge badge-danger'>Inactive</span>";
		else if($status == '2' || $status == '50')
            $statuslabel = "<span class='badge badge-primary'>Completed</span>";
        else if($status == '3')
            $statuslabel = "<span class='badge badge-warning'>Suspended</span>";
        else if($status == '4')
            $statuslabel = "<span class='badge badge-primary'>Withdrawn</span>";
		else if($status == '6')
            $statuslabel = "<span class='badge badge-info'>Alumni</span>";
		else if($status == '10')
            $statuslabel = "<span class='badge badge-primary'>Exempted</span>";
         else $statuslabel = $status;
        return $statuslabel; 
    }
    public function asApplicationlabel($status = null) {
        
         if($status == '1' )   $statuslabel = "<span class='badge badge-primary'>Approved</span>";
         else if($status == '10' )   $statuslabel = "<span class='badge badge-success'>Enrolled</span>";
         else if($status == '3')
            $statuslabel = "<span class='badge badge-primary'>Processed</span>";
         else if($status == '5')
            $statuslabel = "<span class='badge badge-default'>In Progress</span>";
         else if($status == '0')
            $statuslabel = "<span class='badge badge-danger'>Pending</span>";
         else if($status == '100')
            $statuslabel = "<span class='badge badge-dark'>Draft</span>";
         else if($status == '50')
            $statuslabel = "<span class='badge badge-default'>Returned</span>";
         else if($status == '-10')
            $statuslabel = "<span class='badge badge-warning'>Withdrawn</span>";
		else if($status == '4')
            $statuslabel = "<span class='badge badge-primary'>Deffered</span>";
		else if($status == '6')
            $statuslabel = "<span class='badge badge-default'>Awaiting decision</span>";
         else $statuslabel = $status;
        return $statuslabel;
                    
            
    }
    public function asPaymentStatuslabel($status = null) {
        
         if($status == '1' )   $statuslabel = "<span class='badge badge-success'>Paid</span>";
         else if($status == '0')
            $statuslabel = "<span class='badge badge-danger'>Pending</span>";
         else if($status == '-10')
            $statuslabel = "<span class='badge badge-warning'>Cancelled</span>";
         else $statuslabel = $status;
        return $statuslabel;
                    
            
    }
    public function asResponsestatus($status = null) {
        
        if($status == '1' ) 
                $format =   "<span class='badge badge-danger'>Pending</span>" ;
        else if($status == '0' ) $format =   "<span class='badge badge-success'>InActive</span>" ;
        elseif($status == '10' ) $format =   "<span class='badge badge-primary'>Submitted</span>" ;
        else $format = $status;
        return  $format;
                    
            
    }
    public function asResultlabel($result = null) {
        
        $format = \backend\modules\assessmentms\models\Assessment::$resultList[$result];
        return  $format;
                    
            
    }
    
    public function asResultformatted($result = null) {
        
        $format = \backend\modules\assessmentms\models\Assessment::$resultListFormatted[$result];
        return  $format;
                    
            
    }
    
    public function asUserlabel($userid = null) {
        if(!$userid) return Null;
        $format = ucfirst(\common\models\User::findOne($userid)->username);
        return  $format;
                    
            
    }
    
    public function asCorelabel($status = null) {
        
         if($status == '1' )   $statuslabel = "<i class ='fa-fw fas fa-check text-success'></i>";
         else if($status == '5')
            $statuslabel = "<span class='badge badge-primary'>Completed</span>";
         else if($status == '0')
            $statuslabel = "<i class ='fa-fw fas fa-info text-info'></i>";
         else if($status == '10')
            $statuslabel = "<span class='badge badge-primary'>Draft</span>";
         else if($status == '-10')
            $statuslabel = "<span class='badge badge-warning'>Cancelled</span>";
         else $statuslabel = $status;
        return $statuslabel;
                    
            
    }
}
