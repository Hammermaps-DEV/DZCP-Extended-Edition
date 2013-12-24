/**
 * PluginAutoLoad: Load your plugins on html DOM without javascript code.
 * http://marcbuils.github.com/jquery.pluginautoload/
 * 
 * Par Marc Buils ( marc.buils@marcbuils.fr )
 * Sous licence LGPL v3 (http://www.gnu.org/licenses/lgpl-3.0.txt)
 * 
 * v0.1.0:
 * First release
 * 
 * v0.2.0:
 * Add Ajax automatic script import 
 * Add a better parmeters parser
 * Add exemples
 * 
 * v0.3.0 - 24/10/2012: 
 * Add asynchron lazyload system for script download 
 * Replace class usation by data-dataloader parameter for DOM loaded
 * Remove console warning for old browsers
 */
(function($){
	$.pluginautoload_options = {
		autoload:		true,
		autoimport:		true,
		libs_dir:		'js/',
		filename: 		function( p_type ){
			return this.libs_dir + 'jquery.' + p_type.toLowerCase() + '.js';
		}
	};
	
	// lazyload script
	// ref: http://www.nczonline.net/blog/2009/07/28/the-best-way-to-load-external-javascript/
	var _loadScript = function(url, callback){
	
	    var script = document.createElement("script")
	    script.type = "text/javascript";
	
	    if (script.readyState){  //IE
	        script.onreadystatechange = function(){
	            if (script.readyState == "loaded" ||
	                    script.readyState == "complete"){
	                script.onreadystatechange = null;
	                callback();
	            }
	        };
	    } else {  //Others
	        script.onload = function(){
	            callback();
	        };
	    }
	
	    script.src = url;
	    document.getElementsByTagName("head")[0].appendChild(script);
	};

	// Script loaded list
	var _loaded = {};

	$.fn.pluginautoload = function( p_options ){
		var _options = $.extend({}, $.pluginautoload_options, p_options);
		
		this.find('[data-jquery-type]:not([data-pluginautoload=1])').each( function(){
			var $_this = $(this);
			var _params = ( typeof($_this.attr('data-jquery-params')) == "undefined" ? [] : JSON.parse($_this.attr('data-jquery-params')) );
			var _type = $_this.attr('data-jquery-type');
			
			if ( _options.autoimport && typeof($.fn[_type]) == "undefined" ){
				if ( typeof( _loaded[ _type ] ) == "undefined" ){
					var _deferred = $.Deferred();
					_loaded[ _type ] = _deferred.promise();
					_loadScript( _options.filename( _type ), function(){
						if ( typeof($.fn[_type]) == "undefined" ){
							if ( typeof(console) != "undefined" ){
								console.error( "File %s loaded but plugin $.fn.%s not found", _options.filename( _type ), _type );
							}
						} else {
							_deferred.resolve( );
						}
						_loaded[ _type ] = undefined;
					});
				}
				_loaded[ _type ].done(function(){
					$_this[_type].apply($_this, _params);
				});
			}else{
				if ( typeof($.fn[_type]) != "undefined" ){
					$_this[_type].apply($_this, _params);
				} else {
					if ( typeof(console) != "undefined" ){
						console.error("Plugin $.fn.%s not found", _type );
					}
				}
			}
			$_this.attr('data-pluginautoload', 1);
		});
		
		return this;
	};

	$(function(){
		if ($.pluginautoload_options.autoload){
			$('body').pluginautoload();
		}
	});
})(jQuery);
