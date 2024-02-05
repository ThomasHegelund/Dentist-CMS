<script>
let today = new Date();
let currentMonth = today.getMonth();
let currentYear = today.getFullYear();
let selectYear = document.getElementById("year");
let selectMonth = document.getElementById("month");

let months = [];

let monthsLong = ["Januar", "Februar", "Marts", "April", "Maj", "Juni", "Juli", "August", "September", "Oktober", "November", "December"];

let monthsShort = ["Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"];


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


function openlink(){
  alert("hi");
  //window.location.href = "https://fischer.thomashegelund.dk/" + path;
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



function fjernVagt(num){
  window.location.href = "https://fischer.thomashegelund.dk/fjernVagt.php?vagt_id=" + num;

}


function accepterVagt(num){
  window.location.href = "https://fischer.thomashegelund.dk/acceptervagt.php?id=" + num;
}

function sætVagtTilSalg(){
  let dato = <?php echo json_encode($dato); ?>;
  let num1 = document.getElementById('appt1').value;
  let num2 = document.getElementById('appt2').value;
  let num3 = document.getElementById('appt3').value;
  if(num1!=""&&num2!=""&&num3!=""){
    window.location.href = "https://fischer.thomashegelund.dk/nyVagt.php?dato=" + dato+"&tid="+num1+"-"+num2+"&afd="+num3;
  }
  else {
    alert("Afdelingen, start- og sluttidspunkt skal være udfyldt.")
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
    console.log("hi")
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

    let dageVagt = <?php echo json_encode($dage); ?>;

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



                let realdate = date+"/"+realMonth +"-"+year
                if (date === today.getDate() && year == today.getFullYear() && month == today.getMonth()) {
                    cell.classList.add("currentDate");
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
                if(dageVagt.includes(date)) {
                  let cellText2 = document.createTextNode("*");
                  cell.appendChild(cellText2);
                }

                row.appendChild(cell);
                date++;
            }


        }

        tbl.appendChild(row); // appending each row into calendar body.
    }

}

window.onload = checkWidth;
window.onresize = checkWidth;
  function checkWidth(){

    let dateHeaders = document.getElementsByClassName('calendarDateHeader');
    if (window.innerWidth < 400) {
      months = monthsShort;
      showCalendar(currentMonth, currentYear);
      for (var i = 0; i < dateHeaders.length; i++) {
        dateHeaders[i].innerHTML = dateHeaders[i].innerHTML.charAt(0) + dateHeaders[i].innerHTML.charAt(1);
      }

    } else {
      months = monthsLong;
      showCalendar(currentMonth, currentYear);
      let dates = ["Man","Tir","Ons","Tor","Fre","Lør","Søn"];
      for (var i = 0; i < dateHeaders.length; i++) {
        dateHeaders[i].innerHTML = dates[i];
      }
    }
  }

  function updateDateHeader(date){

  }


</script>
