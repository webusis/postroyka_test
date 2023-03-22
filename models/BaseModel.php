<?php
namespace models;

use PDO;
use PDOException;
use components\ExceptionHandler;

class BaseModel{

    private $dbh;
    private $model;
    private $attr = [];
    private $prepare = [];
    private $rules = ['!=', '>', '<'];

    function __construct($model){
        $this->model = $model;
        try {
            $this->dbh = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
        } catch (PDOException $e) {
            throw new ExceptionHandler($e->getMessage());
        }
    }

    /**
     * @param $separator
     * @return void
     */
    private function setPrepare($separator = ','){
        $model = $this->model;
        $prepare = [];
        array_filter(array_keys($this->model->columns), function($col) use(&$prepare,$model){
            if(isset($model->$col)){
                $prepare[] = $col.'=:'.$col;
                $this->attr[$col] = $this->$col;
            }
        });

        if(isset($this->id)){
            $ids = $this->id;
            if(is_array($this->id)){
                $ids = implode(',', $this->id);
            }

            $prepare[] = "id in ($ids)";
        }

        if(isset($this->customSearch) && is_array($this->customSearch) && $separator == 'AND'){
            //unset($prepare);
            foreach($this->customSearch as $key => $rule){
                foreach($rule as $r => $d){
                    if(in_array($r,$this->rules))
                        $prepare[] = $key.$r."'$d'";
                }
            }
        }

        $this->prepare = implode(" $separator ", $prepare);
    }

    /**
     * @return array|false
     * @throws ExceptionHandler
     */
    protected function find(){
        self::setPrepare('AND');
        try {
            $sth = $this->dbh->prepare("SELECT * FROM {$this->model->table} WHERE {$this->prepare}");

            $sth->execute($this->attr);

            $array = $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new ExceptionHandler('Отсутсвуют атрибуты выборки');
        }

        return $array;
    }

    /**
     * @return bool
     * @throws ExceptionHandler
     */
    protected function delete(){
        self::setPrepare();
        try {
            $sth = $this->dbh->prepare("DELETE FROM {$this->model->table} WHERE {$this->prepare}");
            $result = $sth->execute($this->attr);
        } catch (PDOException $e) {
            throw new ExceptionHandler($e->getMessage());
        }

        return $result;
    }

    /**
     * @return array|false|string
     * @throws ExceptionHandler
     */
    protected function create() : array|string|false {
        self::setPrepare();
        try {
            $sth = $this->dbh->prepare("INSERT INTO {$this->model->table} SET {$this->prepare}");
            $data = self::validate(', ');
            if(empty($data['errors'])){
                $sth->execute($this->attr);
            }else{
                return $data;
            }
        } catch (PDOException $e) {
            throw new ExceptionHandler($e->getMessage());
        }

        return $this->dbh->lastInsertId();
    }

    /**
     * @return array
     */
    private function validate($separator = '') : array {
        $data = [];
        $data['errors'] = [];
        foreach($this->model->valid_params as $key => $val){
            foreach($this->model->valid_params[$key] as $col => $param){
                if(isset($this->attr[$col]) || $key == 'req'){
                    foreach($param as $param){
                        switch($key){
                            case 'regexp':
                                if(!preg_match('/'.$param.'/iu', $this->attr[$col])){
                                    $data['errors'][$col][] = 'Неверный формат '.$this->model->columns[$col];
                                }
                                break;
                            case 'date':
                                if(date($param,strtotime($this->attr[$col])) == '1970-01-01'){
                                    $data['errors'][$col][] = 'Неверный формат '.$this->model->columns[$col];
                                }
                                break;
                            case 'req':
                                    if(empty($this->attr[$param])){
                                        $data['errors'][$param][] = 'Поле '.$this->model->columns[$param].' обязательно';
                                    }
                                break;
                        }
                    }
                }
            }
        }

        if(!empty($separator)){
            foreach($data['errors'] as &$item){
                $item = implode($separator, $item);
            }
        }

        return $data;
    }

    public function setAttributes($attrs = []){
        foreach($attrs as $k=>$v){
            $this->$k = $v;
        }
    }

    public function __get($name){
        return $this->$name;
    }

    public function __set($name, $val){
        $this->$name = $val;
    }
}
