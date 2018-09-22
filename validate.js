Validate =
{
	patterns : {
		"money" : /^\d+\.?\d*$/,
		"float_strict" : /^-{0,1}(\d+)$|^-{0,1}(\d+[\.,]\d+)$/,			// строгий паттерн для флоата
		"float" : /^-{0,1}(\d+)$|^-{0,1}(\d+\.?\d*)$/,
		"int" : /^-?\d+$/
		},


	isInt: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(this.patterns['int'], arguments[key])) return false;

		return true;
	},

	isUnsignedInt: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.isInt(arguments[key]) || arguments[key] < 0 ) return false;

		return true;
	},

	isIcq: function()
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^\d{4,9}$/, arguments[key])) return false;

		return true;
	},

	isPositive: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (arguments[key] < 0) return false;

		return true;
	},

	isId : function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[1-9]\d{0,}$/, arguments[key])) return false;

		return true;
	},

	isFloat : function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(this.patterns['float_strict'], arguments[key])) return false;

		return true;
	},

	isUnsignedFloat : function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.isFloat(arguments[key]) || arguments[key] < 0) return false;

		return true;
	},

	isEmail: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^([\w\-\+]+(?:\.[\w\-\+]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7})$/, arguments[key])) return false;

		return true;
	},

	isPhone: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^\d{10,17}$/, arguments[key])) return false;

		return true;
	},

	isLogin: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[a-z0-9\_\-\.]{3,32}$/i, arguments[key])) return false;

		return true;
	},

	isSkype: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[a-z0-9\_\-\.]{3,32}$/i, arguments[key])) return false;

		return true;
	},

	isSubdomain: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			// if (!this.match(/^[a-zA-Z0-9][a-zA-Z0-9\-]{1,16}[a-zA-Z0-9]$/i, arguments[key])) return false;
			if(arguments[key].length < 3 || arguments[key].length > 16 || !this.match(/^([a-zA-Z0-9]+(?:\-(?:[a-zA-Z0-9])+)*(?:[a-zA-Z0-9])*)/i, arguments[key])) return false;

		return true;
	},

	isName: function(val)
	{
		return (val.length != 0);
	},

	isPassword: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^.{6,32}$/, arguments[key])) return false;

		return true;
	},

	isMd5: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[\da-f]{32}$/i, arguments[key])) return false;

		return true;
	},

	isColor: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^#[\da-fA-F]{6}$/i, arguments[key])) return false;

		return true;
	},

	isDate: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^(0?[1-9]|[1-2][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(19|20|21|22)\d{2}$/, arguments[key])) return false;

		return true;
	},

	isTime: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9])(:0?[0-9]|[1-5][0-9])?$/, arguments[key])) return false;

		return true;
	},

	isDateTime: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^(0?[1-9]|[1-2][0-9]|3[01])\.(0?[1-9]|1[0-2])\.(19|20|21|22)\d{2} (0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1-5][0-9])(:0?[0-9]|[1-5][0-9])?$/, arguments[key])) return false;

		return true;
	},

	match: function(pattern, data)
	{
		return pattern.test(data);
	},

	// Не дает ввести в input символы, не подпадающие под регулярку
	input: function(object, pattern, e)
	{
		var returnKeys = [35,36,37,38,39,40,8,46,97,98,99,100,101,102,103,104,105],
			val = object.value, keyCode = (e.which) ? e.which : e.keyCode; //Код нажатой клавиши
		if (returnKeys.join().search(keyCode) != -1) return true;
		var key = (keyCode == 190 || keyCode == 110) ? '.' : String.fromCharCode(keyCode); //Получаем символ по его коду
		val = val.slice(0, object.selectionStart) + key + val.slice(object.selectionStart+1, val.length + 1);
		return (this.match(this.patterns[pattern], val) || val == "");
	},

	isControllerAction: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[a-z][a-z0-9]*$/i, arguments[key])) return false;

		return true;
	},

	isSystemName: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[a-z0-9_]{1,64}$/i, arguments[key])) return false;

		return true;
	},

	isSmsSenderName: function(val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^[a-zA-Z0-9]+([ ]*[a-zA-Z0-9]+)*$/i, arguments[key]) || arguments[key].length > 11) return false;

		return true;
	},

	isUrl: function (val)
	{
		for (var key = 0; key < arguments.length; key++)
			if (!this.match(/^((https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?)?(\/?([a-z0-9;:@\/&%=+\$_.-]\.?)+)*\/?(\?[a-zA-Z+&\$_.-][a-z0-9A-Z;:@\/&%=\{\}+\$_.-]*)?(#[a-zA-Z_.-\/0-9]{0,1}[a-zA-Z0-9+\$_.-:]*)?$/, arguments[key])) return false;
		return true;
	}

};

