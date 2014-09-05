/**
 * yii is the root module for all Yii JavaScript modules.
 * It implements a mechanism of organizing JavaScript code in modules through the function "yii.initModule()".
 *
 * Each module should be named as "x.y.z", where "x" stands for the root module (for the Yii core code, this is "yii").
 *
 * A module may be structured as follows:
 *
 * ~~~
 * yii.sample = (function($) {
 *     var pub = {
 *         // whether this module is currently active. If false, init() will not be called for this module
 *         // it will also not be called for all its child modules. If this property is undefined, it means true.
 *         isActive: true,
 *         init: function() {
 *             // ... module initialization code go here ...
 *         },
 *
 *         // ... other public functions and properties go here ...
 *     };
 *
 *     // ... private functions and properties go here ...
 *
 *     return pub;
 * })(jQuery);
 * ~~~
 *
 * Using this structure, you can define public and private functions/properties for a module.
 * Private functions/properties are only visible within the module, while public functions/properties
 * may be accessed outside of the module. For example, you can access "yii.sample.isActive".
 *
 * You must call "yii.initModule()" once for the root module of all your modules.
 */



yii.galpho = (function ($) {
    var pub = {
        // whether this module is currently active. If false, init() will not be called for this module
        // it will also not be called for all its child modules. If this property is undefined, it means true.
        isActive: true,
        init: function () {
            no_validation();
            galphostructure_check();

        }
    };

    /**
     *  Define the handler for button with class no-validation
     *  This handler suppress the yii client cript validation, for use with cancel the form button
     *
     */
    function no_validation() {
        $('.no-validation').on('click.galpho', function (event) {
            var $form = $(this).parents('form');
            var data = $form.data('yiiActiveForm');
            data.validated = true;
            data.settings.beforeSubmit = undefined;
//                 $form.data('yiiActiveForm',data);
            return true;
        });
    }

    function galphostructure_check() {
        // when a check box element is clicked, set the state of this check box to his childs
        $('.galphostructure .galpho-right input').on('click.galpho', function(event) {
            var $this = $(this);
            var val = $this.attr("value");
            var checked = $this.prop("checked");
            var $div = $this.closest('.galpho-line');
            if (checked) {
                $div.find("[value='" + val + "']").prop("checked", true);
            } else {
                $div.find("[value='" + val + "']").prop("checked", false);
            }
            $div.find('.galpho-close').removeClass('galpho-close').addClass('galpho-open');
            event.stopPropagation();
        });
    }


    return pub;
})(jQuery);

