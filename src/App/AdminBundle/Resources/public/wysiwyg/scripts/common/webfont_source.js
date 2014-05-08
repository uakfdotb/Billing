function LoadFont(fontFamily) {
    if (fontFamily != '')
        try { WebFont.load({ google: { families: [fontFamily]} }) } catch (e) { };
}

function EmbedFont(id) {
    var arrSysFonts = ["impact", "palatino linotype", "tahoma",
        "century gothic", "lucida sans unicode", "times new roman",
        "arial narrow", "verdana", "copperplate gothic light",
        "lucida console", "gill sans mt", "trebuchet ms", "courier new",
        "arial", "georgia", "garamond",
        "arial black", "bookman old style", "courier", "helvetica"];

    var sHTML;
    if (!id) {
        sHTML = document.documentElement.innerHTML;
    } else {
        sHTML = document.getElementById(id).innerHTML;
    }
    var urlRegex = /font-family?:.+?(\;|,|")/g;
    var matches = sHTML.match(urlRegex);
    if (matches)
        for (var i = 0, len = matches.length; i < len; i++) {
            var sFont = matches[i].replace(/font-family?:/g, '').replace(/;/g, '').replace(/,/g, '').replace(/"/g, '');
            sFont = jQuery.trim(sFont);

            sFont = sFont.replace("'", "").replace("'", "");
            if (jQuery.inArray(sFont.toLowerCase(), arrSysFonts) == -1) {
                LoadFont(sFont);
            }

            /*if (sFont != 'serif' && sFont != 'Arial' && sFont != 'Arial Black' && sFont != 'Bookman Old Style' && sFont != 'Comic Sans MS' && sFont != 'Courier' && sFont != 'Courier New' && sFont != 'Garamond' && sFont != 'Georgia' && sFont != 'Impact' &&
            sFont != 'Lucida Console' && sFont != 'Lucida Sans Unicode' && sFont != 'MS Sans Serif' && sFont != 'MS Serif' && sFont != 'Palatino Linotype' && sFont != 'Tahoma' && sFont != 'Times New Roman' && sFont != 'Trebuchet MS' && sFont != 'Verdana') {
            LoadFont(sFont);
            }*/
        }
}
/*Check Editing Area (onRender), if found inline embeded font, include google font css*/

/*
function customExec(oEditor) {

    var oEditor = eval("idContent" + oEditor.oName);
    var sHTML;
    if (navigator.appName.indexOf('Microsoft') != -1) {
      sHTML = oEditor.document.documentElement.outerHTML;
    } else {
      sHTML = getOuterHTML(oEditor.document.documentElement); 
    }

    var urlRegex = /font-family?:.+?(\;|,|")/g;
    var matches = sHTML.match(urlRegex);
    if (!matches) return;
    
    for (var i = 0, len = matches.length; i < len; i++) {
        var sFont = matches[i].replace(/font-family?:/g, '').replace(/;/g, '').replace(/,/g, '').replace(/"/g, '');
        sFont = jQuery.trim(sFont);
        if (sFont != 'serif' && sFont != 'Arial' && sFont != 'Arial Black' && sFont != 'Bookman Old Style' && sFont != 'Comic Sans MS' && sFont != 'Courier' && sFont != 'Courier New' && sFont != 'Garamond' && sFont != 'Georgia' && sFont != 'Impact' &&
          sFont != 'Lucida Console' && sFont != 'Lucida Sans Unicode' && sFont != 'MS Sans Serif' && sFont != 'MS Serif' && sFont != 'Palatino Linotype' && sFont != 'Tahoma' && sFont != 'Times New Roman' && sFont != 'Trebuchet MS' && sFont != 'Verdana') {
          sURL = "http://fonts.googleapis.com/css?family=" + sFont;
          var objL = oEditor.document.createElement("LINK");
          objL.href = sURL;
          objL.rel = "StyleSheet";
          oEditor.document.documentElement.childNodes[0].appendChild(objL);
        }

    }
}*/


function ISApplyWebFont(edtId) {

    var oEditor = eval("idContent" + edtId);
    var sHTML;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        sHTML = oEditor.document.documentElement.outerHTML;
    } else {
        sHTML = getOuterHTML(oEditor.document.documentElement); /*moz/saf*/
    }
    sHTML = sHTML.replace(/FONT-FAMILY/g, 'font-family');
    var urlRegex = /font-family?:.+?(\;|,|")/g;
    var matches = sHTML.match(urlRegex);
    if (matches) {
        for (var j = 0, len = matches.length; j < len; j++) {
            var sFont = matches[j].replace(/font-family?:/g, '').replace(/;/g, '').replace(/,/g, '').replace(/"/g, '');
            sFont = jQuery.trim(sFont);
            var sFontLower = sFont.toLowerCase();
            if (sFontLower != 'serif' && sFontLower != 'arial' && sFontLower != 'arial black' && sFontLower != 'bookman old style' && sFontLower != 'comic sans ms' && sFontLower != 'courier' && sFontLower != 'courier new' && sFontLower != 'garamond' && sFontLower != 'georgia' && sFontLower != 'impact' &&
            sFontLower != 'lucida console' && sFontLower != 'lucida sans unicode' && sFontLower != 'ms sans serif' && sFontLower != 'ms serif' && sFontLower != 'palatino linotype' && sFontLower != 'tahoma' && sFontLower != 'times new roman' && sFontLower != 'trebuchet ms' && sFontLower != 'verdana') {
                sURL = oUtil.protocol + "//fonts.googleapis.com/css?family=" + sFont;
                var objL = oEditor.document.createElement("LINK");
                objL.href = sURL;
                objL.rel = "StyleSheet";
                oEditor.document.documentElement.childNodes[0].appendChild(objL);
            }
        }
    }

};


jQuery(document).ready(function () {
    //EmbedFont('preview');
    EmbedFont();

    //Alternative: oEdit1.onRender = function () { customExec(this) };
    if (typeof oUtil != "undefined") {
        for (var i = 0; i < oUtil.arrEditor.length; i++) {
            var oEditor = eval("idContent" + oUtil.arrEditor[i]);
            var sHTML;
            if (navigator.appName.indexOf('Microsoft') != -1) {
                sHTML = oEditor.document.documentElement.outerHTML;
            } else {
                sHTML = getOuterHTML(oEditor.document.documentElement); /*moz/saf*/
            }
            sHTML = sHTML.replace(/FONT-FAMILY/g, 'font-family');
            var urlRegex = /font-family?:.+?(\;|,|")/g;
            var matches = sHTML.match(urlRegex);
            if (matches) {
                for (var j = 0, len = matches.length; j < len; j++) {
                    var sFont = matches[j].replace(/font-family?:/g, '').replace(/;/g, '').replace(/,/g, '').replace(/"/g, '');
                    sFont = jQuery.trim(sFont);
                    var sFontLower = sFont.toLowerCase();
                    if (sFontLower != 'serif' && sFontLower != 'arial' && sFontLower != 'arial black' && sFontLower != 'bookman old style' && sFontLower != 'comic sans ms' && sFontLower != 'courier' && sFontLower != 'courier new' && sFontLower != 'garamond' && sFontLower != 'georgia' && sFontLower != 'impact' &&
                    sFontLower != 'lucida console' && sFontLower != 'lucida sans unicode' && sFontLower != 'ms sans serif' && sFontLower != 'ms serif' && sFontLower != 'palatino linotype' && sFontLower != 'tahoma' && sFontLower != 'times new roman' && sFontLower != 'trebuchet ms' && sFontLower != 'verdana') {
                        sURL = oUtil.protocol + "//fonts.googleapis.com/css?family=" + sFont;
                        var objL = oEditor.document.createElement("LINK");
                        objL.href = sURL;
                        objL.rel = "StyleSheet";
                        oEditor.document.documentElement.childNodes[0].appendChild(objL);
                    }
                }
            }
        }
    }

});
