<!DOCTYPE html>
<html>
    <head>
        <script>
            function subst() {

                var vars = {};
                
                var query_strings_from_url = document.location.search.substring(1).split('&');
                
                for (var query_string in query_strings_from_url) {
                    
                    if (query_strings_from_url.hasOwnProperty(query_string)) {
                        
                        var temp_var = query_strings_from_url[query_string].split('=', 2);
                        vars[temp_var[0]] = decodeURI(temp_var[1]);
                    }
                }

                var css_selector_classes = ['page', 'frompage', 'topage', 'webpage', 'section', 'subsection', 'date', 'isodate', 'time', 'title', 'doctitle', 'sitepage', 'sitepages'];
                
                for (var css_class in css_selector_classes) {
                    
                    if (css_selector_classes.hasOwnProperty(css_class)) {
                        
                        var element = document.getElementsByClassName(css_selector_classes[css_class]);
                        
                        for (var j = 0; j < element.length; j++) {
                           
                            element[j].textContent = vars[css_selector_classes[css_class]];
                        }
                    }

                    // if (vars['page'] == vars['topage']) { 
                    
                    //     document.getElementById("last").style.display = 'block';
                    // }
                }
            }
        </script>
    </head>
    <body style="border:0; margin: 0;" onload="subst()">
        <table style="font-size:12px; width: 100%" >
            <tr class="pageNo">
               
                <td style="text-align:center">
                    SUBJECT TO RAMPUR JURISDICTION <br>
                    This is a Computer Generated Invoice
                </td>
            </tr>
        </table>
    </body>
</html>
