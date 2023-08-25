<?php

return [
	'inputContainer' => '<div class="mb-3 input {{type}}{{required}}">{{content}}</div>',
	'input' => '<input class="form-control" type="{{type}}" name="{{name}}"{{attrs}}/>',
	'label' => '<label class="form-label"{{attrs}}>{{text}}</label>',
	'select' => '<select class="form-select" name="{{name}}"{{attrs}}>{{content}}</select>',
];