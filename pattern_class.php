<?php

/* 
    Class to output patterns of html code
*/
class pattern
{

    /*
        To show a script to work with slide container
    */
    function add_slide()
    {   
        echo <<<_END
        
        <script>
        $(document).ready(function() {
            $('.slide_container').hide();
            var st_text = $('.slide_title').text();
            $('.slide_title').text("Show " + st_text);
            
            $('.slide_title').click(function() {
                var sc = $(this).next('.slide_container');
                
                if (sc.is(':hidden')) {
                    sc.slideToggle();
                    $('.slide_title').text("Hide " + st_text);
                } else {
                    sc.slideToggle();
                    $('.slide_title').text("Show " + st_text);
                }
            });
        });
        </script>

_END;
    }


   /*
        To show a top of page
    */
    function show_top()
    {
        echo <<<_END
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
_END;


        echo <<<_END
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>  
        <link rel="stylesheet" type="text/css" href="css/jquery.css"/>
        <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
        <link rel="stylesheet" type="text/css" href="css/blog.css"/>
        <link href="froala_editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui.min.js"></script>
_END;
        $this->add_slide();

        echo <<<_END
        </head>
        <body>
_END;
    }    

    /*
        To show a bottom of page
    */
    function show_bottom()
    {
        echo <<<_END
        </body>
        </html>
_END;
    }

    /*
        To include WYSIWYG editor
    */
    function add_wysiwyg_editor()
    {
        echo <<<_END
        <script type="text/javascript" src="froala_editor/js/froala_editor.pkgd.min.js"></script>
        <script> 
            var editor = new FroalaEditor('textarea.wysiwyg_editor', {
            });    
            </script>
_END;
    }

}


?>