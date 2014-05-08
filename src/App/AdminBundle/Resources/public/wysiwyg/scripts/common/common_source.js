/*
Parent Usage Summary:
- parent.oUtil.oEditor
- parent.oUtil.obj
obj.setFocus()
obj.doCmd
obj.applySpanStyle(arrStyle)
obj.applyParagraph
obj.applyColor
obj.applyFontName
obj.doClean
obj.insertHTML
obj.saveForUndo
- parent.getSelectedElement(oSel)
- parent.isTextSelected(oSel)
- parent.oUtil.activeElement
TODO: Buat generic utk all browsers shg common.js independent (bisa utk semua editable iframe).
*/
var sPath = "/"; /*location of common folder*/
var isiPad = navigator.userAgent.match(/iPad/i) != null;
var obj;
if (parent.oUtil + '' != 'undefined') obj = parent.oUtil.obj;

function I_SelectedText() {
    var oEditor = parent.oUtil.oEditor;
    var oSel;
    var oEl;
    var bNoTxtSelect = false;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        var oSel = oEditor.document.selection.createRange();
        if (oSel.parentElement) return oSel.text;
    }
    else {
        oSel = oEditor.getSelection();
        var range = oSel.getRangeAt(0);
        return range.toString();
    }
    return null;
}

function GetElement(oElement, sMatchTag) {
    while (oElement != null && oElement.tagName != sMatchTag) {
        if (oElement.tagName == "BODY") return null;
        oElement = oElement.parentNode;
    }
    return oElement;
}

function I_ApplyOrderedList(sType) {
    obj.setFocus();
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        oSel = oEditor.document.selection.createRange();
        if (oSel.parentElement) oElement = oSel.parentElement();

        while (oElement != null &&
        oElement.tagName != "OL" &&
        oElement.tagName != "UL") {
            if (oElement.tagName == "BODY") {
                obj.doCmd("InsertOrderedList");
                I_ApplyOrderedList(sType);
                return;
            }
            oElement = oElement.parentElement;
        }
    }
    else {
        oSel = oEditor.getSelection();
        oElement = parent.getSelectedElement(oSel);

        while (oElement != null && oElement.nodeName != "UL" && oElement.nodeName != "OL") {
            if (oElement.nodeName == "BODY") {
                obj.doCmd("InsertOrderedList");
                I_ApplyOrderedList(sType);
                return;
            }
            oElement = oElement.parentNode;
        }
    }

    if (oElement) {
        if (oElement.tagName != "OL") {
            obj.doCmd("InsertOrderedList");
            I_ApplyOrderedList(sType);
            return;
        }
        oElement.type = sType;
        oElement.style.listStyleImage = "";
    }
}

function I_ApplyUnOrderedList(sType, sImg) {
    obj.setFocus();
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        oSel = oEditor.document.selection.createRange();
        if (oSel.parentElement) oElement = oSel.parentElement();

        while (oElement != null &&
        oElement.tagName != "OL" &&
        oElement.tagName != "UL") {
            if (oElement.tagName == "BODY") {
                obj.doCmd("InsertUnOrderedList");
                I_ApplyUnOrderedList(sType, sImg);
                return;
            }
            oElement = oElement.parentElement;
        }
    }
    else {
        oSel = oEditor.getSelection();
        oElement = parent.getSelectedElement(oSel);

        while (oElement != null && oElement.nodeName != "UL" && oElement.nodeName != "OL") {
            if (oElement.nodeName == "BODY") {
                obj.doCmd("InsertUnOrderedList");
                I_ApplyUnOrderedList(sType, sImg);
                return;
            }
            oElement = oElement.parentNode;
        }
    }

    if (oElement) {
        if (oElement.tagName != "UL") {
            obj.doCmd("InsertUnOrderedList");
            I_ApplyUnOrderedList(sType, sImg);
            return;
        }
        oElement.type = sType;
        if (sImg) oElement.style.listStyleImage = "url('" + sPath + sImg + "')";
        else oElement.style.listStyleImage = "";
    }
}
function I_TextTransform(s) {
    obj.setFocus();
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    if (I_SelectedText() == "") {

        var oSel;
        var oElement;
        if (navigator.appName.indexOf('Microsoft') != -1) {
            oSel = oEditor.document.selection.createRange();
            if (oSel.parentElement) oElement = oSel.parentElement();
        }
        else {
            oSel = oEditor.getSelection();
            oElement = parent.getSelectedElement(oSel);
        }
        if (oElement.style.textTransform == s) {
            I_FormatText("text-transform", "");
        } else {
            I_FormatText("text-transform", s);
        }

    }
    else {
        var arrStyle = [];
        arrStyle.push(["textTransform", s]);
        obj.applySpanStyle(arrStyle); /*Built-In Editor (Requires Text Selection*/
    }
}
function I_SmallCaps() {
    obj.setFocus();
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    if (I_SelectedText() == "") {

        var oSel;
        var oElement;
        if (navigator.appName.indexOf('Microsoft') != -1) {
            oSel = oEditor.document.selection.createRange();
            if (oSel.parentElement) oElement = oSel.parentElement();
        }
        else {
            oSel = oEditor.getSelection();
            oElement = parent.getSelectedElement(oSel);
        }
        if (oElement.style.fontVariant == "small-caps") {
            I_FormatText("font-variant", "");
        } else {
            I_FormatText("font-variant", "small-caps");
        }

    }
    else {
        var arrStyle = [];
        arrStyle.push(["fontVariant", "small-caps"]);
        obj.applySpanStyle(arrStyle); /*Built-In Editor (Requires Text Selection)*/
    }
}
function I_ApplyShadow(i) {
    obj.setFocus();
    obj.saveForUndo();

    if (I_SelectedText() == "") {
        if (i == -1) I_FormatText("text-shadow", "");

        /* SOURCE: webexpedition18.com/download/css3_text_shadow/ */
        if (i == 0) I_FormatText("text-shadow", "0px 2px 3px #666"); /*Inset or Letterpress*/
        if (i == 1) I_FormatText("text-shadow", "4px 4px 0px rgba(255,255,255,.8), 10px 10px 0px rgba(187,187,187,0.5)"); /*Retro*/
        if (i == 2) I_FormatText("text-shadow", "0 1px 0 #ccc,0 2px 0 #c9c9c9,0 3px 0 #bbb,0 4px 0 #b9b9b9,0 5px 0 #aaa,0 6px 1px rgba(0,0,0,.1),0 0 5px rgba(0,0,0,.1),0 1px 3px rgba(0,0,0,.3),0 3px 5px rgba(0,0,0,.2),0 5px 10px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.2),0 20px 20px rgba(0,0,0,.15)"); /*3D*/
        if (i == 3) I_FormatText("text-shadow", "-4px -2px 0 #fff"); /* Hard shadow => requires dark bg*/

        /* SOURCE: www.amazingthings.in/2011/07/css3-text-shadow-tutorial-simple_03.html */
        if (i == 4) I_FormatText("text-shadow", "2px 4px 3px rgba(0,0,0,0.3)"); /*Basic*/
        if (i == 5) I_FormatText("text-shadow", "2px 8px 6px rgba(0,0,0,0.2),0px -5px 35px rgba(255,255,255,0.3)"); /*Soft Emboss*/
        if (i == 6) I_FormatText("text-shadow", "0px 15px 5px rgba(0,0,0,0.1),10px 20px 5px rgba(0,0,0,0.05),-10px 20px 5px rgba(0,0,0,0.05)"); /*Multiple Light Source*/
        if (i == 7) I_FormatText("text-shadow", "0px 3px 0px #b2a98f,0px 14px 10px rgba(0,0,0,0.15),0px 24px 2px rgba(0,0,0,0.1),0px 34px 30px rgba(0,0,0,0.1)"); /* Down and Distant */
        if (i == 8) I_FormatText("text-shadow", "6px 6px 0px rgba(0,0,0,0.2)"); /*Hard shadow => light bg */
        if (i == 9) I_FormatText("text-shadow", "0px 4px 3px rgba(0,0,0,0.4),0px 8px 13px rgba(0,0,0,0.1),0px 18px 23px rgba(0,0,0,0.1)"); /*Close and Heavy*/

        /* SOURCE: blog.echoenduring.com/2010/05/13/create-beautiful-css3-typography/ */
        if (i == 10) I_FormatText("text-shadow", "1px 1px 1px rgba(255,255,255,0.6)"); /*Basic*/

        /* SOURCE: sixrevisions.com/css/how-to-create-inset-typography-with-css3/ */
        if (i == 11) I_FormatText("text-shadow", "rgba(0,0,0,0.5) -1px 0, rgba(0,0,0,0.3) 0 -1px, rgba(255,255,255,0.5) 0 1px, rgba(0,0,0,0.3) -1px -2px"); /*Inset or Letterpress*/

    }
    else {
        var arrStyle = [];

        if (i == -1) I_FormatText("text-shadow", "");

        if (i == 0) arrStyle.push(["textShadow", "0px 2px 3px #666"]); /*Inset or Letterpress*/
        if (i == 1) arrStyle.push(["textShadow", "4px 4px 0px rgba(255,255,255,.8), 10px 10px 0px rgba(187,187,187,0.5)"]); /*Retro*/
        if (i == 2) arrStyle.push(["textShadow", "0 1px 0 #ccc,0 2px 0 #c9c9c9,0 3px 0 #bbb,0 4px 0 #b9b9b9,0 5px 0 #aaa,0 6px 1px rgba(0,0,0,.1),0 0 5px rgba(0,0,0,.1),0 1px 3px rgba(0,0,0,.3),0 3px 5px rgba(0,0,0,.2),0 5px 10px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.2),0 20px 20px rgba(0,0,0,.15)"]); /*3D*/
        if (i == 3) arrStyle.push(["textShadow", "-4px -2px 0 #fff"]); /* Hard shadow => requires dark bg*/

        if (i == 4) arrStyle.push(["textShadow", "2px 4px 3px rgba(0,0,0,0.3)"]); /*Basic*/
        if (i == 5) arrStyle.push(["textShadow", "2px 8px 6px rgba(0,0,0,0.2),0px -5px 35px rgba(255,255,255,0.3)"]); /*Soft Emboss*/
        if (i == 6) arrStyle.push(["textShadow", "0px 15px 5px rgba(0,0,0,0.1),10px 20px 5px rgba(0,0,0,0.05),-10px 20px 5px rgba(0,0,0,0.05)"]); /*Multiple Light Source*/
        if (i == 7) arrStyle.push(["textShadow", "0px 3px 0px #b2a98f,0px 14px 10px rgba(0,0,0,0.15),0px 24px 2px rgba(0,0,0,0.1),0px 34px 30px rgba(0,0,0,0.1)"]); /* Down and Distant */
        if (i == 8) arrStyle.push(["textShadow", "6px 6px 0px rgba(0,0,0,0.2)"]); /*Hard shadow => light bg */
        if (i == 9) arrStyle.push(["textShadow", "0px 4px 3px rgba(0,0,0,0.4),0px 8px 13px rgba(0,0,0,0.1),0px 18px 23px rgba(0,0,0,0.1)"]); /*Close and Heavy*/

        if (i == 10) arrStyle.push(["textShadow", "1px 1px 1px rgba(255,255,255,0.6)"]); /*Basic*/

        if (i == 11) arrStyle.push(["textShadow", "rgba(0,0,0,0.5) -1px 0, rgba(0,0,0,0.3) 0 -1px, rgba(255,255,255,0.5) 0 1px, rgba(0,0,0,0.3) -1px -2px"]); /*Inset or Letterpress*/

        obj.applySpanStyle(arrStyle); /*Built-In Editor (Requires Text Selection)*/
    }
}

function I_ApplyHeading(val) {
    obj.setFocus();
    obj.saveForUndo();

    obj.applyParagraph('<' + val + '>');
}

function I_Size(val) {
    if (!isiPad) obj.setFocus();
    obj.saveForUndo();

    //I_FormatText('font-size', val + 'px');
    obj.applyFontSize(val + 'px');

    /*
    if (I_SelectedText() == "") {
    I_FormatText('font-size', val + 'px');
    }
    else {
    var arrStyle = [];
    arrStyle.push(["fontSize", val]);

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    var oElement;
    if (navigator.appName.indexOf('Microsoft') != -1) {
    oSel = oEditor.document.selection.createRange();
    if (oSel.parentElement) oElement = oSel.parentElement();
    }
    else {
    oSel = oEditor.getSelection();
    oElement = parent.getSelectedElement(oSel);
    }
    parent.copyStyleClass(oElement, arrStyle);
    }*/
}

function I_ApplyForeColor(sColor) {
    if (!isiPad) obj.setFocus();
    if (I_SelectedText() == "") {
        I_FormatText('color', sColor);
    }
    else {
        obj.applyColor('ForeColor', sColor);
    }
}

function I_ApplyBgColor(sColor) {
    if (!isiPad) obj.setFocus();
    if (I_SelectedText() == "") {
        I_FormatText('background-color', sColor);
    }
    else {
        obj.applyColor('hilitecolor', sColor);
    }
}

function I_ApplyFont(sFont, bSys) {
    obj.setFocus();
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    if (I_SelectedText() == "") {
        I_FormatText('font-family', sFont, bSys);
    }
    else {
        obj.applyFontName(sFont);
    }

    if (!bSys) {
        sURL = parent.oUtil.protocol + "//fonts.googleapis.com/css?family=" + sFont;
        var oLink = oEditor.document.createElement("LINK");
        oLink.href = sURL;
        oLink.rel = "StyleSheet";
        oEditor.document.documentElement.childNodes[0].appendChild(oLink);
    }
}

function I_DoCmd(sCmd) {
    obj.setFocus();
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    if (I_SelectedText() == "") {
        var doc = oEditor.document;
        if (sCmd == 'Bold') {
            if (doc.queryCommandState("Bold")) I_FormatText('font-weight', 'normal');
            else I_FormatText('font-weight', 'bold');
        }
        else if (sCmd == 'Italic') {
            if (doc.queryCommandState("Italic")) I_FormatText('font-style', 'normal');
            else I_FormatText('font-style', 'italic');
        }
        else if (sCmd == 'Underline') {
            if (doc.queryCommandState("Underline")) I_FormatText('text-decoration', 'none');
            else I_FormatText('text-decoration', 'underline');
        }
        else if (sCmd == 'Strikethrough') {
            if (doc.queryCommandState("Strikethrough")) I_FormatText('text-decoration', 'none');
            else I_FormatText('text-decoration', 'line-through');
        }
        else {
            obj.doCmd(sCmd);
        }
    }
    else {
        obj.doCmd(sCmd);
    }
}

function I_DoClean(sCmd) {
    obj.setFocus();
    obj.saveForUndo();

    obj.doClean()
}

/********************** TABLE ************************/
function I_InsertTable(nRow, nCol) {
    obj.setFocus();
    obj.saveForUndo();

    var sHTML = "<br /><table id='innovatable' style='border-collapse:collapse;width:100%;'>";
    for (var i = 1; i <= nRow; i++) {
        sHTML += "<tr>";
        for (var j = 1; j <= nCol; j++) {
            sHTML += "<td>&nbsp;</td>";
        }
        sHTML += "</tr>";
    }
    sHTML += "</table><br />";

    obj.insertHTML(sHTML);

    var oEditor = parent.document.getElementById("idContent" + obj.oName).contentWindow;

    var newTable = oEditor.document.getElementById("innovatable");
    if (newTable && oEditor.document.createRange) {
        newTable.removeAttribute("id");

        var range = oEditor.document.createRange();
        range.selectNodeContents(newTable.rows[0].cells[0]);
        range.collapse(true);

        oSel = oEditor.getSelection();
        oSel.removeAllRanges();
        oSel.addRange(range);

    }


    if (navigator.appName.indexOf('Microsoft') != -1) {
        obj.runtimeBorder(false);
    }
}

function I_GetTable() {
    if (!isiPad) obj.setFocus();

    var oEditor = parent.oUtil.oEditor;
    var oTable;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        oSel = oEditor.document.selection;
        var oRange = oSel.createRange();
        oTable = (oRange.parentElement != null ? GetElement(oRange.parentElement(), "TABLE") : GetElement(oRange.item(0), "TABLE"));
        if (oTable == null) return null;
    }
    else {
        oSel = oEditor.getSelection();
        oTable = parent.getSelectedElement(oSel);
        oTable = GetElement(oTable, "TABLE");
        if (oTable == null) return null;
    }
    return oTable;
}

function I_GetTD() {
    if (!isiPad && navigator.appName.indexOf('Microsoft') < 0) obj.setFocus();

    var oEditor = parent.oUtil.oEditor;
    var oTD;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        oSel = oEditor.document.selection;
        var oRange = oSel.createRange();
        oTD = (oRange.parentElement != null ? GetElement(oRange.parentElement(), "TD") : GetElement(oRange.item(0), "TD"));
        if (oTD == null) return;
    }
    else {
        oSel = oEditor.getSelection();
        oTD = parent.getSelectedElement(oSel);
        oTD = GetElement(oTD, "TD");
        if (oTD == null) return null;
    }
    return oTD;
}

function I_GetTR() {
    if (!isiPad) obj.setFocus();

    var oEditor = parent.oUtil.oEditor;
    var oTR;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        oSel = oEditor.document.selection;
        var oRange = oSel.createRange();
        oTR = (oRange.parentElement != null ? GetElement(oRange.parentElement(), "TR") : GetElement(oRange.item(0), "TR"));
        if (oTR == null) return;
    }
    else {
        oSel = oEditor.getSelection();
        oTR = parent.getSelectedElement(oSel);
        oTR = GetElement(oTR, "TR");
        if (oTR == null) return null;
    }
    return oTR;
}

function I_GetRadioValue(s) {
    var sVal = '';
    for (var i = 0; i < document.getElementsByName(s).length; i++) {
        if (document.getElementsByName(s)[i].checked) {
            sVal = document.getElementsByName(s)[i].value;
        }
    }
    return sVal;
}

function isEven(someNumber) {
    return (someNumber % 2 == 0) ? true : false;
}

function I_TablePadding(nPadding) {
    var oTable = I_GetTable();
    if (oTable) oTable.cellPadding = nPadding;
}

function I_BorderThickness(oTD, nThickness) {
    var sType = I_GetRadioValue('rdoBorder');
    if (sType == 'outside') {
        if (nThickness == 0) {
            oTD.style.borderWidth = '';
            oTD.style.borderStyle = '';
        } else {
            oTD.style.borderWidth = nThickness + 'px';
            oTD.style.borderStyle = 'solid';
        }
    }
    if (sType == 'top') {
        if (nThickness == 0) {
            oTD.style.borderTopWidth = '';
            oTD.style.borderTopStyle = '';
        } else {
            oTD.style.borderTopWidth = nThickness + 'px';
            oTD.style.borderTopStyle = 'solid';
        }
    }
    if (sType == 'bottom') {
        if (nThickness == 0) {
            oTD.style.borderBottomWidth = '';
            oTD.style.borderBottomStyle = '';
        } else {
            oTD.style.borderBottomWidth = nThickness + 'px';
            oTD.style.borderBottomStyle = 'solid';
        }
    }
    if (sType == 'left') {
        if (nThickness == 0) {
            oTD.style.borderLeftWidth = '';
            oTD.style.borderLeftStyle = '';
        } else {
            oTD.style.borderLeftWidth = nThickness + 'px';
            oTD.style.borderLeftStyle = 'solid';
        }
    }
    if (sType == 'right') {
        if (nThickness == 0) {
            oTD.style.borderRightWidth = '';
            oTD.style.borderRightStyle = '';
        } else {
            oTD.style.borderRightWidth = nThickness + 'px';
            oTD.style.borderRightStyle = 'solid';
        }
    }
    if (navigator.appName.indexOf('Microsoft') != -1) {
        obj.runtimeBorder(false);
    }
}

function I_BorderColor(oTD, hex) {
    oTD.style.borderColor = hex;

    var sType = I_GetRadioValue('rdoBorder');
    if (sType == 'outside') {
        oTD.style.borderColor = hex;
    }
    if (sType == 'top') {
        oTD.style.borderTopColor = hex;
    }
    if (sType == 'bottom') {
        oTD.style.borderBottomColor = hex;
    }
    if (sType == 'left') {
        oTD.style.borderLeftColor = hex;
    }
    if (sType == 'right') {
        oTD.style.borderRightColor = hex;
    }
}

function I_TableBorder(nThickness) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (sApplyTo == 'cell') {
        I_BorderThickness(oCell, nThickness);
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                I_BorderThickness(oTD, nThickness);
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                I_BorderThickness(oTD, nThickness);
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                I_BorderThickness(oTD, nThickness);
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                I_BorderThickness(oTD, nThickness);
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                I_BorderThickness(oTD, nThickness);
            }

        }
    }
}

function getCellIndex(oTable, oTR, oTD) {
    var nCount = 0;
    var bFinish = false;
    for (var i = 0; i < oTR.cells.length; i++) {
        if (bFinish == false) {
            nCount += oTR.cells[i].colSpan;
        }
        if (oTD == oTR.cells[i]) bFinish = true;
    }
    nCount = nCount - (oTD.colSpan - 1);

    var nCellIndex = nCount - 1;
    return nCellIndex;
}

function I_TableColor(hex) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (sApplyTo == 'cell') {
        oCell.style.borderColor = hex;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.borderColor = hex;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.borderColor = hex;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.borderColor = hex;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.borderColor = hex;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.borderColor = hex;
            }

        }
    }
}

function I_TableBackground(hex) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (sApplyTo == 'cell') {
        oCell.style.backgroundColor = hex;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.backgroundColor = hex;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.backgroundColor = hex;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.backgroundColor = hex;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.backgroundColor = hex;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.backgroundColor = hex;
            }

        }
    }
}

function I_TableTextColor(hex) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (sApplyTo == 'cell') {
        oCell.style.color = hex;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.color = hex;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.color = hex;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.color = hex;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.color = hex;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.color = hex;
            }

        }
    }
}

function cellHrAlign(s) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (sApplyTo == 'cell') {
        oCell.style.textAlign = s;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.textAlign = s;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.textAlign = s;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.textAlign = s;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.textAlign = s;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.textAlign = s;
            }

        }
    }
}

function cellVrAlign(s) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (sApplyTo == 'cell') {
        oCell.style.verticalAlign = s;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.verticalAlign = s;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.verticalAlign = s;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.verticalAlign = s;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.verticalAlign = s;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.verticalAlign = s;
            }

        }
    }
}

function cellWidth(s) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (s == "") {

    } else {
        s = s + 'px';
    }

    if (sApplyTo == 'cell') {
        oCell.style.width = s;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.width = s;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.width = s;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.width = s;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.width = s;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.width = s;
            }

        }
    }
}

function cellHeight(s) {
    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sApplyTo = I_GetRadioValue('rdoApply');

    if (s == "") {

    } else {
        s = s + 'px';
    }

    if (sApplyTo == 'cell') {
        oCell.style.height = s;
        return;
    }

    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            if (sApplyTo == 'table') {
                oTD.style.height = s;
            }
            if (sApplyTo == 'even' && isEven(i + 1)) {/*even=genap*/
                oTD.style.height = s;
            }
            if (sApplyTo == 'odd' && !isEven(i + 1)) {
                oTD.style.height = s;
            }
            if (sApplyTo == 'row' && oTR == I_GetTR()) {
                oTD.style.height = s;
            }
            if (sApplyTo == 'column' && j == getCellIndex(oTable, oRow, oCell)) {
                oTD.style.height = s;
            }

        }
    }
}

function I_DelTable() {
    obj.saveForUndo();
    var oTable = I_GetTable();
    oTable.parentNode.removeChild(oTable);
}

/**** MODIFY ****/

function I_InsertCol(bLeft) {
    obj.saveForUndo();

    var oTable = I_GetTable();
    var oTR = I_GetTR();
    var oTD = I_GetTD();
    if (!oTable) return;

    var nCellIndex = oTD.cellIndex;
    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR_tmp = oTable.rows[i];
        if (bLeft) {
            var oNewCell = oTR_tmp.insertCell(nCellIndex);
            oNewCell.className = oTD.className; /*"cell_normal";*/
            oNewCell.innerHTML = "<br />";
        }
        else {
            var oNewCell = oTR_tmp.insertCell(nCellIndex + 1);
            oNewCell.className = oTD.className; /*"cell_normal";*/
            oNewCell.innerHTML = "<br />";
        }
    }
    if (navigator.appName.indexOf('Microsoft') != -1) {
      obj.runtimeBorder(false);
    }
}

function I_DeleteCol() {
    obj.saveForUndo();

    var oTable = I_GetTable();
    var oTR = I_GetTR();
    var oTD = I_GetTD();
    if (!oTable) return;

    var nCellIndex = oTD.cellIndex;
    for (var i = 0; i < oTable.rows.length; i++) oTable.rows[i].deleteCell(nCellIndex);
    
    if (navigator.appName.indexOf('Microsoft') != -1) {
      obj.runtimeBorder(false);
    }
}

function I_InsertRow(bAbove) {
    obj.saveForUndo();

    var oTable = I_GetTable();
    var oTR = I_GetTR();
    var oTD = I_GetTD();
    if (!oTable) return;

    var oNewRow;
    if (bAbove) oNewRow = oTable.insertRow(oTR.rowIndex);
    else oNewRow = oTable.insertRow(oTR.rowIndex + 1);

    for (var i = 0; i < oTR.cells.length; i++) {
        var oNewTD = oNewRow.insertCell(oNewRow.cells.length);
        oNewTD.className = oTD.className; /*"cell_normal";*/
        oNewTD.innerHTML = "&nbsp;";
    }
    if (navigator.appName.indexOf('Microsoft') != -1) {
            obj.runtimeBorder(false);
    }
}

function I_DeleteRow() {
    obj.saveForUndo();
    var oTable = I_GetTable();
    var oTR = I_GetTR();
    var oTD = I_GetTD();
    if (!oTable) return;

    oTable.deleteRow(oTR.rowIndex);
    if (oTable.rows.length == 0) oTable.parentNode.removeChild(oTable);
}

function I_MergeCell() {
    obj.saveForUndo();
    var oTable = I_GetTable();
    var oTR = I_GetTR();
    var oTD = I_GetTD();
    if (!oTable) return;

    oTD.colSpan = oTD.colSpan + 1; /*TODO: Merge 2 cell yg masig2 ada colspan-nya.*/

    if (oTD.cellIndex + 1 < oTable.rows[oTR.rowIndex].cells.length) {
        oTable.rows[oTR.rowIndex].deleteCell(oTD.cellIndex + 1);
    }
}

function cleanFormat() {
    obj.saveForUndo();

    var oTable = I_GetTable();
    if (!oTable) return;

    oTable.className = '';
    oTable.removeAttribute('class');
    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR = oTable.rows[i];
        oTR.className = '';
        oTR.removeAttribute('class');
        oTR.removeAttribute('style');
        for (var j = 0; j < oTR.cells.length; j++) {
            var oTD = oTR.cells[j];
            oTD.className = '';
            oTD.removeAttribute('class');
            oTD.removeAttribute('style');
        }
    }
}

function applyFormat(sName) {
    obj.saveForUndo();

    var oTable = I_GetTable();
    var oRow = I_GetTR();
    var oCell = I_GetTD();
    if (!oTable) return;

    var sTableClass = sName;
    var sCellClass = "";
    var sAltCellClass = "alt";
    var sHeaderRowClass = "hd";
    var sFirstCellClass = "fc";
    var sAltFirstCellClass = "fcalt";

    oTable.className = sTableClass;
    var bIsAlternate = false;
    var sCell;
    var sFirstCell;
    for (var i = 0; i < oTable.rows.length; i++) {
        var oTR_tmp = oTable.rows[i];
        if (bIsAlternate) {
            sCell = sAltCellClass;
            sFirstCell = sAltFirstCellClass;
            bIsAlternate = false;
        }
        else {
            sCell = sCellClass;
            sFirstCell = sFirstCellClass;
            bIsAlternate = true;
        }
        for (var j = 0; j < oTR_tmp.cells.length; j++) {
            if (j == 0) oTR_tmp.cells[j].className = sFirstCell; /*first column*/
            if (i == 0) oTR_tmp.cells[j].className = sHeaderRowClass; /*header row*/
            if (i != 0 && j != 0) oTR_tmp.cells[j].className = sCell;
        }
    }

}
/********************** /TABLE ************************/

function I_SetCss(oElement, cssRule, cssVal) {
    if (cssRule == 'font-family') oElement.style.fontFamily = cssVal;
    if (cssRule == 'color') oElement.style.color = cssVal;
    if (cssRule == 'background-color') oElement.style.backgroundColor = cssVal;
    if (cssRule == 'font-weight') oElement.style.fontWeight = cssVal;
    if (cssRule == 'font-style') oElement.style.fontStyle = cssVal;
    if (cssRule == 'text-decoration') oElement.style.textDecoration = cssVal;
    if (cssRule == 'font-size') oElement.style.fontSize = cssVal;
    if (cssRule == 'text-shadow') oElement.style.textShadow = cssVal;
    if (cssRule == 'line-height') oElement.style.lineHeight = cssVal;
    if (cssRule == 'letter-spacing') oElement.style.letterSpacing = cssVal;
    if (cssRule == 'word-spacing') oElement.style.wordSpacing = cssVal;
    if (cssRule == 'text-transform') oElement.style.textTransform = cssVal;
    if (cssRule == 'font-variant') oElement.style.fontVariant = cssVal;
}

function I_FormatText(cssRule, cssVal, bSys) {
    if (!isiPad) obj.setFocus();

    /*obj.saveForUndo();*/

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    if (parent.oUtil.activeElement) {
        oElement = parent.oUtil.activeElement;
        I_SetCss(oElement, cssRule, cssVal);
    }
    else {
        if (navigator.appName.indexOf('Microsoft') != -1) {

            oSel = oEditor.document.selection.createRange();
            if (oSel.parentElement) {
                if (oSel.text == "") {
                    oElement = oSel.parentElement();
                    if (oElement)
                        if (oElement.tagName != "BODY") I_SetCss(oElement, cssRule, cssVal);
                }
                else {
                    obj.applySpanStyle([[cssRule, cssVal]]);
                }
            }
            else {
                oElement = oSel.item(0);
                if (oElement)
                    I_SetCss(oElement, cssRule, cssVal);
            }

        }
        else {
            /*** obj.applySpanStyle([cssRule, cssVal]);  //line ini membuat font-family hilang wkt apply font size ***/
            /*** For Chrome ***/ /*** also on FF ***/
            oSel = oEditor.getSelection();
            oElement = parent.getSelectedElement(oSel);
            I_SetCss(oElement, cssRule, cssVal);
            /*** /For Chrome ***/

            /*
            oSel = oEditor.getSelection();
            oElement = parent.getSelectedElement(oSel);
            if (parent.isTextSelected(oSel)) {
            if (oSel != "") {
            obj.applySpanStyle([cssRule, cssVal]);
            }
            else {
            if (oElement)
            if (oElement.nodeName != "BODY") I_SetCss(oElement, cssRule, cssVal);
            }
            }
            else {
            if (oElement)
            if (oElement.nodeName != "BODY") I_SetCss(oElement, cssRule, cssVal);
            }*/
        }
    }

    /*window.focus();*/
}

function I_InsertImage(sURL, sAltText, sCssClass, sCssStyle) {
    if (sURL == "") return;

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        oEditor.focus();
        obj.setFocus();
        oSel = oEditor.document.selection.createRange();
        oSel.execCommand("InsertImage", false, sURL);

        var oSel = oEditor.document.selection.createRange();
        if (oSel.parentElement) oElement = oSel.parentElement();
        else oElement = oSel.item(0);
        if (oElement.tagName == "IMG") {
            oElement.alt = sAltText;
            oElement.border = "0";
            if (sCssClass != "") oElement.className = sCssClass;
            if (sCssStyle != "") oElement.style.cssText = sCssStyle;
        }
    }
    else {
        oEditor.document.execCommand("InsertImage", false, sURL);

        oSel = oEditor.getSelection();
        var range = oSel.getRangeAt(0);
        var oElement = range.startContainer.childNodes[range.startOffset - 1];
        oSel = oEditor.getSelection();
        range = oEditor.document.createRange();
        range.selectNodeContents(oElement);
        oSel.removeAllRanges();
        oSel.addRange(range);
        if (oElement.tagName == "IMG") {
            oElement.setAttribute("ALT", sAltText);
            oElement.setAttribute("border", "0");
            if (sCssClass != "") oElement.setAttribute("class", sCssClass);
            if (sCssStyle != "") oElement.setAttribute("style", sCssStyle);
        }
    }
    return oElement;
}

function I_CreateLink(sURL, sTitle, sTarget, sCssClass, sCssStyle) {
    if (sURL == "" || sURL == "http://") return;

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        if (!oEditor) return;
        oEditor.focus();
        obj.setFocus();
        oSel = oEditor.document.selection.createRange();
        if (oSel.text == "") {/*If no (text) selection, then build selection using the typed URL*/
            var oSelTmp = oSel.duplicate();
            oSel.text = sURL;
            oSel.setEndPoint("StartToStart", oSelTmp);
            oSel.select();
        }
        oSel.execCommand("CreateLink", false, sURL);

        if (oSel.parentElement) oElement = GetElement(oSel.parentElement(), "A");
        else oElement = GetElement(oSel.item(0), "A");

        oElement.title = sTitle;
        if (sTarget != "") oElement.target = sTarget;
        if (sCssClass != "") oElement.className = sCssClass;
        if (sCssStyle != "") oElement.style.cssText = sCssStyle;

        return oElement;
    }
    else {
        oSel = oEditor.getSelection();
        var range = oSel.getRangeAt(0);

        var emptySel = false;
        if (range.toString() == "") {
            /*If no (text) selection, then build selection using the typed URL*/
            if (range.startContainer.nodeType == Node.ELEMENT_NODE) {
                if (range.startContainer.nodeName == "IMG") {
                    range.setStartBefore(range.startContainer);
                    emptySel = false;
                }
                else
                    if (range.startContainer.childNodes[range.startOffset] != null && range.startContainer.childNodes[range.startOffset].nodeType != Node.TEXT_NODE) {
                        if (range.startContainer.childNodes[range.startOffset].nodeName == "BR") emptySel = true; else emptySel = false;
                    }
                    else {
                        emptySel = true;
                    }
            }
            else {
                emptySel = true;
            }
        }

        if (emptySel) {
            var node = oEditor.document.createTextNode(sURL);
            range.insertNode(node);
            oEditor.document.designMode = "on";

            range = oEditor.document.createRange();
            range.setStart(node, 0);
            range.setEnd(node, sURL.length);

            oSel = oEditor.getSelection();
            oSel.removeAllRanges();
            oSel.addRange(range);
        }
        oEditor.document.execCommand("CreateLink", false, sURL);

        oSel = oEditor.getSelection();

        var oElement = GetElement((window.opener ? window.opener : parent).getSelectedElement(oSel), "A");

        oElement.setAttribute("title", sTitle);
        if (sTarget != "") oElement.setAttribute("target", sTarget);
        if (sCssClass != "") oElement.setAttribute("class", sCssClass);
        if (sCssStyle != "") oElement.setAttribute("style", sCssStyle);

        return oElement;
    }
}

function I_InsertHTML(sHTML) {
    obj.saveForUndo();

    var oEditor = parent.oUtil.oEditor;
    var oSel;
    if (navigator.appName.indexOf('Microsoft') != -1) {
        if (!oEditor) return;
        oEditor.focus();
        obj.setFocus();
        oSel = oEditor.document.selection.createRange();
        if (oSel.parentElement) { oSel.pasteHTML(sHTML); }
        else oSel.item(0).outerHTML = sHTML;
    }
    else {
        oSel = oEditor.getSelection();
        var range = oSel.getRangeAt(0);

        var docFrag = range.createContextualFragment(sHTML);
        range.collapse(false);
        var lastNode = docFrag.childNodes[docFrag.childNodes.length - 1];
        range.insertNode(docFrag);
        try { oEditor.document.designMode = "on"; } catch (e) { } /*Saf tdk perlu*/
        if (lastNode.nodeType == Node.TEXT_NODE) {
            range = oEditor.document.createRange();
            range.setStart(lastNode, lastNode.nodeValue.length);
            range.setEnd(lastNode, lastNode.nodeValue.length);
            oSel = oEditor.getSelection();
            oSel.removeAllRanges();
            oSel.addRange(range);

            /*Saf tdk perlu*/
            var comCon = range.commonAncestorContainer;
            if (comCon && comCon.parentNode) {
                try { comCon.parentNode.normalize(); } catch (e) { }
            }
        }

    }
}


function I_Close() {
    try { parent.box.close(); } catch (e) { }
}