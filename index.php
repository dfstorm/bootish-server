<?php
  // bootish.genois.tk

    session_start();

    class bootish {

      var $sdbAdress = 'localhost';
      var $sdbUser = 'XXXXXX';
      var $sdbPass = 'XXXXXX';
      var $sdbName = 'XXXXXX';


      var $sSession = false;
      var $db = false;


	  var $iWidth = 200;
	  var $iHeight = 40;
	  var $sLabelWaiting = 'I&#39;m not a robot';
	  var $sLabelValidating = 'Validating...';
	  var $sLabelError = 'Network error';
	  var $sLabelOkay = 'I&#39;m not a robot';



      public function init() {

        $this->db = new mysqli(
          $this->sdbAdress,
          $this->sdbUser,
          $this->sdbPass,
          $this->sdbName
        );

        if(isset($_GET['s'])) {
          $this->sSession = $_GET['s'];
        }

		if(isset($_GET['sData'])) {
			$arrData = json_decode(base64_decode($_GET['sData']));
			if($arrData) {
				if(isset($arrData->iWidth)) { $this->iWidth = ($arrData->iWidth!==''?$arrData->iWidth:$this->iWidth); }
				if(isset($arrData->iHeight)) { $this->iHeight = ($arrData->iHeight!==''?$arrData->iHeight:$this->iHeight); }
				if(isset($arrData->sLabelWaiting)) { $this->sLabelWaiting = ($arrData->sLabelWaiting!==''?$arrData->sLabelWaiting:$this->sLabelWaiting); }
				if(isset($arrData->sLabelValidating)) { $this->sLabelValidating = ($arrData->sLabelValidating!==''?$arrData->sLabelValidating:$this->sLabelValidating); }
				if(isset($arrData->sLabelError)) { $this->sLabelError = ($arrData->sLabelError!==''?$arrData->sLabelError:$this->sLabelError); }
				if(isset($arrData->sLabelOkay)) { $this->sLabelOkay = ($arrData->sLabelOkay!==''?$arrData->sLabelOkay:$this->sLabelOkay); }
			}
		}


        if(isset($_GET)) {
          if(isset($_GET['isItOkay'])) {
            header("Access-Control-Allow-Origin: *");

            $sql = "SELECT sID, iNoSession FROM sessions WHERE sID = '" . mysql_real_escape_string($_GET['isItOkay'])."'";
            $result = $this->db->query($sql);
            if ($result->num_rows > 0) {
                $this->db->query('DELETE FROM sessions WHERE sID = "' . mysql_real_escape_string($_GET['isItOkay']).'"');
                echo base64_encode($_GET['isItOkay']);
            } else {
                echo "0";
            }
            return;
          }
          if(isset($_GET['session'])) {
            // security to be added

            if ($this->db->query("INSERT INTO sessions( sID ) VALUES('".mysql_real_escape_string($_GET['session']."')")) === TRUE) {
                echo base64_encode($_GET['session']);
            } else {
                echo '0';
            }
            return;
          }
        }
        if($this->sSession) {
          echo $this->show();
?>

<script>
var Base64 = {


    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",


    encode: function(input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },


    decode: function(input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    _utf8_encode: function(string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    _utf8_decode: function(utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while (i < utftext.length) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}
</script>

<?php
        } else {
          header('Content-Type: application/json');
          echo json_encode(array('error','Acces denied. Refere to https://github.com/dfstorm/bootish'));
        }

        $this->db->close();
      }

      public function show() {
        $sHtml = <<<HTMLRENDER
          <html>
            <head>
<meta charset="utf-8" />
            <link rel="stylesheet" href="css/font-awesome.min.css">
            <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css' integrity='sha384-MIwDKRSSImVFAZCVLtU0LMDdON6KVCrZHyVQQj6e8wIEJkW4tvwqXrbMIya1vriY' crossorigin='anonymous'>
            <style>
            .btn {
              margin: 0px;
              height: {$this->iHeight}px;
              width: {$this->iWidth}px;
            }
            </style>
            </head>
            <body>
            <form action="" onsubmit="bootish.send('{$this->sSession}'); return false;" style="margin:0px; padding: 0px;">
              <button id="bootish-btn" type="submit" class="btn btn-primary"><i class="fa fa-square-o" aria-hidden="true"></i> {$this->sLabelWaiting}</button>
            </form>
            <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js' integrity='sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY' crossorigin='anonymous'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js' integrity='sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB' crossorigin='anonymous'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js' integrity='sha384-ux8v3A6CPtOTqOzMKiuo3d/DomGaaClxFYdCu2HPMBEkf6x2xiDyJ7gkXU0MWwaD' crossorigin='anonymous'></script>
            <script>

            var bootish = {

              sUrl : 'https://bootish.genois.tk/',
              iSession : 0,
              send : function send(iSession) {

                bootish.iSession = iSession;

                $('#bootish-btn').html('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> {$this->sLabelValidating}');
                $('#bootish-btn').removeClass('btn-primary');
                $('#bootish-btn').removeClass('btn-success');
                $('#bootish-btn').removeClass('btn-danger');
                $('#bootish-btn').addClass('btn-info');

                setTimeout(function() {
                  $.ajax({
                    url: bootish.sUrl,
                    data: {session:bootish.iSession},
                    success: function(resonse) {
                      $('#bootish-btn').removeClass('btn-info');
					  if(Base64.decode(resonse) == bootish.iSession) {
						$('#bootish-btn').addClass('btn-success');
                          $('#bootish-btn').attr('disabled',true);
                          $('#bootish-btn').html('<i class="fa fa-check" aria-hidden="true"> {$this->sLabelOkay}');
					  } else {
						  $('#bootish-btn').addClass('btn-warning');
                          $('#bootish-btn').html('<i class="fa fa-ban" aria-hidden="true"></i> ++++');
					  }
                    },
                    error : function() {
                      $('#bootish-btn').removeClass('btn-info');
                      $('#bootish-btn').addClass('btn-danger');
                      $('#bootish-btn').html('<i class="fa fa-times" aria-hidden="true"></i> {$this->sLabelError}');
                    }
                  });
                },1000);



              }
            }


            </script>


</body>
          </html>
HTMLRENDER;
        return $sHtml;
      }

    }

    $system = new bootish;
    $system->init();



?>
