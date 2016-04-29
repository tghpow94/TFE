<html lang="en"><head>
    <meta charset="utf-8">
    <title>jQuery UI Autocomplete - Combobox</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style>
        .custom-combobox {
            position: relative;
            display: inline-block;
        }
        .custom-combobox-toggle {
            position: absolute;
            top: 0;
            bottom: 0;
            margin-left: -1px;
            padding: 0;
        }
        .custom-combobox-input {
            margin: 0;
            padding: 5px 10px;
        }
    </style>
    <script>
        (function( $ ) {
            $.widget( "custom.combobox", {
                _create: function() {
                    this.wrapper = $( "<span>" )
                        .addClass( "custom-combobox" )
                        .insertAfter( this.element );

                    this.element.hide();
                    this._createAutocomplete();
                    this._createShowAllButton();
                },

                _createAutocomplete: function() {
                    var selected = this.element.children( ":selected" ),
                        value = selected.val() ? selected.text() : "";

                    this.input = $( "<input>" )
                        .appendTo( this.wrapper )
                        .val( value )
                        .attr( "title", "" )
                        .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                        .autocomplete({
                            delay: 0,
                            minLength: 0,
                            source: $.proxy( this, "_source" )
                        })
                        .tooltip({
                            tooltipClass: "ui-state-highlight"
                        });

                    this._on( this.input, {
                        autocompleteselect: function( event, ui ) {
                            ui.item.option.selected = true;
                            this._trigger( "select", event, {
                                item: ui.item.option
                            });
                        },

                        autocompletechange: "_removeIfInvalid"
                    });
                },

                _createShowAllButton: function() {
                    var input = this.input,
                        wasOpen = false;

                    $( "<a>" )
                        .attr( "tabIndex", -1 )
                        .attr( "title", "Show All Items" )
                        .tooltip()
                        .appendTo( this.wrapper )
                        .button({
                            icons: {
                                primary: "ui-icon-triangle-1-s"
                            },
                            text: false
                        })
                        .removeClass( "ui-corner-all" )
                        .addClass( "custom-combobox-toggle ui-corner-right" )
                        .mousedown(function() {
                            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
                        })
                        .click(function() {
                            input.focus();

                            // Close if already visible
                            if ( wasOpen ) {
                                return;
                            }

                            // Pass empty string as value to search for, displaying all results
                            input.autocomplete( "search", "" );
                        });
                },

                _source: function( request, response ) {
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    response( this.element.children( "option" ).map(function() {
                        var text = $( this ).text();
                        if ( this.value && ( !request.term || matcher.test(text) ) )
                            return {
                                label: text,
                                value: text,
                                option: this
                            };
                    }) );
                },

                _removeIfInvalid: function( event, ui ) {

                    // Selected an item, nothing to do
                    if ( ui.item ) {
                        return;
                    }

                    // Search for a match (case-insensitive)
                    var value = this.input.val(),
                        valueLowerCase = value.toLowerCase(),
                        valid = false;
                    this.element.children( "option" ).each(function() {
                        if ( $( this ).text().toLowerCase() === valueLowerCase ) {
                            this.selected = valid = true;
                            return false;
                        }
                    });

                    // Found a match, nothing to do
                    if ( valid ) {
                        return;
                    }

                    // Remove invalid value
                    this.input
                        .val( "" )
                        .attr( "title", value + " didn't match any item" )
                        .tooltip( "open" );
                    this.element.val( "" );
                    this._delay(function() {
                        this.input.tooltip( "close" ).attr( "title", "" );
                    }, 2500 );
                    this.input.autocomplete( "instance" ).term = "";
                },

                _destroy: function() {
                    this.wrapper.remove();
                    this.element.show();
                }
            });
        })( jQuery );

        $(function() {
            $( "#combobox" ).combobox();
            $( "#toggle" ).click(function() {
                $( "#combobox" ).toggle();
            });
        });
    </script>
</head>
<body>

<div class="ui-widget">
    <label>Your preferred programming language: </label>
    <select id="combobox" style="display: none;">
        <option value="">Select one...</option>
        <option value="ActionScript">ActionScript</option>
        <option value="AppleScript">AppleScript</option>
        <option value="Asp">Asp</option>
        <option value="BASIC">BASIC</option>
        <option value="C">C</option>
        <option value="C++">C++</option>
        <option value="Clojure">Clojure</option>
        <option value="COBOL">COBOL</option>
        <option value="ColdFusion">ColdFusion</option>
        <option value="Erlang">Erlang</option>
        <option value="Fortran">Fortran</option>
        <option value="Groovy">Groovy</option>
        <option value="Haskell">Haskell</option>
        <option value="Java">Java</option>
        <option value="JavaScript">JavaScript</option>
        <option value="Lisp">Lisp</option>
        <option value="Perl">Perl</option>
        <option value="PHP">PHP</option>
        <option value="Python">Python</option>
        <option value="Ruby">Ruby</option>
        <option value="Scala">Scala</option>
        <option value="Scheme">Scheme</option>
    </select>
    <span class="custom-combobox">
        <input title="" class="custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left ui-autocomplete-input" autocomplete="off">
        <a tabindex="-1" title="Show All Items" class="ui-button ui-widget ui-state-default ui-button-icon-only custom-combobox-toggle ui-corner-right" role="button">
            <span class="ui-button-icon-primary ui-icon ui-icon-triangle-1-s"></span>
            <span class="ui-button-text"></span>
        </a>
    </span>
</div>
<button id="toggle">Show underlying select</button>




<ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-1" tabindex="0" style="display: none; width: 176px; top: 33px; left: 237.094px;">
    <li class="ui-menu-item" id="ui-id-3" tabindex="-1">ActionScript</li>
    <li class="ui-menu-item" id="ui-id-4" tabindex="-1">AppleScript</li>
    <li class="ui-menu-item" id="ui-id-5" tabindex="-1">Asp</li>
    <li class="ui-menu-item" id="ui-id-6" tabindex="-1">BASIC</li>
    <li class="ui-menu-item" id="ui-id-7" tabindex="-1">C</li>
    <li class="ui-menu-item" id="ui-id-8" tabindex="-1">C++</li>
    <li class="ui-menu-item" id="ui-id-9" tabindex="-1">Clojure</li>
    <li class="ui-menu-item" id="ui-id-10" tabindex="-1">COBOL</li>
    <li class="ui-menu-item" id="ui-id-11" tabindex="-1">ColdFusion</li>
    <li class="ui-menu-item" id="ui-id-12" tabindex="-1">Erlang</li>
    <li class="ui-menu-item" id="ui-id-13" tabindex="-1">Fortran</li>
    <li class="ui-menu-item" id="ui-id-14" tabindex="-1">Groovy</li>
    <li class="ui-menu-item" id="ui-id-15" tabindex="-1">Haskell</li>
    <li class="ui-menu-item" id="ui-id-16" tabindex="-1">Java</li>
    <li class="ui-menu-item" id="ui-id-17" tabindex="-1">JavaScript</li>
    <li class="ui-menu-item" id="ui-id-18" tabindex="-1">Lisp</li>
    <li class="ui-menu-item" id="ui-id-19" tabindex="-1">Perl</li>
    <li class="ui-menu-item" id="ui-id-20" tabindex="-1">PHP</li>
    <li class="ui-menu-item" id="ui-id-21" tabindex="-1">Python</li>
    <li class="ui-menu-item" id="ui-id-22" tabindex="-1">Ruby</li>
    <li class="ui-menu-item" id="ui-id-23" tabindex="-1">Scala</li>
    <li class="ui-menu-item" id="ui-id-24" tabindex="-1">Scheme</li>
</ul>
<span role="status" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible">
    <div style="display: none;">22 results are available, use up and down arrow keys to navigate.</div>
    <div style="display: none;">ActionScript</div>
    <div style="display: none;">AppleScript</div>
    <div style="display: none;">Asp</div>
    <div style="display: none;">BASIC</div>
    <div>C</div>
</span>
<div role="log" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible"></div>
<div role="log" aria-live="assertive" aria-relevant="additions" class="ui-helper-hidden-accessible">
    <div>Show All Items</div>
</div>
</body>
</html>