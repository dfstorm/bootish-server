// global. true: pass. fasle: nope.
var bBootish = false;

document.addEventListener("DOMContentLoaded", function(event) {

  var bootish = {
  // BASE URL
  sBaseUrl : 'https://bootish.genois.tk/',
  // Validation interval
  iInterval : 3000,
  oTimer: false,
  eFormElement : false,
  sSession : false,
  sAction : '',


	// Setup data defaults
	sFormAction : false,
	iWidth : 200,
	iHeight: 40,
	fncCallback : function() {},
	fncLoaded : function() {},
	sLabelWaiting : '',
	sLabelValidating : '',
	sLabelError : '',
	sLabelOkay : '',


    getRandom : function() {
      return guid();
    },

    init : function() {

		// Get the options
		try {
			if (bootishSetup) {
		    	try {
					if(bootishSetup.sFormAction !== undefined) {
						bootish.sFormAction = bootishSetup.sFormAction;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.iWidth !== undefined) {
						bootish.iWidth = bootishSetup.iWidth;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.iHeight !== undefined) {
						bootish.iHeight = bootishSetup.iHeight;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.fncCallback !== undefined) {
						bootish.fncCallback = bootishSetup.fncCallback;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.fncLoaded !== undefined) {
						bootish.fncLoaded = bootishSetup.fncLoaded;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.sLabelWaiting !== undefined) {
						bootish.sLabelWaiting = bootishSetup.sLabelWaiting;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.sLabelValidating !== undefined) {
						bootish.sLabelValidating = bootishSetup.sLabelValidating;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.sLabelError !== undefined) {
						bootish.sLabelError = bootishSetup.sLabelError;
					}
 				} catch(e) { }
				try {
					if(bootishSetup.sLabelOkay !== undefined) {
						bootish.sLabelOkay = bootishSetup.sLabelOkay;
					}
 				} catch(e) { }
			}
		} catch(e) { }


		// genetate a session
		bootish.sSession = bootish.getRandom();

		// Inject CSS
		var link = document.createElement( "link" );
		link.href = bootish.sBaseUrl + "bootish.css";
		link.type = "text/css";
		link.rel = "stylesheet";
		link.media = "screen,print";
		document.getElementsByTagName( "head" )[0].appendChild( link );

		// create an input to get form data
		var input = document.createElement( "input" );
		input.style = 'display:none;';
		input.id = 'bootish-input';
		input.type = 'text';

		var oData = {
			iWidth: bootish.iWidth,
			iHeight: bootish.iHeight,
			sLabelWaiting: bootish.sLabelWaiting,
			sLabelValidating: bootish.sLabelValidating,
			sLabelError: bootish.sLabelError,
			sLabelOkay: bootish.sLabelOkay
		};


		var sData = base64.encode(JSON.stringify(oData));
		var sUrl = bootish.sBaseUrl+'?s='+bootish.sSession+'&sData='+sData;

		// Add an iFrame
		document.getElementById("bootish-container").innerHTML = '<iframe style="border:none; width: '+bootish.iWidth+'px; height: '+bootish.iHeight+'px;" src="'+sUrl+'"></iframe>';

		// Add the input
		document.getElementById("bootish-container").appendChild( input );

		bootish.eFormElement = document.getElementById("bootish-input").form;

		// get the input if not set in options
		if(!bootish.sFormAction) {
			bootish.sAction = bootish.eFormElement.action;
		} else {
      bootish.sAction = bootish.sFormAction;
    }
    console.info(bootish.sAction);

		// disable submit btn
		var inputsList = document.getElementsByTagName( "input" );
		for(var x in inputsList) {
			if(inputsList[x].type == 'submit') {
				inputsList[x].disabled = true;
			}
		}

		// diasble form
      	bootish.eFormElement.setAttribute('onsubmit','return false;');
		bootish.eFormElement.action = '#';

      // trigger event
      bootish.fncLoaded();

      //launch worker
      bootish.worker(bootish.sSession);
    },
    worker : function(sSession) {
      bootish.oTimer = window.setInterval(function() {
        var xdr = new XMLHttpRequest();

        // Action on response
        xdr.onload = function() {

          if(base64.decode(xdr.responseText) == bootish.sSession)
        	{
            // success
      			var inputsList = document.getElementsByTagName( "input" );
      			for(var x in inputsList) {
      				if(inputsList[x].type == 'submit') {
      					inputsList[x].disabled = false;
      				}
      			}
			      bootish.eFormElement.action = bootish.sAction;
            bBootish = true;
            bootish.eFormElement.setAttribute('onsubmit','')
            window.clearInterval(bootish.oTimer);
            bootish.fncCallback();
          }
        }
        // send the request
        xdr.open("GET", bootish.sBaseUrl + "?isItOkay="+sSession);
        xdr.send();
      }, bootish.iInterval);
    }
  }


var base64 = {
        _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
        encode : function encode(input)
        {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            input = this._utf8_encode(input);

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

                output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

            }

            return output;
        },
        decode : function decode(input)
        {
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

            output = this._utf8_decode(output);

            return output;

        },
        _utf8_encode : function _utf8_encode(string)
        {
            string = string.replace(/\r\n/g,"\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {

                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                }
                else if((c > 127) && (c < 2048)) {
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
        _utf8_decode : function _utf8_decode(utftext)
        {
            var string = "";
            var i = 0;
            var c = c1 = c2 = 0;

            while ( i < utftext.length ) {

                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                }
                else if((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i+1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                }
                else {
                    c2 = utftext.charCodeAt(i+1);
                    c3 = utftext.charCodeAt(i+2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            }

            return string;
        }
    }


  bootish.init();

  function guid() {
    function s4() {
      return Math.floor((1 + Math.random()) * 0x10000)
        .toString(16)
        .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
      s4() + '-' + s4() + s4() + s4();
    }
});
