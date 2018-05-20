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
        }
        else
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
                }
                else
                {
                    $id = $this->db->connection->LastInsertId();
                }
            }
            else
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
                    }
                    else
                    {
                        $b += 1;
                    }
                }
                else
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
                }
                else
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
        }
        else
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
        }
        else
        {
            if (empty($obj->bear_order_id))
            {
                $bear_order_id = $this->db->connection->LastInsertId();
            }
            else
            {
                $bear_order_id = $obj->bear_order_id;
            }
        }
        $result->bear_order_id = $bear_order_id;
        return $result;
    }

}
