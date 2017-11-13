<?php

function templateEmail($data,$header = null)
{
    $title = !empty($data->topic) ? $data->topic : NULL;
    $textMsg = !empty($data->textMsg) ? $data->textMsg : NULL;

    if(isset($header)){
        $companySettingName = !empty($header->companySettingName) ? $header->companySettingName : NULL;
        $companySettingPic = !empty($header->companySettingPic) ? '../../assets'.$header->companySettingPic : NULL;
        $companySettingTax = !empty($header->companySettingTax) ? $header->companySettingTax : NULL;
        $companySettingAddress = !empty($header->companySettingAddress) ? $header->companySettingAddress : NULL;
        $companySettingEmail = !empty($header->companySettingEmail) ? $header->companySettingEmail : NULL;
        $companySettingFax = !empty($header->companySettingFax) ? $header->companySettingFax : NULL;
        $companySettingTel = !empty($header->companySettingTel) ? $header->companySettingTel : NULL;
    }


$msg = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="initial-scale=1.0"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<meta name="format-detection" content="telephone=no"/>
<title>KNSquotation</title>
<link href="http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,cyrillic,greek" rel="stylesheet" type="text/css">
<style type="text/css">

    
    /* Resets: see reset.css for details */
    .ReadMsgBody { width: 100%; background-color: #ffffff;}
    .ExternalClass {width: 100%; background-color: #ffffff;}
    .ExternalClass, .ExternalClass p, .ExternalClass span,
    .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height:100%;}
    #outlook a{ padding:0;}
    html{width: 100%; }
    body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none; }
    html,body {background-color: #ffffff; margin: 0; padding: 0; }
    table {border-spacing:0;}
    table td {border-collapse:collapse;}
    br, strong br, b br, em br, i br { line-height:100%; }
    h1, h2, h3, h4, h5, h6 { line-height: 100% !important; -webkit-font-smoothing: antialiased; }
    img{height: auto !important; line-height: 100%; outline: none; text-decoration: none; display:block !important; }
    span a { text-decoration: none !important;}
    a{ text-decoration: none !important; }
    table p{margin:0;}
    .yshortcuts, .yshortcuts a, .yshortcuts a:link,.yshortcuts a:visited,
    .yshortcuts a:hover, .yshortcuts a span { text-decoration: none !important; border-bottom: none !important;}
    table{ mso-table-lspace:0pt; mso-table-rspace:0pt; }
    img{ -ms-interpolation-mode:bicubic; }
    ul{list-style: initial; margin:0; padding-left:20px;}
    /*mailChimp class*/
    .default-edit-image{
    height:20px;
    }
    .tpl-repeatblock {
    padding: 0px !important;
    border: 1px dotted rgba(0,0,0,0.2);
    }
    img{height:auto !important;}
    td[class="image-270px"] img{
    width:270px;
    height:auto !important;
    max-width:270px !important;
    }
    td[class="image-170px"] img{
    width:170px;
    height:auto !important;
    max-width:170px !important;
    }
    td[class="image-185px"] img{
    width:185px;
    height:auto !important;
    max-width:185px !important;
    }
    td[class="image-124px"] img{
    width:124px;
    height:auto !important;
    max-width:124px !important;
    }
    @media only screen and (max-width: 640px){
    body{
    width:auto!important;
    }
    table[class="container"]{
    width: 100%!important;
    padding-left: 20px!important;
    padding-right: 20px!important;
    min-width:100% !important;
    }
    td[class="image-270px"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    }
    td[class="image-170px"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    }
    td[class="image-185px"] img{
    width:185px !important;
    height:auto !important;
    max-width:185px !important;
    }
    td[class="image-124px"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    }
    td[class="image-100-percent"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    }
    td[class="small-image-100-percent"] img{
    width:100% !important;
    height:auto !important;
    }
    table[class="full-width"]{
    width:100% !important;
    min-width:100% !important;
    }
    table[class="full-width-text"]{
    width:100% !important;
    background-color:#ffffff;
    padding-left:20px !important;
    padding-right:20px !important;
    }
    table[class="full-width-text2"]{
    width:100% !important;
    background-color:#ffffff;
    padding-left:20px !important;
    padding-right:20px !important;
    }
    table[class="col-2-3img"]{
    width:50% !important;
    margin-right: 20px !important;
    }
    table[class="col-2-3img-last"]{
    width:50% !important;
    }
    table[class="col-2-footer"]{
    width:55% !important;
    margin-right:20px !important;
    }
    table[class="col-2-footer-last"]{
    width:40% !important;
    }
    table[class="col-2"]{
    width:47% !important;
    margin-right:20px !important;
    }
    table[class="col-2-last"]{
    width:47% !important;
    }
    table[class="col-3"]{
    width:29% !important;
    margin-right:20px !important;
    }
    table[class="col-3-last"]{
    width:29% !important;
    }
    table[class="row-2"]{
    width:50% !important;
    }
    td[class="text-center"]{
    text-align: center !important;
    }
    /* start clear and remove*/
    table[class="remove"]{
    display:none !important;
    }
    td[class="remove"]{
    display:none !important;
    }
    /* end clear and remove*/
    table[class="fix-box"]{
    padding-left:20px !important;
    padding-right:20px !important;
    }
    td[class="fix-box"]{
    padding-left:20px !important;
    padding-right:20px !important;
    }
    td[class="font-resize"]{
    font-size: 18px !important;
    line-height: 22px !important;
    }
    table[class="space-scale"]{
    width:100% !important;
    float:none !important;
    }
    table[class="clear-align-640"]{
    float:none !important;
    }
    table[class="show-full-mobile"]{
    display:none !important;
    width:100% !important;
    min-width:100% !important;
    }
    }
    @media only screen and (max-width: 479px){
    body{
    font-size:10px !important;
    }
    table[class="container"]{
    width: 100%!important;
    padding-left: 10px!important;
    padding-right:10px!important;
    min-width:100% !important;
    }
    table[class="container2"]{
    width: 100%!important;
    float:none !important;
    min-width:100% !important;
    }
    td[class="full-width"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    min-width:100% !important;
    }
    td[class="image-270px"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    }
    td[class="image-170px"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    }
    td[class="image-185px"] img{
    width:185px !important;
    height:auto !important;
    max-width:185px !important;
    min-width:124px !important;
    }
    td[class="image-124px"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    }
    td[class="image-100-percent"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    }
    td[class="small-image-100-percent"] img{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    }
    table[class="full-width"]{
    width:100% !important;
    }
    table[class="full-width-text"]{
    width:100% !important;
    background-color:#ffffff;
    padding-left:20px !important;
    padding-right:20px !important;
    }
    table[class="full-width-text2"]{
    width:100% !important;
    background-color:#ffffff;
    padding-left:20px !important;
    padding-right:20px !important;
    }
    table[class="col-2-footer"]{
    width:100% !important;
    margin-right:0px !important;
    }
    table[class="col-2-footer-last"]{
    width:100% !important;
    }
    table[class="col-2"]{
    width:100% !important;
    margin-right:0px !important;
    }
    table[class="col-2-last"]{
    width:100% !important;
    }
    table[class="col-3"]{
    width:100% !important;
    margin-right:0px !important;
    }
    table[class="col-3-last"]{
    width:100% !important;
    }
    table[class="row-2"]{
    width:100% !important;
    }
    table[id="col-underline"]{
    float: none !important;
    width: 100% !important;
    border-bottom: 1px solid #eee;
    }
    td[id="col-underline"]{
    float: none !important;
    width: 100% !important;
    border-bottom: 1px solid #eee;
    }
    td[class="col-underline"]{
    float: none !important;
    width: 100% !important;
    border-bottom: 1px solid #eee;
    }
    /*start text center*/
    td[class="text-center"]{
    text-align: center !important;
    }
    div[class="text-center"]{
    text-align: center !important;
    }
    /*end text center*/
    /* start  clear and remove */
    table[id="clear-padding"]{
    padding:0 !important;
    }
    td[id="clear-padding"]{
    padding:0 !important;
    }
    td[class="clear-padding"]{
    padding:0 !important;
    }
    table[class="remove-479"]{
    display:none !important;
    }
    td[class="remove-479"]{
    display:none !important;
    }
    table[class="clear-align"]{
    float:none !important;
    }
    /* end  clear and remove */
    table[class="width-small"]{
    width:100% !important;
    }
    table[class="fix-box"]{
    padding-left:15px !important;
    padding-right:15px !important;
    }
    td[class="fix-box"]{
    padding-left:15px !important;
    padding-right:15px !important;
    }
    td[class="font-resize"]{
    font-size: 14px !important;
    }
    td[class="increase-Height"]{
    height:10px !important;
    }
    td[class="increase-Height-20"]{
    height:20px !important;
    }
    table[width="595"]{
    width:100% !important;
    }
    table[class="show-full-mobile"]{
    display:table !important;
    width:100% !important;
    min-width:100% !important;
    }
    }
    @media only screen and (max-width: 320px){
    table[class="width-small"]{
    width:125px !important;
    }
    img[class="image-100-percent"]{
    width:100% !important;
    height:auto !important;
    max-width:100% !important;
    min-width:124px !important;
    }
    }
    a:active{color:initial !important;} a:visited{color:initial !important;}
    td ul{list-style: initial; margin:0; padding-left:20px;}

    @media only screen and (max-width: 640px){ .image-100-percent{ width:100%!important; height: auto !important; max-width: 100% !important; min-width: 124px !important;}}body{background-color:#efefef;} .default-edit-image{height:20px;} tr.tpl-repeatblock , tr.tpl-repeatblock > td{ display:block !important;} .tpl-repeatblock {padding: 0px !important;border: 1px dotted rgba(0,0,0,0.2);} table[width="595"]{width:100% !important;}a img{ border: 0 !important;}
a:active{color:initial } a:visited{color:initial }
.tpl-content{padding:0 !important;}
.full-mb,*[fix="full-mb"]{width:100%!important;} .auto-mb,*[fix="auto-mb"]{width:auto!important;}
</style>
<!--[if gte mso 15]>
<style type="text/css">
a{text-decoration: none !important;}
body { font-size: 0; line-height: 0; }
tr { font-size:1px; mso-line-height-alt:0; mso-margin-top-alt:1px; }
table { font-size:1px; line-height:0; mso-margin-top-alt:1px; }
body,table,td,span,a,font{font-family: Arial, Helvetica, sans-serif !important;}
a img{ border: 0 !important;}
</style>
<![endif]-->
<!--[if gte mso 9]><xml><o:OfficeDocumentSettings><o:AllowPNG/><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml><![endif]-->
</head>
<body  style="font-size:12px; width:100%; height:100%;">
<table id="mainStructure" width="800" class="full-width" align="center" border="0" cellspacing="0" cellpadding="0" style="background-color: #efefef; width: 800px; max-width: 800px; outline: rgb(239, 239, 239) solid 1px; box-shadow: rgb(224, 224, 224) 0px 0px 5px; margin: 0px auto;">





        <!-- START LAYOUT-1/2 --><tr><td align="center" valign="top" style="background-color:#ecebeb;" bgcolor="#ecebeb">
            <!-- start  container width 600px -->
            <table width="600" align="center" border="0" cellspacing="0" cellpadding="0" class="container" style="background-color: #ffffff; padding-left: 20px; padding-right: 20px; min-width: 600px; width: 600px; margin: 0px auto;"><tbody><tr><td valign="top">
                  <!-- start container width 560px -->
                  <table width="560" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width" style="width: 560px; margin: 0px auto;"><!-- start text content --><tbody><tr><td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 0px auto;"><!-- start text content --><tbody><tr dup="0"><td valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 0px auto;"><!--start space height --><tbody><tr><td height="15" style="height: 15px; font-size: 0px; line-height: 0; border-collapse: collapse;"></td>
                                </tr><!--end space height --><tr><td style="font-size: 13px; font-family: Roboto, Arial, Helvetica, Arial; color: #000000; font-weight: 300; text-align: left; word-break: break-word; line-height: 21px;" align="center"><span style="line-height: 21px; font-size: 18px; font-weight: 300; font-family: Roboto, Arial, Helvetica, Arial;"><font face="Roboto, Arial, Helvetica, Arial">


                                    '.$title.'


                                  </font></span></td>
                                </tr><!--start space height --><tr><td height="15" style="height: 15px; font-size: 0px; line-height: 0; border-collapse: collapse;"></td>
                                </tr><!--end space height --></tbody></table></td>
                          </tr><!-- end text content --><tr><td valign="top" width="100%" align="center">
                              <!-- start button -->
                              <table width="auto" border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 0px auto;"><tbody><tr><td valign="top">
                                    </td>
                                </tr></tbody></table><!-- end button --></td>
                          </tr></tbody></table></td>
                    </tr><!-- end text content --><!--start space height --><tr><td height="20" style="height: 20px; font-size: 0px; line-height: 0; border-collapse: collapse;">&nbsp;</td>
                    </tr><!--end space height --></tbody></table><!-- end  container width 560px --></td>
              </tr></tbody></table><!-- end  container width 600px --></td>
        </tr><!-- END LAYOUT-1/2 -->


        <!-- START LAYOUT-1/2 --><tr><td align="center" valign="top" style="background-color:#ecebeb;" bgcolor="#ecebeb">
            <!-- start  container width 600px -->
            <table width="600" align="center" cellspacing="0" cellpadding="0" class="container" style=" background-color: #ffffff; padding-left: 20px; padding-right: 20px; min-width: 600px; width: 600px; margin: 0px auto;"><tbody><tr><td valign="top">
                  <!-- start container width 560px -->
                  <table width="560" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width" style=" border-top : 1px solid #000000; width: 560px; margin: 0px auto;"><!-- start text content --><tbody><tr><td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 0px auto;"><!-- start text content --><tbody><tr dup="0"><td valign="top">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 0px auto;"><!--start space height --><tbody><tr><td height="15" style="height: 15px; font-size: 0px; line-height: 0; border-collapse: collapse;"></td>
                                </tr><!--end space height --><tr><td style="font-size: 13px; font-family: Roboto, Arial, Helvetica, Arial; color: #000000; font-weight: 300; text-align: left; word-break: break-word; line-height: 21px;" align="center"><span style="line-height: 21px; font-size: 13px; font-weight: 300; font-family: Roboto, Arial, Helvetica, Arial;"><font face="Roboto, Arial, Helvetica, Arial">


                                    '.$textMsg.'


                                  </font></span></td>
                                </tr><!--start space height --><tr><td height="15" style="height: 15px; font-size: 0px; line-height: 0; border-collapse: collapse;"></td>
                                </tr><!--end space height --></tbody></table></td>
                          </tr><!-- end text content --><tr><td valign="top" width="100%" align="center">
                              <!-- start button -->
                              <table width="auto" border="0" align="center" cellpadding="0" cellspacing="0" style="margin: 0px auto;"><tbody><tr><td valign="top">
                                    </td>
                                </tr></tbody></table><!-- end button --></td>
                          </tr></tbody></table></td>
                    </tr><!-- end text content --><!--start space height --><tr><td height="20" style="height: 20px; font-size: 0px; line-height: 0; border-collapse: collapse;">&nbsp;</td>
                    </tr><!--end space height --></tbody></table><!-- end  container width 560px --></td>
              </tr></tbody></table><!-- end  container width 600px --></td>
        </tr><!-- END LAYOUT-1/2 -->

        <!-- START LAYOUT-1/2 --><tr><td align="center" valign="top" style="background-color:#ecebeb;" bgcolor="#ecebeb">
            <!-- start  container width 600px -->
            <table width="600" align="center" border="0" cellspacing="0" cellpadding="0" class="container" style="background-color: #ffffff; padding-left: 20px; padding-right: 20px; min-width: 600px; width: 600px; margin: 0px auto;"><tbody><tr><td valign="top">
                  <!-- start container width 560px -->
                  <table width="560" align="center" border="0" cellspacing="0" cellpadding="0" class="full-width" style="width: 560px; margin: 0px auto;"><!-- start image and content --><tbody><tr><td valign="top" width="100%">
                        <!-- start content left -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt; mso-table-rspace:0pt;"><!--start space height --><tbody><tr><td height="20" style="height: 20px; font-size: 0px; line-height: 0; border-collapse: collapse;">&nbsp;</td>
                          </tr><!--end space height --><!-- start content top--><tr><td valign="top" align="left">
                              <table  cellspacing="0" cellpadding="0" align="left" width="100%" style="border-top : 1px solid #000000;  mso-table-lspace:0pt; mso-table-rspace:0pt;"><tbody>
                                <tr>
                                <td style="height : 15px; font-size: 18px; font-family: Roboto, Arial, Helvetica, sans-serif; color: #555555; font-weight: 300; text-align: left; word-break: break-word; line-height: 26px;" align="left">

                                </td>
                                </tr>
                                <tr><td valign="top" align="left" style="padding-right: 20px; width: 100px;" width="100">
                                    <a href="" style="font-size: inherit; border-style: none; text-decoration: none !important;" border="0">
                                      <img src="'.$companySettingPic.'" width="120" alt="face1" style="max-width:120px; display:block !important; " border="0" hspace="0" vspace="0"></a>
                                  </td>
                                  <td style="colspan=2;" valign="top">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt; mso-table-rspace:0pt;"><tbody>
                                    <tr><td style=" font-size: 18px; font-family: Roboto, Arial, Helvetica, sans-serif; color: #555555; font-weight: 300; text-align: left; word-break: break-word; line-height: 26px;" align="left">
                                          <span style="color: #555555; line-height: 26px; font-size: 18px; font-weight: 300; font-family: Roboto, Arial, Helvetica, sans-serif;"><font face="Roboto, Arial, Helvetica, sans-serif">
                                            <a href="#" style="text-decoration: none solid rgb(85, 85, 85) !important; color: #555555; border-style: none; line-height: 26px; font-size: 18px; font-weight: 300; font-family: Roboto, Arial, Helvetica, sans-serif;" border="0"><font face="Roboto, Arial, Helvetica, sans-serif">
                                              '.$companySettingName.'
                                            </font></a>
                                          </font></span>
                                        </td>
                                      </tr><!--start space height -->

                                      <tr>
                                        <td style="colspan=2; font-size: 13px; font-family: Roboto, Arial, Helvetica, sans-serif; color: #a3a2a2; font-weight: 300; text-align: left; word-break: break-word; line-height: 21px;" align="left"><span style="line-height: 13px; font-size: 13px; font-weight: 300; font-family: Roboto, Arial, Helvetica, sans-serif;"><font face="Roboto, Arial, Helvetica, sans-serif">
                                            '.$companySettingAddress.'
                                        </font></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px; font-family: Roboto, Arial, Helvetica, sans-serif; color: #a3a2a2; font-weight: 300; text-align: left; word-break: break-word; line-height: 13px;" align="left">
                                        <span style="width: 300px; line-height: 13px; font-size: 13px; font-weight: 300; font-family: Roboto, Arial, Helvetica, sans-serif;"><font face="Roboto, Arial, Helvetica, sans-serif">
                                            โทรศัพท์ : '.$companySettingTel.' </span>
                                        <span style="width: 300px; line-height: 13px; font-size: 13px; font-weight: 300; font-family: Roboto, Arial, Helvetica, sans-serif;"><font face="Roboto, Arial, Helvetica, sans-serif">
                                             แฟกซ์ : '.$companySettingFax.'
                                        </font></span>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td style="font-size: 13px; font-family: Roboto, Arial, Helvetica, sans-serif; color: #a3a2a2; font-weight: 300; text-align: left; word-break: break-word; line-height: 13px;" align="left"><span style="line-height: 13px; font-size: 13px; font-weight: 300; font-family: Roboto, Arial, Helvetica, sans-serif;"><font face="Roboto, Arial, Helvetica, sans-serif">
                                            อีเมล : '.$companySettingEmail.' เลขประจำตัวผู้เสียภาษี : '.$companySettingTax.'
                                        </font></span>
                                        </td>
                                        
                                      </tr></tbody></table></td>
                                </tr></tbody></table></td>
                          </tr><!-- end  content top--><!--start space height --><tr><td height="15" class="col-underline" style="height: 15px; font-size: 0px; line-height: 0; border-collapse: collapse;"></td>
                          </tr><!--end space height --><!--start space height --><tr><td class="increase-Height-20"></td>
                          </tr><!--end space height --></tbody></table><!-- end content left --></td>
                    </tr><!-- end image and content --><tr dup="0"><td>
                        <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0px auto;"><tbody><tr><td height="5" style="height: 5px; font-size: 0px; line-height: 0; border-collapse: collapse;"></td>
                          </tr><tr><td style="border-bottom:1px solid #c7c7c7;"></td>
                          </tr></tbody></table></td>
                    </tr></tbody></table><!-- end  container width 560px --></td>
              </tr></tbody></table><!-- end  container width 600px --></td>
        </tr><!-- END LAYOUT-1/2 --></table></body>
</html>
';

return $msg;
}