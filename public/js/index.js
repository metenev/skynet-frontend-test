(function (window, document, $) {

    // Common functions

    function outputErrors(errors) {
        var message = '';
        for (var i = 0; i < errors.length; i++) {
            message += "\n" + errors[i].message;
        }
        alert('Error: ' + message.substr(1));
    }

    // Views

    var views = [];
    var $viewContainer = null;
    var currentView = null;

    function createView(html, id) {
        var $elem;

        if (typeof html == 'string') {
            $elem = $('<div class="view container">' + html + '</div>');
        } else {
            $elem = html;
        }

        if (!id) id = 'view-' + Math.random() * 99999999999;

        $elem.attr('id', id);

        var view = {
            id: id,
            $elem: $elem,
        };

        views.push(view);

        return view;
    }

    function addView(view) {
        currentView.$elem
            .data('state', 'deep')
            .removeClass('visible')
            .addClass('hidden');

        $viewContainer.append(view.$elem);

        setTimeout(function() {
            view.$elem.addClass('visible');
        }, 100);

        currentView = view;
    }

    function removeView() {
        var view = views.pop();
        var viewIndex = views.length;

        currentView = viewIndex > 0 ? views[ viewIndex - 1 ] : null;

        view.$elem
            .data('state', 'dispose')
            .addClass('hidden back');

        if (currentView) {
            currentView.$elem
                .removeClass('hidden')
                .addClass('visible');
        }
        
        return view;
    }

    function reduceViewsUntil(viewIndex) {
        var i = views.length;

        while (i > (viewIndex + 2)) {
            var viewToDestroy = views[ i - 1 ];
            viewToDestroy.$elem.remove();
            i--;
        }

        views.splice(viewIndex + 2, views.length - (viewIndex + 2));

        removeView(); // And remove single next view with animation
    }

    function loadPage(url, callback) {
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.status != 'ok') {
                    outputErrors(data.errors);
                } else {
                    // Ok, execute callback

                    callback(data);
                }
            },
            error: function (jqXHR, statusText, errorThrown) {
                if (jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                    outputErrors(jqXHR.responseJSON.errors);
                } else {
                    outputErrors([ { message: 'Unknown error (' + statusText + ')' } ]);
                }
            }
        });
    }

    // Initialization

    $(document).ready(function () {

        $viewContainer = $('#views');
        currentView = createView($viewContainer.find('.view'));

        /**
         * Back buttons
         */
        $(document.documentElement).on('click', 'header .button-back', function (evt) {
            if (window.history.length <= 0) return;
            if (views.length <= 1) {
                // We have no previously loaded states. Just let the user
                // load the link
                return;
            }

            evt.preventDefault();

            window.history.back();

            // removeView();

            return false;
        });

        /**
         * List item body buttons
         */
        $(document.documentElement).on('click', '.list-item .body-button', function (evt) {
            evt.preventDefault();

            var url = $(evt.target).closest('a').attr('href');

            loadPage(url, function (data) {
                var view = createView(data.content);

                state = { url: url, viewId: view.id };
                history.pushState(state, null, url);

                addView(view);
            });

            return false;
        });

        $(window).on('popstate', function (evt) {
            evt = evt.originalEvent;

            if (!evt.state) {
                // This is probably our index page, the first initial history item

                reduceViewsUntil(0);
            }
            else {
                var state = evt.state;

                // Find an existing view

                var viewIndex = -1;

                var i = views.length;
                while (i--) {
                    var viewItem = views[i];

                    if (viewItem.id == state.viewId) {
                        viewIndex = i;
                        break;
                    }
                }

                if (viewIndex >= 0) {
                    // We've found our view

                    reduceViewsUntil(viewIndex);
                }
                else {
                    // No views found - maybe they wasn't loaded
                    // Just load the page state points to

                    loadPage(state.url, function (data) {
                        var view = createView(data.content, state.viewId);
        
                        addView(view);
                    });
                }
            }
        });

        /**
         * Views destruction
         */
        $(document.documentElement).on('transitionend webkitTransitionEnd oTransitionEnd', function (evt) {
            var $view = $(evt.target);
            if ($view.data('state') == 'dispose') {
                $(evt.target).remove();
            }
        });

    });

})(window, document, jQuery);