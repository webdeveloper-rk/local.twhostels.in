<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>:: TWHOSTELS.IN ::</title>

<style type="text/css">

<!--

body {

	margin-left: 0px;

	margin-top: 0px;

	margin-right: 0px;

	margin-bottom: 0px;

	background-color:#ccc;

	font-family:Arial, Helvetica, sans-serif;

	 

	background-repeat:repeat;

}

-->

.alert-danger {

    color: #a94442;

    background-color: #f2dede;

    border-color: #ebccd1;

}

.alert {

    padding: 15px;

    margin-bottom: 20px;

    border: 1px solid transparent;

    border-radius: 4px;

}



.alert-success

{

	 color: #FFF;

    background-color: #4CAF50;

    border-color: #ebccd1;

}





.boxshadow{

box-shadow: 0px 12px 12px #222;

}

</style>



<link rel="stylesheet" type="text/css" href="ddsmoothmenu.css" />

<link rel="stylesheet" type="text/css" href="ddsmoothmenu-v.css" />





 

<link href="<?php echo site_url();?>css/style_swf.css" rel="stylesheet" type="text/css"> 



<script type="text/javascript" src="<?php echo site_url();?>js/jquery-1.2.6.min.js"></script>

<script type="text/javascript" src="<?php echo site_url();?>slideshow.js"></script>





</head>



<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td align="center"><table width="1020" border="0" align="center" cellpadding="0" cellspacing="0"  class="boxshadow">

  <tr>

    <td height="5" bgcolor="#000000"></td>

  </tr>

  <tr>

    <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">

      <tr>

        <td height="120" align="left" bgcolor="#FFFFFF"><img src="<?php echo site_url();?>images/logo.jpg" width="1024"   /></td>

      </tr>

    </table></td>

  </tr>

  

   

  

  

   

      <tr>

        <td align="center" bgcolor="#FFFFFF"><table width="99%" cellspacing="0" cellpadding="0">

          <tr>
            <td colspan="2" align="center" valign="top" >&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="left" valign="top" bgcolor="#eeeeee" style="padding:10px;"><h3> Support Email : <span style="color:#FF0000;font-weight:bold;">twhostelsitcell@gmail.com</span> | Contact : <span style="color:#FF0000;font-weight:bold;">Vinod Joshi</span> | Mobile : <span style="color:#FF0000;font-weight:bold;"> 9848888795</span></h3></td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="top" > <h3>&nbsp;</h3></td>
            </tr>
          <tr>

            <td align="center" valign="top" bgcolor="#FFFFCC" ><table width="320" cellspacing="0" cellpadding="0">

              <tr>

                <td align="left" valign="top" height="5"></td>
              </tr>

              <tr>

                <td align="left" valign="top"><table width="98%" align="center" cellpadding="0" cellspacing="0">

                    <tr>

                      <td width="4" align="left" valign="middle"><img src="<?php echo site_url();?>images_swf/login-icon.jpg" width="14" height="14" /></td>

                      <td align="left"  class="main-headings">TWHOSTELS Login </td>
                    </tr>

                </table></td>
              </tr>

              <tr>

                <td align="left" valign="middle" height="10"><img src="<?php echo site_url();?>images_swf/line-bar-new.jpg" width="100%" height="1" /></td>
              </tr>

              <tr>

                <td align="left" valign="top" height="10"></td>
              </tr>

              <tr>

                <td align="left" valign="top">

				<form method="post" role="form" id="main_form" action="<?php echo site_url('admin/login');?>">

				

				<div class="form-group">
                
               

					<h4>Please use DDO Code to Login.</h4>

					<div class="input-group">

						<div class="input-group-addon">

							<i class="entypo-user"></i>						</div>

						

						<input type="text" class="form-control" required name="email" id="inputEmail" placeholder="DDO Code" autocomplete="off" data-mask="email" />
					</div>
				</div>

				

				<div class="form-group">

					

					<div class="input-group">

						<div class="input-group-addon">

							<i class="entypo-key"></i>						</div>

						

						<input type="password" class="form-control"  required name="password" id="password" placeholder="Password" autocomplete="off" />
					</div>
				</div>
<div class="form-group">

					

					<div class="input-group">

						<div class="input-group-addon">

							<i class="entypo-key"></i>						</div>

						

						
						<input type="text" class="form-control"  required name="captcha" id="captcha" placeholder="Enter below Captcha Code here " autocomplete="off" />
						<img src="<?php echo site_url('captcha');?>" id="captchacode"><a title ="Reload captcha code" href="javascript:void(0);" onclick="reloadcaptcha()" ><img src="<?php echo site_url();?>images/reload.jpg" height="30" width="30" alt='Reload' style="margin-left:10px;"></a>					</div>
				</div>
				

				<div class="form-group" >
					<span id="logmessage" style="display:none;font-weight:bold;">Login Processing Please Wait .... </span>
					<span id="loginbutton"><button type="submit" class="btn btn-primary btn-block btn-login" style="font-size:18px; padding:5px;">

						<i class="entypo-login"></i>

						Login					</button>
						</span>				</div>

			 <div id="notifier"></div>
 
				<div><a  style="
    background-color: #2A7FFF;
    padding: 9px;
    color: #FFFFFF;
    text-decoration: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: bold;
" target="_blank" href="http://twhostels.in/twhostels_help.pdf"><!--user_manual_new.PDF--><strong>Hwo / Hm User Manual Telugu</strong></a></div>
			</form>

			

		

		<!--	<img src="<?php echo site_url();?>images/maintain.jpg">-->			</td>
              </tr>

              

              

              <tr>

                <td align="left" valign="top" height="10">
                
                
                
                <br /> <br />
                <h4>Applicatio Demo Video</h4>
                
                <iframe width="100%" height="275" src="https://www.youtube.com/embed/GK1w1iX0UlM" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>                </td>
              </tr>

              
            

              
 
              

              
 

              <tr>

                <td align="left" valign="top">&nbsp;</td>
              </tr>

            </table></td>

            <td align="right" valign="top" class="left-side-border"><table width="98%" align="center" cellpadding="0" cellspacing="0">

                <tr>

                  <td align="left" valign="top" height="5"></td>
                </tr>

                <tr>

                  <td align="left" valign="top" class="sub-main-headings">Welcome to TWHOSTELS</td>
                </tr>

                <tr>

                  <td align="left" valign="top" class="main-content">Government of Telengana Tribal Welfare Department Hostel Monitoring System Currently runs 468 Ashram Hostels in Telengana under the department of Tribal Welfare, Government of Telengana. TW Ashram Schools provides  education & Hostel to SC/ST/BC communities from 5th class to Intermediate. The prime objective of the Tribal TW Ashram Schools is to provide quality residential education to the underprivileged communities. TW Ashram Hostels implements a scientifically developed menu which provides quality and nutritional food to the students. To monitor the daily Menu, quality and standard of the food in the schools, the Society brings this software, TWHOSTELS, A Menu Monitoring Software (MMS). </td>
                </tr>

                <tr>

                  <td align="left" valign="top"><table width="97%" align="left" cellpadding="0" cellspacing="0">

                      <tr>

                        <td align="left" valign="baseline">&nbsp;</td>
                      </tr>

                      <tr>

                        <td align="left" valign="baseline" class="sub-main-headings">MENU MONITORING SOFTWARE is an online</td>
                      </tr>

                      <tr>

                        <td align="left" valign="baseline"><table width="100%" align="left" cellpadding="0" cellspacing="0">

                            

                            <tr>

                              <td align="left" valign="top" class="main-content" style="line-height:28PX;">
										  Menu Monitoring Software is used across Telengana Social Welfare & Tribal  Welfare Department. This software helps to update and track the complete Food Provisional Items, Monthly Indent Generation, Approved Tenderer, Daily Consumption Report and Students Attendance information. Every school updates menu issue and perishable items issues Four times a day i.e at 1. Breakfast 2.Lunch 3. Supper 4. Snacks. Reports are generated based on the above data at school, district and Head office level, these reports help in ensuring the quality of food supplied. We can also know consumption history, purchase history, opening balance and closing balance at any given instance                            </td>
                            </tr>

                            

                        </table></td>
                      </tr>

                      <tr>

                        <td align="left" valign="baseline" height="10"></td>
                      </tr>

                      <tr>

                        <td align="left" valign="baseline" class="sub-main-headings">Features of The Software</td>
                      </tr>

                      <tr>

                        <td align="left" valign="baseline"><table width="95%" align="left" cellpadding="0" cellspacing="0">

                            <tr>

                              <td width="3%" align="center" valign="baseline"><font color="#000000" size="+2">&bull;</font></td>

                              <td width="93%" height="20" align="left" valign="baseline" class="main-content">Add / Edit Student Attendence Entry </td>
                            </tr>

                            <tr>

                              <td width="3%" align="center" valign="baseline"><font color="#000000" size="+2">&bull;</font></td>

                              <td width="93%" height="20" align="left" valign="baseline" class="main-content">Add Opening Balance Entries of menu list..</td>
                            </tr>

                            <tr>

                              <td width="3%" align="center" valign="baseline"><font color="#000000" size="+2">&bull;</font></td>

                              <td width="93%" height="20" align="left" valign="baseline" class="main-content">Add Purchase entries for date wise</td>
                            </tr>

                            <tr>

                              <td width="3%" align="center" valign="baseline"><font color="#000000" size="+2">&bull;</font></td>

                              <td width="93%" height="20" align="left" valign="baseline" class="main-content">Add Consumption entries of breakfast, lunch, dinner, snaks for list of menu items. </td>
                            </tr>

                            <tr>

                              <td width="3%" align="center" valign="baseline"><font color="#000000" size="+2">&bull;</font></td>

                              <td width="93%" height="20" align="left" valign="baseline" class="main-content">Reports of item wise for calendar wise</td>
                            </tr>

                        </table></td>
                      </tr>

                  </table></td>
                </tr>

            </table></td>
          </tr>

        </table></td>

      </tr>

      <tr>

        <td bgcolor="#FFFFFF">&nbsp;</td>

      </tr>

    </table></td>

  </tr>

  <tr>

    <td height="30" align="center" bgcolor="#000000" class="footerxt">© Copyright TWHOSTELS</td>

  </tr>

</table>

<!-- jQuery 1.10.2 -->

        <script src="<?php echo site_url();?>assets/admin/js/jquery-1.10.2.min.js"></script>

        <script src="<?php echo site_url();?>assets/admin/js/jquery.form.js"></script>

        

		<script type="text/javascript" src="ddsmoothmenu.js"></script>

		<!-- Bootstrap -->

        <script src="<?php echo site_url();?>assets/admin/js/bootstrap.min.js" type="text/javascript"></script>      
<script type="text/javascript">
function reloadcaptcha()
{
	//console.log("hi...");
	 
	$("#captchacode").attr("src","<?php echo site_url("captcha");?>");  
}
            $(document).ready(function() {

                $('#main_form').ajaxForm({dataType: 'json', 
										 
										beforeSerialize: function($form, options) { 
																		$("#passsaved").val( $("#password").val());    
																	  $("#password").val(CryptoJS.AES.encrypt(JSON.stringify($("#password").val()), "<?php echo $this->config->item("hash_code");?>", {format: CryptoJSAesJson}).toString());            
																},
								 beforeSubmit : function(arr, $form, options){
														 
													$("#loginbutton").hide();
													$("#logmessage").show();

												  },
				
				
				success: processJson});

                $("#inputEmail").focus();

            });

            function processJson(data) {

                if (data.success) {
					
					
					
													
													

                    $("#notifier").html(data.message);

                     setTimeout(function() {  
					 
							 
					window.location = "<?php echo site_url('admin'); ?>";

                    }, 2000); 

                } else {

                    $("#notifier").html(data.message);
					
					$("#loginbutton").show();
					$("#logmessage").hide();
					
						$("#password").val( $("#passsaved").val());  
						$("#captcha").val("");  
						reloadcaptcha();
                }

            }

        </script></td>

  </tr>

</table>

<script type="text/javascript" src="<?php echo site_url();?>js/aes.js"></script>
  <script type="text/javascript" src="<?php echo site_url();?>js/aes-json-format.js"></script>

</body>

</html>

