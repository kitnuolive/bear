<?php

class account extends Model
{

    private function accountData()
    {
        $dataRow = array(
            'user_account_id' => 'user_account_id',
            'user_account_name' => 'user_account_name',
            'user_account_last_name' => 'user_account_last_name',
            'user_account_email' => 'user_account_email',
            'user_account_tel' => 'user_account_tel',
            'user_account_password' => 'user_account_password',
            'user_account_tel' => 'user_account_tel',
            'create_date' => 'create_date',
            'last_update' => 'last_update'
        );

        return $dataRow;
    }

    public function accountUpdate($obj)
    {

        $field = NULL;
        $data = array();
        $dataRow = $this->accountData();
        foreach ($dataRow as $key => $DBFieldName)
        {
            if (isset($obj->$key))
            {
                $field[] = "`$DBFieldName`=:{$DBFieldName}";
                $data[":{$DBFieldName}"] = $obj->$key;
            }
        }
        if (!empty($field))
        {
            $field = implode(',', $field);
        }
        if (empty($obj->user_account_id))
        {
            $sql = "INSERT INTO `user_account` SET {$field}";
        }
        else
        {
            $sql = "UPDATE `user_account` SET {$field} WHERE `user_account_id` = '{$obj->user_account_id}'";
        }
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute($data);
        $result = new stdClass();
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $result->error = $error[2];
        }
        else
        {
            if (empty($obj->user_account_id))
            {
                $user_account_id = $this->db->connection->LastInsertId();
            }
            else
            {
                $user_account_id = $obj->user_account_id;
            }
        }
        $result->user_account_id = $user_account_id;
        return $result;
    }

    public function accountList($obj, $page = null)
    {
        if (empty($obj))
        {
            $obj = new stdClass();
        }
        $dataSearch = $this->accountData();
        $orderType = 'DESC';
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (empty($obj->order_by))
        {
            $obj->order_by = 'user_account_id';
        }
        foreach ($dataSearch as $key => $DBFieldName)
        {
            if ($obj->order_by == $key)
            {
                $orderBy = $DBFieldName;
            }//sort
            if (isset($obj->$key))
            {
                if ($key == 'user_account_id' || $key == 'user_account_email')
                {
                    $value[] = "{$DBFieldName} = :{$key}";
                    $data[":{$key}"] = "{$obj->$key}";
                }
                else
                {
                    $value[] = "{$DBFieldName} LIKE :{$key}";
                    $data[":{$key}"] = "%{$obj->$key}%";
                }
            }//search
        }
        $where = null;
        if (!empty($value))
        {
            $value = implode(') AND (', $value);
            $where = "WHERE ({$value})";
        }
//        var_dump($value);
//        var_dump($data);
        /* all list */
        $allList = (int) 0;
        if (!empty($obj->allList))
        {
            $allList = (int) 1;
        }
//        var_dump($allList);
        $show_output = 30;
        (empty($page)) ? $page = 1 : NULL;
        $page = ($page - 1) * $show_output;
        $c_page = 0;
        $sql_c = "SELECT SQL_CACHE count(`user_account_id`) AS count FROM `user_account` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $row_c = $stmt_c->fetch();
        $c_page = $row_c['count'];

        $sql = "SELECT SQL_CACHE * "
                . "FROM `user_account` {$where} ORDER BY {$orderBy} {$orderType} LIMIT {$page},{$show_output}";
//        echo $sql . "<br>";
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute($data);
        $result = null;
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $result[] = new stdClass();
            $result[0]->error = $error[2];
        }
        else
        {
            $i = 0;
            while ($row = $stmt->fetch())
            {
                $result[] = new stdClass();
                foreach ($dataSearch as $key => $value)
                {
                    $result[$i]->$key = $row[$value];
                }
                ++$i;
            }
        }
        $return = new stdClass();
        $return->view = $result;
        $return->next_page = ceil($c_page / 30);
        $return->total = $c_page;
        return $return;
    }

}
