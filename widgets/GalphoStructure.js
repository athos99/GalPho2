/* galphoStrucuture version 1.0.0
 * Copyright (c) 2013 Michel Bobillier aka Athos99 www.athos99.com
 *
 * GNU General Public License, version 3 (GPL-3.0) http://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Description:
 *   Usage : $("#id").galphoStrucuture();
 *           $("#id").galphoStrucuture( {filter:'asc'});
 *           $("ul").galphoStrucuture( {filter:'asc',child:'ul'});
 */
(function ($) {
    "use strict";
    // protected functions

    // public methods
    var methods = {
        /**
         * Initialize the plugin, set the options
         *   Options :
         *       order : 'asc', 'desc', 'rand' or 'none'. Default : 'asc'
         *       filter : string. Default: ''
         *       filterExact : true, false,0,1 :  filter selection begin at pos 0 or is include in item : defaut false
         *       child : tag_name of child list, 'option','div','li'. Default : 'option'
         *       childValue : function for returning the element value.
         *                    For select the 1st column of a table,  set for parameter a anonymous function
         *                    function(){return $(this.children()[0]).text();}, [0] is for the column number 0
         *       submitAll : true, false,0,1 :  if true and if list is in form, select all child when form is submit
         *
         *  Remarque:
         *   After un ajax load of children list, you need to init this list $(#list).listorder('init');
         */
        init:function (options) {
            return this.each(function () {
                var datas = {};
                var $this = $(this);

                datas.settings = $.extend({
                    order:'asc',
                    child:'option'
                }, options);

                $this.data('galphoStructure', datas);
                methods.bind.call($this);
            });

        },

        bind:function () {
            return this.each(function () {
                var $root = $(this);
                $(this).find('.galpho-name span').on("click.galphoStructure", function (event) {
//                    event.preventDefault();
//                    event.stopPropagation();
                    var $this = $(this);
                    var $div = $this.closest('.galpho-line');
                    if ($div.hasClass('galpho-open')) {
                        $div.children('.galpho-child').addClass('galpho-close').removeClass('galpho-open');
                        $div.addClass('galpho-close').removeClass('galpho-open');
                    } else {
                        $div.children('.galpho-child').addClass('galpho-open').removeClass('galpho-close');
                        $div.addClass('galpho-open').removeClass('galpho-close');
                    }
                });
                $(this).find('.galpho-name').on("xmouseenter.galphoStructure", function () {
                    var $this = $(this);
                    var $div = $this.closest('.galpho-line');
                    $root.find('.galpho-open').addClass('galpho-close').removeClass('galpho-open');
                    var x = $div.parentsUntil($root, '.galpho-close');
                    $div.parentsUntil($root, '.galpho-close').addClass('galpho-open').removeClass('galpho-close');

                    $div.children('.galpho-child').addClass('galpho-open').removeClass('galpho-close');
                    $div.addClass('galpho-open').removeClass('galpho-close');
                });
            });
        },
        /**
         * Set and refresh the display list filter value
         *
         *  usage : $('#list1').listorder('filter', filter,filterExact);
         *
         *  filter : match string
         *  filterExact: (optional) indication if filter selection begin at pos 0 or is include in item.
         *                value : true, false, '1','0',1,0
         */
        filter:function (filter, filterExact) {
            return this.each(function () {
                var $list = $(this);
                var datas = $list.data('listorder');
                if (filterExact !== undefined) {
                    if ($.isNumeric(filterExact)) {
                        filterExact = parseInt(filterExact, 10);
                    }
                    datas.settings.filterExact = filterExact;
                }
                if (typeof(filter) === "string") {
                    datas.settings.filter = filter;
                } else {
                    datas.settings.filter = '';
                }
                _display($list, datas);
            });
        }
    }


    $.fn.galphoStructure = function (method) {

        // Method calling logic
        if (methods[method]) {
            return methods[ method ].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.galphoStructure');
        }
        return false;
    };
})(jQuery);

