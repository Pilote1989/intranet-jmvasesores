jQuery(document).ready(function() {
    // Requerido por qTip para mostrar el fondo obscuro
    jQuery("<div id='qtip-bg'>").css({
        position: "absolute",
        left: 0,
        height: jQuery(document).height(),
        width: "100%",
        opacity: 0.7, 
        backgroundColor: 'black',
        zIndex: 5000  
    }).appendTo(document.body).hide();

    // Inicializamos todo la página
    init(jQuery(document));

    // El componente dropdown
    /*
    jQuery(".uix-dropdown").live("click", function(type, event) {
        var menu = jQuery(this).find("div");
        if (menu.is(":visible")) {
            menu.hide();
        }
        else {
            var position = jQuery(this).position();
            if (jQuery(this).is("div")) {
                menu.css("left", position.left - (menu.width() - jQuery(this).width()));
            }
            else {
                menu.css("left", position.left);
            }
            menu.css("top", position.top + jQuery(this).height());
            menu.show();
        }
        event.stopPropagation();
    });
    */
});

/**
 * init  Define el comportamiento de los actuales y futuros elementos
 */
function init(parent) {
    parent.find(".link-modal").each(function() {   
        jQuery(this).qtip({
            content: { 
                extra: {
                    url: jQuery(this).attr("href")
                }
            },
            position: {
                target: jQuery(document.body),
                corner: "center"
            },
            show: {
                when: "click",
                solo: true
            },
            hide: false,
            style: {
                width: { max: 900 },
                padding: "10px",
                border: {
                    width: 9,
                    radius: 9,
                    color: "#666"
                },
                name: "light",
                classes: {
                    tooltip: "qtip qtip-modal"
                }
            },
            api: {
                beforeShow: function() {
                    this.updateContent("<div class='uix-seccion'><div class='uix-seccion-contenido'>Cargando...</div></div>");
                    this.loadContent(this.options.content.extra.url);
                },

                onContentUpdate: function() {
                    init(jQuery(".qtip"));

                    jQuery(".qtip .boton-cerrar").click(function() {
                        jQuery(".qtip-active").qtip("api").hide(); 
                    });
                }
            }
        }).click(function() { return false; });
    });

    parent.find("[x-tooltip]").each(function() {
        var bg = "#365d86";
        var fg = "#ffffff";
        if (jQuery(this).attr("x-tooltip-fg")) {
            fg = jQuery(this).attr("x-tooltip-fg");
        }
        if (jQuery(this).attr("x-tooltip-bg")) {
            bg = jQuery(this).attr("x-tooltip-bg");
        }

        var orientacion = ["topMiddle", "bottomMiddle"];
        if (jQuery(this).attr("x-tooltip-orientacion") == "arriba") {
            orientacion = ["bottomMiddle", "topMiddle"]; 
        }

        jQuery(this).qtip({
            content: jQuery(this).attr("x-tooltip"),
            position: {
                corner: {
                    tooltip: orientacion[0],
                    target: orientacion[1]
                }
            },
            style: {
                tip: {
                    corner: orientacion[0],
                    size: {
                        x: 8,
                        y: 4
                    }
                    
                },
                border: {
                    width: 2,
                    radius: 3,
                    color: bg
                },
                fontSize: "11px",
                padding: 0,
                color: fg,
                backgroundColor: bg
            }
        });
    });

    parent.find(".uix-dropdown").each(function() {
        var dropdown = jQuery(this);

        var orientacion = ["topLeft", "bottomLeft"];
        if (jQuery(this).is("div")) {
            var orientacion = ["topRight", "bottomRight"];
        }

        jQuery(this).qtip({
            content: jQuery(this).find("div"),
            position: {
                corner: {
                    tooltip: orientacion[0],
                    target: orientacion[1]
                }
            },
			show: {
                when: "click",
                solo: true
            },
			hide: {
				fixed: true,
				when: "unfocus"
			},
            style: {
                border: 0,
                fontSize: "11px",
                padding: 5,
                color: "#000",
                backgroundColor: "#FFF",
				name: "light",
				width: {
					min: 0,
					max: 400
				},
                classes: {
                    tooltip: "qtip qtip-dropdown"
                }
            },
            api: {
                beforeShow: function() {
                    this.elements.content.empty();
                    this.updateContent(dropdown.find("div"));

                    var qtip = this;
                    this.elements.content.find(".tooltip-dynamic").click(function() {
                        jQuery(".qtip-active").addClass("qtip-tooltip-dynamic");
                        qtip.loadContent(jQuery(this).attr("href"));
                    });
                },

                onContentUpdate: function() {
                    /*
                    init(jQuery(".qtip-active"));

                    if (jQuery(".qtip-active").hasClass("qtip-tooltip-dynamic")) {
                        this.options.hide.when.event = "inactive";
                    }

                    jQuery(".qtip-active .boton-cerrar").click(function() {
                        jQuery(".qtip-active").qtip("hide");
                    });
                    */
                }
            }
        });
    });

    parent.find(".tooltip-dynamic").each(function() {
        if (jQuery(this).parents(".uix-dropdown").length > 0) {
            return jQuery(this).click(function() { 
                return false; 
            });
        }

        var orientacion = ["topLeft", "bottomLeft"];
        if (jQuery(this).is("a")) {
            var orientacion = ["topRight", "bottomRight"];
        }

        var content = { extra: { } };
        if (jQuery(this).attr("x-url")) {
            content["extra"]["url"] = jQuery(this).attr("x-url"); 
        }
        else {
            content["extra"]["text"] = jQuery(this).find("> div");
        }

        jQuery(this).qtip({
            content: content,
            position: {
                corner: {
                    tooltip: orientacion[0],
                    target: orientacion[1]
                }
            },
			show: {
                when: "click",
                solo: true
            },
			hide: false,
            style: {
                border: 0,
                fontSize: "11px",
                padding: 5,
                color: "#000",
                backgroundColor: "#FFF",
				name: "light",
				width: {
					min: 0,
					max: 400
				},
                classes: {
                    tooltip: "qtip qtip-tooltip-dynamic"
                }
            },
            api: {
                beforeShow: function() {
                    this.updateContent("<div class='uix-seccion'><div class='uix-seccion-contenido'>Cargando...</div></div>");
                    //this.elements.content.empty();
                    if (this.options.content.extra.url) {
                        this.loadContent(this.options.content.extra.url);
                    }
                    else {
                        this.updateContent(this.options.content.extra.text);
                    }
                },

                onContentUpdate: function() {
                    init(jQuery(".qtip"));

                    jQuery(".qtip .boton-cerrar").click(function() {
                        jQuery(".qtip-active").qtip("api").hide(); 
                    });
                }
            }
        });
    });

    parent.find(".datepicker").datepicker({ 
        dateFormat: 'dd/mm/yy', 
        changeYear: true,
        dayNamesMin: ['D', 'L', 'Ma', 'Mi', 'J', 'V', 'S'],
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        firstDay: 1,
        yearRange: '1900:2100'
    });				  

//    autoload(parent);
}

/**
 * autoload  Carga una página utilizando AJAX y todos sus hijos
 */
function autoload(parent, callback) {
    jQuery(parent).find(".seccion-autoload").each(function() {
        loading(true);
        jQuery(this).load(jQuery(this).attr("x-autoload-url"), function() {
            init(jQuery(this));
            autoload(jQuery(this));
            callback ? callback() : true;
            loading(false);
        });
    });
}

/**
 * loading  Muestra o esconde una animación de cargando
 */
function loading(visible) {
    var div = jQuery("#uix-autoload-loading");
    if (visible) {
        jQuery("#uix-autoload-loading").css({
            top: jQuery(window).height()/2-div.height()/2,
            left: jQuery(window).width()/2-div.width()/2,
        }).fadeIn("fast");
    }
    else {
        jQuery("#uix-autoload-loading").fadeOut("fast");
    }
}
