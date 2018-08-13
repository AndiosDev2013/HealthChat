var Base64 = {
    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    // public method for encoding
    encode : function (input) {
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
            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
    // public method for decoding
    decode : function (input) {
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
    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
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
    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
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

//note:  it is assumed that the Base64 object has already been defined
//License:  Apache 2.0
Base64.byteToCharMap_ = null;
Base64.charToByteMap_ = null;
Base64.byteToCharMapWebSafe_ = null;
Base64.charToByteMapWebSafe_ = null;
Base64.ENCODED_VALS_BASE =
    'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
    'abcdefghijklmnopqrstuvwxyz' +
    '0123456789';

/**
 * Our default alphabet. Value 64 (=) is special; it means "nothing."
 * @type {string}
 */
Base64.ENCODED_VALS = Base64.ENCODED_VALS_BASE + '+/=';
Base64.ENCODED_VALS_WEBSAFE = Base64.ENCODED_VALS_BASE + '-_.';

/**
 * Base64-encode an array of bytes.
 *
 * @param {Array.<number>|Uint8Array} input An array of bytes (numbers with
 *     value in [0, 255]) to encode.
 * @param {boolean=} opt_webSafe Boolean indicating we should use the
 *     alternative alphabet.
 * @return {string} The base64 encoded string.
 */
Base64.encodeByteArray = function(input, opt_webSafe) {
  Base64.init_();

  var byteToCharMap = opt_webSafe ?
                      Base64.byteToCharMapWebSafe_ :
                      Base64.byteToCharMap_;

  var output = [];

  for (var i = 0; i < input.length; i += 3) {
    var byte1 = input[i];
    var haveByte2 = i + 1 < input.length;
    var byte2 = haveByte2 ? input[i + 1] : 0;
    var haveByte3 = i + 2 < input.length;
    var byte3 = haveByte3 ? input[i + 2] : 0;

    var outByte1 = byte1 >> 2;
    var outByte2 = ((byte1 & 0x03) << 4) | (byte2 >> 4);
    var outByte3 = ((byte2 & 0x0F) << 2) | (byte3 >> 6);
    var outByte4 = byte3 & 0x3F;

    if (!haveByte3) {
      outByte4 = 64;

      if (!haveByte2) {
        outByte3 = 64;
      }
    }

    output.push(byteToCharMap[outByte1],
                byteToCharMap[outByte2],
                byteToCharMap[outByte3],
                byteToCharMap[outByte4]);
  }

  return output.join('');
};

/**
 * Lazy static initialization function. Called before
 * accessing any of the static map variables.
 * @private
 */
Base64.init_ = function() {
  if (!Base64.byteToCharMap_) {
    Base64.byteToCharMap_ = {};
    Base64.charToByteMap_ = {};
    Base64.byteToCharMapWebSafe_ = {};
    Base64.charToByteMapWebSafe_ = {};

    // We want quick mappings back and forth, so we precompute two maps.
    for (var i = 0; i < Base64.ENCODED_VALS.length; i++) {
      Base64.byteToCharMap_[i] =
          Base64.ENCODED_VALS.charAt(i);
      Base64.charToByteMap_[Base64.byteToCharMap_[i]] = i;
      Base64.byteToCharMapWebSafe_[i] =
          Base64.ENCODED_VALS_WEBSAFE.charAt(i);
      Base64.charToByteMapWebSafe_[
          Base64.byteToCharMapWebSafe_[i]] = i;
    }
  }
};