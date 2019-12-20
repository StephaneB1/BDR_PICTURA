/*
* http://jqueryui.com/datepicker
*/

$(function() {
	var dateFormat = "dd.mm.yy";
	
	//Datepicker "From"
	var fromDate = $("#startDatepicker").datepicker({
		dateFormat: dateFormat,
		firstDay: 1,
		yearRange: "-100:+100",

        nextText: ">",
        prevText: "<",

		dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
		dayNamesMin: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
		
		monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		monthNamesShort: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		
		changeMonth: true,
		changeYear: true
	});
	
	//Datepicker "To"
	var toDate = $("#endDatepicker").datepicker({
		dateFormat: dateFormat,
		firstDay: 1,
		yearRange: "-100:+100",

        nextText: ">",
        prevText: "<",
		
		dayNames: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
		dayNamesMin: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
		
		monthNames: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		monthNamesShort: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
		
		changeMonth: true,
		changeYear: true
	});
	
	//Update "From" date limit
	toDate.on("change", function(){
		fromDate.datepicker("option", "maxDate", getDate(this));
	});
	
	//Update "To" date limit
	fromDate.on("change", function(){
		toDate.datepicker("option", "minDate", getDate(this));
	});
	
	function getDate(ele) {
		var date;
		try {
			date = $.datepicker.parseDate(dateFormat, ele.value);
		} catch( error ) {
			date = null;
		}		
		return date;
    }
});