/////////////////////////////////////////////////////////////////////////////////
// File Name    : md5.js
/////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
// Function Name    : getDigest
///////////////////////////////////////////////////////////////////////////////
function getDigest(data)
{
    return convertHex(calcDigest(convertUtf8(data))).toLowerCase();
}

///////////////////////////////////////////////////////////////////////////////
// Calculate the MD5 of a raw string
///////////////////////////////////////////////////////////////////////////////
function calcDigest(data)
{
    return toString(calcFromBitArray(toBitArray(data), data.length * 8));
}

///////////////////////////////////////////////////////////////////////////////
// Convert a raw string to a hex string
///////////////////////////////////////////////////////////////////////////////
function convertHex(data)
{
    var i, buf, result = "";

    var data_len = data.length;
    var hex_table = "0123456789abcdef";

    for(i = 0; i < data_len; i++)
    {
        buf = data.charCodeAt(i);
        result += hex_table.charAt((buf >>> 4) & 0x0F) +  hex_table.charAt(buf & 0x0F);
    }

    return result;
}

///////////////////////////////////////////////////////////////////////////////
// Encode a string as utf-8.
// For efficiency, this assumes the data is valid utf-16.
///////////////////////////////////////////////////////////////////////////////
function convertUtf8(data)
{
    var result = "";
    var x, y, i = -1;
    var data_len = data.length;

    while(++i < data_len)
    {
        // Decode utf-16 surrogate pairs
        x = data.charCodeAt(i);
        y = i + 1 < data_len ? data.charCodeAt(i + 1) : 0;

        if(0xD800 <= x && x <= 0xDBFF && 0xDC00 <= y && y <= 0xDFFF)
        {
            x = 0x10000 + ((x & 0x03FF) << 10) + (y & 0x03FF);
            i++;
        }

        // Encode result as utf-8
        if(x <= 0x7F)
        {
            result += String.fromCharCode(x);
            continue;
        }

        if(x <= 0x7FF)
        {
            result += String.fromCharCode(0xC0 | ((x >>> 6 ) & 0x1F), 0x80 | (x & 0x3F));
            continue;
        }

        if(x <= 0xFFFF)
        {
            result += String.fromCharCode(0xE0 | ((x >>> 12) & 0x0F), 0x80 | ((x >>> 6 ) & 0x3F), 0x80 | (x & 0x3F));
            continue;
        }

        if(x <= 0x1FFFFF)
            result += String.fromCharCode(0xF0 | ((x >>> 18) & 0x07), 0x80 | ((x >>> 12) & 0x3F), 0x80 | ((x >>> 6 ) & 0x3F), 0x80 | (x & 0x3F));
    }

    return result;
}

/////////////////////////////////////////////////////////////////////////////////
// Convert a raw string to an array of little-endian words
// Characters >255 have their high-byte silently ignored.
 /////////////////////////////////////////////////////////////////////////////////
function toBitArray(data)
{
    var data_len = data.length;
    var result = Array(data_len >> 2);

    for(var i = 0; i < result.length; i++)
        result[i] = 0;

    for(var i = 0; i < data_len * 8; i += 8)
        result[i >> 5] |= (data.charCodeAt(i / 8) & 0xFF) << (i%32);

    return result;
}

/////////////////////////////////////////////////////////////////////////////////
// Convert an array of little-endian words to a string
/////////////////////////////////////////////////////////////////////////////////
function toString(data)
{
    var result = "";
    var data_len = data.length;

    for(var i = 0; i < data_len * 32; i += 8)
        result += String.fromCharCode((data[i >> 5] >>> (i % 32)) & 0xFF);

    return result;
}

/////////////////////////////////////////////////////////////////////////////////
// Calculate the MD5 of an array of little-endian words, and a bit length.
/////////////////////////////////////////////////////////////////////////////////
function calcFromBitArray(buffer, len)
{
    // append padding
   buffer[len >> 5] |= 0x80 << ((len) % 32);
   buffer[(((len + 64) >>> 9) << 4) + 14] = len;

    var a =  0x67452301;
    var b = 0xEFCDAB89;
    var c = 0x98BADCFE;
    var d =  0x10325476;

    var data_len = buffer.length;
    var old_a, old_b, old_c, old_d;

    for(var i = 0; i <  data_len; i += 16)
    {
        old_a = a;
        old_b = b;
        old_c = c;
        old_d = d;

        a = md5_f(a, b, c, d, buffer[i+ 0], 7 , -680876936);
        d = md5_f(d, a, b, c, buffer[i+ 1], 12, -389564586);
        c = md5_f(c, d, a, b, buffer[i+ 2], 17,  606105819);
        b = md5_f(b, c, d, a, buffer[i+ 3], 22, -1044525330);
        a = md5_f(a, b, c, d, buffer[i+ 4], 7 , -176418897);
        d = md5_f(d, a, b, c, buffer[i+ 5], 12,  1200080426);
        c = md5_f(c, d, a, b, buffer[i+ 6], 17, -1473231341);
        b = md5_f(b, c, d, a, buffer[i+ 7], 22, -45705983);
        a = md5_f(a, b, c, d, buffer[i+ 8], 7 ,  1770035416);
        d = md5_f(d, a, b, c, buffer[i+ 9], 12, -1958414417);
        c = md5_f(c, d, a, b, buffer[i+10], 17, -42063);
        b = md5_f(b, c, d, a, buffer[i+11], 22, -1990404162);
        a = md5_f(a, b, c, d, buffer[i+12], 7 ,  1804603682);
        d = md5_f(d, a, b, c, buffer[i+13], 12, -40341101);
        c = md5_f(c, d, a, b, buffer[i+14], 17, -1502002290);
        b = md5_f(b, c, d, a, buffer[i+15], 22,  1236535329);

        a = md5_g(a, b, c, d, buffer[i+ 1], 5 , -165796510);
        d = md5_g(d, a, b, c, buffer[i+ 6], 9 , -1069501632);
        c = md5_g(c, d, a, b, buffer[i+11], 14,  643717713);
        b = md5_g(b, c, d, a, buffer[i+ 0], 20, -373897302);
        a = md5_g(a, b, c, d, buffer[i+ 5], 5 , -701558691);
        d = md5_g(d, a, b, c, buffer[i+10], 9 ,  38016083);
        c = md5_g(c, d, a, b, buffer[i+15], 14, -660478335);
        b = md5_g(b, c, d, a, buffer[i+ 4], 20, -405537848);
        a = md5_g(a, b, c, d, buffer[i+ 9], 5 ,  568446438);
        d = md5_g(d, a, b, c, buffer[i+14], 9 , -1019803690);
        c = md5_g(c, d, a, b, buffer[i+ 3], 14, -187363961);
        b = md5_g(b, c, d, a, buffer[i+ 8], 20,  1163531501);
        a = md5_g(a, b, c, d, buffer[i+13], 5 , -1444681467);
        d = md5_g(d, a, b, c, buffer[i+ 2], 9 , -51403784);
        c = md5_g(c, d, a, b, buffer[i+ 7], 14,  1735328473);
        b = md5_g(b, c, d, a, buffer[i+12], 20, -1926607734);

        a = md5_h(a, b, c, d, buffer[i+ 5], 4 , -378558);
        d = md5_h(d, a, b, c, buffer[i+ 8], 11, -2022574463);
        c = md5_h(c, d, a, b, buffer[i+11], 16,  1839030562);
        b = md5_h(b, c, d, a, buffer[i+14], 23, -35309556);
        a = md5_h(a, b, c, d, buffer[i+ 1], 4 , -1530992060);
        d = md5_h(d, a, b, c, buffer[i+ 4], 11,  1272893353);
        c = md5_h(c, d, a, b, buffer[i+ 7], 16, -155497632);
        b = md5_h(b, c, d, a, buffer[i+10], 23, -1094730640);
        a = md5_h(a, b, c, d, buffer[i+13], 4 ,  681279174);
        d = md5_h(d, a, b, c, buffer[i+ 0], 11, -358537222);
        c = md5_h(c, d, a, b, buffer[i+ 3], 16, -722521979);
        b = md5_h(b, c, d, a, buffer[i+ 6], 23,  76029189);
        a = md5_h(a, b, c, d, buffer[i+ 9], 4 , -640364487);
        d = md5_h(d, a, b, c, buffer[i+12], 11, -421815835);
        c = md5_h(c, d, a, b, buffer[i+15], 16,  530742520);
        b = md5_h(b, c, d, a, buffer[i+ 2], 23, -995338651);

        a = md5_i(a, b, c, d, buffer[i+ 0], 6 , -198630844);
        d = md5_i(d, a, b, c, buffer[i+ 7], 10,  1126891415);
        c = md5_i(c, d, a, b, buffer[i+14], 15, -1416354905);
        b = md5_i(b, c, d, a, buffer[i+ 5], 21, -57434055);
        a = md5_i(a, b, c, d, buffer[i+12], 6 ,  1700485571);
        d = md5_i(d, a, b, c, buffer[i+ 3], 10, -1894986606);
        c = md5_i(c, d, a, b, buffer[i+10], 15, -1051523);
        b = md5_i(b, c, d, a, buffer[i+ 1], 21, -2054922799);
        a = md5_i(a, b, c, d, buffer[i+ 8], 6 ,  1873313359);
        d = md5_i(d, a, b, c, buffer[i+15], 10, -30611744);
        c = md5_i(c, d, a, b, buffer[i+ 6], 15, -1560198380);
        b = md5_i(b, c, d, a, buffer[i+13], 21,  1309151649);
        a = md5_i(a, b, c, d, buffer[i+ 4], 6 , -145523070);
        d = md5_i(d, a, b, c, buffer[i+11], 10, -1120210379);
        c = md5_i(c, d, a, b, buffer[i+ 2], 15,  718787259);
        b = md5_i(b, c, d, a, buffer[i+ 9], 21, -343485551);

        a = safe_add(a, old_a);
        b = safe_add(b, old_b);
        c = safe_add(c, old_c);
        d = safe_add(d, old_d);
    }

    return Array(a, b, c, d);
}

///////////////////////////////////////////////////////////////////////////////
// Round Functions
// These functions implement the four basic operations the algorithm uses.
///////////////////////////////////////////////////////////////////////////////

function md5_round_common(q, a, b, x, s, t)
{
    return safe_add(rotateLeftShiftU32(safe_add(safe_add(a, q), safe_add(x, t)), s),b);
}

function md5_f(a, b, c, d, x, s, t)
{
    return md5_round_common((b & c) | ((~b) & d), a, b, x, s, t);
}

function md5_g(a, b, c, d, x, s, t)
{
    return md5_round_common((b & d) | (c & (~d)), a, b, x, s, t);
}

function md5_h(a, b, c, d, x, s, t)
{
    return md5_round_common(b ^ c ^ d, a, b, x, s, t);
}

function md5_i(a, b, c, d, x, s, t)
{
    return md5_round_common(c ^ (b | (~d)), a, b, x, s, t);
}

///////////////////////////////////////////////////////////////////////////////
// Add integers, wrapping at 2^32. This uses 16-bit operations internally
// to work around bugs in some JS interpreters.
///////////////////////////////////////////////////////////////////////////////
function safe_add(x, y)
{
    var LowWord = (x & 0xFFFF) + (y & 0xFFFF);                            // Least Significant Word
    var HighWord = (x >> 16) + (y >> 16) + (LowWord >> 16);       // Most Significant Word

    return (HighWord << 16) | (LowWord & 0xFFFF);
}

///////////////////////////////////////////////////////////////////////////////
// Bitwise rotate a 32-bit number to the left.
///////////////////////////////////////////////////////////////////////////////
function rotateLeftShiftU32(n_data, n_shift)
{
    return (n_data << n_shift) | (n_data >>> (32 - n_shift));
}