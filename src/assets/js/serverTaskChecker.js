(function ($, window, document, undefined) {
    var pluginName = "serverTaskChecker",
        defaults = {
            'queryInterval': 30 * 1000,
            'id': undefined,
            'pjaxSelector': undefined,
            'ajax': {}
        };

    function Plugin(element, options) {
        this.element = $(element);
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.intervalId = null;
        this.init();
    }

    Plugin.prototype = {
        init: function () {
            this.bindListeners();
        },
        bindListeners: function () {
            var _this = this;
            var interval = this.settings.queryInterval;

            this.intervalId = Visibility.every(interval, 5 * interval, function () {
                if (window.Pace !== undefined) {
                    Pace.ignore(_this.query.bind(_this));
                } else {
                    _this.query();
                }
            });
        },
        query: function () {
            $.ajax($.extend({}, {
                type: 'GET',
                dataType: 'json',
                data: this.prepareQueryData(),
                success: this.processQuery.bind(this)
            }, this.settings.ajax));
        },
        prepareQueryData: function () {
            return {id: this.settings.id};
        },
        processQuery: function (data) {
            if (data.result) {
                Visibility.stop(this.intervalId);
                $.pjax.reload(this.settings.pjaxSelector);
            }
        }
    };

    $.fn[pluginName] = function (options) {
        this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
            }
        });
        return this;
    };
})(jQuery, window, document);
