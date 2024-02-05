let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
let selectYear = document.getElementById("year");
let selectMonth = document.getElementById("month");

let months = ["Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"];

let monthAndYear = document.getElementById("monthAndYear");

try {
  if (getCookie("Month") != "" && getCookie("Year") != "" && getCookie("Month") != null && getCookie("Year") != null) {
    currentMonth = getCookie("Month");
    currentYear= getCookie("Year");
  }

} catch (e) {
  console.log(e)
}

showCalendar(currentMonth, currentYear);

function openDate(num, ele){
  selectDate(ele, num);
}

function closeDate(ele) {
  selectDate(ele, 0);
}

function selectDate(ele, num){
  let selected = ele.classList.contains("selected");
  if (!selected && num !=0) {
    document.cookie = "dato="+num;
    window.location.href = "https://fischer.thomashegelund.dk/";
  }
  else{
    document.cookie = "dato=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    window.location.href = "https://fischer.thomashegelund.dk/";
  }

}


function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}






function accepterVagt(num){
  window.location.href = "https://fischer.thomashegelund.dk/acceptervagt.php?id=" + num;
}

function sætVagtTilSalg(){
  var dato = document.getElementById('datoText').innerHTML;
  var num1 = document.getElementById('appt1').value;
  var num2 = document.getElementById('appt2').value;
  if(num1!=""&&num2!=""){
    window.location.href = "https://fischer.thomashegelund.dk/nyVagt.php?dato=" + dato+"&tid="+num1+"-"+num2;
  }
  else {
    alert("Start- og sluttidspunkt skal være udfyldt.")
  }
}



function logout() {
  document.cookie = "dato=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "Month=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
  document.cookie = "Year=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}



function next() {
    currentYear = (parseInt(currentMonth) === 11) ? parseInt(currentYear) + 1 : parseInt(currentYear);

    currentMonth = (parseInt(currentMonth) + 1) % 12;
    document.cookie = "Month="+ currentMonth;
    document.cookie = "Year="+ currentYear;


    window.location.href = "https://fischer.thomashegelund.dk/";
}

function previous() {
    currentYear = (currentMonth === 0) ? currentYear - 1 : currentYear;
    currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
    document.cookie = "Month="+ currentMonth;
    document.cookie = "Year="+ currentYear;
    window.location.href = "https://fischer.thomashegelund.dk/";
}





function showCalendar(month, year) {

    let firstDay = (new Date(year, month)).getDay();

    let daysInMonth = 32 - new Date(year, month, 32).getDate();

    let tbl = document.getElementById("calendar-body"); // body of the calendar

    // clearing all previous cells
    tbl.innerHTML = "";

    // filing data about month and in the page via DOM.
    monthAndYear.innerHTML = months[month] + " " + year;


    let realMonth = parseInt(month)+1;
    var cookie = getCookie("dato");
    var selectedDate = "";
    var selectedMonth = "";
    var selectedYear = "";

    if (cookie != "" && cookie != null) {
      selectedDate = cookie.split("/")[0];
      selectedMonth = cookie.split("/")[1].split("-")[0];
      selectedYear = cookie.split("-")[1];
    }




    // creating all cells
    let date = 1;
    let endReached = false;
    for (let i = 0; i < 6; i++) {


        if(endReached){
          break;
        }
        // creates a table row
        let row = document.createElement("tr");

        //creating individual cells, filing them up with data.
        for (let j = 0; j < 7; j++) {
          if(date >= daysInMonth){
            endReached = true;
          }
             if (i === 0 && j < firstDay-1 || i===0 && firstDay===0 && j<6 || date > daysInMonth) {

                let cell = document.createElement("td");
                cell.classList.add("cell")
                let cellText = document.createTextNode("");
                cell.appendChild(cellText);
                row.appendChild(cell);
            }

            else {

                let cell = document.createElement("td");
                cell.classList.add("cell")
                let cellText = document.createTextNode(date);
                let cellText2 = document.createTextNode("*");


                let realdate = date+"/"+realMonth +"-"+year
                if (date === today.getDate() && year == today.getFullYear() && month == today.getMonth()) {
                    cell.classList.add("bg-info");
                } // color today's date

                try {
                  if (cookie != "") {

                    if (date == selectedDate && year == selectedYear && realMonth == selectedMonth) {
                      cell.classList.add("selected");
                    }
                  }
                } catch (e) {
                  console.log(e);
                }

                if (year > today.getFullYear() || year==today.getFullYear() && month > today.getMonth() || year==today.getFullYear() && month == today.getMonth() && date >= today.getDate()) {
                  cell.onclick = function(){ openDate(realdate, this); } ;
                }
                else{
                  cell.onclick = function(){ closeDate(this); } ;
                }

                cell.classList.add("WithDate");

                cell.appendChild(cellText);
                cell.appendChild(cellText2);
                row.appendChild(cell);
                date++;
            }


        }

        tbl.appendChild(row); // appending each row into calendar body.
    }

}
