maxKeys = 140;
var IE = (document.all) ? 1 : 0;
var DOM = 0; 

if (parseInt(navigator.appVersion) >=5) {DOM=1};

function txtshow( txt2show ) {
// Detect Browser
	if (DOM) {
		var viewer = document.getElementById("txtmsg");
                viewer.innerHTML=txt2show;
            }
            else if(IE) {
                document.all["txtmsg"].innerHTML=txt2show;
            }
}

function keyup(what) {
	var str = new String(what.value);
	var len = str.length;
	var showstr = maxKeys - len;
	txtshow( showstr );
}
