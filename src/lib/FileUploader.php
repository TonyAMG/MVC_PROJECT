<?php


namespace lib;


class FileUploader
{
    function __construct($photo_max_size, $upload_photo_path, $error_msg)
    {
        $this->photo_max_size = $photo_max_size;
        $this->upload_photo_path = $upload_photo_path;
        $this->error_msg = $error_msg;
    }


    //загрузка файла и проверка его размера
    public function photoUploader(&$validation_error = ''): bool
    {
        if (@$_FILES['profile_pic']['size'] < $this->photo_max_size) {
            if (move_uploaded_file(@$_FILES['profile_pic']['tmp_name'], $this->upload_photo_path)) {
                $_SESSION["input"]["upload_photo"] = "file";
                return true;
            } else {
                if (file_exists($this->upload_photo_path)) {
                    $_SESSION["input"]["upload_photo"] = "file";
                    return true;
                } else {
                    $validation_error .= $this->error_msg["file"]["file_handler"];
                    return false;
                }
            }
        } else {
            $validation_error .= $this->error_msg["file"]["big_file"];
            return false;
        }
    }
}