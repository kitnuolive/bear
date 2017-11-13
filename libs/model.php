<?php

class Model
{

    public $db;

    public function __construct()
    {
        $this->db = APP::$db;
    }

    public function updateDb($obj, $fieldDB, $tableName, $idUpdate, $fieldId)
    {
        $error = null;
        $result = null;
        $id = null;
        if (empty($obj))
        {
            $error = "No data obj.";
        }
        else
        {
            if (empty($obj->$idUpdate))
            {
                if (empty($obj->createDate))
                {
                    $obj->createDate = date("Y-m-d H:i:s");
                }
            }
            /* DB */
            $data = array();
            foreach ($fieldDB as $key => $DBFieldName)
            {
                if (isset($obj->$key))
                {
                    $field[] = "`$DBFieldName`=:{$key}";
                    $data[":{$key}"] = $obj->$key;
                }
            }
            $field = implode(',', $field);

            if (empty($obj->$idUpdate))
            {
                $sql = "INSERT INTO `{$tableName}` SET {$field}";
            }
            else
            {
                $sql = "UPDATE `{$tableName}` SET {$field} WHERE {$fieldId} = '{$obj->$idUpdate}'";
            }
//            echo ($sql) . "<P>";
//            exit();
            $stmt = $this->db->connection->prepare($sql);
//            var_dump($data);
//            exit();
            $query = $stmt->execute($data);
            if (!$query)
            {
                $error = $stmt->errorInfo();
                $error = $error[2];
            }
            else
            {
                if (empty($obj->$idUpdate))
                {
                    $id = $this->db->connection->LastInsertId();
                }
                else
                {
                    $id = $obj->$idUpdate;
                }
            }

            /* return */
            $result = new stdClass();
            if (empty($idUpdate))
            {
                $result->pass = true;
            }
            else
            {
                $result->$idUpdate = $id;
            }
//        var_dump($result);
        }

        $return = new stdClass();
        $return->result = $result;
        $return->error = $error;

        return $return;
    }

    public function listDb($obj, $fieldDB, $typeSearch, $tableName, $orderBy, $orderType, $showOutput, $page, $fieldCountPage, $decodeJson = NULL)
    {
        $value = NULL;
        $data = NULL;
        $where = NULL;
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (!empty($obj->order_by2))
        {
            $orderBy = $obj->order_by2;
        }
        foreach ($fieldDB as $key => $DBFieldName)
        {
            if (!empty($obj->order_by) && $obj->order_by == $key)
            {
                $orderBy = $DBFieldName;
            }//sort
            if (isset($obj->$key))
            {
                if (isset($typeSearch[$key]) && $typeSearch[$key] == $key)
                {
                    $value[] = "{$DBFieldName} = :{$key}";
                    $data[":{$key}"] = $obj->$key;
                }
                else if (isset($typeSearch['IN']) && $typeSearch['IN'] == $key)
                {
                    $value[] = "{$DBFieldName} IN (" . implode(',', $obj->$key) . ")";
                }
                else if (isset($typeSearch['date']) && ($typeSearch['date'] == $key && $obj->$key != "0000-00-00"))
                {
                    $dateUpdate = explode(':', $obj->$key);
                    $startLastUpdate = $dateUpdate[0] . " 00:00:00";
                    $endLastUpdate = $dateUpdate[1] . " 23:59:59";
                    $value[] = "{$DBFieldName} BETWEEN :startLastUpdate AND :endLastUpdate";
                    $data[":startLastUpdate"] = "{$startLastUpdate}";
                    $data[":endLastUpdate"] = "{$endLastUpdate}";
                }
                else
                {
                    $value[] = "{$DBFieldName} LIKE :{$key}";
                    $data[":{$key}"] = "%{$obj->$key}%";
                }
            }//search
        }
        if (!empty($value))
        {
            $value = implode(') AND (', $value);
            $where = "WHERE ({$value})";
        }
//        var_dump($value);
//        var_dump($data);
        $cPage = 0;
        $nextPage = NULL;
        $sql_c = "SELECT SQL_CACHE count(`{$fieldCountPage}`) AS count FROM `{$tableName}` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $rowC = $stmt_c->fetch();
        $cPage = $rowC['count'];
        $nextPage = ceil($cPage / $showOutput);
        $limit = NULL;
        empty($page) ? $page = 1 : NULL;
        if (isset($page) && $page != "all")
        {
            $page = ($page - 1) * $showOutput;
            $limit = "LIMIT {$page},{$showOutput}";
        }
        $groupBy = NULL;
        if (!empty($obj->groupBy))
        {
            $groupBy = "GROUP BY {$obj->groupBy}";
        }

        $sql = "SELECT SQL_CACHE * FROM `{$tableName}` {$where} {$groupBy} ORDER BY {$orderBy} {$orderType} {$limit}";
//        echo $sql."<p>";
//        var_dump($sql);
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute($data);
        $result = NULL;
        $error = NULL;
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $error = $error[2];
        }
        else
        {
            $i = 0;
            while ($row = $stmt->fetch())
            {
                $result[] = new stdClass();
                foreach ($fieldDB as $key => $value)
                {
                    if (!empty($decodeJson[$key]))
                    {
                        $result[$i]->$key = json_decode($row[$value]);
                    }
                    else
                    {
                        $result[$i]->$key = $row[$value];
                    }
                }
                $i++;
            }
        }
        $return = new stdClass();
        $return->view = $result;
        $return->nextPage = $nextPage;
        $return->status = $cPage;
        $return->error = $error;

        return $return;
    }

    public function deleteUpdateDb($tableName, $fieldDelete, $fieldEnable, $fieldId, $idDeleteUpdate)
    {
        $result = NULL;
        $error = NULL;
        $data = array(':idDeleteUpdate' => "{$idDeleteUpdate}",
            ':fieldEnable' => "{$fieldEnable}"
        );
        $sql = "UPDATE `{$tableName}` SET `{$fieldDelete}` = :fieldEnable WHERE `{$fieldId}` = :idDeleteUpdate";
        $stmt = $this->db->connection->prepare($sql);
//            var_dump($data);
//            exit();
        $query = $stmt->execute($data);
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $error = $error[2];
        }
        else
        {
            $result = $idDeleteUpdate;
        }
        $return = new stdClass();
        $return->result = $result;
        $return->error = $error;

        return $return;
    }

    public function deleteDb($tableName, $fieldId, $idDelete)
    {
        $result = NULL;
        $error = NULL;
        $data = array(':idDelete' => "{$idDelete}");
        $sql = "DELETE FROM `{$tableName}` WHERE `{$fieldId}` = :idDelete";
        $stmt = $this->db->connection->prepare($sql);
//            var_dump($data);
//            exit();
        $query = $stmt->execute($data);
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $error = $error[2];
        }
        else
        {
            $result = $idDelete;
        }
        $return = new stdClass();
        $return->result = $result;
        $return->error = $error;

        return $return;
    }

    public function listDbCustom($obj, $fieldDB, $typeSearch, $sql, $orderBy, $orderType, $showOutput, $page, $sqlC, $customWhere = NULL, $decodeJson = NULL, $typeSearchNot = NULL)
    {
        $value = NULL;
        $data = NULL;
        $where = NULL;
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        foreach ($fieldDB as $key => $DBFieldName)
        {
            if (!empty($obj->order_by) && $obj->order_by == $key)
            {
                $orderBy = $DBFieldName;
            }//sort
            if (isset($obj->$key))
            {
                if (isset($typeSearch[$key]) && $typeSearch[$key] == $key)
                {
                    $value[] = "{$DBFieldName} = :{$key}";
                    $data[":{$key}"] = $obj->$key;
                }
                else if (isset($typeSearch['IN']) && $typeSearch['IN'] == $key)
                {
                    $value[] = "{$DBFieldName} IN (" . implode(',', $obj->$key) . ")";
                }
                else if (isset($typeSearchNot[$key]))
                {
                    $value[] = "{$DBFieldName} != :{$key}";
                    $data[":{$key}"] = $obj->$key;
                }
                else if (isset($typeSearch['date']) && ($typeSearch['date'] == $key && $obj->$key != "0000-00-00"))
                {
                    $dateUpdate = explode(':', $obj->$key);
                    $startLastUpdate = $dateUpdate[0] . " 00:00:00";
                    $endLastUpdate = $dateUpdate[1] . " 23:59:59";
                    $value[] = "{$DBFieldName} BETWEEN :startLastUpdate AND :endLastUpdate";
                    $data[":startLastUpdate"] = "{$startLastUpdate}";
                    $data[":endLastUpdate"] = "{$endLastUpdate}";
                }
                else
                {
                    $value[] = "{$DBFieldName} LIKE :{$key}";
                    $data[":{$key}"] = "%{$obj->$key}%";
                }
            }//search
        }
        if (!empty($value))
        {
            $value = implode(') AND (', $value);
            $where = "WHERE ({$value})";
        }

        if (empty($where) && !empty($customWhere))
        {
            $customWhere = "WHERE {$customWhere}";
        }
        else if (!empty($where) && !empty($customWhere))
        {
            $customWhere = "AND {$customWhere}";
        }
//        var_dump($value);
//        var_dump($data);
        $cPage = 0;
        $nextPage = NULL;
        $sql_c = "{$sqlC} {$where} {$customWhere}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $rowC = $stmt_c->fetch();
        $cPage = $rowC['count'];
        $nextPage = ceil($cPage / $showOutput);
        $limit = NULL;
        empty($page) ? $page = 1 : NULL;
        if (isset($page) && $page != "all")
        {
            $page = ($page - 1) * $showOutput;
            $limit = "LIMIT {$page},{$showOutput}";
        }
        $groupBy = NULL;
        if (!empty($obj->groupBy))
        {
            $groupBy = "GROUP BY {$obj->groupBy}";
        }

        $sql = "{$sql} {$where} {$customWhere} {$groupBy} ORDER BY {$orderBy} {$orderType} {$limit}";
//        echo $sql."<p>";
//        var_dump($sql);
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute($data);
        $result = NULL;
        $error = NULL;
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $error = $error[2];
        }
        else
        {
            $i = 0;
            while ($row = $stmt->fetch())
            {
                $result[] = new stdClass();
                foreach ($fieldDB as $key => $value)
                {
                    if (isset($row[$value]))
                    {
                        if (!empty($decodeJson[$key]))
                        {
                            $result[$i]->$key = json_decode($row[$value]);
                        }
                        else
                        {
                            $result[$i]->$key = $row[$value];
                        }
                    }
                }
                $i++;
            }
        }
        $return = new stdClass();
        $return->view = $result;
        $return->nextPage = $nextPage;
        $return->status = $cPage;
        $return->error = $error;

        return $return;
    }

    public function countStatusViewPage($status, $tableName, $fieldCountPage, $fieldStatus)
    {
        $cPage = 0;
        $error = NULL;
        $data = array(':status' => "{$status}");
        $sql_c = "SELECT SQL_CACHE count(`{$fieldCountPage}`) AS count FROM `{$tableName}` WHERE `{$fieldStatus}` = :status";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $query = $stmt_c->execute($data);
        if (!$query)
        {
            $error = $stmt_c->errorInfo();
            $error = $error[2];
        }
        else
        {
            $rowC = $stmt_c->fetch();
            $cPage = $rowC['count'];
        }

        $return = new stdClass();
        $return->data = $cPage;
        $return->error = $error;
        return $return;
    }

    public function deletePathDb($tableName, $fieldPath, $pathDelete)
    {
        $result = NULL;
        $error = NULL;
        $data = array(':pathDelete' => "{$pathDelete}");
        $sql = "DELETE FROM `{$tableName}` WHERE `{$fieldPath}` = :pathDelete";
        $stmt = $this->db->connection->prepare($sql);
//            var_dump($data);
//            exit();
        $query = $stmt->execute($data);
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $error = $error[2];
        }
        else
        {
            $upload = new UploadController();
            $upload->removeFile($pathDelete);
            $result = $pathDelete;
        }
        $return = new stdClass();
        $return->result = $result;
        $return->error = $error;

        return $return;
    }

}
