jQuery(document).on('ready', function () {
    "use strict";
    var busy = false, clientica_install_by_hand,
        clientica_settings = {'emitter': null, 'receiver': null, 'start_theme_type': null};

    jQuery(document).tooltip();
    if (jQuery('#import_attachments_checkbox').is(":checked")) jQuery('#import_attachment_data').css({'display': 'block'});
    else jQuery('#import_attachment_data').css({'display': 'none'});


    function clientica_preg_match(str) {
        var out = '', result, regexp = /___([^_]+)___/gm;
        while (result = regexp.exec(str)) {
            out += result[1];
        }
        return out;
    }

    function setCookie(name, value, options) {
        options = options || {};

        var expires = options.expires;

        if (typeof expires === "number" && expires) {
            var d = new Date();
            d.setTime(d.getTime() + expires * 1000);
            expires = options.expires = d;
        }
        if (expires && expires.toUTCString) {
            options.expires = expires.toUTCString();
        }

        value = encodeURIComponent(value);

        var updatedCookie = name + "=" + value;

        for (var propName in options) {
            updatedCookie += "; " + propName;
            var propValue = options[propName];
            if (propValue !== true) {
                updatedCookie += "=" + propValue;
            }
        }

        document.cookie = updatedCookie;
    }

    function getCookie(name) {
        var matches = document.cookie.match(new RegExp(
            "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));
        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    function deleteCookie(name) {
        setCookie(name, "", {
            expires: -1
        })
    }

    function implode(glue, pieces) {	// Join array elements with a string
        return ( ( pieces instanceof Array ) ? pieces.join(glue) : pieces );
    }

    function explode(delimiter, string) {	// Split a string by string

        var emptyArray = {0: ''};

        if (arguments.length !== 2
            || typeof arguments[0] === 'undefined'
            || typeof arguments[1] === 'undefined') {
            return null;
        }

        if (delimiter === ''
            || delimiter === false
            || delimiter === null) {
            return false;
        }

        if (typeof delimiter === 'function'
            || typeof delimiter === 'object'
            || typeof string === 'function'
            || typeof string === 'object') {
            return emptyArray;
        }

        if (delimiter === true) {
            delimiter = '1';
        }

        return string.toString().split(delimiter.toString());
    }

    function clientica_kill_cookies() {
        deleteCookie('clientica_install_tasks');
        deleteCookie('clientica_install_by_hand');
        deleteCookie('clientica_theme_type');
    }

    jQuery('#theme_setup_submit').on('click', function (e) {
        var theme_type,
            sets = {
                'install_plugins': {
                    'start': 'Installation of plugins.',
                    'progress': {'start': 0, 'end': 20, 'step': 2, 'duration': 360}
                },
                'activate_plugins': {
                    'start': 'Activation of plugins.',
                    'progress': {'start': 20, 'end': 34, 'step': 1, 'duration': 60}
                },
                'technical_refresh': {
                    'start': 'Page reaload ... ',
                    'progress': {'start': 34, 'end': 40, 'duration': 1}
                },
                'import_attachments': {
                    'start': 'Installation of images.',
                    'progress': {'start': 0, 'end': 100, 'duration': 470}
                },
                'import_sample_data': {
                    'start': 'Installation of demo data.',
                    'progress': {'start': 0, 'end': 100, 'duration': 470}
                },
                // 'set_sliders': {
                //     'start': 'Installation of sliders of Revolution Slider. It can take a few minutes.',
                //     'progress': {'start': 0, 'end': 100, 'duration': 100}
                // },
                'set_elementor_active_kit_id': {
                    'start': 'Installation of Elementor\'s site settings. It can take a few minutes.',
                    'progress': {'start': 0, 'end': 100, 'duration': 100}
                },
                'clear_cache': {
                    'start': 'Generate CSS. It can take a few minutes.',
                    'progress': {'start': 0, 'end': 100, 'duration': 100}
                },
                // 'import_customizer_data': {
                //     'start': 'Customizer data loading.',
                //     'progress': {'start': 0, 'end': 100, 'duration': 10}
                // },
                'import_caldera_forms': {
                    'start': 'Installation of Caldera Forms.',
                    'progress': {'start': 0, 'end': 100, 'duration': 470}
                },
                'import_widgets': {
                    'start': 'Installation of widgets.',
                    'progress': {'start': 0, 'end': 100, 'duration': 40}
                }
            },
            i = 0, tm, j = 0, op, done = false,
            box = jQuery('#theme_setup_result'),
            el, k, tasks = [], task, current_install_by_hand, attacment_import = '', settings = '';

        e.preventDefault();

        if (busy) return;

        current_install_by_hand = getCookie('clientica_install_by_hand');

        if (current_install_by_hand != 0) {
            jQuery("input[id*='_checkbox']").each(function (i, el) {
                if (jQuery(el).is(":checked")) {
                    tasks.push(el.name);
                }
            });
        }
        else {
            tasks = explode(',', getCookie('clientica_install_tasks'));
        }
        setCookie('clientica_install_tasks', implode(',', tasks), {'expires': 60 * 15});

        theme_type = getCookie('clientica_theme_type');
        if (!theme_type || theme_type === undefined) theme_type = jQuery('#theme_type_select').val();
        setCookie('clientica_theme_type', theme_type, {'expires': 60 * 15});
        jQuery('.welcome_install input').each(function (i, el) {
            if (jQuery(el).attr('checked') === 'checked') {
                settings += '&' + jQuery(el).attr('name') + '=1';
            }
            else
                settings += '&' + jQuery(el).attr('name') + '=0';
            //console.log(settings);
        });

        if (tasks.length == 0) return;

        tm = setInterval(function () {
            if (jQuery("#attachment-importer-progressbar").text() == aiL10n.done) {
                tasks.shift();
                busy = false;
                jQuery("#attachment-importer-progressbar").hide().text('')
            }
            if (!busy) {
                busy = true;
                if (current_install_by_hand == 0) {
                    if (tasks[0] && tasks[0] == 'technical_refresh') tasks.shift();
                    setCookie('clientica_install_by_hand', 1);
                }
                if (!done && tasks[0] && tasks[0] != 'technical_refresh') {
                    console.log(tasks[0]);
                    if (tasks[0] && tasks[0] == 'import_attachments') {
                        attachments_import(theme_type, 'auto_install');
                        // if(jQuery("#attachment-importer-progressbar").text() == aiL10n.done){
                        //    tasks.shift();
                        //    busy = false;
                        // }
                        // done = true;
                    } else {

                        jQuery('#progressBar1').css({'display': 'block;'});
                        k = 0;
                        progress(sets[tasks[0]].progress.start, jQuery('#progressBar1'));
                        jQuery(box).html(jQuery(box).html() + '<div class="setup_item_start">' + sets[tasks[0]].start + '</div>');
                        jQuery.ajax({
                            url: localajax['url'],
                            method: 'POST',
                            data: 'action=setup_theme&theme_type=' + theme_type + '&op=' + tasks[0] + settings,
                            timeout: 1000 * 60 * 60 * 2,
                            success: function (result) {
                                if ('install_plugins' === tasks[0] || 'activate_plugins' === tasks[0]) {

                                    var plugins = JSON.parse(result.substr(0, result.length - 1));
                                    var task = tasks[0].substring(0, tasks[0].length - 1);
                                    console.log(tasks[0]);
                                    var set = sets[tasks[0]];

                                    function plugin_action(i, task) {
                                        if (i < plugins.length) {
                                            var value = plugins[i];
                                            value.action = 'setup_theme';
                                            value.op = task;
                                            value.nonce = welcome_params.nonce;
                                            jQuery.ajax({
                                                url: localajax['url'],
                                                method: 'POST',
                                                data: value,
                                                success: function (res) {
                                                    jQuery(box).html(jQuery(box).html() + '<div class="setup_item_start">' + value.name + '</div>');
                                                    console.log(task);
                                                    // progress(parseInt(set.progress.start + set.progress.step*(i+1)), jQuery('#progressBar1'));
                                                    progress(parseInt((100 / plugins.length) * (i + 1)), jQuery('#progressBar1'));
                                                    plugin_action(i + 1, task);
                                                },
                                                error: function (jqXHR, textStatus, errorThrown) {
                                                    jQuery.ajax({
                                                        url: localajax['url'],
                                                        method: 'POST',
                                                        data: 'action=setup_theme&op=abort'
                                                    });
                                                    busy = done = true;
                                                    clientica_kill_cookies();
                                                    clearInterval(tm);
                                                    alert("Something went wrong. Ask your hosting provider to check error logs.");
                                                }
                                            });
                                        } else {
                                            busy = false;
                                        }
                                    };
                                    plugin_action(0, task);
                                } else {
                                    busy = false;
                                }
                                var n, text, messages, msg = '';
                                messages = result.substr(0, result.length - 1);
                                text = clientica_preg_match(messages);
                                if (text) {
                                    jQuery(box).html(jQuery(box).html() + '<br>' + text);
                                }
                                if (tasks[0] && false == busy) {
                                    progress(sets[tasks[0]].progress.end, jQuery('#progressBar1'));
                                }
                                if (result.match(/There are problems with WP_Import classes/) || result.match(/Fatal\serror/) ||
                                    tasks.length == 0) {
                                    busy = done = true;
                                    jQuery.ajax({
                                        url: localajax['url'],
                                        method: 'POST',
                                        data: 'action=setup_theme&op=abort'
                                    });
                                    clientica_kill_cookies();
                                    clearInterval(tm);
                                }
                                tasks.shift();
                                setCookie('clientica_install_tasks', implode(',', tasks, {'expires': 60 * 15}));
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                jQuery.ajax({
                                    url: localajax['url'],
                                    method: 'POST',
                                    data: 'action=setup_theme&op=abort'
                                });
                                busy = done = true;
                                clientica_kill_cookies();
                                clearInterval(tm);
                                alert("Something went wrong. Ask your hosting provider to check error logs.");
                            }
                        });
                    }

                    settings = '';
                }
                else if (tasks[0] && tasks[0] == 'technical_refresh') {
                    tasks.shift();
                    setCookie('clientica_install_tasks', implode(',', tasks, {'expires': 60 * 15}));
                    setCookie('clientica_install_by_hand', 0);
                    location.reload();
                }
            }
            if (tasks.length == 0) done = true;
            if (!done && sets[tasks[0]] && false == busy) progress(parseInt((sets[tasks[0]].progress.end - sets[tasks[0]].progress.start) * k / sets[tasks[0]].progress.duration + sets[tasks[0]].progress.start), jQuery('#progressBar1'));
            j++;
            k++;
            if (j > 1800 || done) {
                progress(100, jQuery('#progressBar1'));
                clientica_kill_cookies();
                busy = false;
                clearInterval(tm);
                setTimeout(function () {
                    alert(aiL10n.done);
                    // window.location.assign("admin.php?page=_clientica_theme_options&atiframebuilderfrom=atiframebuilder-welcome")
                }, 1500);
            }
        }, 1000);
        return false;

    });

    function progress(percent, $element) {
        var progressBarWidth = percent * $element.width() / 100;
        $element.find('div.progress_bar').animate({width: progressBarWidth}, 100);
        $element.find('div.progress_count').html(percent + "% ");
    }

    jQuery('.bulk_install_item').on('change', function (e) {
        var option = true;
        if (jQuery(e.target).is(":checked")) option = true;
        else option = false;
        jQuery(e.target).parents("div[id$='_summary']").find('.install_steps input').attr("checked", option);
    });


    jQuery('.manual_install').on('click', function (e) {
        var theme_type,
            op = e.target.id.match(/manual_(.+)/),
            tm, done = false,
            sets = {
                'install_plugins': {
                    'start': 'Installation of plugins.',
                    'progress': {'start': 10, 'step': 10, 'end': 100, 'duration': 360}
                },
                'activate_plugins': {
                    'start': 'Activation of plugins.',
                    'progress': {'start': 10, 'step': 10, 'end': 100, 'duration': 360}
                },
                'technical_refresh': {
                    'start': 'Page reaload ... ',
                    'progress': {'start': 0, 'end': 100, 'duration': 1}
                },
                'import_attachments': {
                    'start': 'Installation of images.',
                    'progress': {'start': 0, 'end': 100, 'duration': 470}
                },
                'import_sample_data': {
                    'start': 'Installation of demo data.',
                    'progress': {'start': 0, 'end': 100, 'duration': 470}
                },
                // 'set_sliders': {
                //     'start': 'Installation of sliders of Revolution Slider. It can take a few minutes.',
                //     'progress': {'start': 65, 'end': 75, 'duration': 100}
                // },
                'set_elementor_active_kit_id': {
                    'start': 'Installation of Elementor\'s site settings. It can take a few minutes.',
                    'progress': {'start': 75, 'end': 80, 'duration': 100}
                },
                'clear_cache': {
                    'start': 'Generate CSS. It can take a few minutes.',
                    'progress': {'start': 80, 'end': 85, 'duration': 100}
                },
                // 'import_customizer_data': {
                //     'start': 'Customizer data loading.',
                //     'progress': {'start': 0, 'end': 100, 'duration': 10}
                // },
                'import_caldera_forms': {
                    'start': 'Installation of Caldera Forms.',
                    'progress': {'start': 85, 'end': 90, 'duration': 470}
                },
                'import_widgets': {
                    'start': 'Installation of widgets.',
                    'progress': {'start': 0, 'end': 100, 'duration': 40}
                }
            }, k = 1, box = jQuery('#manual_theme_install_result');

        e.preventDefault();
        op = op[1];

        theme_type = getCookie('clientica_theme_type');
        if (!theme_type || theme_type === undefined) theme_type = jQuery('#manual_theme_type_select').val();
        setCookie('clientica_theme_type', theme_type, {'expires': 60 * 15});

        jQuery(box).html(jQuery(box).html() + '<div class="setup_item_start">' + sets[op].start + '</div>');

        tm = setInterval(function () {
            if (done) {
                clearInterval(tm);
                progress(100, jQuery('#progressBar2'));
            }
            else {
                progress(parseInt((sets[op].progress.end - sets[op].progress.start) * k / sets[op].progress.duration + sets[op].progress.start), jQuery('#progressBar2'));
                k++;
            }
        }, 1000);

        jQuery('#progressBar2').css({'display': 'block'});
        if (jQuery("#attachment-importer-progressbar").text() === aiL10n.done) {
            jQuery("#attachment-importer-progressbar").hide().text('')
        }

        if (op && op !== 'import_attachments') {
            jQuery.ajax({
                url: localajax['url'],
                method: 'POST',
                data: 'action=setup_theme&theme_type=' + theme_type + '&op=' + op,
                timeout: 1000 * 60 * 60 * 2,
                success: function (result) {
                    if ('install_plugins' === op || 'activate_plugins' === op) {
                        clearInterval(tm);
                        var plugins = JSON.parse(result.substr(0, result.length - 1));
                        var task = op.substring(0, op.length - 1);
                        console.log(op);
                        var set = sets[op];
                        console.log(plugins.length);
                        console.log(set);

                        function man_plugin_action(i, task) {
                            console.log(i);
                            if (i < plugins.length) {
                                var value = plugins[i];
                                value.action = 'setup_theme';
                                value.op = task;
                                value.nonce = welcome_params.nonce;
                                jQuery.ajax({
                                    url: localajax['url'],
                                    method: 'POST',
                                    data: value,
                                    success: function (res) {
                                        jQuery(box).html(jQuery(box).html() + '<div class="setup_item_start">' + value.name + '</div>');
                                        console.log(task);
                                        // progress(parseInt(set.progress.start + set.progress.step*(i)), jQuery('#progressBar2'));
                                        progress(parseInt((100 / plugins.length) * (i + 1)), jQuery('#progressBar2'));
                                        console.log(value);
                                        man_plugin_action(i + 1, task);
                                    },
                                    error: function (jqXHR, textStatus, errorThrown) {
                                        jQuery.ajax({
                                            url: localajax['url'],
                                            method: 'POST',
                                            data: 'action=setup_theme&op=abort'
                                        });
                                        busy = done = true;
                                        clientica_kill_cookies();
                                        clearInterval(tm);
                                        alert("Something went wrong. Ask your hosting provider to check error logs.");
                                    }
                                });
                            } else {
                                done = true;
                            }
                        }

                        man_plugin_action(0, task);
                    } else {
                        done = true;
                    }
                    var text, messages;
                    messages = result.substr(0, result.length - 1);
                    jQuery.ajax({
                        url: localajax['url'],
                        method: 'POST',
                        data: 'action=setup_theme&op=abort'
                    });
                    text = clientica_preg_match(messages);
                    if (text.length > 0) {
                        jQuery(box).html(jQuery(box).html() + '<br>' + text);
                    }
                    deleteCookie('clientica_theme_type');
                    done = true;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    jQuery.ajax({
                        url: localajax['url'],
                        method: 'POST',
                        data: 'action=setup_theme&op=abort'
                    });
                    deleteCookie('clientica_theme_type');
                    alert("Something went wrong. Ask your hosting provider to check error logs.");
                }
            });
        }
        else if (op && op === 'import_attachments') {
            clearInterval(tm);
            attachments_import(theme_type, 'manual_install');
        }
    });

    jQuery('#theme_type_dialog').on('click', function (e) {
        e.preventDefault();
        if (e.target.id == 'theme_type_dialog_continue') {
            jQuery(clientica_settings.receiver).attr('value', jQuery(clientica_settings.emitter).val());
        }
        else if (e.target.id == 'theme_type_dialog_stop') {
            jQuery(clientica_settings.emitter).attr('value', clientica_settings.start_theme_type);
        }
        jQuery('#theme_type_dialog').css({'display': 'none'});
    });

    jQuery("select[id*='theme_type_select']").on('focus', function (e) {
        clientica_settings.start_theme_type = jQuery(e.target).val();
    });

    jQuery("select[id*='theme_type_select']").on('change', function (e) {
        var theme_type, target_theme_type, parent;

        clientica_settings.emitter = jQuery(e.target);

        if (e.target.id == 'theme_type_select') clientica_settings.receiver = jQuery('#manual_theme_type_select');
        else clientica_settings.receiver = jQuery('#theme_type_select');

        theme_type = getCookie('clientica_theme_type');
        if (!theme_type || theme_type == 'undefined') theme_type = jQuery(e.target).val();
        else {
            target_theme_type = jQuery(e.target).val();
            if (target_theme_type != theme_type) {
                parent = jQuery(e.target).parents('.tab-pane');
                jQuery('#theme_type_dialog').css({
                    'display': 'block',
                    'left': (jQuery(document).width() - jQuery('#theme_type_dialog').width()) / 2 + 'px',
                    'top': (jQuery(window).height() - jQuery('#theme_type_dialog').outerHeight()) / 2 + jQuery(document).scrollTop() + 'px'
                });
                return;
            }
        }

        jQuery(clientica_settings.receiver).attr('value', jQuery(clientica_settings.emitter).val());

    });

    clientica_install_by_hand = getCookie('clientica_install_by_hand');
    if (clientica_install_by_hand && clientica_install_by_hand == 0) {
        jQuery('#theme_setup_submit').trigger('click');
    }

    if (window.location.href.match(/superadmin=1/)) jQuery('.install_steps').css({'display': 'block'});

});
