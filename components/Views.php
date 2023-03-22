<?php
namespace components;
/**
 * @uses ExceptionHandler
 */
class Views{

    private static $views_path = '/../views/';
    public static $default_view = 'wrap';
    private static $template = '';

    /**
     * @return void
     */
    protected static function setTemplatePath() : void {
        self::$template = __DIR__.self::$views_path.self::$default_view.'.php';
    }

    /**
     * @return string
     */
    public function getTemplatePath() : string {
        return self::$template;
    }

    /**
     * @param $view
     * @param $args
     * @param $echo
     * @return string|void
     */
    public static function show($view = '', $args = ['test' =>'test'], $echo = true) : string {

        if(!empty($view))
            self::$default_view = $view;

        self::setTemplatePath();

        try{
            if (file_exists(self::$template))
            {
                extract($args);
                ob_start();
                include self::$template;
                $template = ob_get_contents();
                ob_end_clean();

                if($echo){
                    die($template);
                }
                return $template;
            }else throw new ExceptionHandler('Шаблон '.self::$default_view.' не найден');

        }catch(ExceptionHandler $e){}
    }
}