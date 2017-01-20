function initCustomMirrorlist() {
    var $customMirrorForm = $("#custom-mirrorlist"),
        $pCheckbox = $customMirrorForm.find(".protocol-checkbox"),
        $tCheckbox = $customMirrorForm.find(".type-checkbox"),
        $cCheckbox = $customMirrorForm.find(".country-checkbox");

    // initialize the checkbox functionality
    $("#custom-mirrorlist input[type='checkbox']").checkbox();

    // initialize form submit functionality
    $customMirrorForm.on("submit", function(e) {
        var pString = "",
            tString = "",
            cString = "",
            firstVar = true,
            url = "/mirrorlist/generate";

        e.preventDefault();

        $pCheckbox.each(function() {
            var $this = $(this);

            if ($this[0].checked) {
                if (pString === "") {
                    pString = "p=";
                } else {
                    pString += ",";
                }

                pString += $this.val();
            }
        });

        $tCheckbox.each(function() {
            var $this = $(this);

            if ($this[0].checked) {
                if (tString === "") {
                    tString = "t=";
                } else {
                    tString += ",";
                }

                tString += $this.val();
            }
        });

        $cCheckbox.each(function() {
            var $this = $(this);

            if ($this[0].checked) {
                if (cString === "") {
                    cString = "c=";
                } else {
                    cString += ",";
                }

                cString += $this.val();
            }
        });

        if (pString !== "" || tString !== "" || cString !== "") {
            url += "?";

            if (pString !== "") {
                firstVar = false;
                url += pString;
            }

            if (tString !== "") {
                if (!firstVar) {
                    url += "&";
                } else {
                    firstVar = false;
                }

                url += tString;
            }

            if (cString !== "") {
                if (!firstVar) {
                    url += "&";
                }

                url += cString;
            }
        }

        window.open(url, "_blank");
    });
}
