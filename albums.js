// AJAX IFRAME METHOD (AIM) - http://www.webtoolkit.info/ajax-file-upload.html
// This does the image uploading in the background

AIM = {

	frame : function(c) {

		var n = 'f' + Math.floor(Math.random() * 99999);
		var d = document.createElement('DIV');
		d.innerHTML = '<iframe style="display:none" src="about:blank" id="'+n+'" name="'+n+'" onload="AIM.loaded(\''+n+'\')"></iframe>';
		document.body.appendChild(d);

		var i = document.getElementById(n);
		if (c && typeof(c.onComplete) == 'function') {
			i.onComplete = c.onComplete;
		}

		return n;
	},

	form : function(f, name) {
		f.setAttribute('target', name);
	},

	submit : function(f, c) {
		AIM.form(f, AIM.frame(c));
		if (c && typeof(c.onStart) == 'function') {
			return c.onStart();
		} else {
			return true;
		}
	},

	loaded : function(id) {
		var i = document.getElementById(id);
		if (i.contentDocument) {
			var d = i.contentDocument;
		} else if (i.contentWindow) {
			var d = i.contentWindow.document;
		} else {
			var d = window.frames[id].document;
		}
		if (d.location.href == "about:blank") {
			return;
		}

		if (typeof(i.onComplete) == 'function') {
			i.onComplete(d.body.innerHTML);
		}
	}

}


// Adapted from 24 Ways: http://24ways.org/2005/edit-in-place-with-ajax
// This does the inline editing of content

Event.observe(window, 'load', init, false);

function init(){
	makeEditable('name');
	makeEditable('description');
	makeEditable('credits');
	makeEditable('tags');
}

function makeEditable(id){
	Event.observe(id, 'click', function(){edit($(id))}, false);
	Event.observe(id, 'mouseover', function(){showAsEditable($(id))}, false);
	Event.observe(id, 'mouseout', function(){showAsEditable($(id), true)}, false);
}

function edit(obj){
	Element.hide(obj);
	
	var textarea = '<div id="'+obj.id+'_editor"><textarea id="'+obj.id+'_edit" name="'+obj.id+'" rows="1" cols="60" style="height:30px;">'+obj.innerHTML+'</textarea>';
	var button	 = '<div><input class="albumsStyleConfirm" id="'+obj.id+'_save" type="button" value="Update" /> or <input class="albumsStyleCancel" id="'+obj.id+'_cancel" type="button" value="Cancel" /></div></div>';
	
	new Insertion.After(obj, textarea+button);
		
	Event.observe(obj.id+'_save', 'click', function(){saveChanges(obj)}, false);
	Event.observe(obj.id+'_cancel', 'click', function(){cleanUp(obj)}, false);
	
}

function showAsEditable(obj, clear){
	if (!clear){
		Element.addClassName(obj, 'editable');
	}else{
		Element.removeClassName(obj, 'editable');
	}
}

function saveChanges(obj){

	var page = window.location.href;
	var pageArray = page.split('/');
	var arrayLength = pageArray.length;
	var pageID = pageArray[arrayLength-1];
	var new_content	=  escape($F(obj.id+'_edit'));
	obj.innerHTML	= "Saving...";
	cleanUp(obj, true);

	var success	= function(t){editComplete(t, obj);}
	var failure	= function(t){editFailed(t, obj);}

	modRewrite = '';
	if(pageArray[4] == '?') { modRewrite = '?/'; }
	var type_content = pageArray[arrayLength-2];
  	var url = pageArray[0]+'//'+pageArray[2]+'/'+pageArray[3]+'/'+modRewrite+'albums/editHandler/'+pageID+'';
	var pars = 'id='+obj.id+'&content='+new_content+'&type='+type_content;
	var myAjax = new Ajax.Request(url, {method:'post', postBody:pars, onSuccess:success, onFailure:failure});

}

function cleanUp(obj, keepEditable){
	Element.remove(obj.id+'_editor');
	Element.show(obj);
	if (!keepEditable) showAsEditable(obj, true);
}

function editComplete(t, obj){
	obj.innerHTML	= t.responseText;
	showAsEditable(obj, true);
}

function editFailed(t, obj){
	obj.innerHTML	= 'Sorry, the update failed.';
	cleanUp(obj);
}

