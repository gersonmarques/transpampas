jQuery(document).ready(function() {

selectFields = [ 'username', 'password', 'email', 'role', 'title', 'content', 'excerpt', 'term_name', 'featured_image' ];
shortcodeTypes = [ 'text', 'email', 'textarea', 'number', 'url', 'wysiwyg' ];


function getSelectFields(type){		
	var typeFields = [];
	if(jQuery.inArray(type, selectFields) !== -1){
		typeFields= [ type ];	
	}else{
		switch(type){
				case 'text':
					typeFields = ['username', 'title', 'term_name'];
					break;
				case 'textarea':
					typeFields = ['content', 'excerpt'];
					break;
				case 'wysiwyg':
					typeFields = ['content'];
					break;		
				case 'image':
					typeFields = ['featured_image'];
					break;
				case 'select':
					typeFields = ['role'];
					break;
				case 'radio':
					typeFields = ['role'];
					break;
		}
	}
	return typeFields;
}
 

function onPanelShow(panel, model) {
	editor = panel.getCurrentPageView();

	editedModel = model;

	settingsModel = editedModel.get('settings');		
	
	var formFields = (settingsModel.get('form_fields') == 'fields') ? 'fields' : 'field_groups';	
	
	resetSelectFields(formFields);

};

function resetSelectFields(formFields){
	var chosenFields = [];
	var acfGroups = settingsModel.get('field_groups_select');
	var acfFields = settingsModel.get('fields_select');
	
	if(formFields == 'field_groups' && acfGroups.length > 0){
		jQuery.each(acfGroups, function(index, group){
			jQuery.each(acf_grouped_fields[group], function(ind, field){
				chosenFields.push(field);
			});
		});
	}
	if(formFields == 'fields' && acfFields.length > 0){
		chosenFields = acfFields;
	}
	
	setSelectFields(chosenFields);
}

function setSelectFields(chosenFields, remove){
	if( typeof selectFields != 'undefined' ){
		jQuery.each(selectFields, function(index, selectField){
			var fieldControl = editor.collection.findWhere({ name: selectField + '_field' });
				if( typeof fieldControl != 'undefined' && typeof chosenFields != 'undefined' ){
					if(jQuery.isArray(chosenFields)){
						fieldControl.set('options', { '' : ''});
						jQuery.each(chosenFields, function(){
							addFieldChoice(this, selectField, fieldControl);
						});
					}else{				
						addFieldChoice(chosenFields, selectField, fieldControl, remove);
					}
					var fieldView = editor.children.findByModelCid(fieldControl.cid);
					if (fieldView) {
						fieldView.render();
					}
				}
		});
	}
}

function addFieldChoice(field, selectField, fieldControl, remove){
	var fieldData = acf_field_types[field];

	if( typeof fieldData != 'undefined' ){
		var type = acf_field_types[field]['type'];
		var fieldTypes = getSelectFields(type);
		if(jQuery.inArray(selectField, fieldTypes) > -1){
			if(remove){
				delete fieldControl.get('options')[field];
			}else{
				var fieldLabel = acf_field_types[field]['label'];
				fieldControl.get('options')[field] = fieldLabel;
			}
		}
	}
}
	

jQuery("#elementor-panel").on("select2:select", "select[data-setting=fields_select]", function(e){
  var newFieldKey = e.params.data.id;
	setSelectFields(newFieldKey);
});  

jQuery("#elementor-panel").on("select2:select", "select[data-setting=field_groups_select]", function(e){
  var newGroupKey = e.params.data.id;
  jQuery.each(acf_grouped_fields[newGroupKey], function(index, field){
  	setSelectFields(field);
  });
});  

jQuery("#elementor-panel").on("select2:unselect", "select[data-setting=fields_select]", function(e){
  var oldFieldKey = e.params.data.id;
  setSelectFields(oldFieldKey, true);
});  

jQuery("#elementor-panel").on("select2:unselect", "select[data-setting=field_groups_select]", function(e){
  var oldGroupKey = e.params.data.id;
  jQuery.each(acf_grouped_fields[oldGroupKey], function(index, field){
  	setSelectFields(field, true);
  });	
});  

jQuery("#elementor-panel").on("change", "select[data-setting=form_fields]", function(e){
  var formFields = (jQuery(this).val() == 'fields') ? 'fields' : 'field_groups';	
  resetSelectFields(formFields);
}); 



elementor.hooks.addAction('panel/open_editor/widget/acf_ele_form', onPanelShow);
});