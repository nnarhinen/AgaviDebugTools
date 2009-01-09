/**
 *
 */

window.addEvent("domready", function(){

	var tabs = new SimpleTabs('adt-sections', {
		selector: 'h2'
	});
	
	var rtabs = new SimpleTabs('adt-section-routing', {
		selector: 'h3'
	});
	
	if ($('adt-section-actions')) {
		var atabs = new SimpleTabs('adt-section-actions', {
			selector: 'h3'
		});
	}
	
	var rdtabs = new SimpleTabs('adt-section-globalrd', {
		selector: 'h3'
	});

	var environmentsTabs = new SimpleTabs('adt-section-environments', {
		selector: 'h3'
	});
	
	var toggle = new Element('a', {
		'class': 'myClass',
		'html': 'Hide',
		'styles': {
			'float': 'right',
			'background': '#fff',
			'color': '#03f',
			'padding': '0.3em',		
			'border': '1px solid #000',
			'font-weight': 'bold',
			'text-decoration': 'none',
			'cursor': 'pointer'
		},
		'events': {
			'click': function(){
				var container = $('adt-container');
				var sections = $('adt-sections');
				if (sections.getStyle('display') == 'none') {
					sections.setStyle('display', 'block');
					Cookie.write('adt-visible', 'true', {path: '/'});
					this.set('html', 'Hide');
				}
				else {
					sections.setStyle('display', 'none');
					Cookie.write('adt-visible', 'false', {path: '/'});
					this.set('html', 'Show');
				}
			},
		}
	});
	toggle.inject($('adt-container'), 'top');
	
	if (Cookie.read('adt-visible') == 'false') {
		$('adt-sections').setStyle('display', 'none');
		toggle.set('html', 'Show');
	}
});
