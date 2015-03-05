var aesKey = "abcdefghijklmnopqrstuvwxyz123456";	// 16 byte = 128 bit 키

// 공백 문자 padding
function padding16(sVal)
{
	var nCount = 16 - (sVal.length % 16);
	for (i=0;i<nCount;i++)
		sVal += ' ';
	return sVal;
}

function padding32(sVal)
{
	var nCount = 32 - (sVal.length % 32);
	for (i=0;i<nCount;i++)
		sVal += ' ';
	return sVal;
}

// trim 함수
function trim(str)
{
	return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
}
// 복호화
function doDec( decTargetStr )
{
	if ( decTargetStr != null  )
	{
		var text = hex2s(decTargetStr);
		return trim(hex2s(byteArrayToHex(rijndaelDecrypt(text, aesKey, "ECB"))));
	}
}


//암호화
function doEnc( endTargetStr )
{
	if ( endTargetStr != null  )
	{
		var text = padding16(endTargetStr);
		text = byteArrayToHex(rijndaelEncrypt(text, aesKey, "ECB"));
		return text.replace(/^\s+|\s+$/g,"");
	}
}

function getStringByte( str )
{
	var _byte = 0;
	if (str.length != 0)
	{
		for (var i = 0; i < str.length; i++)
		{
			var str2 = str.charAt(i);
			if (escape(str2).length > 4)
			{
				_byte += 2;
			} else
			{
				_byte++;
			}
		}
	}
	return _byte;
}

