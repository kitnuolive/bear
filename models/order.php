<?php

class order extends Model
{

    private function orderData()
    {
        $dataRow = array(
            'bear_order_id' => 'bear_order_id',
            'bear_order_status' => 'bear_order_status',
            'bear_order_number' => 'bear_order_number',
            'user_account_id' => 'user_account_id',
            'bear_order_path' => 'bear_order_path',
            'bear_order_path_svg' => 'bear_order_path_svg',
            'frame_list_id' => 'frame_list_id',
            'frame_category_id' => 'frame_category_id',
            'sticker_list_id' => 'sticker_list_id',
            'create_date' => 'create_date'
        );

        return $dataRow;
    }

    function engArr()
    {
        $arr = range('A', 'Z');

        return $arr;
    }

    public function genOrderNumber()
    {
        $sql = "SELECT `bear_order_number` FROM `bear_order` ORDER BY `bear_order_number` DESC";
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute();
        $result = null;
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $result[] = new stdClass();
            $result[0]->error = $error[2];
        } else
        {

            $row = $stmt->fetch();
            if (!$row)
            {
                $number = "00000001";
                $sql = "INSERT INTO `bear_order`(`bear_order_number`) VALUES ('{$number}')";
//                echo $sql;
                $stmt = $this->db->connection->prepare($sql);
                $query = $stmt->execute();
                $result = null;
                if (!$query)
                {
                    $error = $stmt->errorInfo();
                    $result[] = new stdClass();
                    $result[0]->error = $error[2];
                } else
                {
                    $id = $this->db->connection->LastInsertId();
                }
            } else
            {
                $n = $row['bear_order_number'];
                $a = (int) substr($n, 0, 2);
                $b = (int) substr($n, 2, 2);
                $c = (int) substr($n, 4);

                if ($c == 9999)
                {
                    $c = 1;
                    if ($b == 25)
                    {
                        $b = 0;
                        $a += 1;
                    } else
                    {
                        $b += 1;
                    }
                } else
                {
                    $c += 1;
                }
                $a = sprintf('%02d', $a);
                $b = sprintf('%02d', $b);
                $c = sprintf('%04d', $c);

                $number = $a . $b . $c;

                $sql = "INSERT INTO `bear_order`(`bear_order_number`) VALUES ('{$number}')";
//                echo $sql;
                $stmt = $this->db->connection->prepare($sql);
                $query = $stmt->execute();
                $result = null;
                if (!$query)
                {
                    $error = $stmt->errorInfo();
                    $result[] = new stdClass();
                    $result[0]->error = $error[2];
                } else
                {
                    $id = $this->db->connection->LastInsertId();
                }
            }
        }

        $return = new stdClass();
        $return->id = $id;
        $return->number = $number;

        return $return;
    }

    public function orderUpdate($obj)
    {
        $field = NULL;
        $data = array();
        $dataRow = $this->orderData();
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
        if (empty($obj->bear_order_id))
        {
            $sql = "INSERT INTO `bear_order` SET {$field}";
        } else
        {
            $sql = "UPDATE `bear_order` SET {$field} WHERE `bear_order_id` = '{$obj->bear_order_id}'";
        }
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute($data);
        $result = new stdClass();
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $result->error = $error[2];
        } else
        {
            if (empty($obj->bear_order_id))
            {
                $bear_order_id = $this->db->connection->LastInsertId();
            } else
            {
                $bear_order_id = $obj->bear_order_id;
            }
        }
        $result->bear_order_id = $bear_order_id;
        return $result;
    }

    public function downloadOrder($id)
    {
        $sql = "UPDATE `bear_order` SET download_count = download_count+1 WHERE `bear_order_id` = '{$id}'";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
        
        $sql = "INSERT INTO `order_download_log` (`id`, `bear_order_id`, `download_date`) VALUES (NULL, '{$id}', CURRENT_TIMESTAMP)";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
        
        return true;
    }
    
    public function orderList($obj, $page = null)
    {
        if (empty($obj))
        {
            $obj = new stdClass();
        }
        $dataSearch = $this->orderData();
        $orderType = 'ASC';
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (empty($obj->order_by))
        {
            $obj->order_by = 'bear_order_id';
        }
        $data = NULL;
        foreach ($dataSearch as $key => $DBFieldName)
        {
            if ($obj->order_by == $key)
            {
                $orderBy = $DBFieldName;
            }//sort
            if (isset($obj->$key))
            {
                if ($key == 'bear_order_id' || $key == 'bear_order_id')
                {
                    $value[] = "{$DBFieldName} = :{$key}";
                    $data[":{$key}"] = "{$obj->$key}";
                } else
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
        $sql_c = "SELECT SQL_CACHE count(`bear_order_id`) AS count FROM `bear_order` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $row_c = $stmt_c->fetch();
        $c_page = $row_c['count'];

        $sql = "SELECT SQL_CACHE * "
                . "FROM `bear_order` {$where} ORDER BY {$orderBy} {$orderType} LIMIT {$page},{$show_output}";
//        echo $sql . "<br>";
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute($data);
        $result = null;
        if (!$query)
        {
            $error = $stmt->errorInfo();
            $result[] = new stdClass();
            $result[0]->error = $error[2];
        } else
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
        $return->row_total = $c_page;
        return $return;
    }

}
