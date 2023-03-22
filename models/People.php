<?php
namespace models;
use helpers\BaseMethods;

class People extends BaseModel {
    protected $table = 'people';

    protected $columns = [
        'id',
        'fname' => 'Имя',
        'sname' => 'Фамилия',
        'gender' => 'Пол',
        'birthday' => 'День рождения',
        'city' => 'Город',
        'phone' => 'Телефон',
        'email' => 'E-mail'
    ];

    protected $valid_params = [
        'regexp' => [
            'fname' => [
                '^[a-zа-я]+$'
            ],
            'sname' => [
                '^[a-zа-я]+$'
            ],
            'city' => [
                '^[a-zа-я]+$'
            ],
            'phone' => [
                '^\+[0-9]{0,12}$'
            ],
            'email' => [
                '^[a-z0-9._%+-]+@[a-z0-9-]+.+.[a-z]{2,4}$'
            ]
        ],
        'date' => [
            'birthday' => [
                'Y-m-d'
            ]
        ],
        'req' => [['fname','sname','city','birthday','phone','email']]
    ];

    /**
     * @throws \components\ExceptionHandler
     */
    public function __construct(){
        parent::__construct($this);
    }

    /**
     * @return array|false
     * @throws \components\ExceptionHandler
     */
    public function find(){
        $data = parent::find();

        if(is_array($data)){
            foreach($data as &$d){
		    $d['birthday'] = BaseMethods::calcYersAgo($d['birthday']);
		    $d['gender'] = ($d['gender'] == 1) ? 'Мужской' : 'Женский';
            }
        }
        return $data;
    }

    /**
     * @return array|false|string
     * @throws \components\ExceptionHandler
     */
    public function create() : array|string|false {
        return parent::create(', ');
    }

    /**
     * @return bool
     * @throws \components\ExceptionHandler
     */
    public function delete() : bool {
        return parent::delete();
    }

    /**
     * @return mixed|string[]
     */
    public function clTr() : array {
        return $this->columns;
    }
}
