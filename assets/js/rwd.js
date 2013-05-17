document.documentElement.className += ' js';

// Responsive, dynamic mobile navigation <select> in raw JS
// toddmotto.com/creating-a-responsive-dynamic-mobile-navigation-from-pure-javascript
var select = document.createElement('select');
var first = document.createElement('option');

first.innerHTML = '- Navigation -';
select.setAttribute('id', 'mobile');
select.appendChild(first);

var nav = document.getElementById('nav');
var loadLinks = function(element, hyphen, level) {

	var e = element;
	var children = e.children;

	for(var i = 0; i < e.children.length; ++i) {

		var currentLink = children[i];

		switch(currentLink.nodeName) {
			case 'A':
				var option = document.createElement('option');
				option.innerHTML = (level++ < 1 ? '' : hyphen) + currentLink.innerHTML;
				option.value = currentLink.href;
				select.appendChild(option);
				break;
			default:
				if(currentLink.nodeName === 'UL') {
					(level < 2) || (hyphen += hyphen);
				}
				loadLinks(currentLink, hyphen, level);
				break;
		}
	}
}

loadLinks(nav, '- ', 0);

nav.appendChild(select);

var mobile = document.getElementById('mobile');

if(mobile.addEventListener) {
	mobile.addEventListener('change', function () {
		window.location.href = mobile.options[mobile.selectedIndex].value;
	});
} else if(mobile.attachEvent) {
	mobile.attachEvent('onchange', function () {
		window.location.href = mobile.options[mobile.selectedIndex].value;
	});
} else {
	mobile.onchange = function () {
		window.location.href = mobile.options[mobile.selectedIndex].value;
	}
}

// Button behind <select> nav
var span = document.createElement('span');
span.className = 'mobile';
nav.appendChild(span);

