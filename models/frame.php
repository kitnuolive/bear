<?php

class Frame extends Model
{

    function frameCategoryDataRow()
    {
        $dataRow = array('frame_category_id' => 'frame_category_id',
            'frame_category_code' => 'frame_category_code',
            'frame_category_branch_id' => 'frame_category_branch_id',
            'frame_category_name' => 'frame_category_name',
            'frame_category_posotion' => 'frame_category_posotion');

        return $dataRow;
    }

    public function frameCategory($obj, $page = null)
    {
        if (empty($obj))
        {
            $obj = new stdClass();
        }
        $dataSearch = $this->frameCategoryDataRow();
        $orderType = 'ASC';
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (empty($obj->order_by))
        {
            $obj->order_by = 'frame_category_posotion';
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
                if ($key == 'frame_category_id')
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
        $sql_c = "SELECT SQL_CACHE count(`frame_category_id`) AS count FROM `frame_category` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $row_c = $stmt_c->fetch();
        $c_page = $row_c['count'];

        $sql = "SELECT SQL_CACHE * "
                . "FROM `frame_category` {$where} ORDER BY {$orderBy} {$orderType} LIMIT {$page},{$show_output}";
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

    function frameListDataRow()
    {
        $dataRow = array('frame_list_id' => 'frame_list_id',
            'frame_category_id' => 'frame_category_id',
            'frame_list_name' => 'frame_list_name',
            'frame_list_pic' => 'frame_list_pic',
            'frame_list_position' => 'frame_list_position');

        return $dataRow;
    }

    public function frameList($obj, $page = null)
    {
        if (empty($obj))
        {
            $obj = new stdClass();
        }
        $dataSearch = $this->frameListDataRow();
        $orderType = 'ASC';
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (empty($obj->order_by))
        {
            $obj->order_by = 'frame_list_position';
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
                if ($key == 'frame_category_id' || $key == 'frame_list_id')
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
        $sql_c = "SELECT SQL_CACHE count(`frame_list_id`) AS count FROM `frame_list` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $row_c = $stmt_c->fetch();
        $c_page = $row_c['count'];

        $sql = "SELECT SQL_CACHE * "
                . "FROM `frame_list` {$where} ORDER BY {$orderBy} {$orderType} LIMIT {$page},{$show_output}";
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

    function stickerCategoryDataRow()
    {
        $dataRow = array('sticker_category_id' => 'sticker_category_id',
            'sticker_category_branch_id'=>'sticker_category_branch_id',
            'sticker_category_name' => 'sticker_category_name',
            'sticker_category_position' => 'sticker_category_position');

        return $dataRow;
    }

    public function stickerCategory($obj, $page = null)
    {
        if (empty($obj))
        {
            $obj = new stdClass();
        }
        $dataSearch = $this->stickerCategoryDataRow();
        $orderType = 'ASC';
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (empty($obj->order_by))
        {
            $obj->order_by = 'sticker_category_position';
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
                if ($key == 'sticker_category_id')
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
        $sql_c = "SELECT SQL_CACHE count(`sticker_category_id`) AS count FROM `sticker_category` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $row_c = $stmt_c->fetch();
        $c_page = $row_c['count'];

        $sql = "SELECT SQL_CACHE * "
                . "FROM `sticker_category` {$where} ORDER BY {$orderBy} {$orderType} LIMIT {$page},{$show_output}";
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

    function stickerListDataRow()
    {
        $dataRow = array('sticker_list_id' => 'sticker_list_id',
            'sticker_category_id' => 'sticker_category_id',
            'sticker_list_name' => 'sticker_list_name',
            'sticker_list_pic' => 'sticker_list_pic',
            'sticker_list_pic_svg' => 'sticker_list_pic_svg',
            'sticker_list_position' => 'sticker_list_position');

        return $dataRow;
    }

    public function stickerList($obj, $page = null)
    {
        if (empty($obj))
        {
            $obj = new stdClass();
        }
        $dataSearch = $this->stickerListDataRow();
        $orderType = 'ASC';
        if (!empty($obj->order_type))
        {
            $orderType = $obj->order_type;
        }
        if (empty($obj->order_by))
        {
            $obj->order_by = 'sticker_list_position';
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
                if ($key == 'sticker_category_id' || $key == 'sticker_list_id')
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
        $sql_c = "SELECT SQL_CACHE count(`sticker_list_id`) AS count FROM `sticker_list` {$where}";
//        var_dump($sql_c);
        $stmt_c = $this->db->connection->prepare($sql_c);
        $stmt_c->execute($data);
        $row_c = $stmt_c->fetch();
        $c_page = $row_c['count'];

        $sql = "SELECT SQL_CACHE * "
                . "FROM `sticker_list` {$where} ORDER BY {$orderBy} {$orderType} LIMIT {$page},{$show_output}";
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
