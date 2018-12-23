<?PHP

class admin extends Model
{

    private $timeZone;

    public function __construct()
    {
        parent::__construct();
    }

    public function adminAddUser($username, $psw)
    {
        $psw = md5($psw);
        $result = NULL;
        $sql = "INSERT INTO `admin_user` (`admin_id`, `admin_user`, `admin_password`, `admin_permission`, `admin_active`) VALUES (NULL, '{$username}', '{$psw}', '', '1')";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
    }

    public function adminUser($username, $psw)
    {
        $result = NULL;
        $sql = "SELECT SQL_CACHE * FROM `admin_user` WHERE `admin_user` = '{$username}' AND `admin_password` = '" . md5($psw) . "'";
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute();
        if (!$query)
        {
            $error = $stmt->errorInfo();
            throw new Exception($error[2]);
        }
        $row = $stmt->fetch();
        if ($row != FALSE)
        {
            $result = new stdClass();
            $result->admin_id = $row['admin_id'];
            $result->admin_user = $row['admin_user'];
        }
        return $result;
    }

    public function getAdminSession($id, $sid = null)
    {
        $result = NULL;
        $sql = "SELECT SQL_CACHE * FROM `admin_session` WHERE  ";
        if (!empty($sid))
        {
            $sql .= " `a_sid` = '{$sid}' ";
        } else
        {
            $sql .= " `a_id` = '{$id}' ";
        }
//        echo $sql;
        $stmt = $this->db->connection->prepare($sql);
        $query = $stmt->execute();
        if (!$query)
        {
            $error = $stmt->errorInfo();
            throw new Exception($error[2]);
        }
        $row = $stmt->fetch();
        if ($row != FALSE)
        {
            $result = new stdClass();
            $result->sid = $row['a_sid'];
            $result->admin_id = $row['a_id'];
            $result->ip = $row['a_ip'];
            $result->login_date = $row['a_login_date'];
            $result->expire_date = $row['a_exp_date'];
            $result->expired = $row['exp'];
        }
        return $result;
    }

    public function updateSessionAdmin($obj)
    {
        $sql = "UPDATE `admin_session` "
                . "SET "
                . "`a_sid`='{$obj->sid}',"
//                . "`a_ip`='{$obj->ip}',"
                . "`a_login_date`='{$obj->login_date}',"
//                . "`a_exp_date`='{$obj->expire_date}',"
                . "`exp`='{$obj->expired}' "
                . "WHERE `a_id` = '{$obj->admin_id}'";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
    }

    public function addSessionAdmin($obj)
    {
//        $ip = $this->getUserIpAddress();
        $ip = null;
        $sql = "INSERT INTO `admin_session` "
                . "(`a_id`, `a_sid`, `a_ip`, `a_login_date`, `exp`) "
                . "VALUES ('{$obj->admin_id}', "
                . "'{$obj->sid}', "
                . "'{$ip}', "
                . "'{$obj->login_date}', "
                . "'{$obj->expired}');";
        $stmt = $this->db->connection->prepare($sql);
        $stmt->execute();
    }

}
