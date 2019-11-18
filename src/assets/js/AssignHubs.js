(function ($, window, document, undefined) {
	var pluginName = "assignHubs",
		defaults = {
			countModels: []
		};

	function Plugin(element, options) {
		this.element = $(element);
		this.settings = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.attributes = ['net', 'net2', 'kvm', 'pdu', 'pdu2', 'rack', 'ipmi'];
		this.formAttribute = 'assignhubsform';
		this.init();
	}

	Plugin.prototype = {
		init: function () {
			const _countModels = this.settings.countModels;
			if (_countModels > 1) {
				[...Array(_countModels).keys()].forEach(el => this.registerItems(el))
			}
		},
		registerItems: function (index) {
			const indexAttribute = this.formAttribute + '-' + index;
			this.attributes.forEach(attribute => {
				const itemAttribute = indexAttribute + '-' + attribute + '_id';
				const $attributeBlock = $(`#${itemAttribute}`);
				const $applyAllLink = $(`.apply-all-${index}-${attribute}`);

				$attributeBlock.on('change', event => {
					$applyAllLink.removeClass('hidden');
				});

				$applyAllLink.on('click', event => {
					const _countModels = this.settings.countModels;
					[...Array(_countModels).keys()].forEach(el => {
						const value = $attributeBlock.children().last().clone();
						const iterable = $(`#${this.formAttribute}-${el}-${attribute}_id`);
						iterable.empty();
						iterable.append(value);
					});
					$(event.target).addClass('hidden');
				});
			});
		},
	};

	$.fn[pluginName] = function (options) {
		this.each(function () {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new Plugin(this, options));
			}
		});
		return this;
	};
})(jQuery, window, document);
