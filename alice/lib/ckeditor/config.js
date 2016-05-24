/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    //img upload url
    config.filebrowserUploadUrl = "http://local.wizdata:8080/api/apickeditor.php";
    //config.filebrowserUploadUrl = "http://www.wizdata.com.cn/api/apickeditor.php";
    config.allowedContent = true;
};
