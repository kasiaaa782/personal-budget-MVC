window.onload = () => {
	var title = document.getElementById("title").innerHTML;
	if (title === "Dodawanie wydatku" || title === "Dodawanie przychodu") setCurrentDate();
}

setCurrentDate = () => {
	var dateOnPage = document.getElementById("date1");
	if (dateOnPage.value == "") {
		var today = new Date();

		var day = today.getDate().toString();
		day = (day.length === 1) ? '0' + day : day;

		var month = today.getMonth() + 1;
		month = (month.toString().length === 1) ? '0' + month : month;

		var year = today.getFullYear();

		dateOnPage.value = year + "-" + month + "-" + day;

	}
}