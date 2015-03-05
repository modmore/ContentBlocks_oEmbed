// Wrap your stuff in this module pattern for dependency injection
(function ($, ContentBlocks) {
    // Add your custom input to the fieldTypes object as a function
    // The dom variable contains the injected field (from the template)
    // and the data variable contains field information, properties etc.
    ContentBlocks.fieldTypes.oembedinput = function(dom, data) {
        var input = {
            embedData: false,
            urlRegex: /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-]*)?\??(?:[\-\+=&;%@\.\w]*)#?(?:[\.\!\/\\\w]*))?)/g // http://blog.mattheworiordan.com/post/13174566389/url-regular-expression-for-links-with-or-without-the
        };

        // Do something when the input is being loaded
        input.init = function() {
            if (data.html) {
                input.embedData = data;

                dom.find('.embed').html(input.embedData.html);
                dom.addClass('preview');
            }


            dom.find('.link').on('change', function () {
                var url = $(this).val();
                setTimeout(function() {
                    if (dom.find('.link').val() == url) {
                        // Make sure we have a URL
                        if (input.urlRegex.test(url) || input.urlRegex.test('http://' + url)) {
                            input.loadEmbed(url);
                        }
                    }
                }, 250)
            }).on('keyup', function() {
                var fld = $(this);
                setTimeout(function() {
                    fld.trigger('change');
                }, 100);
            });

            // Delete embed
            dom.find('.contentblocks-field-unset-embed').on('click', function() {
                dom.removeClass('preview');
                dom.find('.embed').html('');
                input.embedData = false;
            });
        };

        input.loadEmbed = function(url) {
            dom.addClass('contentblocks-field-loading');
            $.ajax({
                dataType: 'json',
                url: oEmbedInputConnectorUrl,
                data: {
                    action: 'loadembed',
                    url: url
                },
                context: this,
                headers: {
                    'modAuth': MODx.siteId
                },
                success: function(result) {
                    dom.removeClass('contentblocks-field-loading');
                    dom.find('.link').val('');
                    if (!result.success) {
                        if (result.message && result.message.length > 0) {
                            alert(result.message);
                        }
                    }
                    else {
                        dom.find('.embed').html(result.object.html);
                        dom.addClass('preview');
                        input.embedData = result.object;
                    }
                    dom.removeClass('contentblocks-field-loading');
                }
            });
        };

        // Get the data from this input, it has to be a simple object.
        input.getData = function() {
            if (input.embedData && input.embedData.html) {
                return input.embedData
            }
            else {
                return {
                    html: '',
                    title: '',
                    provider_name: '',
                    url: ''
                }
            }
        };

        // Always return the input variable.
        return input;
    }
})(vcJquery, ContentBlocks);
