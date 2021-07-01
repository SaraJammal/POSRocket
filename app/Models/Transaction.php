<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $table='transactions';

    private $isValid; 
    private $json; 
    
    function __construct($json) {
        // print "In BaseClass constructor\n";
        $this->json = $json; 
        // self::create(['log'=>(array)$json]);
    }
    function set_isValid($isValid) {
        $this->isValid = $isValid;
    }
    
    function get_isValid() {
        return $this->isValid;
    }

    public function isValid(){      //json  -   Array
        $transcation = $this->json; 
        foreach($transcation['itemization'] as $item){
            $total_tax = 0; 
            foreach($item['taxes'] as $tax){
               $total_tax = $total_tax + $tax['applied_money']['amount']; 
            }
    
            //Noting that the net_sales_money is calculated with the quantity.
            if($item['net_sales_money']['amount'] + $total_tax != $item['total_money']['amount']){
                return false; 
            }
            return true; 
        }
    }

    public function isValid_XML(){  //XML
        $transcation = $this->json; 
        foreach($transcation['itemization']as $item){
            $total_tax = 0; 
    
            if(isset($item['taxes']['element'][1])){
                $taxes = $item['taxes']['element']; 
            }else{
                $taxes = $item['taxes']; // for one element applied money was there 
            }
            foreach($taxes as $tax){
               $total_tax = $total_tax + $tax['applied_money']['amount']; 
            }
    
            //Noting that the net_sales_money is calculated with the quantity.
            if($item['net_sales_money']['amount'] + $total_tax != $item['total_money']['amount']){
                return false; 
            }
            return true; 
        }
    }
    
    use HasFactory;
}
