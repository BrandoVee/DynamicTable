<?php


class WIPTable
{
    private $randomize = true;
    private $columns = array();
    private $rows = array();

    public function __construct($column, $rows, $randomize)
    {
        $this->columns = $column;
        $this->rows = $rows;
        $this->randomize = $randomize;
        $this->dispTable($this->columns,$this->rows,$this->randomize);
    }

    public function __destruct()
    {
        echo "WIPTable Destroyed";
    }

    private function dispTable($colums,$rows,$is_random){
        if(!$is_random){

        }
    }


    public function getDisplayTable(){

}
}