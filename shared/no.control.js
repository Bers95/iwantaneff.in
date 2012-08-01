// Disable Context Menu
document.oncontextmenu = function () {
    return false
};

// Disable dragging of HTML elements
document.ondragstart = function () {
    return false
};

// Break out of frames
if (self != top) top.location = self.location;
document.onkeydown = function (e) {
    if (e.keyCode == 93) {
        alert('_')
    }
};

// Disable selecting of text
document.onselectstart = function () {
    if (event.srcElement.type != "text" && event.srcElement.type != "textarea" && event.srcElement.type != "password") {
        return false
    } else {
        return true
    }
};

// Disable Mousedown event, except for text-areas & Inputs where it's needed
document.onmousedown = function (e) {
    var obj = e.target;
    if (obj.tagName.toUpperCase() == "INPUT" || obj.tagName.toUpperCase() == "TEXTAREA" || obj.tagName.toUpperCase() == "PASSWORD") {
        return true
    } else {
        return false
    }
};

// Stop CTRL Key abuse on your page!
window.onkeydown = function (e) {
    if (e.ctrlKey == true) {
        return false
    }
};