<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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

                    for (var j = 0; j < element.length; ++j) {

                        element[j].textContent = vars[css_selector_classes[css_class]];
                    }
                }
                
                if (vars['page'] == 1) { 
                    
                    document.getElementById("Header").style.display = 'none';
                }
            }
        }
    </script>
</head>
<style>
    .header {
        padding: 20px;
    }
    .service-name {
        font-size: 2rem;
        color:#f44718;
        font-weight: 700;
    }
    .address {
        color:#548DD4;
        font-size:1.6rem;
        word-spacing: normal;
    }
    .td {
        font-weight: 700;
    }
</style>

<body onload="subst()">
    <table style="width: 100%; border: none; margin-bottom: 10px;padding: 10px" id="Header">
        <tbody>
            
            <tr align="justify" style="display: table-row">
                <td>
                    <span class="service-name">{{"{$booking->booking_service->service->name} Audit Report"}}</span> 
                    <br>
                    <span class="address">{{"{$booking->address->body}, {$booking->address->landmark}, {$booking->address->city}, {$booking->address->state}, {$booking->address->pin}"}} </span>
                </td>
                <td style="display: flex;align-items: center">
                    <img style="display:inline;height: 100px;" src="{{ public_path("/images/safetifyme-front.jpg") }}"
                        height="auto" />
                </td>
               
            </tr>
            <tr>
                <td class="td">
                 {{"{$user}"}}
                </td>
                <td class="td">
                    {{"Report ID: {$report_id}-{$booking->id}"}}
                </td>
            </tr>
            <tr>
                <td class="td">
                    {{"{$email}"}}
                </td>
                <td class="td">
                    {{"{$booking->user->mobile}"}}
                </td>
            </tr>
        </tbody>
    </table>
    </table>
</body>

</html>