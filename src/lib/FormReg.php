<?php


namespace lib;


class FormReg extends Form
{

    //сверка с базой данной
    public function dbCheck()
    {
        $ip = $this->inputs_properties;
        $sp = $this->sanitized_post;
        $db_check = $this->db_check;
        $error_msg = $this->error_msg;

        foreach ($sp as $key => $value) {
            //valid - если данные должны быть в БД
            if (isset($ip[$key]["db_check"])) {
                if (in_array($sp[$key], $db_check[$key])) {
                    $validated_post_db[$key] = $sp[$key];
                } else {
                    $this->validation_error .= $error_msg[$key]["db_check"];
                }
            }
            //valid - если данные НЕ должны быть в БД
            if (isset($ip[$key]["db_check_reverse"])) {
                if (!in_array($sp[$key], $db_check[$key])) {
                    $validated_post_db[$key] = $sp[$key];
                } else {
                    $this->validation_error .= $error_msg[$key]["db_check"];
                }
            }
        }
        return @$validated_post_db;
    }

    //валидатор на основе filter_var()
    public function validator($sanitized_post, $inputs_properties, $error_msg, &$validation_error)
    {
        $ip = $inputs_properties;
        $sp = $sanitized_post;
        foreach ($sp as $key => $value) {
            if (isset($ip[$key]["filter"])) {
                if (isset($ip[$key]["filter_var_options"]) ) {
                    if (filter_var($sp[$key], $ip[$key]["filter"], $ip[$key]["filter_var_options"])) {
                        $validated_post[$key] = $sp[$key];
                    } else {
                        $validation_error .= $error_msg[$key]["validator"];
                    }
                } else {
                    if (filter_var($sp[$key], $ip[$key]["filter"])) {
                        $validated_post[$key] = $sp[$key];
                    } else {
                        $validation_error .= $error_msg[$key]["validator"];
                    }
                }
            }
            //исключение для номера кредитки, т.к. в этом случае должен быть
            //ОБЯЗАТЕЛЬНО корректно указан ещё и тип платёжной системы
            if (isset($ip[$key]["filter_credit_card"])) {
                if ($sp["card_type"] === "mastercard") {
                    if (filter_var($sp[$key], $ip[$key]["filter_credit_card"], $ip[$key]["filter_var_options"]["mastercard"])) {
                        $validated_post[$key] = $sp[$key];
                    } else {
                        $validation_error .= $error_msg[$key]["validator"];
                    }
                } elseif ($sp["card_type"] === "visa") {
                    if (filter_var($sp[$key], $ip[$key]["filter_credit_card"], $ip[$key]["filter_var_options"]["visa"])) {
                        $validated_post[$key] = $sp[$key];
                    } else {
                        $validation_error .= $error_msg[$key]["validator"];
                    }
                }
            }
        }
        return @$validated_post;
    }
}