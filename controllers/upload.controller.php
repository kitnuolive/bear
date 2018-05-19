<?php

class uploadController extends controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
    }

    public function uploadFileTmp()
    {
        if ($_FILES['file']['tmp_name'])
        {
            $count_file = count($_FILES['file']['tmp_name']);
            $type = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            for ($i = 0; $i < $count_file; $i++)
            {
                $file = md5(time() . $_FILES['file']['name']) . "." . $type;
                if (move_uploaded_file($_FILES['file']['tmp_name'], ROOT . DS . "assets/upload/tmp/{$file}"))
                {
                    $path_file = "/upload/tmp/{$file}";
                }
            }
            Result::setResult('path', $path_file);
            Result::showResult();
        }
        else
        {
            Result::setResult('path', null);
            Result::showResult();
        }
    }

    public function uploadFile($fileupload)
    {
        if ($fileupload['file']['tmp_name'])
        {
            $count_file = count($fileupload['file']['tmp_name']);
            $type = pathinfo($fileupload['file']['name'], PATHINFO_EXTENSION);
            for ($i = 0; $i < $count_file; $i++)
            {
                $file = md5(time() . $fileupload['file']['name']) . "." . $type;
                if (move_uploaded_file($fileupload['file']['tmp_name'], ROOT . DS . "assets/upload/order/{$file}"))
                {
                    $path_file = "/upload/order/{$file}";
                }
            }
            return $path_file;
        }
        else
        {
            return FALSE;
        }
    }

    public function moveFile($filename)
    {
        $pAdmin = NULL;
        $pBlog = NULL;
        $filePath = '/user';
        $fileName = explode("/", $filename);
        /* folderDirectory */
        $folderDirectory = "assets/upload{$filePath}";
        $pathReturn = "/upload{$filePath}" . '/' . "{$fileName[3]}";
        /* add folder */
        if (!file_exists(ROOT . DS . $folderDirectory))
        {
            mkdir(ROOT . DS . $folderDirectory, 0777, true);
        }
        $newPath = $folderDirectory . DS . $fileName[3]; // new path
        copy(ROOT . DS . "assets" . $filename, ROOT . DS . $newPath); // copy file
        return $pathReturn;
    }

    public function moveFileAdmin($filename)
    {
        $pAdmin = NULL;
        $pBlog = NULL;
        $filePath = '/admin';
        $fileName = explode("/", $filename);
        /* folderDirectory */
        $folderDirectory = "assets/upload{$filePath}";
        $pathReturn = "/upload{$filePath}" . '/' . "{$fileName[3]}";
        /* add folder */
        if (!file_exists(ROOT . DS . $folderDirectory))
        {
            mkdir(ROOT . DS . $folderDirectory, 0777, true);
        }
        $newPath = $folderDirectory . DS . $fileName[3]; // new path
        copy(ROOT . DS . "assets" . $filename, ROOT . DS . $newPath); // copy file
        return $pathReturn;
    }

    public function remove_file($file_path)
    {
        $file_path = ROOT . DS . "assets" . $file_path;
        // var_dump($file_path);
        if (empty($file_path))
        {
            return FALSE;
        }
        if (is_file($file_path))
        {
            unlink($file_path);
            return TRUE;
        }
    }

    public function convertSVGToPNG($path)
    {
        
    }

}
