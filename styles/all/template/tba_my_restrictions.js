$(function() {
    function disableFields(radioValue) {
        switch (radioValue) {
            case "none":
                $(".time-restriction input").val("").attr("disabled", true);
                $("input[name=guardian]").val("").attr("disabled", true);
                break;
            case "me":
                $(".time-restriction input").attr("disabled", false);
                $("input[name=guardian]").val("").attr("disabled", true);
                prefillTimes();
                break;
            case "guardian":
                $(".time-restriction input").attr("disabled", true);
                $("input[name=guardian]").attr("disabled", false);
                prefillTimes();
                break;
        }
    }

    function prefillTimes() {
        prefillInput($(".time-restriction.from input"));
        prefillInput($(".time-restriction.until input"));
    }

    function prefillInput(fields) {
        fields.each(function() {
            if (!$(this).val()) {
                $(this).val(this.defaultValue);
            }
        });
    }

    $("input[type=radio][name=restrictmode]").change(function() {
        disableFields(this.value);
    });

    disableFields($("input[type=radio][name=restrictmode]:checked").val());
});