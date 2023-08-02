/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */
 var BASE_URL = "https://easchem.anbon.vip/assets/";
// var BASE_URL = "http://127.0.0.1:8000/assets/";
CKEDITOR.editorConfig = function( config ) {
//	 Define changes to default configuration here. For example:
	config.toolbarGroups = [
	                        { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
	                        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
	                        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
	                        // { name: 'tools' },
	                        // { name: 'forms' },
	                        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
	                        { name: 'links' },
	                        { name: 'others' },
	                        '/',
	                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
	                        { name: 'styles' },
	                        { name: 'colors' },
	                        { name: 'insert' }
	                    ];
    
	 config.language = 'zh_TW';
	 config.uiColor = '#DDDDDD';
	
	config.filebrowserBrowseUrl = BASE_URL+'ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = BASE_URL+'ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = BASE_URL+'ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = BASE_URL+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'; //可上傳一般檔案
	config.filebrowserImageUploadUrl = BASE_URL+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';//可上傳圖檔
	config.filebrowserFlashUploadUrl = BASE_URL+'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';//可上傳Flash檔案
};
