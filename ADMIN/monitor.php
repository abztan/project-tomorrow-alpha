<?php

include "_parentFunctions.php";

?>


<table border = "1">
<th colspan = "2">Monitor Information</th>
<tr>
	<td>Added pastor profiles on the month of <?php echo date("F");?></td>
	<td><?php echo  countNewPastorProfiles();?></td>
</tr>

<tr>
	<td>BAC</td>
	<td><?php echo  countNewPastorProfiles_Base(1);?></td>
</tr>

<tr>
	<td>BOH</td>
	<td><?php echo  countNewPastorProfiles_Base(2);?></td>
</tr>

<tr>
	<td>DGT</td>
	<td><?php echo  countNewPastorProfiles_Base(3);?></td>
</tr>

<tr>
	<td>GNS</td>
	<td><?php echo  countNewPastorProfiles_Base(4);?></td>
</tr>

<tr>
	<td>KOR</td>
	<td><?php echo  countNewPastorProfiles_Base(5);?></td>
</tr>

<tr>
	<td>PWN</td>
	<td><?php echo  countNewPastorProfiles_Base(6);?></td>
</tr>

<tr>
	<td>DPG</td>
	<td><?php echo  countNewPastorProfiles_Base(7);?></td>
</tr>

<tr>
	<td>ILO</td>
	<td><?php echo  countNewPastorProfiles_Base(8);?></td>
</tr>

<tr>
	<td>CEB</td>
	<td><?php echo  countNewPastorProfiles_Base(9);?></td>
</tr>

<tr>
	<td>RXS</td>
	<td><?php echo  countNewPastorProfiles_Base(10);?></td>
</tr>

</table>

