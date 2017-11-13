<?php

require_once(ROOT . DS . 'libs' . DS . 'geoip' . DS . 'geoip.inc');

class Setting_utill
{

    public static function customerIp()
    {
        $cus_ip = $_SERVER['REMOTE_ADDR'];
        if ($cus_ip == '127.0.0.1' || $cus_ip == '::1')
        {
            $cus_ip = "124.120.229.128";
        }

        $gi = geoip_open('GeoIP.dat', GEOIP_MEMORY_CACHE);
        $countryIp = geoip_country_name_by_addr($gi, $cus_ip);
        geoip_close($gi);
        return $countryIp;
    }

    public static function get_user_ip_address()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        { //if from shared
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {   //if from a proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED']))
        {
            return $_SERVER['HTTP_X_FORWARDED'];
        }
        else if (!empty($_SERVER['HTTP_FORWARDED_FOR']))
        {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }
        else if (!empty($_SERVER['HTTP_FORWARDED']))
        {
            return $_SERVER['HTTP_FORWARDED'];
        }
        else
        {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    public static function encrypt_decrypt($action, $string, $module = null)
    {
        if (!empty($module))
        {
//            $string = $string . $module;
        }
        $output = false;

        if ($action == 'encrypt')
        {
            $output = base64_encode(base64_encode($string));
            $output = base64_encode($output);
        }
        else if ($action == 'decrypt')
        {
            $output = base64_decode(base64_decode($string));
            $output = base64_decode($output);
        }
        return $output;
    }

    public static function extractInt($str)
    {
        preg_match('/[^0-9]*([0-9]+)[^0-9]*/', $str, $regs);
        return (intval($regs[1]));
    }

    public static function arrayToSingleObject($object)
    {
        if (empty($object))
        {
            return null;
        }
        if (is_array($object))
        {
            return $object[0];
        }
        return $object;
    }

    public static function genarateFilePathName($accId, $optionPath = NULL, $fileNameOption = NULL, $setFileName = NULL)
    {
        if (is_NULL($optionPath))
        {
            $directory = "upload/" . $accId;
        }
        else
        {
            $directory = "upload/" . $accId . "/" . $optionPath;
        }
        if (empty($accId))
        {
            $directory = "upload/" . $optionPath;
        }

        if (!is_dir($directory))
        {
            mkdir($directory, 0777, true);
        }
        if (!empty($setFileName))
        {
            return $directory . "/" . $setFileName;
        }
        return $directory . "/" . $fileNameOption . time() . rand(1, 99);
    }

    public static function arraySortByColumn($data, $column, $orderTypeKey)
    {
//        $orderType = ['ASC' => SORT_ASC,
//            'DESC' => SORT_DESC];
//        $sortCol = array();
//        foreach ($data as $key => $row)
//        {
//            $sortCol[$key] = $row->$column;
//        }
//        array_multisort($sortCol, $orderType[$orderTypeKey], $data);
//
//        return $data;
    }

    public static function getTime($seconds)
    {
        $seconds = (int) $seconds;
        $dtF = new DateTime("@0");
        $dtT = new DateTime("@$seconds");
//        var_dump($dtF);
//        var_dump($dtT);
        if ($seconds < 60)
        {
            return 'Just now';
        }
        if ($seconds < 3600 && $seconds >= 300)
        {
            return $dtF->diff($dtT)->format('%im');
        }
        if ($seconds >= 3600 && $seconds < 86400)
        {
            return $dtF->diff($dtT)->format('%hH %im');
        }

        return $dtF->diff($dtT)->format('%dD %hH %im');
    }

    public static function formatDate($date)
    {
        $date = date_create($date);

        $monthThai = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', ' พ.ย.', 'ธ.ค.');

        $d = date_format($date, "d");
        $m = (int) date_format($date, "m");
        $y = date_format($date, "y");
        $y = ($y + 43);

        $date = "$d $monthThai[$m] $y";

        return $date;
    }

    public static function formatDateFull($date, $time = NULL)
    {
        $t = $date;
        $date = date_create($date);

        $monthThai = array('', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', ' พ.ย.', 'ธ.ค.');

        $d = date_format($date, "d");
        $m = (int) date_format($date, "m");
        $y = date_format($date, "Y");
        $y = ($y + 543);
        $date = "$d $monthThai[$m] $y";
        if(!empty($time))
        {
            $h = date("H:i", strtotime($t));
            $date = "$d $monthThai[$m] $y $h";
        }

        return $date;
    }

    public static function ThaiBaht($Number)
    {
        //ตัดสิ่งที่ไม่ต้องการทิ้งลงโถส้วม
        for ($i = 0; $i < strlen($Number); $i++)
        {
            $Number = str_replace(",", "", $Number); //ไม่ต้องการเครื่องหมายคอมมาร์
            $Number = str_replace(" ", "", $Number); //ไม่ต้องการช่องว่าง
            $Number = str_replace("บาท", "", $Number); //ไม่ต้องการตัวหนังสือ บาท
            $Number = str_replace("฿", "", $Number); //ไม่ต้องการสัญลักษณ์สกุลเงินบาท
        }
        //สร้างอะเรย์เก็บค่าที่ต้องการใช้เอาไว้
        $TxtNumArr = array("ศูนย์", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า", "สิบ");
        $TxtDigitArr = array("", "สิบ", "ร้อย", "พัน", "หมื่น", "แสน", "ล้าน");
        $BahtText = "";
        //ตรวจสอบดูซะหน่อยว่าใช่ตัวเลขที่ถูกต้องหรือเปล่า ด้วย isNaN == true ถ้าเป็นข้อความ == false ถ้าเป็นตัวเลข
        if (empty($Number))
        {
            return $BahtText;
        }
        else
        {
            //ตรวสอบอีกสักครั้งว่าตัวเลขมากเกินความต้องการหรือเปล่า
            if (($Number - 0) > 9999999.9999)
            {
                return $BahtText;
            }
            else
            {
                //พรากทศนิยม กับจำนวนเต็มออกจากกัน (บาปหรือเปล่าหนอเรา พรากคู่เขา)
                $Number = explode(".", $Number);
                //ขั้นตอนต่อไปนี้เป็นการประมวลผลดูกันเอาเองครับ แบบว่าขี้เกียจจะจิ้มดีดแล้ว อิอิอิ
                if (strlen($Number[1]) > 0)
                {
                    $Number[1] = substr($Number[1], 0, 2);
                }
                $NumberLen = strlen($Number[0]) - 0;
                // var_dump($NumberLen);
                // var_dump($Number[0]);
                for ($i = 0; $i < $NumberLen; $i++)
                {
                    $tmp = substr($Number[0], $i, 1) - 0;
                    if ($tmp != 0)
                    {
                        if (($i == ($NumberLen - 1)) && ($tmp == 1))
                        {
                            $BahtText = $BahtText . "เอ็ด";
                        }
                        else
                        if (($i == ($NumberLen - 2)) && ($tmp == 2))
                        {
                            $BahtText = $BahtText . "ยี่";
                        }
                        else
                        if (($i == ($NumberLen - 2)) && ($tmp == 1))
                        {
                            $BahtText = $BahtText . "";
                        }
                        else
                        {
                            $BahtText = $BahtText . $TxtNumArr[$tmp];
                        }
                        $BahtText = $BahtText . $TxtDigitArr[$NumberLen - $i - 1];
                    }
                }
                $BahtText = $BahtText . "บาท";
                if (($Number[1] == "0") || ($Number[1] == "00"))
                {
                    $BahtText = $BahtText . "ถ้วน";
                }
                else
                {
                    $DecimalLen = strlen($Number[1]) - 0;
                    for ($i = 0; $i < $DecimalLen; $i++)
                    {
                        $tmp = substr($Number[1], $i, 1) - 0;
                        if ($tmp != 0)
                        {
                            if (($i == ($DecimalLen - 1)) && ($tmp == 1))
                            {
                                $BahtText = $BahtText . "เอ็ด";
                            }
                            else
                            if (($i == ($DecimalLen - 2)) && ($tmp == 2))
                            {
                                $BahtText = $BahtText . "ยี่";
                            }
                            else
                            if (($i == ($DecimalLen - 2)) && ($tmp == 1))
                            {
                                $BahtText = $BahtText . "";
                            }
                            else
                            {
                                $BahtText = $BahtText . $TxtNumArr[$tmp];
                            }
                            $BahtText = $BahtText . $TxtDigitArr[$DecimalLen - $i - 1];
                        }
                    }
                    $BahtText = $BahtText . "สตางค์";
                }
                return $BahtText;
            }
        }
    }

    public static function ThaiMonth($dayId = NULL, $monthId = NULL, $year = NULL)
    {
        if (!empty($dayId) || !empty($monthId) || !empty($year))
        {
            $thaiDayArray = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
            $thaiMonthArray = array(
                "01" => "มกราคม",
                "02" => "กุมภาพันธ์",
                "03" => "มีนาคม",
                "04" => "เมษายน",
                "05" => "พฤษภาคม",
                "06" => "มิถุนายน",
                "07" => "กรกฎาคม",
                "08" => "สิงหาคม",
                "09" => "กันยายน",
                "10" => "ตุลาคม",
                "11" => "พฤศจิกายน",
                "12" => "ธันวาคม"
            );
            $dayTH = !empty($dayId) ? $thaiDayArray[$dayId] : NULL;
            $monthTH = !empty($monthId) ? $thaiMonthArray[$monthId] : NULL;
            $yearTH = !empty($year) ? $year + 543 : NULL;
        }
        else
        {
            $dayTH = NULL;
            $monthTH = NULL;
            $yearTH = NULL;
        }

        $result = new stdClass();
        $result->day = $dayTH;
        $result->month = $monthTH;
        $result->year = $yearTH;
        $result->fullDay = $dayTH.' '.$monthTH.' '.$yearTH;
        return $result;
    }

    public static function numberFormatch($number, $zero = NULL)
    {
        $dot = 2;
        if (!empty($zero))
        {
            $dot = 0;
        }
        $return = number_format($number, $dot, ".", ",");

        return $return;
    }

    public static function convert_number_to_words($number)
    {

        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number))
        {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX)
        {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0)
        {
            return $negative . Setting_utill::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false)
        {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true)
        {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units)
                {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder)
                {
                    $string .= $conjunction . Setting_utill::convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = Setting_utill::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder)
                {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= Setting_utill::convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction))
        {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number)
            {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
    
    public static function workDay($strStartDate, $strEndDate)
    {
	$intWorkDay = 0;
	$intHoliday = 0;
	$intTotalDay = ((strtotime($strEndDate) - strtotime($strStartDate))/  ( 60 * 60 * 24 )) + 1; 

	while (strtotime($strStartDate) <= strtotime($strEndDate)) {
		
		$DayOfWeek = date("w", strtotime($strStartDate));
		if($DayOfWeek == 0)  // 0 = Sunday, 6 = Saturday
		{
			$intHoliday++;
//			echo "$strStartDate = <font color=red>Holiday</font><br>";
		}
		else
		{
			$intWorkDay++;
//			echo "$strStartDate = <b>Work Day</b><br>";
		}
		//$DayOfWeek = date("l", strtotime($strStartDate)); // return Sunday, Monday,Tuesday....

		$strStartDate = date ("Y-m-d", strtotime("+1 day", strtotime($strStartDate)));
	}

//	echo "<hr>";
//	echo "<br>Total Day = $intTotalDay";
//	echo "<br>Work Day = $intWorkDay";
//	echo "<br>Holiday = $intHoliday";
        $result = new stdClass();
        $result->TotalDay= $intTotalDay;
        $result->WorkDay = $intWorkDay;
        $result->Holiday = $intHoliday;
        return $result;
    }
    
    public static function removeBr($str)
    {
        return preg_replace("/<br\W*?\/>/", "", $str);
    }

}
