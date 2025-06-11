function attachments_import(theme_type, install_type) {
    "use strict";
    var pb, result_box, uploaded_attachments;

    if (install_type === 'auto_install') {
        pb = jQuery('#progressBar1');
        result_box = jQuery('#theme_setup_result');
    }
    else if (install_type === 'manual_install') {
        pb = jQuery('#progressBar2');
        result_box = jQuery('#manual_theme_install_result');
    }
    jQuery(pb).find('.progress_count').text(aiL10n.import_start);

    jQuery.ajax({
        url: localajax['url'],
        method: 'POST',
        data: 'action=setup_theme&theme_type=' + theme_type + '&op=get_uploaded_attachments',
        success: function (data) {

            data = data.substr(0, data.length - 1);
            data = jQuery.parseJSON(data);
            if (data['empty'] === 'no') uploaded_attachments = data.content;
            else uploaded_attachments = false;

            jQuery.ajax({
                url: localajax['url'],
                method: 'POST',
                data: 'action=setup_theme&theme_type=' + theme_type + '&op=get_xml_file',
                success: function (file) {

                    file = file.substr(0, file.length - 1);

                    jQuery('#attachment-importer-progressbar').remove();
                    jQuery('#attachment-importer-progresslabel').remove();
                    jQuery('#attachment-importer-output').remove();
                    jQuery(result_box).empty().append('<div id="attachment-importer-progressbar"><div id="attachment-importer-progresslabel"></div></div><div id="attachment-importer-output"></div>');
                    jQuery(pb).css({'display': 'none'});

                    var progressBar = jQuery("#attachment-importer-progressbar"),
                        progressLabel = jQuery("#attachment-importer-progresslabel"),
                        parser = new DOMParser(),
                        xml = parser.parseFromString(file, "text/xml"),
                        url = [],
                        title = [],
                        link = [],
                        pubDate = [],
                        creator = [],
                        guid = [],
                        postID = [],
                        postDate = [],
                        postDateGMT = [],
                        commentStatus = [],
                        pingStatus = [],
                        postName = [],
                        status = [],
                        postParent = [],
                        menuOrder = [],
                        postType = [],
                        postPassword = [],
                        isSticky = [],
                        divOutput = jQuery('#attachment-importer-output'),
                        author1 = jQuery("input[name='author']:checked").val(),
                        author2 = jQuery("select[name='user']").val(),
                        delay = ( jQuery("input[name='delay']").is(':checked') ? 1500 : 0 );

                    jQuery(function () {
                        progressBar.progressbar({
                            value: false
                        });
                        progressLabel.text(aiL10n.parsing);
                    });

                    jQuery(xml).find('item').each(function () {

                        var xml_post_type = jQuery(this).find('wp\\:post_type, post_type').text();

                        if (xml_post_type == 'attachment') { // We're only looking for image attachments.
                            url.push(jQuery(this).find('wp\\:attachment_url, attachment_url').text());
                            title.push(jQuery(this).find('title').text());
                            link.push(jQuery(this).find('link').text());
                            pubDate.push(jQuery(this).find('pubDate').text());
                            creator.push(jQuery(this).find('dc\\:creator, creator').text());
                            guid.push(jQuery(this).find('guid').text());
                            postID.push(jQuery(this).find('wp\\:post_id, post_id').text());
                            postDate.push(jQuery(this).find('wp\\:post_date, post_date').text());
                            postDateGMT.push(jQuery(this).find('wp\\:post_date_gmt, post_date_gmt').text());
                            commentStatus.push(jQuery(this).find('wp\\:comment_status, comment_status').text());
                            pingStatus.push(jQuery(this).find('wp\\:ping_status, ping_status').text());
                            postName.push(jQuery(this).find('wp\\:post_name, post_name').text());
                            status.push(jQuery(this).find('wp\\:status, status').text());
                            postParent.push(jQuery(this).find('wp\\:post_parent, post_parent').text());
                            menuOrder.push(jQuery(this).find('wp\\:menu_order, menu_order').text());
                            postType.push(xml_post_type);
                            postPassword.push(jQuery(this).find('wp\\:post_password, post_password').text());
                            isSticky.push(jQuery(this).find('wp\\:is_sticky, is_sticky').text());
                        }
                    });

                    var pbMax = postType.length;

                    jQuery(function () {
                        progressBar.progressbar({
                            value: 0,
                            max: postType.length,
                            complete: function () {
                                progressLabel.text(aiL10n.done);
                                // busy =  false;
                                // if (install_type == 'auto_install') {
                                // 	setTimeout(function () {
                                // 		alert(aiL10n.done);
                                // 		window.location.assign("admin.php?page=_options&atiframebuilderfrom=atiframebuilder-welcome")
                                // 	}, 1500);
                                // }
                                //seo_kill_cookies();
                            }
                        });
                    });

                    // Define counter variable outside the import attachments function
                    // to keep track of the failed attachments to re-import them.
                    var failedAttachments = 0;

                    function import_attachments(i) {
                        //alert(jQuery.inArray(postID[i], uploaded_attachments));
                        // uploaded_attachments = uploaded_attachments;
                        progressBar.progressbar();
                        if (jQuery.inArray(postID[i], uploaded_attachments) !== -1) {
                            next_image(i);
                            progressLabel.text(aiL10n.importing + '" ' + title[i] + '". ' + aiL10n.progress + progressBar.progressbar("value") + "/" + pbMax);
                        }
                        else {
                            progressLabel.text(aiL10n.importing + '" ' + title[i] + '". ' + aiL10n.progress + progressBar.progressbar("value") + "/" + pbMax);
                            jQuery.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'setup_theme',
                                    op: 'attachment_upload',
                                    //_ajax_nonce: aiSecurity.nonce,
                                    author1: author1,
                                    author2: author2,
                                    url: url[i],
                                    title: title[i],
                                    link: link[i],
                                    pubDate: pubDate[i],
                                    creator: creator[i],
                                    guid: guid[i],
                                    post_id: postID[i],
                                    post_date: postDate[i],
                                    post_date_gmt: postDateGMT[i],
                                    comment_status: commentStatus[i],
                                    ping_status: pingStatus[i],
                                    post_name: postName[i],
                                    status: status[i],
                                    post_parent: postParent[i],
                                    menu_order: menuOrder[i],
                                    post_type: postType[i],
                                    post_password: postPassword[i],
                                    is_sticky: isSticky[i]
                                }
                            })
                                .done(function (data, status, xhr) {
                                    // Parse the response.
                                    var obj = jQuery.parseJSON(data);

                                    // If error shows the server did not respond,
                                    // try the upload again, to a max of 3 tries.
                                    if (obj.message == "Remote server did not respond" && failedAttachments < 5) {
                                        failedAttachments++;
                                        progressLabel.text(aiL10n.retrying + '"' + title[i] + '". ' + aiL10n.progress + progressBar.progressbar("value") + "/" + pbMax);
                                        setTimeout(function () {
                                            import_attachments(i);
                                        }, 5000);
                                    }

                                    // If a non-fatal error occurs, note it and move on.
                                    else if (obj.type == "error" && !obj.fatal) {
                                        if (!obj.text.match(/St.\sLouis\sBlues|2014-slider-mobile-behavior|Indie-Pop|alico2055|Storm-darck/i)) {
                                            jQuery('<p>' + obj.text + '</p>').appendTo(divOutput);
                                        }
                                        next_image(i);

                                    }

                                    // If a fatal error occurs, stop the program and print the error to the browser.
                                    else if (obj.fatal) {
                                        progressBar.progressbar("value", pbMax);
                                        progressLabel.text(aiL10n.fatalUpload);
                                        jQuery('<div class="' + obj.type + '">' + obj.text + '</div>').appendTo(divOutput);
                                        //seo_kill_cookies();
                                        return false;
                                    }

                                    else { // Moving on.
                                        next_image(i);
                                    }
                                })
                                .fail(function (xhr, status, error) {
                                    console.error(status);
                                    console.error(error);
                                    //progressBar.progressbar( "value", pbMax );
                                    //progressLabel.text( aiL10n.pbAjaxFail );
                                    //jQuery( '<div class="error">' + aiL10n.ajaxFail +'</div>' ).appendTo( divOutput );
                                    //seo_kill_cookies();
                                    if (xhr.status == 500) {
                                        setTimeout(function () {
                                            i--;
                                            next_image(i);
                                        }, 7000);
                                    }
                                });
                        }
                    }

                    function next_image(i) {
                        // Increment the internal counter and progress bar.
                        i++;
                        progressBar.progressbar();
                        progressBar.progressbar("value", progressBar.progressbar("value") + 1);
                        failedAttachments = 0;

                        // If every thing is normal, but we still have posts to process,
                        // then continue with the program.
                        if (postType[i]) {
                            setTimeout(function () {
                                import_attachments(i)
                            }, delay);
                        }

                        // Getting this far means there are no more attachments, so stop the program.
                        else {
                            return false;
                        }
                    }

                    if (postType[0]) {
                        import_attachments(0);
                    } else {
                        progressBar.progressbar("value", pbMax);
                        progressLabel.text(aiL10n.pbAjaxFail);
                        jQuery('<div class="error">' + aiL10n.noAttachments + '</div>').appendTo(divOutput);
                        //seo_kill_cookies();
                    }

                    //}

                },
                error: function () {
                    jQuery(pb).empty().text(aiL10n.import_start_failed).css({'display': 'block'});
                    //seo_kill_cookies();
                },
                fail: function () {
                    jQuery(pb).empty().text(aiL10n.import_start_failed).css({'display': 'block'});
                    //seo_kill_cookies();
                }

            });

        }

    });

}
