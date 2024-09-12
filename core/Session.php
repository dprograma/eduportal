<?php 

// class Session{

//     public static function put($name,$value){
//         $_SESSION[$name] = $value;
//     }

//     public static function get($name){
//         if($_SESSION[$name]){
//             return $_SESSION[$name];
//         }
//     }

//     public static function exists($name){
//         if(isset($_SESSION[$name])){
//             return true;
//         }
//         return false;
//     }

//     public static function unset($name){

//         unset($_SESSION[$name]);
//         return true;
//     }
// }


class Session {
    public static function put($name, $value) {
        $_SESSION[$name] = $value;
    }

    public static function get($name) {
        return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
    }

    public static function exists($name) {
        return isset($_SESSION[$name]);
    }

    public static function remove($name) {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }
}
