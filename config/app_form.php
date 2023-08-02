<?php

return [
	'inputContainer' => '<div class="mb-3 input {{type}}{{required}}">{{content}}</div>',
	'label' => '<label for="{{name}}" class="form-label">{{text}}</label>',
	'input' => '<div class="col-sm-10"><input class="form-control" type="{{type}}" name="{{name}}"{{attrs}} /></div>',
	'textarea' => '<div class="col-sm-10"><textarea class="form-control" name="{{name}}"{{attrs}}>{{value}}</textarea></div>',
	'select' => '<div class="col-sm-10"><select class="form-select" name="{{name}}"{{attrs}}>{{content}}</select></div>',
	'checkbox' => '<div class="col-sm-10"><input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}></div>',
	'file' => '<div class="col-sm-10"><input class="form-control" type="file" name="{{name}}"{{attrs}}></div>',
	'button' => '<button {{attrs}} class="btn btn-primary">{{text}}</button>',
	'selectMultiple' => '<div class="col-sm-10"><select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select></div>'
];