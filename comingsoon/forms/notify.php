<?php
  /**
  * Requires the "PHP Email Form" library
  * The "PHP Email Form" library is available only in the pro version of the template
  * The library should be uploaded to: vendor/php-email-form/php-email-form.php
  * For more info and help: https://bootstrapmade.com/php-email-form/
  */

  // Replace contact@example.com with your real receiving email address

  $to = "rutulp.90@gmail.com";
  $subject = "OutOut Subscription Form";
  
  $message = "<b>You can find the details below</b>";
  $message .= "<table><tbody>
                <tr>
                  <td> One user is trying to contact us. </td>
                </tr>

  </tbody></table>";
  
  $header = "From:info@cricketcups.com \r\n";
  $header .= "Cc:noreply@cricketcups.com \r\n";
  $header .= "MIME-Version: 1.0\r\n";
  $header .= "Content-type: text/html\r\n";
  
  $retval = mail ($to,$subject,$message,$header);

  if( $retval == true ) {
    echo "Message sent successfully...";
 }else {
    echo "Message could not be sent...";
 }

?>
