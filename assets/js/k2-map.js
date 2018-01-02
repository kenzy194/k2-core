/**
 * @name K2Map for Google Maps V3
 * @team: K2Team
 * @version 1.0.0 [Nov 01, 2017]
 * @author KP-K2Team
 */
;(function ($, window, document, undefined) {
    "use strict";

    function K2_Map(element, options) {

        /**
         * Current options set by the caller including defaults.
         */
        this.options = $.extend({}, K2_Map.defaults, options);

        /**
         * Plugin element.
         */
        this.$element = $(element);
        this._handler.buildMap(this);
    }

    K2_Map.defaults = {
        zoom: 13,
        center: {lat: 53.4759219, lng: -2.242631699999947},
        styles:[
            {
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#bdbdbd"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dadada"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#c9c9c9"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            }
        ],
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: true,
        rotateControl: true,
        fullscreenControl: true
    };

    /**
     * Kp-carousel register event handlers
     * @type {{buildLayout: Kp._handler.buildLayout}}
     * @private
     */
    // K2Map.prototype.registerEventHandlers = function () {
    //
    // };

    /**
     * K2Map handle
     * @type {{buildMap: K2Map._handler.buildLayout}}
     * @private
     */
    K2_Map.prototype._handler = {
        /**
         * kp-carousel build layout
         * @param _this
         */
        buildMap: function (_this) {
            var element_id = _this.$element.attr('id');
            var _options = {
                zoom: _this.options.zoom,
                center: _this.options.center,
                styles: _this.options.styles,
                zoomControl: _this.options.zoomControl,
                mapTypeControl: _this.options.mapTypeControl,
                scaleControl: _this.options.scaleControl,
                streetViewControl: _this.options.streetViewControl,
                rotateControl: _this.options.rotateControl,
                fullscreenControl: _this.options.fullscreenControl
            };
            var k2_map = new google.maps.Map(document.getElementById(element_id),_options);
        }

    };
    /**
     *  The jQuery Plugin for the Kp Carousel
     * @param options
     * @returns {*}
     */
    $.fn.K2Map = function (options) {
        var args = Array.prototype.slice.call(arguments, 1); // for a possible method call
        var _this = this;
        return _this.each(function (i, _element) { // loop each DOM element involved
            var element = $(_element);
            data = new K2_Map(element, typeof options === 'object' && options);
            element.data('k2map', data);
            var data = element.data('k2map');
            if (typeof options === 'string' && options.charAt(0) !== '_') {
                data[options].apply(data, args);
            }
        });
    }
})(window.Zepto || window.jQuery, window, document);