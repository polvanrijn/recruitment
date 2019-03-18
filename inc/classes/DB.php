<?php
/**
 * Created by PhpStorm.
 * User: pol
 * Date: 2019-01-22
 * Time: 18:16
 */
require __DIR__ . '/Mail.php';

class DB
{

    static function get_connection(){
        include_once('creditials.php');
        $settings = get_mqsl_creditials();
        $user = $settings['user'];
        $password = $settings['password'];
        $db = $settings['db'];
        $host = $settings['host'];

        $conn = new mysqli($host, $user, $password, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else{
            return $conn;
        }
    }

    static function get_available_slots(){
        $conn = self::get_connection();
        $sql = "SELECT * FROM slots";
        $result =  $conn->query($sql);
        $conn->close();
        return $result;
    }

    static function date_is_free($id){
        $sql = "SELECT * FROM slots WHERE id='$id' and confirmed=1";
        $conn = self::get_connection();
        $result = $conn->query($sql);
        $conn->close();
        if($result->num_rows == 0) {
            return true;
        } else{
            return false;
        }
    }

    static function create_confirmation($email, $id){
        $conn = self::get_connection();
        $confirmation_code = hash('md5', $email .'CAPT19');

        $sql = "UPDATE slots SET email='$email', confirmation_code = '$confirmation_code' WHERE id='$id'";
        $conn->query($sql);
        $conn->close();
        $slot = self::get_slot($id);
        $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/confirm.php?id=$id&code=$confirmation_code";
        Mail::send_confirmation($email, $url, $slot);
    }

    static function confirm_email($id, $confirmation_code){
        $sql = "SELECT * FROM slots WHERE id='$id' and confirmation_code = '$confirmation_code' limit 1";
        $conn = self::get_connection();
        $result = $conn->query($sql);
        if($result->num_rows == 1) {
            $result = mysqli_fetch_assoc($result);
            if (is_null($result['confirmed'])){
                $sql = "UPDATE slots SET confirmed=1 WHERE id='$id'";
                $conn->query($sql);
                Mail::send_invitation($result);
                echo "<script>window.location.replace('/profile.php?id=$id');</script>";
            } else {
                $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}/profile.php?id=$id";
                return "Dit account is al geactiveerd. Bekijk uw afspraak op <a href='$url'>deze pagina</a>";
            }

        } else {
            return 'Er is iets misgegaan';
        }
        $
        $conn->close();
    }

    static function get_slot($id){
        $sql = "SELECT * FROM slots WHERE id='$id'";
        $conn = self::get_connection();
        $slot = $conn->query($sql);
        $conn->close();
        return mysqli_fetch_assoc($slot);
    }

    static function cancel_appointment($id, $send_mail = true){
        $obj = self::get_slot($id);
        $sql = "UPDATE slots SET email=NULL, confirmation_code = NULL, confirmed=NULL WHERE id='$id'";
        $conn = self::get_connection();
        $conn->query($sql);
        $conn->close();
        if ($send_mail){
            Mail::send_cancel($obj);
        }

    }

    static function change_appointment($old_id, $new_id){
        $old_obj = self::get_slot($old_id);
        self::cancel_appointment($old_id, false);

        $email = $old_obj['email'];
        $confirmation_code = $old_obj['confirmation_code'];
        $sql = "UPDATE slots SET email='$email', confirmation_code = '$confirmation_code', confirmed=1 WHERE id='$new_id'";
        $conn = self::get_connection();
        $conn->query($sql);
        $conn->close();
        $new_obj = self::get_slot($new_id);
        Mail::send_change($old_obj, $new_obj);
    }

    static function extract_obj_information($obj, $add_br = false){
        setlocale(LC_ALL, 'nl_NL');
        $br = ' ';

        if ($add_br){
            $br = '<br>';
        }
        $obj['date'] = strftime("%A$br%d %B", strtotime($obj['date']));
        $obj['start'] = substr($obj['start'], 0, 5);
        $obj['end'] = substr($obj['end'], 0, 5);
        return $obj;
    }



}