
<!doctype html>
  <html lang="en-US">

  <head>
      <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
      <title>Room Reservation Success</title>
      <meta name="description" content="New Account Email Template.">
      <style type="text/css">
          a:hover {text-decoration: underline !important;}
      </style>
  </head>

  <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
      <!-- 100% body table -->
      <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8"
          style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: 'Open Sans', sans-serif;">
          <tr>
              <td>
                  <table style="background-color: #f2f3f8; max-width:670px; margin:0 auto;" width="100%" border="0"
                      align="center" cellpadding="0" cellspacing="0">
                      <tr>
                          <td style="height:80px;">&nbsp;</td>
                      </tr>
                      <tr>
                          <td style="text-align:center;">
                              <a href="#" title="logo" target="_blank">
                              <img width="60" src="https://kdgh.simbillsoft.in/assets/img/kadampuzha-bhagavathy.png" title="logo" alt="logo">
                            </a>
                          </td>
                      </tr> <tr>
                        <td style="text-align:center;">Kadampuzha Bhagavathy Devaswom Guest House</td>
                    </tr>

                      <tr>
                          <td style="height:20px;">&nbsp;</td>
                      </tr>
                      <tr>
                          <td>
                              <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0"
                                  style="max-width:670px; background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);">
                                  <tr>
                                      <td style="height:40px;">&nbsp;</td>
                                  </tr>
                                  <tr>
                                      <td style="padding:0 35px;">
                                          <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">Room Reservation Success
                                          </h1>
                                          <p>Dear {{ $booking->guest_details->name }},</p>

                                          <p style="font-size:15px; color:#455056; margin:8px 0 0; line-height:24px;">
                                            Your reservation has been successfully confirmed.<br> Here are the details:</strong>.</p>
                                          <span
                                              style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                                          <p
                                              style="color:#455056; font-size:18px;line-height:20px; margin:0; font-weight: 500;">
                                              <strong
                                                  style="display: block;font-size: 13px; margin: 0 0 4px; color:rgba(0,0,0,.64); font-weight:normal;">Booking Number</strong>{{ $booking->booking_number }}
                                              <strong
                                                  style="display: block; font-size: 13px; margin: 24px 0 4px 0; font-weight:normal; color:rgba(0,0,0,.64);">Check-in Date</strong>{{ \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y') }}
                                                  <strong
                                                  style="display: block; font-size: 13px; margin: 24px 0 4px 0; font-weight:normal; color:rgba(0,0,0,.64);">Check-out Date</strong>{{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y') }}
                                          </p>

                                          <p style="font-size:15px; color:#455056; margin:8px 0 0; line-height:24px;">If you have any questions or need further assistance, feel free to contact us.</p>

                                          <p style="font-size:15px; color:#455056; margin:8px 0 0; line-height:24px;">Thank you for choosing our service!</p>
                                          <a href="https://kdgh.simbillsoft.in/booking-invoice/{{ $booking->id }}"
                                              style="background:#20e277;text-decoration:none !important; display:inline-block; font-weight:500; margin-top:24px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">Download Invoice</a>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td style="height:40px;">&nbsp;</td>
                                  </tr>
                              </table>
                          </td>
                      </tr>
                      <tr>
                          <td style="height:20px;">&nbsp;</td>
                      </tr>
                      <tr>
                          <!-- <td style="text-align:center;">
                              <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy; <strong>kdfc</strong> </p>
                          </td> -->
                      </tr>
                      <tr>
                          <td style="height:80px;">&nbsp;</td>
                      </tr>
                  </table>
              </td>
          </tr>
      </table>
      <!--/100% body table-->
  </body>

  </html>



