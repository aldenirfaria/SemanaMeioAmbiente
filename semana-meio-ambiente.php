
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">

<head><title>FS.WordFinder - Word Search Builder <br /> Criador de Caça-Palavras</title>
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />

<style type="text/css">
  html,body{background:green;color:black;height:90%;}
  td{font-size: 6.0mm; font-family: Courier New;padding:0px 7px 0px 7px;}
  #sp{width:15px;}
</style>


<script type="text/javascript"><!--
var words = new Array();
var wordcount=0;
var minutes=0;
var seconds=0;
var rev=0;
var mistakes=0;
var paused=0;
var revealed='';
var wordList=0;
var savedGame=1;
var createdMain='';

var mouseIsDown = 0;


function eventHandle() {
  document.onkeypress=pause;
  document.onmousedown=omd;
  document.onmouseup=omu;
  document.onselectstart=omd;
}

function omd() { mouseIsDown = 1; return false; }

function omu() { mouseIsDown = 0; if(highlighted.length) clearHighlighted(0,0); return false; }

function pause(e) {
  var code;
  if (!e) var e = window.event;
  if (e.keyCode) code = e.keyCode;
  else if (e.which) code = e.which;
  if(code==13 && paused!=2) {
    if(!paused) {
      document.getElementById('grid').style.visibility = 'hidden';
      document.getElementById('list').style.visibility = 'hidden';
      document.getElementById('paused').style.visibility = 'visible';
      paused=1;
    }
    else {
      document.getElementById('grid').style.visibility = 'visible';
      document.getElementById('list').style.visibility = 'visible';
      document.getElementById('paused').style.visibility = 'hidden';
      paused=0;
    }
  }
  else if(code==114 || code==82) {
    if(!wordList) {
      document.getElementById('list').style.visibility = 'hidden';
      wordList=1;
    }
    else {
      document.getElementById('list').style.visibility = 'visible';
      wordList=0;
    }
  }
  else if(code==115 || code==83) {
    var random = Math.ceil(Math.random()*10)+20;
    var sg_version = '01';
    var cL = '';
    var temp = '';
    var tenc = '';

    var save = '1072235411|GINCANA AMBIENTAL,12,12,6.0,green,black,white,0,forward,nodiag,upanddown,,,,,,TRUE,right,,,,square' + '|' + revealed + '|' + minutes + ',' + seconds + ',' + mistakes + '|' + words + '|SEMANA,MEIO,AMBIENTE,SEPARAR,UTILIZAR,EVITAR,USAR,PLANTAR,EDUCAR,SCHADEK,SER,ÁRVORE,DICAS,VALIOSAS,' + '|' + '0' + '|' + cL;

    for(a=0;a<save.length;a++) {
      temp = save.charCodeAt(a);
      seed = ((100000*(random*a))+49297) % 233280;
      seed = Math.ceil((seed/233280.0)*20)+20;
      temp = (temp+seed);
      temp = String.fromCharCode(temp);
      tenc += temp;
    }
    save = escape(tenc);

    var saved = '';
    var count = 0;
    var co = 0;
    var t = (Math.ceil(save.length/1000));
    for(h=1;h<=t;h++) {
      var temp = '';
      count = (1000*h);
      temp = save.slice(co,count);
      co = (co+1000);
      saved += temp + '\n';
    }
    saved = saved.substr(0,saved.length-1);
    saved = random+saved+hex_sha1(saved);
    saved = sg_version+saved;

    paused=1;
    savedGame=1;

    document.getElementById('grid').style.visibility = 'hidden';
    document.getElementById('list').style.visibility = 'hidden';

    document.getElementById('code').style.display = 'block';
    document.getElementById('area').value = saved;
    document.getElementById('area').focus();
    document.getElementById('area').select();
  var echoThis = '<br /><br /><a href="/cacapalavras/fs.wordfinder.php?savedgame='+saved+'">URL do caça-palavras salvo</a>';

  createdMain = document.createElement("DIV");
  createdMain.id = "savegameURL";
  createdMain.innerHTML = echoThis;
  document.getElementById('code').appendChild(createdMain);

  }
}

function hideSave() {
  document.getElementById('code').style.display = 'none';
  document.getElementById('grid').style.visibility = 'visible';
  document.getElementById('list').style.visibility = 'visible';

  document.getElementById('code').removeChild(createdMain);


  paused=0;
  savedGame = 1;
}

function display(){
  if(!paused) seconds++;
  if(seconds==60) { minutes++;seconds=0; }
  remaining(0);
  setTimeout("display()",1000);
}

function lineme(id,revealed) {
  document.getElementById(id).style.textDecoration = 'line-through';
  wordcount++;
  if(revealed) rev++;
  if(wordcount==14) {
    if(minutes!=0) {
      if(minutes==1) var minPlural = "minuto"; else var minPlural = "minutos";
      if(seconds==1) var secPlural = "segundo"; else var secPlural = "segundos";
      time = minutes+' '+minPlural+' '+seconds+' '+secPlural;
    } else { time = seconds+' segundos'; }
    paused=2;
    document.getElementById('list').style.visibility = 'visible';
    alert('Caça-Palavras terminado\n==========\nTempo usado:\t\t'+time+'\nErros:\t\t'+mistakes+'\nPalavras reveladas:\t'+rev);
  }
}

function r(cross,revealLetters) {
  tenc = '';
  temp = '';
  revealLetters = unescape(revealLetters);
  for(a=0;a<revealLetters.length;a++) {
    temp = revealLetters.charCodeAt(a);
    temp = (temp-20);
    temp = String.fromCharCode(temp);
    tenc += temp;
  }
  revealLetters = tenc.split(",");
  var id = '';

  if(!mouseIsDown) {
    for(var i=0;i<revealLetters.length;i++) {
      id = revealLetters[i];
      document.getElementById('_'+id).style.color = 'red';
    }
    revealed+=cross+'r,';
    hideQM(cross+'r');
    lineme(cross,1);

  } else {
    for(var i=0;i<revealLetters.length;i++) {
      id = revealLetters[i];
      document.getElementById('_'+id).style.backgroundColor = 'white';
    }
    hideQM(cross+'r');
    lineme(cross,0);
  }
  savedGame=0;
}

function remaining(m) {
  if(m) mistakes++;
  if(paused==1) { var p=' (PAUSA)'; }
  else if(paused==2) { var p=' (Caça-Palavras terminado)'; savedGame=1; }
  else { var p=''; }
  if(minutes>0) {
    if(minutes==1) var minPlural = "minuto"; else var minPlural = "minutos";
    if(seconds==1) var secPlural = "segundo"; else var secPlural = "segundos";
    time = minutes+' '+minPlural+' '+seconds+' '+secPlural;
  } else { time = seconds+' segundos'; }
  if(mistakes==1) var misP = "Erro"; else var misP = "Erros";
  if(rev==1) var revP = "palavra"; else var revP = "palavras";
  window.status=14-wordcount+' de 14 Palavras restantes ('+mistakes+' '+misP+' - '+rev+' '+revP+' Revelado) - Time: '+time+p;
}

function hideQM(id) {
  document.getElementById(id).style.visibility = 'hidden';
}

function showQM(id) {
  document.getElementById(id).style.visibility = 'visible';
}

function hideWords() {
  document.getElementById('list').style.visibility='hidden';
}

function unload(e) {
  if(!savedGame) {
    msg = 'As alterações não salvas serão perdidas.';
    if (!e && window.event) {
      e = window.event;
    }
    e.returnValue = msg;
    return msg;
  }
}

window.onbeforeunload = function(e) {
  if (!e) e = event;
  return unload(e);
}



/*
 * A JavaScript implementation of the Secure Hash Algorithm, SHA-1, as defined
 * in FIPS PUB 180-1
 * Version 2.1 Copyright Paul Johnston 2000 - 2002.
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for details.
 */

var hexcase = 0;
var b64pad  = "";
var chrsz   = 8;

function hex_sha1(s){return binb2hex(core_sha1(str2binb(s),s.length * chrsz));}
function b64_sha1(s){return binb2b64(core_sha1(str2binb(s),s.length * chrsz));}
function str_sha1(s){return binb2str(core_sha1(str2binb(s),s.length * chrsz));}
function hex_hmac_sha1(key, data){ return binb2hex(core_hmac_sha1(key, data));}
function b64_hmac_sha1(key, data){ return binb2b64(core_hmac_sha1(key, data));}
function str_hmac_sha1(key, data){ return binb2str(core_hmac_sha1(key, data));}

function sha1_vm_test()
{
  return hex_sha1("abc") == "a9993e364706816aba3e25717850c26c9cd0d89d";
}

function core_sha1(x, len)
{
  x[len >> 5] |= 0x80 << (24 - len % 32);
  x[((len + 64 >> 9) << 4) + 15] = len;

  var w = Array(80);
  var a =  1732584193;
  var b = -271733879;
  var c = -1732584194;
  var d =  271733878;
  var e = -1009589776;

  for(var i = 0; i < x.length; i += 16)
  {
    var olda = a;
    var oldb = b;
    var oldc = c;
    var oldd = d;
    var olde = e;

    for(var j = 0; j < 80; j++)
    {
      if(j < 16) w[j] = x[i + j];
      else w[j] = rol(w[j-3] ^ w[j-8] ^ w[j-14] ^ w[j-16], 1);
      var t = safe_add(safe_add(rol(a, 5), sha1_ft(j, b, c, d)), 
                       safe_add(safe_add(e, w[j]), sha1_kt(j)));
      e = d;
      d = c;
      c = rol(b, 30);
      b = a;
      a = t;
    }

    a = safe_add(a, olda);
    b = safe_add(b, oldb);
    c = safe_add(c, oldc);
    d = safe_add(d, oldd);
    e = safe_add(e, olde);
  }
  return Array(a, b, c, d, e);
  
}

function sha1_ft(t, b, c, d)
{
  if(t < 20) return (b & c) | ((~b) & d);
  if(t < 40) return b ^ c ^ d;
  if(t < 60) return (b & c) | (b & d) | (c & d);
  return b ^ c ^ d;
}

function sha1_kt(t)
{
  return (t < 20) ?  1518500249 : (t < 40) ?  1859775393 :
         (t < 60) ? -1894007588 : -899497514;
}  

function core_hmac_sha1(key, data)
{
  var bkey = str2binb(key);
  if(bkey.length > 16) bkey = core_sha1(bkey, key.length * chrsz);

  var ipad = Array(16), opad = Array(16);
  for(var i = 0; i < 16; i++) 
  {
    ipad[i] = bkey[i] ^ 0x36363636;
    opad[i] = bkey[i] ^ 0x5C5C5C5C;
  }

  var hash = core_sha1(ipad.concat(str2binb(data)), 512 + data.length * chrsz);
  return core_sha1(opad.concat(hash), 512 + 160);
}

function safe_add(x, y)
{
  var lsw = (x & 0xFFFF) + (y & 0xFFFF);
  var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
  return (msw << 16) | (lsw & 0xFFFF);
}

function rol(num, cnt)
{
  return (num << cnt) | (num >>> (32 - cnt));
}

function str2binb(str)
{
  var bin = Array();
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < str.length * chrsz; i += chrsz)
    bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (24 - i%32);
  return bin;
}

function binb2str(bin)
{
  var str = "";
  var mask = (1 << chrsz) - 1;
  for(var i = 0; i < bin.length * 32; i += chrsz)
    str += String.fromCharCode((bin[i>>5] >>> (24 - i%32)) & mask);
  return str;
}

function binb2hex(binarray)
{
  var hex_tab = hexcase ? "0123456789ABCDEF" : "0123456789abcdef";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i++)
  {
    str += hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8+4)) & 0xF) +
           hex_tab.charAt((binarray[i>>2] >> ((3 - i%4)*8  )) & 0xF);
  }
  return str;
}

function binb2b64(binarray)
{
  var tab = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
  var str = "";
  for(var i = 0; i < binarray.length * 4; i += 3)
  {
    var triplet = (((binarray[i   >> 2] >> 8 * (3 -  i   %4)) & 0xFF) << 16)
                | (((binarray[i+1 >> 2] >> 8 * (3 - (i+1)%4)) & 0xFF) << 8 )
                |  ((binarray[i+2 >> 2] >> 8 * (3 - (i+2)%4)) & 0xFF);
    for(var j = 0; j < 4; j++)
    {
      if(i * 8 + j * 6 > binarray.length * 32) str += b64pad;
      else str += tab.charAt((triplet >> 6*(3-j)) & 0x3F);
    }
  }
  return str;
}
// End of SHA-1

var highlighted = new Array();
var startingCell = 0;
var endingCell = 0;
var startX = 0;
var startY = 0;
function cellHighlight(cell) {

  if(paused==2) return false;

  var good = true;
  var x = Math.ceil(cell/12);
  var y = (12 - ((x*12)-cell));

  endingCell = cell;
  if(startingCell==0) {
    startingCell = cell;
    startX = Math.ceil(cell/12);
    startY = (12 - ((x*12)-cell));
  }

  var relX = x - startX;
  var relY = y - startY;

  if(startingCell==endingCell) {
    document.getElementById('_'+cell).style.backgroundColor = 'white';
    highlighted[highlighted.length]=cell;
  } else if(relY==0 && relX<0) {
    cleanUp();
    for(var a=startingCell;;a=(a-12)) {
      highlightMe(a);
      if(a==endingCell) break;
    }
  } else if(relY==0 && relX>0) {
    cleanUp();
    for(var a=startingCell;;a=(a+12)) {
      highlightMe(a);
      if(a==endingCell) break;
    }
  } else if(relX==0 && relY<0) {
    cleanUp();
    for(var a=startingCell;;a=(a-1)) {
      highlightMe(a);
      if(a==endingCell) break;
    }
  } else if(relX==0 && relY>0) {
    cleanUp();
    for(var a=startingCell;;a=(a+1)) {
      highlightMe(a);
      if(a==endingCell) break;
    }
  } else if(Math.abs(relX)==Math.abs(relY)) {
    cleanUp();
    if(relX<0 && relY<0) {
      for(var a=startingCell;;a=(a-13)) {
        highlightMe(a);
        if(a==endingCell) break;
      }
    } else if(relX<0 && relY>0) {
      for(var a=startingCell;;a=(a-11)) {
        highlightMe(a);
        if(a==endingCell) break;
      }
    } else if(relX>0 && relY<0) {
      for(var a=startingCell;;a=(a+11)) {
        highlightMe(a);
        if(a==endingCell) break;
      }
    } else if(relX>0 && relY>0) {
      for(var a=startingCell;;a=(a+13)) {
        highlightMe(a);
        if(a==endingCell) break;
      }
    }
  } else { good = false; }

}

function clearHighlighted(xy,word) {
  var good = false;
  var temp = 0;
  if(xy!=0) {
    for(var x=xy.length-1;x>=0;x--) {
      var xyTemp = xy[x].split("|");
      var xyt = '';
      if(highlighted.length==xyTemp.length) {
        for(var a=xyTemp.length-1;a>=0;a--) {
          for(var b=xyTemp.length-1;b>=0;b--) {
            var highTemp = highlighted[b].toString();
            if(highTemp.charAt(0)=='x') xyt='x'+xyTemp[a];
            else xyt = xyTemp[a];
            if(xyt==highlighted[b]) { temp++; break; }
          }
        }
        if(temp==xyTemp.length) {
          tenc = '';
          temp = '';
          for(var a=0;a<word.length;a++) {
            word[a] = unescape(word[a]);
            for(b=0;b<word[a].length;b++) {
              temp = word[a].charCodeAt(b);
              temp = (temp-20);
              temp = String.fromCharCode(temp);
              tenc += temp;
            }
            word[a] = tenc;
            tenc = '';
          }
          if(document.getElementById(word[x]).style.textDecoration!='line-through') {
            words[words.length] = word[x];
            hideQM(word[x]+'r');
            lineme(word[x],0);
            good = true;
          } else if(document.getElementById('_'+highlighted[0]).style.color=='red') {
            good = false;
          } else { good = true; }
        }
      }
    }
  }

  if(!good) {
    if(highlighted.length>0) mistakes++;
    for(var a=highlighted.length-1;a>=0;a--) {
      var highTemp = highlighted[a].toString();
      if(highTemp.charAt(0)!='x') {
        document.getElementById('_'+highlighted[a]).style.backgroundColor = 'green';
      }
    }
  }
  highlighted = new Array();
  startingCell = 0;
  savedGame = 0;
}

function cleanUp() {
  for(var a=highlighted.length-1;a>=0;a--) {
    var highTemp = highlighted[a].toString();
    if(highTemp.charAt(0)!='x') {
      document.getElementById('_'+highlighted[a]).style.backgroundColor = 'green';
    }
  }
  highlighted = new Array();
}

function highlightMe(a) {
  if(document.getElementById('_'+a).style.backgroundColor != 'white') {
    document.getElementById('_'+a).style.backgroundColor = 'white';
    highlighted[highlighted.length]=a;
  } else {
    highlighted[highlighted.length]='x'+a;
  }
}

//--></script>
<br><br><br><br>
</head><body style="cursor:crosshair;" onload="eventHandle();display();"><div style="text-align:center;font-weight:bold;font-size:35px;text-decoration:underline;font-family:Arial;margin-bottom:0px;">GINCANA AMBIENTAL</div>

<script type="text/javascript">
<!--
document.write(unescape("%3Ctable%20style%3D%22height%3A100%25%3Bmargin-left%3Aauto%3Bmargin-right%3Aauto%3Btext-align%3A%20left%3B%22%3E%3Ctr%3E%3Ctd%3E%3Ctable%20style%3D%22border%3A0px%3B%22%3E%3Ctr%3E%3Ctd%20valign%3D%22top%22%3E%3Cdiv%20id%3D%22grid%22%3E%3Ctable%20border%3D%220%22%20cellspacing%3D%220%22%20style%3D%22border-right%3A1px%20solid%20black%3B%22%3E%3Ctr%3E%3Ctd%20id%3D%22_1%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%281%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%281%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27fea0%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C9%3C%2Ftd%3E%3Ctd%20id%3D%22_2%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%282%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%282%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27adc9606%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EQ%3C%2Ftd%3E%3Ctd%20id%3D%22_3%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%283%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%283%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%253b479%257ba79c%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EM%3C%2Ftd%3E%3Ctd%20id%3D%22_4%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%284%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%284%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%270b44c5a7%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ED%3C%2Ftd%3E%3Ctd%20id%3D%22_5%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20cellHighlight%285%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%285%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EP%3C%2Ftd%3E%3Ctd%20id%3D%22_6%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%286%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%286%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_7%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%287%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%287%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_8%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%288%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%288%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EP%3C%2Ftd%3E%3Ctd%20id%3D%22_9%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%289%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%289%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_10%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%2810%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2810%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_11%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%2C%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%2811%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%2C%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2811%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%2C%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%2C%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_12%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20cellHighlight%2812%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2812%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYdUfUf%27%29%3Bx%3Dnew%20Array%28%276%7C7%7C8%7C9%7C10%7C11%7C12%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_13%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2813%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2813%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25bb992fd7%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EH%3C%2Ftd%3E%3Ctd%20id%3D%22_14%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2814%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2814%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27de492113f7%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_15%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2815%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2815%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%2747efadcc32d%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_16%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2816%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2816%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%258ac7217%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%CA%3C%2Ftd%3E%3Ctd%20id%3D%22_17%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20cellHighlight%2817%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2817%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EL%3C%2Ftd%3E%3Ctd%20id%3D%22_18%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2818%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2818%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2547b27acf09%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D5%3C%2Ftd%3E%3Ctd%20id%3D%22_19%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20cellHighlight%2819%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2819%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27igUf%27%29%3Bx%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EU%3C%2Ftd%3E%3Ctd%20id%3D%22_20%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2820%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2820%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%250772e3b21e817f%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_21%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2821%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2821%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25b3d1%25d2%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D3%3C%2Ftd%3E%3Ctd%20id%3D%22_22%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2822%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2822%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%251f3f%250b%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EL%3C%2Ftd%3E%3Ctd%20id%3D%22_23%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2823%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2823%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EM%3C%2Ftd%3E%3Ctd%20id%3D%22_24%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2824%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2824%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EV%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_25%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2825%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2825%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27fbdbf%256d%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_26%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2826%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2826%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%258763f%25ae425be%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EO%3C%2Ftd%3E%3Ctd%20id%3D%22_27%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2827%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2827%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27a6679%25eb2%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C7%3C%2Ftd%3E%3Ctd%20id%3D%22_28%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2828%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2828%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%274c7eca85e%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D4%3C%2Ftd%3E%3Ctd%20id%3D%22_29%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20cellHighlight%2829%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2829%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_30%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2830%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2830%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25da%2553fccf0%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_31%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20cellHighlight%2831%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2831%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27igUf%27%29%3Bx%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_32%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20cellHighlight%2832%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2832%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYaUbU%27%29%3Bx%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_33%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2833%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2833%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27b93e%252166%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EU%3C%2Ftd%3E%3Ctd%20id%3D%22_34%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20cellHighlight%2834%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2834%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27aY%255Dc%27%29%3Bx%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EM%3C%2Ftd%3E%3Ctd%20id%3D%22_35%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2835%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2835%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EB%3C%2Ftd%3E%3Ctd%20id%3D%22_36%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2836%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2836%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_37%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2837%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2837%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2527adad%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_38%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2838%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2838%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2591bc2%25ff%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_39%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2839%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2839%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2502948e%25342b2b%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EG%3C%2Ftd%3E%3Ctd%20id%3D%22_40%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3B%20cellHighlight%2840%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2840%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27X%255DWUg%27%29%3Bx%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ED%3C%2Ftd%3E%3Ctd%20id%3D%22_41%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20cellHighlight%2841%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2841%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EN%3C%2Ftd%3E%3Ctd%20id%3D%22_42%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2842%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2842%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%272a%2550e024%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_43%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20cellHighlight%2843%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2843%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27igUf%27%29%3Bx%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_44%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20cellHighlight%2844%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2844%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYaUbU%27%29%3Bx%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_45%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2845%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2845%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25f49%251c0%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%DC%3C%2Ftd%3E%3Ctd%20id%3D%22_46%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20cellHighlight%2846%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2846%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27aY%255Dc%27%29%3Bx%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_47%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2847%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2847%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_48%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2848%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2848%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EL%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_49%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2849%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2849%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25a7d5f48dcdc%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%DC%3C%2Ftd%3E%3Ctd%20id%3D%22_50%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2850%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2850%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25341e%2537e4%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EP%3C%2Ftd%3E%3Ctd%20id%3D%22_51%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2851%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2851%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27bd309%25353585%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EB%3C%2Ftd%3E%3Ctd%20id%3D%22_52%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3B%20cellHighlight%2852%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2852%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27X%255DWUg%27%29%3Bx%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_53%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20cellHighlight%2853%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2853%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_54%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2854%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2854%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%254cea202264ea%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EK%3C%2Ftd%3E%3Ctd%20id%3D%22_55%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20cellHighlight%2855%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2855%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27igUf%27%29%3Bx%3Dnew%20Array%28%2719%7C31%7C43%7C55%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_56%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20cellHighlight%2856%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2856%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYaUbU%27%29%3Bx%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EM%3C%2Ftd%3E%3Ctd%20id%3D%22_57%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%2857%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2857%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_58%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20cellHighlight%2858%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2858%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27aY%255Dc%27%29%3Bx%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_59%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2859%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2859%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_60%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2860%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2860%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_61%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20cellHighlight%2861%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2861%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27YXiWUf%27%29%3Bx%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_62%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20cellHighlight%2862%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2862%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27YXiWUf%27%29%3Bx%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ED%3C%2Ftd%3E%3Ctd%20id%3D%22_63%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20cellHighlight%2863%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2863%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27YXiWUf%27%29%3Bx%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EU%3C%2Ftd%3E%3Ctd%20id%3D%22_64%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%2C%2740%7C52%7C64%7C76%7C88%27%29%3B%20cellHighlight%2864%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%2C%2740%7C52%7C64%7C76%7C88%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2864%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27YXiWUf%27%2C%27X%255DWUg%27%29%3Bx%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%2C%2740%7C52%7C64%7C76%7C88%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EC%3C%2Ftd%3E%3Ctd%20id%3D%22_65%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%2C%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20cellHighlight%2865%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%2C%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2865%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%2C%27YXiWUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%2C%2761%7C62%7C63%7C64%7C65%7C66%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_66%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20cellHighlight%2866%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2866%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27YXiWUf%27%29%3Bx%3Dnew%20Array%28%2761%7C62%7C63%7C64%7C65%7C66%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_67%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2867%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2867%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%256c5%25296d778%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D5%3C%2Ftd%3E%3Ctd%20id%3D%22_68%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20cellHighlight%2868%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2868%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYaUbU%27%29%3Bx%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_69%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%2869%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2869%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EC%3C%2Ftd%3E%3Ctd%20id%3D%22_70%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20cellHighlight%2870%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2870%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27aY%255Dc%27%29%3Bx%3Dnew%20Array%28%2734%7C46%7C58%7C70%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EO%3C%2Ftd%3E%3Ctd%20id%3D%22_71%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2871%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2871%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EN%3C%2Ftd%3E%3Ctd%20id%3D%22_72%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2872%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2872%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EO%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_73%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2873%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2873%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%278b22%258736f%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EW%3C%2Ftd%3E%3Ctd%20id%3D%22_74%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2874%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2874%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25848bc%25015b68%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EO%3C%2Ftd%3E%3Ctd%20id%3D%22_75%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2875%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2875%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2562c5%25ffd8d0%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EJ%3C%2Ftd%3E%3Ctd%20id%3D%22_76%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3B%20cellHighlight%2876%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2876%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27X%255DWUg%27%29%3Bx%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_77%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20cellHighlight%2877%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2877%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27d%2560UbhUf%27%29%3Bx%3Dnew%20Array%28%275%7C17%7C29%7C41%7C53%7C65%7C77%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_78%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2878%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2878%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2596636%25bc8bd76%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EY%3C%2Ftd%3E%3Ctd%20id%3D%22_79%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2879%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2879%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%256f%2587f7%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%CA%3C%2Ftd%3E%3Ctd%20id%3D%22_80%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20cellHighlight%2880%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2880%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYaUbU%27%29%3Bx%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EN%3C%2Ftd%3E%3Ctd%20id%3D%22_81%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%2881%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2881%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EH%3C%2Ftd%3E%3Ctd%20id%3D%22_82%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2882%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2882%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25c8802c0%25b8%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_83%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2883%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2883%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_84%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2884%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2884%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_85%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2885%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2885%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%2713b59353ac%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EL%3C%2Ftd%3E%3Ctd%20id%3D%22_86%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2886%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2886%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27aa5bf70a6%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EL%3C%2Ftd%3E%3Ctd%20id%3D%22_87%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2887%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2887%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2517fe80%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C0%3C%2Ftd%3E%3Ctd%20id%3D%22_88%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%2C%2788%7C89%7C90%27%29%3B%20cellHighlight%2888%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%2C%2788%7C89%7C90%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2888%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27X%255DWUg%27%2C%27gYf%27%29%3Bx%3Dnew%20Array%28%2740%7C52%7C64%7C76%7C88%27%2C%2788%7C89%7C90%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_89%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2788%7C89%7C90%27%29%3B%20cellHighlight%2889%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2788%7C89%7C90%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2889%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYf%27%29%3Bx%3Dnew%20Array%28%2788%7C89%7C90%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_90%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2788%7C89%7C90%27%29%3B%20cellHighlight%2890%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2788%7C89%7C90%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2890%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYf%27%29%3Bx%3Dnew%20Array%28%2788%7C89%7C90%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_91%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2891%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2891%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27f5e%251c78cd%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_92%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20cellHighlight%2892%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2892%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gYaUbU%27%29%3Bx%3Dnew%20Array%28%2732%7C44%7C56%7C68%7C80%7C92%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_93%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%2893%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2893%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_94%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%2894%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%2894%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25711a%259e0636%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%DC%3C%2Ftd%3E%3Ctd%20id%3D%22_95%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20cellHighlight%2895%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2895%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27UaV%255DYbhY%27%29%3Bx%3Dnew%20Array%28%2711%7C23%7C35%7C47%7C59%7C71%7C83%7C95%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_96%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%2896%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2896%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_97%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20cellHighlight%2897%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2897%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25D5fjcfY%27%29%3Bx%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3E%C1%3C%2Ftd%3E%3Ctd%20id%3D%22_98%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20cellHighlight%2898%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2898%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25D5fjcfY%27%29%3Bx%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_99%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20cellHighlight%2899%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%2899%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25D5fjcfY%27%29%3Bx%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EV%3C%2Ftd%3E%3Ctd%20id%3D%22_100%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20cellHighlight%28100%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28100%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25D5fjcfY%27%29%3Bx%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EO%3C%2Ftd%3E%3Ctd%20id%3D%22_101%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20cellHighlight%28101%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28101%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25D5fjcfY%27%29%3Bx%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_102%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20cellHighlight%28102%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28102%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25D5fjcfY%27%29%3Bx%3Dnew%20Array%28%2797%7C98%7C99%7C100%7C101%7C102%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_103%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28103%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28103%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25a6dd2c4078%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_104%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28104%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28104%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25813d%25c0f80ba%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EY%3C%2Ftd%3E%3Ctd%20id%3D%22_105%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%28105%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28105%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ED%3C%2Ftd%3E%3Ctd%20id%3D%22_106%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28106%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28106%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%279f7a833%254795315%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D3%3C%2Ftd%3E%3Ctd%20id%3D%22_107%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28107%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28107%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%2778011%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%DC%3C%2Ftd%3E%3Ctd%20id%3D%22_108%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20cellHighlight%28108%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28108%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27jU%2560%255DcgUg%27%29%3Bx%3Dnew%20Array%28%2724%7C36%7C48%7C60%7C72%7C84%7C96%7C108%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_109%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28109%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28109%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2536%2589f3d3%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%CD%3C%2Ftd%3E%3Ctd%20id%3D%22_110%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28110%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28110%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ccea73b70e%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_111%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20cellHighlight%28111%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28111%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27Yj%255DhUf%27%29%3Bx%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_112%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20cellHighlight%28112%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28112%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27Yj%255DhUf%27%29%3Bx%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EV%3C%2Ftd%3E%3Ctd%20id%3D%22_113%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20cellHighlight%28113%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28113%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27Yj%255DhUf%27%29%3Bx%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_114%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20cellHighlight%28114%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28114%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27Yj%255DhUf%27%29%3Bx%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_115%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20cellHighlight%28115%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28115%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27Yj%255DhUf%27%29%3Bx%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_116%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20cellHighlight%28116%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28116%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27Yj%255DhUf%27%29%3Bx%3Dnew%20Array%28%27111%7C112%7C113%7C114%7C115%7C116%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%20id%3D%22_117%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%28117%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28117%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EE%3C%2Ftd%3E%3Ctd%20id%3D%22_118%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28118%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28118%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%273b80406c0%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%DC%3C%2Ftd%3E%3Ctd%20id%3D%22_119%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28119%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28119%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25bdaeb%25f8f4137%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ES%3C%2Ftd%3E%3Ctd%20id%3D%22_120%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28120%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28120%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25f412f83f98830%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%DA%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_121%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28121%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28121%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%2532db2b6%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C9%3C%2Ftd%3E%3Ctd%20id%3D%22_122%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28122%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28122%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%2785829%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EU%3C%2Ftd%3E%3Ctd%20id%3D%22_123%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28123%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28123%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%272298fa9%259f1769%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C3%3C%2Ftd%3E%3Ctd%20id%3D%22_124%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28124%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28124%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%256ae7de764%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3ED%3C%2Ftd%3E%3Ctd%20id%3D%22_125%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28125%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28125%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25c7c7ba7eca59%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%CD%3C%2Ftd%3E%3Ctd%20id%3D%22_126%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28126%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28126%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%274a54496da7434%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EZ%3C%2Ftd%3E%3Ctd%20id%3D%22_127%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28127%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28127%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27e0162eb288ba%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D5%3C%2Ftd%3E%3Ctd%20id%3D%22_128%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28128%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28128%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27a2f490e%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EN%3C%2Ftd%3E%3Ctd%20id%3D%22_129%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20cellHighlight%28129%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28129%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27gW%255CUXY_%27%29%3Bx%3Dnew%20Array%28%2757%7C69%7C81%7C93%7C105%7C117%7C129%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EK%3C%2Ftd%3E%3Ctd%20id%3D%22_130%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28130%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28130%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27b0ea712e04%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C2%3C%2Ftd%3E%3Ctd%20id%3D%22_131%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28131%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28131%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%2713247%2520a13d%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EX%3C%2Ftd%3E%3Ctd%20id%3D%22_132%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28132%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28132%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25923d9dbf1%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%D5%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3Ctr%3E%3Ctd%20id%3D%22_133%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28133%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28133%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%2798d9ab2dce%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3E%C9%3C%2Ftd%3E%3Ctd%20id%3D%22_134%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28134%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28134%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%271e11e9%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EJ%3C%2Ftd%3E%3Ctd%20id%3D%22_135%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28135%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28135%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%259a576%255b48%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EV%3C%2Ftd%3E%3Ctd%20id%3D%22_136%22%20onmouseover%3D%22if%28mouseIsDown%29%20cellHighlight%28136%29%3B%22%20onmousedown%3D%22mouseIsDown%3D1%3BcellHighlight%28136%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27%25c1b61b1a93%27%29%3Bx%3Dnew%20Array%28%2721%7C31%7C41%7C51%7C61%27%29%3Bw%3Dx%3D0%3BclearHighlighted%28x%2C0%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_137%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28137%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28137%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EU%3C%2Ftd%3E%3Ctd%20id%3D%22_138%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28138%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28138%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ET%3C%2Ftd%3E%3Ctd%20id%3D%22_139%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28139%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28139%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_140%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28140%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28140%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EL%3C%2Ftd%3E%3Ctd%20id%3D%22_141%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28141%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28141%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EI%3C%2Ftd%3E%3Ctd%20id%3D%22_142%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28142%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28142%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EZ%3C%2Ftd%3E%3Ctd%20id%3D%22_143%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28143%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28143%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3EA%3C%2Ftd%3E%3Ctd%20id%3D%22_144%22%20onmouseover%3D%22if%28mouseIsDown%29%20%7B%20x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20cellHighlight%28144%2Cx%29%3B%20%7D%22%20onmousedown%3D%22x%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3B%20mouseIsDown%3D1%3BcellHighlight%28144%2Cx%29%3B%22%20onmouseup%3D%22w%3Dnew%20Array%28%27ih%255D%2560%255DnUf%27%29%3Bx%3Dnew%20Array%28%27137%7C138%7C139%7C140%7C141%7C142%7C143%7C144%27%29%3BclearHighlighted%28x%2Cw%29%3B%22%20%3ER%3C%2Ftd%3E%3Ctd%3E%26nbsp%3B%3C%2Ftd%3E%3C%2Ftr%3E%3C%2Ftable%3E%3C%2Fdiv%3E%3C%2Ftd%3E%3Ctd%20valign%3D%22top%22%3E%3Ctable%20border%3D%220%22%20cellspacing%3D%220%22%20cellpadding%3D%220%22%20id%3D%22list%22%3E%3Ctr%3E%3Ctd%3E%3Cdiv%20style%3D%22margin-left%3A%2015px%3B%20font-size%3A%206.0mm%3B%20font-family%3A%20Courier%20New%3B%22%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22SEMANAr%22%20onmousedown%3D%22v%3D%5C%27GF%2540HH%2540IJ%2540JL%2540LD%2540MF%5C%27%3Br%28%5C%27SEMANA%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22SEMANA%22%3ESEMANA%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22MEIOr%22%20onmousedown%3D%22v%3D%5C%27GH%2540HJ%2540IL%2540KD%5C%27%3Br%28%5C%27MEIO%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22MEIO%22%3EMEIO%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22AMBIENTEr%22%20onmousedown%3D%22v%3D%5C%27EE%2540FG%2540GI%2540HK%2540IM%2540KE%2540LG%2540MI%5C%27%3Br%28%5C%27AMBIENTE%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22AMBIENTE%22%3EAMBIENTE%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22SEPARARr%22%20onmousedown%3D%22v%3D%5C%27J%2540K%2540L%2540M%2540ED%2540EE%2540EF%5C%27%3Br%28%5C%27SEPARAR%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22SEPARAR%22%3ESEPARAR%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22UTILIZARr%22%20onmousedown%3D%22v%3D%5C%27EGK%2540EGL%2540EGM%2540EHD%2540EHE%2540EHF%2540EHG%2540EHH%5C%27%3Br%28%5C%27UTILIZAR%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22UTILIZAR%22%3EUTILIZAR%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22EVITARr%22%20onmousedown%3D%22v%3D%5C%27EEE%2540EEF%2540EEG%2540EEH%2540EEI%2540EEJ%5C%27%3Br%28%5C%27EVITAR%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22EVITAR%22%3EEVITAR%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22USARr%22%20onmousedown%3D%22v%3D%5C%27EM%2540GE%2540HG%2540II%5C%27%3Br%28%5C%27USAR%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22USAR%22%3EUSAR%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22PLANTARr%22%20onmousedown%3D%22v%3D%5C%27I%2540EK%2540FM%2540HE%2540IG%2540JI%2540KK%5C%27%3Br%28%5C%27PLANTAR%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22PLANTAR%22%3EPLANTAR%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22EDUCARr%22%20onmousedown%3D%22v%3D%5C%27JE%2540JF%2540JG%2540JH%2540JI%2540JJ%5C%27%3Br%28%5C%27EDUCAR%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22EDUCAR%22%3EEDUCAR%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22SCHADEKr%22%20onmousedown%3D%22v%3D%5C%27IK%2540JM%2540LE%2540MG%2540EDI%2540EEK%2540EFM%5C%27%3Br%28%5C%27SCHADEK%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22SCHADEK%22%3ESCHADEK%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22SERr%22%20onmousedown%3D%22v%3D%5C%27LL%2540LM%2540MD%5C%27%3Br%28%5C%27SER%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22SER%22%3ESER%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22%C1RVOREr%22%20onmousedown%3D%22v%3D%5C%27MK%2540ML%2540MM%2540EDD%2540EDE%2540EDF%5C%27%3Br%28%5C%27%C1RVORE%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22%C1RVORE%22%3E%C1RVORE%3C%2Fspan%3E%3Cbr%20%2F%3E%3C%2Fdiv%3E%3C%2Ftd%3E%3Ctd%20valign%3D%22top%22%3E%3Cdiv%20style%3D%22margin-left%3A%2015px%3B%20font-size%3A%206.0mm%3B%20font-family%3A%20Courier%20New%22%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22DICASr%22%20onmousedown%3D%22v%3D%5C%27HD%2540IF%2540JH%2540KJ%2540LL%5C%27%3Br%28%5C%27DICAS%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22DICAS%22%3EDICAS%3C%2Fspan%3E%3Cbr%20%2F%3E%3Cscript%20type%3D%22text%2Fjavascript%22%3Edocument.write%28unescape%28%27%253C%27%29%2B%27span%20id%3D%22VALIOSASr%22%20onmousedown%3D%22v%3D%5C%27FH%2540GJ%2540HL%2540JD%2540KF%2540LH%2540MJ%2540EDL%5C%27%3Br%28%5C%27VALIOSAS%5C%27%2Cv%29%3B%22%3E%28%3F%29%27%2Bunescape%28%27%253C%27%29%2B%27%2Fspan%3E%27%29%3C%2Fscript%3E%3Cspan%20id%3D%22VALIOSAS%22%3EVALIOSAS%3C%2Fspan%3E%3Cbr%20%2F%3E%3C%2Fdiv%3E%3C%2Ftd%3E%3C%2Ftr%3E%3C%2Ftable%3E%3C%2Ftd%3E%3C%2Ftr%3E%3C%2Ftable%3E"));
//-->
</script>
<br><br><br>

<div >
<img src="logo-schadek.png" alt="SCHADEK AUTOMOTIVE LTDA" class="logo"> 
</div>

<style type="text/css">
.logo{

  position:absolute;
  right: 200px;
  width:100px;
}
</style>

</script>
<div id="code" style="display:none;position:absolute;left:20px;top:20px;width:500px;">Copie o código e cole-o em algum lugar para uso futuro:<br /><br /><textarea cols="40" rows="2" id="area"></textarea> <input type="button" value="Continue" onclick="hideSave();" />
<br /><br />Salve esse código em algum lugar seguro, e quando você quiser carregar o seu jogo novamente, cole este código na caixa de texto na parte inferior da página principal.
</div><div id="paused" style="visibility:hidden;position:absolute;left:20px;top:20px;">PAUSA<br /><br />PressIONE Enter para continuar</div></body></html>