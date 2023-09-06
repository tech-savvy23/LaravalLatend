<!DOCTYPE html>
<html>
<head>
    <style>
        html { font-size: 93.1%;}
        body{ height: 30mm; }

        /*–––––––––––––––––––––––– system hacks –––––––––––––––––––––––– */

        /**
         * Unix* system based servers render font much
         * bigger than windows based server.
         * Force windows font to 100% and rescale the
         * linux rendering by the magic factor (1.33)
         */
        .linux {
            font-size: 70%;
        }

        /**
         * base size: 88mm
         *
         * for wkhtmltopdf generation,
         * body size must be multiplied by 1.33 factor on windows systems
         */
        .win body {
            height: 117.04mm;
        }
    </style>
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

            var css_selector_classes = ['last_page', 'page', 'frompage', 'topage', 'webpage', 'section', 'subsection', 'date', 'isodate', 'time', 'title', 'doctitle', 'sitepage', 'sitepages'];

            for (var css_class in css_selector_classes) {

                if (css_selector_classes.hasOwnProperty(css_class)) {

                    var element = document.getElementsByClassName(css_selector_classes[css_class]);

                    for (var j = 0; j < element.length; j++) {
                        if (element[j].className === 'last_page') {
                            if (parseInt(vars['page']) === parseInt(vars['topage'])) {
                                element[j].textContent = '*All findings are dependent on parameters at the time of audit.\n' +
                                    '            This outcome of this audit, which is only conducted at made available locations, is for information purpose\n' +
                                    '            & awareness only and does not guarantee anything else in anyway. * *No legal, non legal or financial claim\n' +
                                    '            is acceptable post or prior to the audit. *The Courts/Forum at Rampur shall have exclusive jurisdiction in\n' +
                                    '            all disputes/claims concerning the audit & or results of the audit. *Audit results are not valid for legal\n' +
                                    '            or insurance purposes. *Contact customer care Tel No. 1800 572 0780 for all queries related to the audit';
                            }
                            else {
                                element[j].textContent = '';
                            }

                        }
                        else  {
                            element[j].textContent = vars[css_selector_classes[css_class]];

                        }
                        // if (vars['page'] === vars['topage']) {
                        //     element[0].textContent = "*All findings are dependent on parameters at the time of audit.";
                        // }else  {
                        // element[j].textContent = vars[css_selector_classes[css_class]];
                        // element[0].textContent = JSON.stringify(vars);
                        // }

                    }
                }


            }
        }
    </script>
</head>
<body style="border:0; margin: 0;" onload="subst()">
<span class="last_page" style="font-size: 12px;"></span>
<table style="border-bottom: 1px solid black; width: 100%">

    <tr class="pageNo">

        <td style="position: relative; width:80%">
            <img style="position: relative; left:45%; " width="200" height="50"
                 src="{{ public_path("/images/perfect-house-logo.jpeg") }}"
                 height="auto"/>
        </td>
        <td style="text-align:right">
            Page <span class="page"></span> of <span class="topage"></span>
        </td>
    </tr>

</table>
</body>
</html>
