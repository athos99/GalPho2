var galpho = galpho|| {};
(function($) {
    var self = this;
    (function() {
        $(document).ready(function() {
            self.bindDialog($(".dialog-open"))
        });
    })();

    this.afterAjaxUpdate = function afterAjaxUpdate(id,data) {
        self.bindDialog($("#"+id).find(".dialog-open"));
    }
    this.bindDialog = function($select) {
        // on click open dialog box and load content with ajax
        $select.off("click.galpho").on("click.galpho",function(){

            $this = $(this);

            var title = $this.attr('title');
            var url = $this.attr("href");
            var updateSelector = $this.data('update-selector');
            var $div =$('<div>').appendTo('body');
            var $dlg =$div.dialog({
                dialogClass: "dialogue",
                close : function(){
                    $dlg.dialog("destroy");
                    $div.remove();
                },
                modal:true,
                width:900,
                title:title,
                position:[null,50]
            });
            $.post( url,{
                data:'data'
            }, _bindAfterLoad, 'html');
            return false;

            // when the content is load bind links
            function _bindAfterLoad(html) {
                if (html.length == 0) {
                    $dlg.dialog("destroy");
                    $(updateSelector).yiiGridView('update');
                }
                $dlg.html(html);
                // on image click store id and close the dialog
                $dlg.find('.dialog-close').click(function(event){

                    $dlg.dialog("destroy");
                    return false
                });
                // on pagination click load content with ajax
                $dlg.find('.dialog-load ').click(function(){
                    $.post($(this).attr('href'), {
                    }, _bindAfterLoad,'html');
                    return false;
                });
                $dlg.find('form').on('submit',function(){
                    $.ajax( url,{
                        type:"POST",

                        data:$(this).serialize()+'&submit=ajax',
                        success: _bindAfterLoad,
                        dataType:"html"
                    });
                    return false;
                });

            }
        });

    };

}).call(galpho,jQuery ); // hand in implicit parameter "this";


