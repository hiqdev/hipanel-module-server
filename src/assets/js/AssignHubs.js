(function ($, window, document, undefined) {
	var pluginName = "assignHubs",
		defaults = {
			countModels: 1,
			attributes: ['net', 'net2', 'kvm', 'pdu', 'pdu2', 'rack', 'ipmi'],
			formAttribute: '',
		};

	function Plugin(element, options) {
		this.element = $(element);
		this.settings = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.attributes = this.settings.attributes;
		this.formAttribute = this.settings.formAttribute;
		this.init();
	}

	Plugin.prototype = {
		init: function () {
			if (this.settings.countModels > 1) {
				this.getKeysArray().forEach(el => this.registerItems(el))
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
					this.getKeysArray().forEach(el => {
						const value = $attributeBlock.children().last().clone();
						const iterable = $(`#${this.formAttribute}-${el}-${attribute}_id`);
						iterable.empty();
						iterable.append(value);
					});
					$(event.target).addClass('hidden');
				});
			});
		},
		getKeysArray: function () {
			const _countModels = this.settings.countModels;
			return [...Array(_countModels).keys()];
		}
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
