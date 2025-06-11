(function ($) {
    'use strict';

    if ('function' !== typeof wp.customize && 'object' !== typeof clienticabuilderCustomizer) {
        return;
    }

    clienticabuilderCustomizer.controlWrapper = '.clienticabuilder-customize-control';
    clienticabuilderCustomizer.fields = clienticabuilderCustomizer.fields || {};

    wp.customize.bind('ready', function () {
        wp.customize.section.each(function (section) {
            section.expanded.bind(function (isExpanding) {
                if (isExpanding) {
                    clienticabuilderCustomizer.init(section);
                }
            });
        });
    });

    $.clienticabuilder = $.clienticabuilder || {};

    $.clienticabuilder.initControls = function () {
        $(clienticabuilderCustomizer.section).find(clienticabuilderCustomizer.controlWrapper + ':visible').each(
            function () {
                let type = $(this).attr('data-type');
                if ('undefined' !== typeof clienticabuilderCustomizer.fields[type]) {
                    clienticabuilderCustomizer.fields[type].init($(this));
                }
            }
        );
    };

    $.clienticabuilder.getControls = function (selector) {
        return $(clienticabuilderCustomizer.section).find(selector);
    };

    clienticabuilderCustomizer.init = function (section) {
        clienticabuilderCustomizer.section = section.contentContainer;
        $.clienticabuilder.initControls();
    };

    clienticabuilderCustomizer.fields.switch = {
        init: function (el) {
            this.wrapper = '.switch-control';
            let parent = el.parents(clienticabuilderCustomizer.controlWrapper);
            if (parent.is(':hidden')) {
                return;
            }
            let control = $(el).find(this.wrapper);
            control.find('label').click(
                function () {
                    let label = $(this);
                    if (label.hasClass('selected')) {
                        return;
                    }
                    $('label', control).toggleClass('selected');
                    $('.checkbox-input', control).val(label.data('status')).trigger('change');
                }
            );
        }
    };

    clienticabuilderCustomizer.fields.color_rgba = {
        init: function(el){
            this.wrapper = '.color_rgba-control';
            let parent = el.parents(clienticabuilderCustomizer.controlWrapper);

            if (parent.is(':hidden')) {
                return;
            }
        }
    };

    clienticabuilderCustomizer.fields.image_select = {
        init: function (el) {
            this.wrapper = '.image_select-control';
            let parent = el.parents(clienticabuilderCustomizer.controlWrapper);
            if (parent.is(':hidden')) {
                return;
            }
            el.find('li').click(
                function () {
                    let li = $(this);
                    if (li.hasClass('selected')) {
                        return;
                    }
                    $('li', el).removeClass('selected');
                    li.addClass('selected');
                    $('.text-input', el).val(li.data('value')).trigger('change');
                }
            );
        }
    };

    clienticabuilderCustomizer.fields.advanced_background = {
        init: function (el) {
            this.l10n = clienticabuilderCustomizer.fields.advanced_background.l10n || {};
            this.imageInputUrlSelector = '.advanced_background-image-url';
            let parent = el.parents(clienticabuilderCustomizer.controlWrapper);
            if (parent.is(':hidden')) {
                return;
            }
            let imageInputUrl = el.find(this.imageInputUrlSelector),
                settingsSelector = '.bg-settings-input';
            el.settings = {};
            el.find('.change-image').click(
                (el) => {
                    this.changeImage(el)
                }
            );

            el.find('.remove-image').click(
                (el) => {
                    this.removeImage(el)
                }
            );

            el.find(settingsSelector).change(
                (input) => {
                    $(el).find(settingsSelector).each((i, input) => {
                        let key = $( input ).data('setting');
                            el.settings[key] = $( input ).val();
                    });
                    el.find('input[data-customize-setting-link]').val(JSON.stringify(el.settings)).trigger('change');
                }
            );

            imageInputUrl.change(
                () => {
                    el.find('.background-image-view img').attr('src', imageInputUrl.val());
                }
            );
        },
        changeImage: function (e) {
            e = $(e.target);
            let parent = e.parent();
            let uploader = wp.media({
                title: this.l10n.frame_title,
                button: {text: this.l10n.select},
                multiple: false
            });
            uploader.on('select', () => {
                let attachment = uploader.state().get('selection').first().toJSON();
                attachment.url = attachment.url.replace(document.location.origin, '');
                this.processImage(parent, attachment);
            });
            uploader.open();
        },
        removeImage: function (e) {
            let p = $(e.target).parent();
            this.processImage(p, null);
        },
        processImage: function (p, attachment) {
            let imageInputUrl = p.find(this.imageInputUrlSelector);
            if (null === attachment) {
                imageInputUrl.val('');
            } else {
                imageInputUrl.val(attachment.url);
            }
            imageInputUrl.trigger('change');
        },
    };

})(jQuery);