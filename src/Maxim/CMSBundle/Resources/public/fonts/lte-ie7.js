/* Use this script if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'icomoon\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-untitled' : '&#xf002;',
			'icon-untitled-2' : '&#xf003;',
			'icon-untitled-3' : '&#xf005;',
			'icon-untitled-4' : '&#xf006;',
			'icon-untitled-5' : '&#xf007;',
			'icon-untitled-6' : '&#xf009;',
			'icon-untitled-7' : '&#xf00a;',
			'icon-untitled-8' : '&#xf00b;',
			'icon-untitled-9' : '&#xf00c;',
			'icon-untitled-10' : '&#xf00d;',
			'icon-untitled-11' : '&#xf011;',
			'icon-untitled-12' : '&#xf012;',
			'icon-untitled-13' : '&#xf013;',
			'icon-untitled-14' : '&#xf014;',
			'icon-untitled-15' : '&#xf015;',
			'icon-untitled-16' : '&#xf016;',
			'icon-untitled-17' : '&#xf017;',
			'icon-untitled-18' : '&#xf019;',
			'icon-untitled-19' : '&#xf01a;',
			'icon-untitled-20' : '&#xf02c;',
			'icon-untitled-21' : '&#xf02b;',
			'icon-untitled-22' : '&#xf02a;',
			'icon-untitled-23' : '&#xf029;',
			'icon-untitled-24' : '&#xf021;',
			'icon-untitled-25' : '&#xf01e;',
			'icon-untitled-26' : '&#xf01b;',
			'icon-untitled-27' : '&#xf03a;',
			'icon-untitled-28' : '&#xf03e;',
			'icon-untitled-29' : '&#xf040;',
			'icon-untitled-30' : '&#xf06a;',
			'icon-untitled-31' : '&#xf067;',
			'icon-untitled-32' : '&#xf068;',
			'icon-untitled-33' : '&#xf069;',
			'icon-untitled-34' : '&#xf05e;',
			'icon-untitled-35' : '&#xf05d;',
			'icon-untitled-36' : '&#xf05c;',
			'icon-untitled-37' : '&#xf05a;',
			'icon-untitled-38' : '&#xf059;',
			'icon-untitled-39' : '&#xf058;',
			'icon-untitled-40' : '&#xf057;',
			'icon-untitled-41' : '&#xf056;',
			'icon-untitled-42' : '&#xf055;',
			'icon-untitled-43' : '&#xf054;',
			'icon-untitled-44' : '&#xf053;',
			'icon-untitled-45' : '&#xf071;',
			'icon-untitled-46' : '&#xf073;',
			'icon-untitled-47' : '&#xf077;',
			'icon-untitled-48' : '&#xf078;',
			'icon-untitled-49' : '&#xf07a;',
			'icon-untitled-50' : '&#xf07b;',
			'icon-untitled-51' : '&#xf07c;',
			'icon-untitled-52' : '&#xf080;',
			'icon-untitled-53' : '&#xf082;',
			'icon-untitled-54' : '&#xf081;',
			'icon-untitled-55' : '&#xf085;',
			'icon-untitled-56' : '&#xf086;',
			'icon-untitled-57' : '&#xf087;',
			'icon-untitled-58' : '&#xf088;',
			'icon-untitled-59' : '&#xf0a3;',
			'icon-untitled-60' : '&#xf09a;',
			'icon-untitled-61' : '&#xf099;',
			'icon-untitled-62' : '&#xf09d;',
			'icon-untitled-63' : '&#xf09c;',
			'icon-untitled-64' : '&#xf095;',
			'icon-untitled-65' : '&#xf093;',
			'icon-untitled-66' : '&#xf092;',
			'icon-untitled-67' : '&#xf091;',
			'icon-untitled-68' : '&#xf090;',
			'icon-untitled-69' : '&#xf08b;',
			'icon-untitled-70' : '&#xf08c;',
			'icon-untitled-71' : '&#xf0a8;',
			'icon-untitled-72' : '&#xf0a9;',
			'icon-untitled-73' : '&#xf0aa;',
			'icon-untitled-74' : '&#xf0ab;',
			'icon-untitled-75' : '&#xf0ac;',
			'icon-untitled-76' : '&#xf0ad;',
			'icon-untitled-77' : '&#xf0ae;',
			'icon-untitled-78' : '&#xf0c0;',
			'icon-untitled-79' : '&#xf0c2;',
			'icon-untitled-80' : '&#xf0c3;',
			'icon-untitled-81' : '&#xf0c9;',
			'icon-untitled-82' : '&#xf0ca;',
			'icon-untitled-83' : '&#xf0cb;',
			'icon-untitled-84' : '&#xf0e8;',
			'icon-untitled-85' : '&#xf0e7;',
			'icon-untitled-86' : '&#xf0e5;',
			'icon-untitled-87' : '&#xf0e6;',
			'icon-untitled-88' : '&#xf0e4;',
			'icon-untitled-89' : '&#xf0e1;',
			'icon-untitled-90' : '&#xf0e2;',
			'icon-untitled-91' : '&#xf0e0;',
			'icon-untitled-92' : '&#xf0de;',
			'icon-untitled-93' : '&#xf0dd;',
			'icon-untitled-94' : '&#xf0dc;',
			'icon-untitled-95' : '&#xf0db;',
			'icon-untitled-96' : '&#xf0da;',
			'icon-untitled-97' : '&#xf0d9;',
			'icon-untitled-98' : '&#xf0d8;',
			'icon-untitled-99' : '&#xf0d7;',
			'icon-untitled-100' : '&#xf0d5;',
			'icon-untitled-101' : '&#xf0d4;',
			'icon-untitled-102' : '&#xf0d3;',
			'icon-untitled-103' : '&#xf0d2;',
			'icon-untitled-104' : '&#xf0d6;',
			'icon-untitled-105' : '&#xf0ce;',
			'icon-untitled-106' : '&#xf106;',
			'icon-untitled-107' : '&#xf107;',
			'icon-untitled-108' : '&#xf108;',
			'icon-untitled-109' : '&#xf109;',
			'icon-untitled-110' : '&#xf10a;',
			'icon-untitled-111' : '&#xf10b;',
			'icon-untitled-112' : '&#xf10c;',
			'icon-untitled-113' : '&#xf10d;',
			'icon-untitled-114' : '&#xf10e;',
			'icon-untitled-115' : '&#xf110;',
			'icon-untitled-116' : '&#xf113;',
			'icon-untitled-117' : '&#xf114;',
			'icon-untitled-118' : '&#xf115;',
			'icon-untitled-119' : '&#xf0ed;',
			'icon-untitled-120' : '&#xf0ee;',
			'icon-untitled-121' : '&#xf0ec;',
			'icon-untitled-122' : '&#xf0eb;',
			'icon-untitled-123' : '&#xf100;',
			'icon-untitled-124' : '&#xf101;',
			'icon-untitled-125' : '&#xf102;',
			'icon-untitled-126' : '&#xf103;',
			'icon-untitled-127' : '&#xf104;',
			'icon-untitled-128' : '&#xf105;',
			'icon-untitled-129' : '&#xf023;',
			'icon-untitled-130' : '&#xf06b;',
			'icon-untitled-131' : '&#xf084;',
			'icon-untitled-132' : '&#xf0a0;',
			'icon-untitled-133' : '&#xf0c7;',
			'icon-untitled-134' : '&#xf022;',
			'icon-untitled-135' : '&#xf06e;',
			'icon-untitled-136' : '&#xf070;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; i < els.length; i += 1) {
		el = els[i];
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};