/*!
 * jQuery Validation Plugin v1.19.0
 *
 * https://jqueryvalidation.org/
 *
 * Copyright (c) 2018 Jörn Zaefferer
 * Released under the MIT license
 */
(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "./jquery.validate"], factory );
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory( require( "jquery" ) );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

	( function() {
	
		function stripHtml( value ) {
	
			// Remove html tags and space chars
			return value.replace( /<.[^<>]*?>/g, " " ).replace( /&nbsp;|&#160;/gi, " " )
	
			// Remove punctuation
			.replace( /[.(),;:!?%#$'\"_+=\/\-“”’]*/g, "" );
		}
		
		/**
		 * Validator load only selected aditional methods. 
		 * @description jQuery validate ignores the hidden element, and since the Chosen plugin adds visibility:hidden attribute to the select, try:
		 * $.validator.setDefaults({ ignore: ":hidden:not(select)" }) //for all select
		 * OR 
		 * $.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" }) /for all select having class .chosen-select
		 * Add this line just before validate() function. It works fine for me.
		 *
		 * #ref1: 
		 * https://stackoverflow.com/questions/11232310/how-can-i-use-jquery-validation-with-the-chosen-plugin
		 * 
		 */
		//#ref1
		$.validator.setDefaults({ ignore: ":hidden:not(select)" })
		//$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" }) 
	
		$.validator.addMethod(
		    "regex",
		    function(value, element, regexp) {
		        var re = new RegExp(regexp);
		        return this.optional(element) || re.test(value);
		    },
		    "Ingrese correctamente el identificador de expediente."
		);
	
		$.validator.addMethod( "maxWords", function( value, element, params ) {
			return this.optional( element ) || stripHtml( value ).match( /\b\w+\b/g ).length <= params;
		}, $.validator.format( "Please enter {0} words or less." ) );
	
		$.validator.addMethod( "minWords", function( value, element, params ) {
			return this.optional( element ) || stripHtml( value ).match( /\b\w+\b/g ).length >= params;
		}, $.validator.format( "Please enter at least {0} words." ) );
	
		$.validator.addMethod( "rangeWords", function( value, element, params ) {
			var valueStripped = stripHtml( value ),
				regex = /\b\w+\b/g;
			return this.optional( element ) || valueStripped.match( regex ).length >= params[ 0 ] && valueStripped.match( regex ).length <= params[ 1 ];
		}, $.validator.format( "Please enter between {0} and {1} words." ) );
	
		/**
		 * Return true, if the value is a valid date, also making this formal check dd/mm/yyyy.
		 *
		 * @example $.validator.methods.date("01/01/1900")
		 * @result true
		 *
		 * @example $.validator.methods.date("01/13/1990")
		 * @result false
		 *
		 * @example $.validator.methods.date("01.01.1900")
		 * @result false
		 *
		 * @example <input name="pippo" class="{dateITA:true}" />
		 * @desc Declares an optional input element whose value must be a valid date.
		 *
		 * @name $.validator.methods.dateITA
		 * @type Boolean
		 * @cat Plugins/Validate/Methods
		 */
		$.validator.addMethod( "dateITA", function( value, element ) {
			var check = false,
				re = /^\d{1,2}\/\d{1,2}\/\d{4}$/,
				adata, gg, mm, aaaa, xdata;
			if ( re.test( value ) ) {
				adata = value.split( "/" );
				gg = parseInt( adata[ 0 ], 10 );
				mm = parseInt( adata[ 1 ], 10 );
				aaaa = parseInt( adata[ 2 ], 10 );
				xdata = new Date( Date.UTC( aaaa, mm - 1, gg, 12, 0, 0, 0 ) );
				if ( ( xdata.getUTCFullYear() === aaaa ) && ( xdata.getUTCMonth() === mm - 1 ) && ( xdata.getUTCDate() === gg ) ) {
					check = true;
				} else {
					check = false;
				}
			} else {
				check = false;
			}
			return this.optional( element ) || check;
		}, $.validator.messages.date );
	
		$.validator.addMethod( "integer", function( value, element ) {
			return this.optional( element ) || /^-?\d+$/.test( value );
		}, "Un número no decimal positivo o negativo por favor" );
	
		$.validator.addMethod( "time", function( value, element ) {
			return this.optional( element ) || /^([01]\d|2[0-3]|[0-9])(:[0-5]\d){1,2}$/.test( value );
		}, "Ingrese una hora válida, entre las 00:00 y las 23:59" );
	
		$.validator.addMethod( "time12h", function( value, element ) {
			return this.optional( element ) || /^((0?[1-9]|1[012])(:[0-5]\d){1,2}(\ ?[AP]M))$/i.test( value );
		}, "Ingrese una hora válida en formato de 12 horas am / pm" );
		
	}() );
	return $;	
}));