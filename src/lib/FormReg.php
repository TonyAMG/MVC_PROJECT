<?php


namespace lib;


class FormReg extends Form
{
    public $validation_error;
    public $validated_post_db;
    public $validated_post_filter;
    public $correct_answers;


    //сверка с базой данной
    public function dbCheck(): array
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
        $this->validated_post_db = $validated_post_db;
        return @$validated_post_db;
    }

    //валидатор на основе filter_var()
    public function validator() : ?array
    {
        $ip = $this->inputs_properties;
        $sp = $this->sanitized_post;
        $error_msg = $this->error_msg;

        foreach ($sp as $key => $value) {
            if (isset($ip[$key]["filter"])) {
                if (isset($ip[$key]["filter_var_options"]) ) {
                    if (filter_var($sp[$key], $ip[$key]["filter"], $ip[$key]["filter_var_options"])) {
                        $validated_post_filter[$key] = $sp[$key];
                    } else {
                        $this->validation_error .= $error_msg[$key]["validator"];
                    }
                } else {
                    if (filter_var($sp[$key], $ip[$key]["filter"])) {
                        $validated_post_filter[$key] = $sp[$key];
                    } else {
                        $this->validation_error .= $error_msg[$key]["validator"];
                    }
                }
            }
            //исключение для номера кредитки, т.к. в этом случае должен быть
            //ОБЯЗАТЕЛЬНО корректно указан ещё и тип платёжной системы
            if (isset($ip[$key]["filter_credit_card"])) {
                if ($sp["card_type"] === "mastercard") {
                    if (filter_var($sp[$key], $ip[$key]["filter_credit_card"], $ip[$key]["filter_var_options"]["mastercard"])) {
                        $validated_post_filter[$key] = $sp[$key];
                    } else {
                        $this->validation_error .= $error_msg[$key]["validator"];
                    }
                } elseif ($sp["card_type"] === "visa") {
                    if (filter_var($sp[$key], $ip[$key]["filter_credit_card"], $ip[$key]["filter_var_options"]["visa"])) {
                        $validated_post_filter[$key] = $sp[$key];
                    } else {
                        $this->validation_error .= $error_msg[$key]["validator"];
                    }
                }
            }
        }
        $this->validated_post_filter = @$validated_post_filter;
        return @$validated_post_filter;
    }

    //рассчитываем правильные ответы параметры берутся
    //из конфига и сравниваются по заданному в конфиге алгоритму
    //можно легко добавить новые условия, не нарушив работу метода
    public function correctAnswersChecker(): ?array
    {
        foreach ($this->inputs_properties as $key => $value) {
            if ($this->inputs_properties[$key]["validation_type"] === "db") {
                if (isset($this->validated_post_db[$key])) {
                    $this->correct_answers[$key] = $this->validated_post_db[$key];
                }
            }
            if ($this->inputs_properties[$key]["validation_type"] === "filter") {
                if (isset($this->validated_post_filter[$key])) {
                    $this->correct_answers[$key] = $this->validated_post_filter[$key];
                }
            }
            if ($this->inputs_properties[$key]["validation_type"] === "db_filter") {
                if (isset($this->validated_post_db[$key]) && isset($this->validated_post_filter[$key])) {
                    $this->correct_answers[$key] = $this->validated_post_db[$key];
                }
            }
            if ($this->inputs_properties[$key]["validation_type"] === "file") {
                if (file_exists($this->upload_dir.session_id().'_photo.jpg')) {
                    $this->correct_answers[$key] = $this->inputs_properties[$key]["validation_type"];
                }
            }
        }
        return $this->correct_answers;
    }


}