<form name = "form" action = "" method = "POST"></form>
  CHOOSE REPORT
  <select id='sup' name='attendance_type' onchange="document.getElementById('x').src=this.value">
    <option value = "#" selected disabled>(Select One)</option>
    <option value = "r1.php">First Day - Summary Reports</option>
    <option value = "r2.php">Second Day - Summary Reports</option>
  </select>
</form>
<iframe src="#" style="overflow:hidden; height:100%;width:100%; border: 0;" height="100%" width="100%" id="x"></iframe>
</html>
