function toDateRange(from_field, to_field)
{
    $(from_field).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
            $(to_field).datepicker("option", "minDate", selectedDate);
        }
    });
    $(to_field).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 3,
        dateFormat: "yy-mm-dd",
        onClose: function( selectedDate ) {
            $(from_field).datepicker("option", "maxDate", selectedDate);
        }
    });
}

$(function(){
    toDateRange("input[name=date_from]", "input[name=date_to]");
});

