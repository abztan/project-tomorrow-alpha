<link rel="shortcut icon" href="../_media/ptfavicon1.ico" >
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php

	function addApplication($pastor_id,$last_name,$first_name,$middle_initial,$application_id,$application_type,$application_province,$application_city,$application_barangay,$application_date,$base_id,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');


		$query = "INSERT INTO list_transform_application
		 (pastor_id,pastor_last_name,pastor_first_name,pastor_middle_initial,application_id,application_type,application_province,application_city,application_barangay,application_date,base_id,created_by,created_date)
		 VALUES
		 ('$pastor_id','$last_name','$first_name','$middle_initial','$application_id','$application_type','$application_province','$application_city','$application_barangay','$application_date','$base_id','$username',TIMESTAMP '$timestamp')";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function addParticipant_PS($entry_id,$participant_pk,$hh_member,$hh_sick,$hh_income,$ps1,$ps2,$ps3,$ps4,$ps5,$ps6,$ps7,$ps8,$ps9,$ps10,$ps11,$ps12,$ps13,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "INSERT INTO list_transform_participant_psc
		 (fk_entry_id,fk_participant_pk,hh_member,hh_sick,hh_income,ps1,ps2,ps3,ps4,ps5,ps6,ps7,ps8,ps9,ps10,ps11,ps12,ps13,created_date,created_by)
		 VALUES
		 ($entry_id,$participant_pk,$hh_member,$hh_sick,$hh_income,'$ps1','$ps2','$ps3','$ps4','$ps5','$ps6','$ps7','$ps8','$ps9','$ps10','$ps11','$ps12','$ps13',TIMESTAMP '$timestamp','$username')";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function addParticipant($last_name,$first_name,$middle_name,$entry_id,$username,$contact_number,$variable1,$category,$gender,$birthday)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');
		$birthday = !empty($birthday) ? "'$birthday'" : "NULL";

		$query = "INSERT INTO list_transform_participant
		 (last_name,first_name,middle_name,fk_entry_id,created_date,created_by,contact_number,variable1,category,gender,birthday)
		 VALUES
		 ('$last_name','$first_name','$middle_name','$entry_id',TIMESTAMP '$timestamp','$username','$contact_number','$variable1','$category','$gender',$birthday)";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function checkApplicationEntry($last_name,$first_name,$middle_initial)
	{

		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
				 FROM list_transform_application
				 WHERE pastor_last_name ilike '%$last_name%' AND
				 pastor_first_name ilike '%$first_name%' AND
				 pastor_middle_initial ilike '%$middle_initial%'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['id'];
	}

	function checkParticipantEntry($last_name,$first_name,$middle_name)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
				 FROM list_transform_participant
				 WHERE last_name ilike '%$last_name%' AND
				 first_name ilike '%$first_name%' AND
				 middle_name ilike '%$middle_name%'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['id'];
	}

	function countParticipantList()
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT count(*)
						FROM list_transform_participant
						WHERE tag <> 0";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function countParticipantList_Tag($tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT count(*)
						FROM list_transform_participant
						WHERE tag = '$tag'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function countParticipantList_4Ps($tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT count(*)
						FROM list_transform_participant
						WHERE variable1 = 'Yes'
						AND tag='$tag'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function countApplication_Participants_byTag_byVariable1($application_pk,$tag,$variable1)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT count(*)
						FROM list_transform_participant
						WHERE fk_entry_id = '$application_pk'
						AND variable1 = '$variable1'
						AND tag = '$tag'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function countParticipantTag($entry_id,$tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT count(*)
					  FROM list_transform_participant
					  WHERE fk_entry_id = '$entry_id' AND tag = '$tag'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function countParticipant_category_tag($entry_id,$category,$tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		if($category == "all_pType")
		{
			$category = "(category = '1'
										OR category = '2'
										OR category = '3'
										OR category = '4'
										OR category = '5')";
		}
		if($category == "all_cType") {
			$category = "(category = '20'
										OR category = '21'
										OR category = '22')";
		}
		$query = "SELECT count(*)
					  FROM list_transform_participant
					  WHERE fk_entry_id = '$entry_id' AND tag = '$tag'
						AND $category";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function countParticipantTotal($id)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT count (*)
						FROM list_transform_participant
						WHERE  fk_entry_id ='$id'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];

		return $count;
	}

	function countApplicationTagByBase($tag,$base)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT count (*)
						FROM list_transform_application
						WHERE  tag ='$tag'
						AND base_id = '$base'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];

		return $count;
	}

	function countApplicationTag($tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT count (*)
						FROM list_transform_application
						WHERE  tag ='$tag'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];

		return $count;
	}

	function countApplication()
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT count (*)
						FROM list_transform_application
						WHERE  tag <> '0'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$count = $row['count'];

		return $count;
	}

	function updateApplicationCommunityID($id,$community_id,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_application SET community_id = '$community_id', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$id'";

		$result = pg_query($dbconn, $query);
	}

	function updateApplicationTag($id,$tag,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_application SET tag = '$tag', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$id'";

		$result = pg_query($dbconn, $query);
	}

	function updateApplicationBatch($id,$batch,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_application SET batch = '$batch', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$id'";

		$result = pg_query($dbconn, $query);
	}

	function updateApplication_reason($application_pk, $reason, $username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_application SET reason = '$reason', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$application_pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateParticipant_PS_Remark($participant_pk,$remark,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		echo $query = "UPDATE list_transform_participant_psc SET remark = '$remark-$username', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE fk_participant_pk = '$participant_pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateParticipantTag($participant_pk,$tag,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_participant SET tag = '$tag', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateVisitor_grad($application_pk,$value,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_application SET visitor_graduate = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$application_pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateParticipant_category($participant_pk,$category,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_participant SET category = '$category', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateParticipant_replacement($participant_pk,$replaced_by,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_participant SET replaced_by = '$replaced_by', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateParticipant($participant_pk,$last_name,$first_name,$middle_name,$username,$contact_number,$variable1,$category,$gender,$birthday)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');
		$birthday = !empty($birthday) ? "'$birthday'" : "NULL";

		$query = "UPDATE list_transform_participant
					   SET last_name = '$last_name', first_name = '$first_name', middle_name = '$middle_name', contact_number = '$contact_number', variable1 = '$variable1', updated_by = '$username', category = '$category', gender = '$gender', birthday = $birthday, updated_date = TIMESTAMP '$timestamp'
					   WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function updateParticipant_PSV($participant_pk,$poverty_score)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "UPDATE list_transform_participant
					   SET variable2 = '$poverty_score'
					   WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function updateParticipant_PS($participant_pk,$hh_member,$hh_sick,$hh_income,$ps1,$ps2,$ps3,$ps4,$ps5,$ps6,$ps7,$ps8,$ps9,$ps10,$ps11,$ps12,$ps13,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_participant_psc
					   SET hh_member = '$hh_member', hh_sick = '$hh_sick', hh_income = '$hh_income', ps1 = '$ps1', ps2 = '$ps2', ps3 = '$ps3', ps4 = '$ps4', ps5 = '$ps5', ps6 = '$ps6', ps7 = '$ps7', ps8 = '$ps8', ps9 = '$ps9', ps10 = '$ps10', ps11 = '$ps11', ps12 = '$ps12', ps13 = '$ps13', updated_by = '$username', updated_date = TIMESTAMP '$timestamp'
					   WHERE fk_participant_pk = '$participant_pk'";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function updateApplication($pk, $pastor_id,$first_name,$middle_initial,$last_name,$application_type,$application_province,$application_city,$application_barangay,$application_date,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_application SET pastor_id = '$pastor_id', pastor_first_name = '$first_name', pastor_middle_initial = '$middle_initial',
					     pastor_last_name = '$last_name', application_type = $application_type, application_province = '$application_province', application_city = '$application_city', application_barangay = '$application_barangay', application_date = '$application_date', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$pk'";

		$result = pg_query($dbconn, $query);
	}

	function updateApplication_ParticipantID($participant_pk,$participant_id,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE list_transform_participant SET participant_id = '$participant_id', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE id = '$participant_pk'";

		$result = pg_query($dbconn, $query);
	}

	function getApplicationDetails($id) //depreciate
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
						FROM list_transform_application
						WHERE id = '$id'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row;
	}

	function getApplication_MaxCommunityId_ByBaseByType($baseid,$application_type,$batch)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT max(community_id)
						FROM list_transform_application
						WHERE base_id = '$baseid'
						AND application_type = '$application_type' AND batch ='$batch'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['0'];
	}

	function getApplication_MaxParticipantId_ByApplication($application_pk)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT max(participant_id)
						FROM list_transform_participant
						WHERE fk_entry_id = '$application_pk'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['0'];
	}

	function getParticipant_PK($last_name,$first_name,$middle_name,$entry_id)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT id
					 FROM list_transform_participant
					 WHERE
						last_name='$last_name' AND
						first_name='$first_name' AND
						middle_name='$middle_name' AND
						fk_entry_id='$entry_id'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['0'];
	}

	function getParticipantDetails($participant_pk)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT *
					 FROM list_transform_participant
					 WHERE
						id ='$participant_pk'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row;
	}

	function getParticipantTag($entry_id,$tag,$sortby,$order)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT *
					 FROM list_transform_participant
					 WHERE
					 fk_entry_id = '$entry_id'
					 AND tag = '$tag'
					 ORDER BY $sortby $order";

		return $query;
	}

	function getApplicationParticipants($entry_id)
	{
		$query = "SELECT *
					 FROM list_transform_participant
					 WHERE fk_entry_id = '$entry_id'
					ORDER BY variable2 DESC";

		return $query;
	}

	function getParticipantShortlisted($app_type, $entry_id)
	{
		if($app_type == 1)
			$max = 30;
		else if($app_type == 2)
			$max = 40;
		else if($app_type == 3)
			$max = 40;
		else if($app_type == 4)
			$max = 30;

		$query = "SELECT *
					 FROM list_transform_participant
					 WHERE fk_entry_id = '$entry_id'
					 AND tag = '2'
					 ORDER BY variable2 DESC
					 LIMIT $max";

		return $query;
	}

	function getParticipantReplacement($entry_id)
	{
		$query = "SELECT *
					 FROM list_transform_participant
					 WHERE fk_entry_id = '$entry_id'
					 AND tag = '2'
					 ORDER BY variable2 DESC
					 OFFSET 30";

		return $query;
	}

	function getListApplicationByTag($tag)
	{
		$query = "SELECT *
					  FROM list_transform_application
					  WHERE tag = '$tag'
					  ORDER BY created_date ASC";
		return $query;
	}

	function getListApplicationByBase_ByTag($base, $tag)
	{
		$query = "SELECT *
					  FROM list_transform_application
					  WHERE base_id = '$base'
					  AND tag = '$tag'
					  ORDER BY created_date ASC";

		return $query;
	}

	function getListApplication_Head()
	{
		$query = "SELECT *
					  FROM list_transform_application
					  WHERE
					  tag = '3'
					  OR
					  tag = '5'
						OR
						tag = '9'
					  ORDER BY base_id, tag, pastor_last_name, pastor_first_name ASC";
		return $query;
	}

	function getListApplication_Head_ByBase($base)
	{
		$query = "SELECT *
					  FROM list_transform_application
					  WHERE
					  (base_id = '$base' AND tag = '3')
					  OR
					  (base_id = '$base' AND tag = '5')
					  OR
					  (base_id = '$base' AND tag = '9')
					  ORDER BY tag, pastor_last_name, pastor_first_name ASC";

		return $query;
	}

	function getNextApplicationId()
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT id
						FROM list_transform_application
						ORDER BY id DESC";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		$currentid = $row['0'];
		$nextid = $currentid + 1;

		$nextid = "APP".str_pad($nextid, 5, 0, STR_PAD_LEFT);

		return $nextid;
	}

	function getPastorIdShort($lname,$fname,$minitial)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
				 FROM list_pastor
				 WHERE lastname='$lname' AND
				 firstname='$fname' AND
				 middlename LIKE '$minitial%'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['0'];
	}

	function getParticipantPscDetails($participant_pk)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
					 FROM list_transform_participant_psc
					 WHERE fk_participant_pk = '$participant_pk'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row;
	}

	function getParticipantScore($participant_pk)
	{
		$row = getParticipantPscDetails($participant_pk);

		$hh_member = $row['hh_member'];
		$hh_sick = $row['hh_sick'];
		$hh_income = $row['hh_income'];
		$ps1 = $row['ps1'];
		$ps2 = $row['ps2'];
		$ps3 = $row['ps3'];
		$ps4 = $row['ps4'];
		$ps5 = $row['ps5'];
		$ps6 = $row['ps6'];
		$ps7 = $row['ps7'];
		$ps8 = $row['ps8'];
		$ps9 = $row['ps9'];
		$ps10 = $row['ps10'];
		$ps11 = $row['ps11'];
		$ps12 = $row['ps12'];
		$ps13 = $row['ps13'];

			if($ps1 == "a")
				$ps1 = 0;
			else if($ps1 == "b")
				$ps1 = 2;
			else if($ps1 == "c")
				$ps1 = 4;
			else
				$ps1 = 999;

			if($ps2 == "a")
				$ps2 = 0;
			else if($ps2 == "b")
				$ps2 = 2;
			else if($ps2 == "c")
				$ps2 = 4;
			else
				$ps2 = 999;

			if($ps3 == "a")
				$ps3 = 0;
			else if($ps3 == "b")
				$ps3 = 1;
			else if($ps3 == "c")
				$ps3 = 2;
			else
				$ps3 = 999;

			if($ps4 == "a")
				$ps4 = 0;
			else if($ps4 == "b")
				$ps4 = 1;
			else if($ps4 == "c")
				$ps4 = 2;
			else if($ps4 == "d")
				$ps4 = 2;
			else if($ps4 == "e")
				$ps4 = 3;
			else if($ps4 == "f")
				$ps4 = 4;
			else
				$ps4 = 999;

			if($ps5 == "a")
				$ps5 = 0;
			else if($ps5 == "b")
				$ps5 = 0;
			else if($ps5 == "c")
				$ps5 = 1;
			else if($ps5 == "d")
				$ps5 = 2;
			else if($ps5 == "e")
				$ps5 = 4;
			else if($ps5 == "f")
				$ps5 = 4;
			else
				$ps5 = 999;

		//temp
			if($ps6 == "a")
				$ps6 = 0;
			else if($ps6 == "b")
				$ps6 = 1;
			else if($ps6 == "c")
				$ps6 = 2;
			else
				$ps6 = 999;

		//revisit
			$ps7 = strlen($ps7);
			$ps8 = strlen($ps8);

		//temp
			if($ps9 == "a")
				$ps9 = 0;
			else if($ps9 == "b")
				$ps9 = 1;
			else if($ps9 == "c")
				$ps9 = 2;
			else if($ps9 == "d")
				$ps9 = 3;
			else
				$ps9 = 999;

			if($ps10 == "a")
				$ps10 = 0;
			else if($ps10 == "b")
				$ps10 = 2;
			else if($ps10 == "c")
				$ps10 = 3;
			else if($ps10 == "d")
				$ps10 = 4;
			else if($ps10 == "e")
				$ps10 = 4;
			else if($ps10 == "f")
				$ps10 = 6;
			else
				$ps10 = 999;

			if($ps11 == "a")
				$ps11 = 0;
			else if($ps11 == "b")
				$ps11 = 1;
			else if($ps11 == "c")
				$ps11 = 2;
			else
				$ps11 = 999;

			if($ps12 == "a")
				$ps12 = 0;
			else if($ps12 == "b")
				$ps12 = 2;
			else if($ps12 == "c")
				$ps12 = 3;
			else
				$ps12 = 999;

			if($ps13 == "a")
				$ps13 = 0;
			else if($ps13 == "b")
				$ps13 = 1;
			else if($ps13 == "c")
				$ps13 = 1;
			else if($ps13 == "d")
				$ps13 = 1;
			else if($ps13 == "e")
				$ps13 = 2;
			else if($ps13 == "f")
				$ps13 = 3;
			else
				$ps13 = 999;

			$i = ($hh_income*4)/($hh_member+$hh_sick);

			$ps = $ps1+$ps2+$ps3+$ps4+$ps5+$ps6+$ps7+$ps8+$ps9+$ps10+$ps11+$ps12+$ps13;

			$final_score = round(100-((($ps/25)*25)+(($i/800)*25)), 1);

		return $final_score;
	}

	function getParticipantScoreNullIncome($participant_pk)
	{
		$row = getParticipantPscDetails($participant_pk);

		$hh_member = $row['hh_member'];
		$hh_sick = $row['hh_sick'];
		$hh_income = $row['hh_income'];
		$ps1 = $row['ps1'];
		$ps2 = $row['ps2'];
		$ps3 = $row['ps3'];
		$ps4 = $row['ps4'];
		$ps5 = $row['ps5'];
		$ps6 = $row['ps6'];
		$ps7 = $row['ps7'];
		$ps8 = $row['ps8'];
		$ps9 = $row['ps9'];
		$ps10 = $row['ps10'];
		$ps11 = $row['ps11'];
		$ps12 = $row['ps12'];
		$ps13 = $row['ps13'];

			if($ps1 == "a")
				$ps1 = 0;
			else if($ps1 == "b")
				$ps1 = 2;
			else if($ps1 == "c")
				$ps1 = 4;
			else
				$ps1 = 999;

			if($ps2 == "a")
				$ps2 = 0;
			else if($ps2 == "b")
				$ps2 = 2;
			else if($ps2 == "c")
				$ps2 = 4;
			else
				$ps2 = 999;

			if($ps3 == "a")
				$ps3 = 0;
			else if($ps3 == "b")
				$ps3 = 1;
			else if($ps3 == "c")
				$ps3 = 2;
			else
				$ps3 = 999;

			if($ps4 == "a")
				$ps4 = 0;
			else if($ps4 == "b")
				$ps4 = 1;
			else if($ps4 == "c")
				$ps4 = 2;
			else if($ps4 == "d")
				$ps4 = 2;
			else if($ps4 == "e")
				$ps4 = 3;
			else if($ps4 == "f")
				$ps4 = 4;
			else
				$ps4 = 999;

			if($ps5 == "a")
				$ps5 = 0;
			else if($ps5 == "b")
				$ps5 = 0;
			else if($ps5 == "c")
				$ps5 = 1;
			else if($ps5 == "d")
				$ps5 = 2;
			else if($ps5 == "e")
				$ps5 = 4;
			else if($ps5 == "f")
				$ps5 = 4;
			else
				$ps5 = 999;

		//temp
			if($ps6 == "a")
				$ps6 = 0;
			else if($ps6 == "b")
				$ps6 = 1;
			else if($ps6 == "c")
				$ps6 = 2;
			else
				$ps6 = 999;

		//revisit
			$ps7 = strlen($ps7);
			$ps8 = strlen($ps8);

		//temp
			if($ps9 == "a")
				$ps9 = 0;
			else if($ps9 == "b")
				$ps9 = 1;
			else if($ps9 == "c")
				$ps9 = 2;
			else if($ps9 == "d")
				$ps9 = 3;
			else
				$ps9 = 999;

			if($ps10 == "a")
				$ps10 = 0;
			else if($ps10 == "b")
				$ps10 = 2;
			else if($ps10 == "c")
				$ps10 = 3;
			else if($ps10 == "d")
				$ps10 = 4;
			else if($ps10 == "e")
				$ps10 = 4;
			else if($ps10 == "f")
				$ps10 = 6;
			else
				$ps10 = 999;

			if($ps11 == "a")
				$ps11 = 0;
			else if($ps11 == "b")
				$ps11 = 1;
			else if($ps11 == "c")
				$ps11 = 2;
			else
				$ps11 = 999;

			if($ps12 == "a")
				$ps12 = 0;
			else if($ps12 == "b")
				$ps12 = 2;
			else if($ps12 == "c")
				$ps12 = 3;
			else
				$ps12 = 999;

			if($ps13 == "a")
				$ps13 = 0;
			else if($ps13 == "b")
				$ps13 = 1;
			else if($ps13 == "c")
				$ps13 = 1;
			else if($ps13 == "d")
				$ps13 = 1;
			else if($ps13 == "e")
				$ps13 = 2;
			else if($ps13 == "f")
				$ps13 = 3;
			else
				$ps13 = 999;

			$i = ($hh_income*4)/($hh_member+$hh_sick);

			$ps = $ps1+$ps2+$ps3+$ps4+$ps5+$ps6+$ps7+$ps8+$ps9+$ps10+$ps11+$ps12+$ps13;

			$final_score = round(100-((($ps/25)*25)+(1*25)), 1);

		return $final_score;
	}

	function scoreHue($a)
	{
		if($a >= 80 && $a <= 100)
		{
			$hue = "#43C640"; //green
		}

		else if($a >= 50 && $a < 80)
		{
			$hue = "#FFB440"; //yellow
		}

		else if($a >= 0 && $a < 50)
		{
			$hue = "#DA642C"; //red
		}

		else if($a < 0)
		{
			$hue = "#E9271D"; //orange
		}

		return $hue;
	}

		function gCID($appid,$apptype)
		{

			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$app = getApplicationDetails($appid);

			//year
			$date = $app['updated_date'];
			//$year = date("y",strtotime($date));
			$year = date("y");
			//base
			$baseid = $app['base_id'];
			$baseid = str_pad($baseid, 2, 0, STR_PAD_LEFT);

			//program
			$programid = $app['application_type'];

			//batch
			$month = date("m");
			$batch = getBatch("specified",$month);
			if($batch < 3)
				$batch++;
			else
			  $batch = "1";

			$next_comm_id = $year.$baseid.$programid.$batch;

			$max_id = getApplication_MaxCommunityId_ByBaseByType($baseid,$apptype,$batch);
			$current_id = substr($max_id, 0, 6);

			echo $next_comm_id,"<br/>";
			echo $current_id;

			if($next_comm_id == $current_id)
			{
			  //append
			  $community_count = substr($max_id, -2);
			  $community_count = $community_count+1;
			}

			else
			  $community_count = "1";

			$community_count = str_pad($community_count, 2, 0, STR_PAD_LEFT);

			//final id
			$next_comm_id = $next_comm_id.$community_count;

			return $next_comm_id;
		}

	function generateCommunityID($appid,$apptype)
	{

		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$app = getApplicationDetails($appid);

		//year
		$date = $app['updated_date'];
		$year = date("y",strtotime($date));
		$year = "15";
		//base
		$baseid = $app['base_id'];
		$baseid = str_pad($baseid, 2, 0, STR_PAD_LEFT);

		//program
		$programid = $app['application_type'];

		//batch
		$month = date("m",strtotime($date));
		$batch = getBatch("specified",$month);
		if($batch == "1")
		  $batch = "2";
		else if($batch == "2")
		  $batch = "3";
		else if($batch == "3")
		  $batch = "1";
		else
		  echo "";
		//force set batch
		$batch = "3";

		echo "s".$next_comm_id = $year.$baseid.$programid.$batch;

		$max_id = getApplication_MaxCommunityId_ByBaseByType($baseid,$apptype,$batch);
		$current_id = substr($max_id, 0, 6);
		echo "x".$current_id."x";
		if($next_comm_id == $current_id)
		{
		  //append
		  $community_count = substr($max_id, -2);
		  $community_count = $community_count+1;
		}

		else
		  $community_count = "1";

		$community_count = str_pad($community_count, 2, 0, STR_PAD_LEFT);

		//final id
		$next_comm_id = $next_comm_id.$community_count;

		return $next_comm_id;
	}

	function generateParticipantID($application_pk,$participant_pk,$community_id)
	{
		$max_id = getApplication_MaxParticipantId_ByApplication($application_pk);

		if($max_id  != "")
		{
		  $participant_count = substr($max_id, -2);
		  $participant_count = $participant_count+1;
		}
		else
		  $participant_count = "1";

		$participant_count = str_pad($participant_count, 2, 0, STR_PAD_LEFT);

		$next_participant_id = $community_id.$participant_count;

		return $next_participant_id;
	}

	function tick_checkbox($a, $b)
	{
		if(strstr($a, $b))
		  return "checked";
		else
		  return "";
	}
	/*
	function braindrain($baseid, $start, $end)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT list_transform_application.application_id, list_transform_application.base_id, COUNT(list_transform_participant.tag) AS participantcount FROM list_transform_participant
						LEFT JOIN list_transform_application
						ON list_transform_participant.fk_entry_id = list_transform_application.id
						where list_transform_application.base_id = '$baseid' AND list_transform_application.tag = '3' AND list_transform_participant.tag = '2'
						GROUP BY application_id, list_transform_application.base_id
						HAVING COUNT(list_transform_participant.tag) >= $start AND COUNT(list_transform_participant.tag) <= $end ";

		return $query;
	}*/


	/*	function checkParticipantTag($entry_id, $tag)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
					FROM list_transform_participant
					WHERE fk_entry_id = '$entry_id'
					AND tag = '$tag'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['id'];
	}*/

	/*function getApplicationParticipantsu($entry_id)
	{
		$query = "SELECT *,
						round((100 - (((variable_a/25)*25) + (((variable_b*4/(variable_c+variable_d))/800)*25))),2) as answer1,
						round((100 - (((variable_a/25)*35) + (((variable_b*4/(variable_c+variable_d))/800)*15))),2) as answer2,
						round((100 - (((variable_a/25)*15) + (((variable_b*4/(variable_c+variable_d))/800)*35))),2) as answer3

						FROM list_transform_participant
						WHERE fk_entry_id = '$entry_id'
						ORDER BY answer1 DESC";

		/*$query = "SELECT *
						FROM list_transform_participant
						WHERE fk_entry_id = '$entry_id'
						ORDER BY last_name DESC";

		return $query;
	}

	function getParticipantsShortlist($entry_id)
	{
		$query = "SELECT *,
						round((100 - (((variable_a/25)*25) + (((variable_b*4/(variable_c+variable_d))/800)*25))),2) as answer1
						FROM list_transform_participant
						WHERE fk_entry_id = '$entry_id'
						AND tag = '3'
						ORDER BY answer1 DESC";

		return $query;
	}*/
//clean function
function checkAttendance_entry($participant_pk, $application_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM log_transform_attendance
					WHERE fk_participant_pk = '$participant_pk'
					AND fk_application_pk = '$application_pk'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$i = $row['0'];
	if($row['0'] == "")
		$return = "0";
	else
		$return = "1";

	return $return;
}

function checkAttendance_pastor_entry($pastor_pk, $application_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM log_transform_pastor_attendance
					WHERE fk_pastor_pk = '$pastor_pk'
					AND fk_application_pk = '$application_pk'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
	$i = $row['0'];
	if($row['0'] == "")
		$return = "0";
	else
		$return = "1";

	return $return;
}

function checkDuplicate_communityID($next_comm_id) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
					FROM list_transform_application
					WHERE community_id = '$next_comm_id'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	if($row['id'] == "")
		$isDuplicate = "false";
	else
		$isDuplicate = "true";

	return $isDuplicate;
}

function checkDuplicate_participantID($participant_id) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT id
					FROM list_transform_participant
					WHERE participant_id = '$participant_id'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	if($row['id'] == "")
		$isDuplicate = "false";
	else
		$isDuplicate = "true";

	return $isDuplicate;
}

function getApplication_byTag($base,$tag,$sort) {
	if($base > 20 )
		$query = "SELECT *
							FROM list_transform_application
							WHERE tag = '$tag'
							ORDER BY $sort, base_id, pastor_last_name ASC";

	else
		$query = "SELECT *
							FROM list_transform_application
							WHERE base_id = '$base' AND tag = '$tag'
							ORDER BY $sort, base_id, pastor_last_name ASC";

	return $query;
}

function countCommunity_byBase_byBatch($yearbatch,$base,$program) {
	$year = substr($yearbatch,2,-1);
	$batch = substr($yearbatch,-1);
	$base = str_pad($base, 2, 0, STR_PAD_LEFT);
	$id_string = $year.$base.$program.$batch."%";

	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
					FROM list_transform_application
					WHERE community_id ilike '$id_string'
					AND tag <> '4'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['count'];
}

function getApplication_byTag_byBatch($base,$tag,$sort,$yearbatch) {
	$year = substr($yearbatch,2,-1);
	$batch = substr($yearbatch,-1);

	if($base > 20 )
		$query = "SELECT *
							FROM list_transform_application
							WHERE (tag = '$tag' or tag = '6')
							AND community_id ilike '".$year."___".$batch."%'
							ORDER BY $sort, base_id, pastor_last_name ASC";

	else
		$query = "SELECT *
							FROM list_transform_application
							WHERE community_id ilike '".$year."___".$batch."%' AND base_id = '$base' AND (tag = '$tag' or tag = '6')
							ORDER BY $sort, base_id, pastor_last_name ASC";

	return $query;
}

//temporary

function krisha_hhid($base,$sort,$yearbatch) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$year = substr($yearbatch,2,-1);
	$batch = substr($yearbatch,-1);
	$query = "SELECT *
							FROM list_transform_application
							WHERE (tag = '9' or tag = '5' or tag = '6')
							AND community_id ilike '".$year.$base."_".$batch."%'
							ORDER BY $sort, base_id, pastor_last_name ASC";
	$result = pg_query($dbconn, $query);
	return $result;
}

function krisha_participants($entry_id)
{
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
				 FROM list_transform_participant
				 WHERE
				 fk_entry_id = '$entry_id'
				 AND (tag = '5' or tag = '6' or tag = '9')
				 ORDER BY last_name";
	$result = pg_query($dbconn, $query);

	return $result;
}

function getApplication_byProgram($base,$application_id,$sort) {
	if($base > 20 )
		$query = "SELECT *
							FROM list_transform_application
							WHERE application_type = '$application_id'
							ORDER BY $sort, base_id, pastor_last_name ASC";

	else
		$query = "SELECT *
							FROM list_transform_application
							WHERE base_id = '$base' AND application_type = '$application_id'
							ORDER BY $sort, base_id, pastor_last_name ASC";

	return $query;
}

function getApplication_Data_byHHID($hhid) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM list_transform_application
					WHERE community_id = '$hhid'";

	$result = pg_query($dbconn, $query);
	$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function getApplication_Data_byID($application_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM list_transform_application
					WHERE id = '$application_pk'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function getApplication_List($a,$sort) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	if($a > 20 )
		$query = "SELECT *
							FROM list_transform_application
							WHERE tag <> 0 AND tag <> 5
							ORDER BY $sort, base_id, pastor_last_name ASC";

	else
		$query = "SELECT *
							FROM list_transform_application
							WHERE base_id = '$a' AND tag <> 5
							ORDER BY $sort, base_id, pastor_last_name ASC";

	return $query;
}

function getApplication_Status($a) {
	if($a == "1")
		$status = "Base Application";
	else if($a == "2")
		$status = "For Ocular Visit";
	else if($a == "3")
		$status = "Selection Process";
	else if($a == "4")
		$status = "Dropped";
	else if($a == "5")
		$status = "Community";
	else
		$status = "Undefined";

	return $status;
}

function getApplication_String($application_type) {
	if($application_type == 1)
		$application_string = "Transform - Regular";
	else if($application_type == 2)
		$application_string = "Transform - Jumpstart Parents";
	else if($application_type == 3)
		$application_string = "Transform - OSY";
	else if($application_type == 4)
		$application_string = "Transform - SLP";
	else if($application_type == 5)
		$application_string = "Transform - PBSP";
	else
		$application_string = "Undefined";

	return $application_type;
}

function getParticipant_attendanceLog($participant_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM log_transform_attendance
					WHERE fk_participant_pk = '$participant_pk'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function getPastor_attendanceLog($application_pk,$pastor_pk) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT *
					FROM log_transform_pastor_attendance
					WHERE fk_pastor_pk = '$pastor_pk'
					AND fk_application_pk = '$application_pk'";
	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row;
}

function getParticipant_class($category) {
	if($category == 1)
		$class_string = "Participant";
	else if($category == 2)
		$class_string = "Participant Replacement Prior";
	else if($category == 3)
		$class_string = "Participant Replacement During";
	else if($category == 4)
		$class_string = "Participant Add Prior";
	else if($category == 5)
		$class_string = "Participant Add During";
	else if($category == 6)
		$class_string = "Participant Add w/o Scorecard";
	else if($category == 20)
		$class_string = "Counselor";
	else
		$class_string = "Undefined";

	return $class_string;
}

function getParticipant_forApplication_byTag($application_pk,$tag,$order_by,$where) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
				FROM list_transform_participant
				WHERE
				fk_entry_id = '$application_pk'
				AND tag $where '$tag'
				ORDER BY $order_by";
	$result = pg_query($dbconn, $query);

	return $result;

}

function getActive_Participants($application_pk,$order_by) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

	$query = "SELECT *
				FROM list_transform_participant
				WHERE
				fk_entry_id = '$application_pk'
				AND (tag = '5' OR tag = '6')
				ORDER BY $order_by";

	return $query;

}

function getProgram_maxParticipants($program_id) {
	if($program_id == "1")
	  $max_participants = 30;
	else if ($program_id == "2")
	  $max_participants = 40;
	else if ($program_id == "3")
	  $max_participants = 30;
	else if ($program_id == "4")
	  $max_participants = 45;

	return $max_participants;
}


	function insertTransform_attendance($insert_pk,$application_pk,$attendance_set,$var_1,$var_2,$var_3,$var_4,$var_5,$var_6,$var_7,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "INSERT INTO log_transform_attendance
		 (fk_participant_pk,fk_application_pk,attendance_set,variable_1,variable_2,variable_3,variable_4,variable_5,variable_6,variable_7,updated_date,updated_by)
		 VALUES
		 ('$insert_pk','$application_pk','$attendance_set','$var_1','$var_2','$var_3','$var_4','$var_5','$var_6','$var_7',TIMESTAMP '$timestamp','$username')";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function updateTransform_attendance($insert_pk,$application_pk,$attendance_set,$var_1,$var_2,$var_3,$var_4,$var_5,$var_6,$var_7,$username)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE log_transform_attendance
					   SET
					   attendance_set = '$attendance_set',
					   variable_1 = '$var_1',
					   variable_2 = '$var_2',
					   variable_3 = '$var_3',
					   variable_4 = '$var_4',
					   variable_5 = '$var_5',
					   variable_6 = '$var_6',
					   variable_7 = '$var_7',
					   updated_date = TIMESTAMP '$timestamp',
					   updated_by = '$username'

					   WHERE fk_participant_pk = '$insert_pk'
					   AND fk_application_pk = '$application_pk'";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function insertTransform_pastor_attendance($pastor_pk,$application_pk,$attendance_set,$username) {
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "INSERT INTO log_transform_pastor_attendance
		 (fk_pastor_pk,fk_application_pk,attendance_set,updated_date,updated_by)
		 VALUES
		 ('$pastor_pk','$application_pk','$attendance_set',TIMESTAMP '$timestamp','$username')";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function updateTransform_pastor_attendance($pastor_pk,$application_pk,$attendance_set,$username) {
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		$query = "UPDATE log_transform_pastor_attendance
					   SET
					   attendance_set = '$attendance_set',
					   updated_date = TIMESTAMP '$timestamp',
					   updated_by = '$username'

					   WHERE fk_pastor_pk = '$pastor_pk'
					   AND fk_application_pk = '$application_pk'";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function checkTransform_weekly_existence($application_pk,$week)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT *
						 FROM log_transform_weekly
						 WHERE fk_application_pk = '$application_pk'
						 AND week_number = '$week'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$i = $row['0'];

		return $i;
	}

	function insertTransform_weekly($application_pk,$class,$week,$value,$username) {
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$dt = new DateTime();
		$timestamp = $dt->format('Y-m-d H:i:s');

		//check existence in database
		$exist = checkTransform_weekly_existence($application_pk,$week);

		if($exist == "") {
			$query = "INSERT INTO log_transform_weekly
			 (week_number,
			  fk_application_pk,
			  $class,
			  updated_by,
			  updated_date)

			 VALUES
			 ('$week',
			 	'$application_pk',
			 	'$value',
				'$username',
				TIMESTAMP '$timestamp')";
		}

		else {
			$query = "UPDATE log_transform_weekly
								SET $class = '$value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp'
								WHERE fk_application_pk = '$application_pk'
								AND week_number = '$week'";
		}

		$result = pg_query($dbconn, $query);
		if (!$result)	{
			echo "An error occurred.\n";
			exit;
		}
	}

	function getAttendance_total_byBase($base_id,$week)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$query = "SELECT count(*)
								FROM list_transform_application


								WHERE base_id = $base_id AND attendance_set ilike '%$week%'

								union

								SELECT count(*)
								FROM list_transform_application
								right join log_transform_attendance
								on list_transform_application.id = log_transform_attendance.fk_application_pk
								WHERE base_id = $base_id AND attendance_set ilike '%$week%'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$i = $row['0'];

		return $i;
	}

	function getAttendance_total_byBase_byClass($base_id,$week,$class)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		if($class == "counselor") {
		$query = "SELECT count(*)
							FROM list_transform_application
							left join log_transform_attendance
							on list_transform_application.id = log_transform_attendance.fk_application_pk
							left join list_transform_participant
							on log_transform_attendance.fk_participant_pk = list_transform_participant.id
							where base_id = '$base_id'
							AND attendance_set ilike '%$week%'
							AND category = '20' OR category = '21' OR category = '22'";
		}
		else if($class == "participant") {
		$query = "SELECT count(*)
							FROM list_transform_application
							left join log_transform_attendance
							on list_transform_application.id = log_transform_attendance.fk_application_pk
							left join list_transform_participant
							on log_transform_attendance.fk_participant_pk = list_transform_participant.id
							where base_id = '$base_id'
							AND attendance_set ilike '%$week%'
							AND (category = '1' OR category = '2' OR category = '3' OR category = '4' OR category = '5')";
		}

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$i = $row['0'];

		return $i;
	}
	function getAttendance_total_byCommunity_byClass($application_pk,$week,$class)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		if($class == "counselor") {
		$query = "SELECT count(*)
							FROM log_transform_attendance
							left join list_transform_participant
							on log_transform_attendance.fk_participant_pk = list_transform_participant.id
							where fk_application_pk = '$application_pk'
							AND attendance_set ilike '%$week%'
							AND (category = '20' OR category = '21' OR category = '22')";
		}
		else if($class == "participant") {
		$query = "SELECT count(*)
							FROM log_transform_attendance
							left join list_transform_participant
							on log_transform_attendance.fk_participant_pk = list_transform_participant.id
							where fk_application_pk = '$application_pk'
							AND attendance_set ilike '%$week%'
							AND (category = '1' OR category = '2' OR category = '3' OR category = '4' OR category = '5')";
		}

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$i = $row['0'];

		return $i;
	}

	function getTransform_weekly_value($week,$application_pk) {
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$query = "SELECT *
								FROM log_transform_weekly
								WHERE week_number = '$week'
								AND fk_application_pk = '$application_pk'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

			return $row;
	}

	function search_ID($id, $table)	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		if($table == "people")
		{
			$query = "SELECT *
								FROM list_transform_participant
								WHERE participant_id ilike '%$id%'";
		}
		else if($table == "community")
		{
			$query = "SELECT *
								FROM list_transform_application
								WHERE community_id ilike '%$id%'";
		}

		return $query;
	}

	function deleteParticipant($participant_pk)	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "DELETE FROM list_transform_participant
							WHERE id=$participant_pk";

		$result = pg_query($dbconn, $query);
		if (!$result)
			{
				echo "An error occurred.\n";
				exit;
			}
	}

	function countApplication_withoutProfile_byBase($base_id)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		$query = "SELECT count(*)
							FROM list_transform_application
							WHERE pastor_id = '0'
							AND tag <> '0'
							AND base_id = '$base_id'";

		$result = pg_query($dbconn, $query);
		$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
		$i = $row['0'];

		return $i;
	}

	function getBatch_list()
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
		//$query = "SELECT * FROM list_batch";

		$query = "SELECT year, batch
							FROM list_batch_week
							GROUP BY year, batch
							ORDER BY year, batch
							LIMIT 5";

		$result = pg_query($dbconn, $query);

		return $result;
	}

	function attendance_variable_count($application_pk, $x, $what)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		if($what == "4ps") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_1 = '$x'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "ngo") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_2 = '$x'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "mfi") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_3 = '$x'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "birth_cert") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_4 = '$x'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "church") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_5 = '$x'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "baptised") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_6 = '$x'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "h2h") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and variable_7 ilike '%$x%'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		else if($what == "attendance") {
			$query = "SELECT count(*)
								from log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								and attendance_set ilike '%$x%'
								and (list_transform_participant.tag = '5' or list_transform_participant.tag = '6' or list_transform_participant.tag = '9')";
		}

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function attendance_breakdown($application_pk, $x, $what, $tag, $which)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		if($which == "1")
			$query = "SELECT count(*)
								FROM log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								AND list_transform_participant.tag = '$tag'
								AND $what = '$x'
								AND (category = '1' OR category = '2' OR category = '3' OR category = '4' OR category = '5' OR category = '6')";
		else if($which == "2")
			$query = "SELECT count(*)
								FROM log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								AND list_transform_participant.tag = '$tag'
								AND $what ilike '%$x%'
								AND (category = '1' OR category = '2' OR category = '3' OR category = '4' OR category = '5' OR category = '6')";
		else if($which == "3")
			$query = "SELECT count(*)
								FROM log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								AND list_transform_participant.tag = '$tag'
								AND $what = '$x'
								AND (category = '20' OR category = '21' OR category = '22')";
		else if($which == "4")
			$query = "SELECT count(*)
								FROM log_transform_attendance
								left join list_transform_participant
								on log_transform_attendance.fk_participant_pk = list_transform_participant.id
								where fk_application_pk = '$application_pk'
								AND list_transform_participant.tag = '$tag'
								AND $what ilike '%$x%'
								AND (category = '20' OR category = '21' OR category = '22')";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	function attendance_people_count($application_pk,$tag,$category)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT count(*)
								FROM list_transform_participant
								WHERE fk_entry_id = '$application_pk'
								AND tag = '$tag'
								AND category = '$category'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row['count'];
	}

	//livelihood functions
	function getBIB_kit()
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT *
							FROM list_bib
							WHERE tag <> 0
							ORDER BY kit_name";

		$result = pg_query($dbconn, $query);
		return $result;
	}

	function getBIB_community($application_pk)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT *
							FROM list_bib_community
							WHERE fk_application_pk = '$application_pk'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row;
	}

	function getBIB_string($id)
	{
		$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
		$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		$query = "SELECT kit_name
							FROM list_bib
							WHERE id = '$id'";

		$result = pg_query($dbconn, $query);
		$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

		return $row[0];
	}

		function updateCommunity_BIB($application_pk,$week,$bib_id,$username)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$dt = new DateTime();
			$timestamp = $dt->format('Y-m-d H:i:s');

			$query = "UPDATE list_bib_community SET $week = '$bib_id', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE fk_application_pk = '$application_pk'";

			$result = pg_query($dbconn, $query);
		}

		function updateCommunity_BIB_capital($application_pk,$week,$capital_value,$username)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$dt = new DateTime();
			$timestamp = $dt->format('Y-m-d H:i:s');

			$query = "UPDATE list_bib_community SET $week = '$capital_value', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE fk_application_pk = '$application_pk'";

			$result = pg_query($dbconn, $query);
		}

		function insertCommunity_BIB_person($participant_pk,$class,$type,$kit_count,$bib_class,$bib_pk,$week,$capital_total,$username)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$dt = new DateTime();
			$timestamp = $dt->format('Y-m-d H:i:s');

			$query = "INSERT INTO list_bib_participant
			 (fk_participant_pk,class,type,kit_count,capital,balance,bib_class,fk_bib_community_pk,week,updated_date,updated_by)
			 VALUES
			 ($participant_pk,'$class',$type,$kit_count,$capital_total,$capital_total,$bib_class,$bib_pk,'$week',TIMESTAMP '$timestamp','$username')";

			$result = pg_query($dbconn, $query);
			if (!$result)
				{
					echo "An error occurred.\n";
					exit;
				}
		}

		function insertCommunity_BIB_payment($bib_pk,$bib_participant_pk,$pay_week,$pay_sale,$pay_cash,$pay_noncash,$username,$week_id,$class)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$dt = new DateTime();
			$timestamp = $dt->format('Y-m-d H:i:s');

			$query = "INSERT INTO log_bib_payment
			 (fk_bib_community_pk,fk_bib_participant_pk,week_entry,sale,payment_cash,payment_noncash,created_date,created_by,week_id,class)
			 VALUES
			 ($bib_pk,$bib_participant_pk,$pay_week,$pay_sale,$pay_cash,$pay_noncash,TIMESTAMP '$timestamp','$username','$week_id','$class')";

			$result = pg_query($dbconn, $query);
			if (!$result)
				{
					echo "An error occurred.\n";
					exit;
				}
			else {
				//echo "SUCCESS: Payment Added!";
			}
		}

		function getBIB_participant($bib_pk,$week)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT *
						 FROM list_bib_participant
						 WHERE
						 fk_bib_community_pk ='$bib_pk'
						 AND
						 week = '$week'
						 ORDER BY id";

			$result = pg_query($dbconn, $query);

			return $result;
		}

		function getBIB_dispersal_max($week,$bib_class)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT variable1
						 FROM list_bib
						 WHERE
						 id ='$bib_class'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$max = $row[0];

			return $max;
		}

		function sumBIB_community_dispersal($week_id,$bib_pk)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT sum(kit_count)
								FROM list_bib_participant
								WHERE week = '$week_id'
								AND fk_bib_community_pk = '$bib_pk'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$sum = $row[0];

			return $sum;
		}

		function sumBIB_participant_payment($week_id,$bib_participant_pk,$class)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT sum(payment_cash + payment_noncash)
								FROM log_bib_payment
								WHERE week_id = '$week_id'
								AND class = '$class'
								AND fk_bib_participant_pk = '$bib_participant_pk'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$sum = $row[0];

			return $sum;
		}

		function sumBIB_community_balance($week_id,$bib_community_pk)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT sum(balance)
								FROM list_bib_participant
								WHERE week = '$week_id'
								AND fk_bib_community_pk = '$bib_community_pk'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$sum = $row[0];

			return $sum;
		}

		function sumBIB_community_payment($bib_pk,$week_id)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT sum(payment_cash + payment_noncash)
						 FROM log_bib_payment
						 WHERE fk_bib_community_pk ='$bib_pk'
						 AND week_id = '$week_id'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

			return $row['0'];
		}

		function sumBIB_community_sales($bib_pk,$week_id)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT count(*)
								FROM log_bib_payment
								WHERE sale = 't'
								AND fk_bib_community_pk = '$bib_pk'
								AND week_id = '$week_id'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$sum = $row[0];

			return $sum;
		}

		function getBIB_payment_log_byWeek($bib_pk,$week_id)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$query = "SELECT *
						 FROM log_bib_payment
						 left join list_bib_participant
						 on log_bib_payment.fk_bib_participant_pk = list_bib_participant.id
						 WHERE
						 log_bib_payment.fk_bib_community_pk ='$bib_pk'
						 AND
						 week_id = '$week_id'
						 ORDER BY week_entry DESC, fk_bib_participant_pk";

			$result = pg_query($dbconn, $query);

			return $result;
		}

		function getList_BIB_participant_details($bib_participant_pk,$class)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		  $query = "SELECT *
						 FROM list_bib_participant
						 WHERE
						 (id = '$bib_participant_pk'
						 AND class = '$class')";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);

			return $row;
		}

		function checkDuplicate_BIB_payment($bib_participant_pk,$week_entry,$class)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		  $query = "SELECT *
						 FROM log_bib_payment
						 WHERE
						 fk_bib_participant_pk = '$bib_participant_pk'
						 AND
						 week_entry = '$week_entry'
						 AND
						 class = '$class'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$return = ($row['id'] != '') ? 1 : 0;

			return $return;
		}

		function checkDuplicate_BIB_person($fk_participant_pk,$week_entry)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

		  $query = "SELECT *
						 FROM list_bib_participant
						 WHERE
						 fk_participant_pk = '$fk_participant_pk'
						 AND
						 week = '$week_entry'";

			$result = pg_query($dbconn, $query);
			$row = pg_fetch_array($result,NULL,PGSQL_BOTH);
			$return = ($row['id'] != '') ? 1 : 0;

			return $return;
		}

		function updateBIB_participant_balance($bib_participant_pk,$pay_total,$username,$class)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());

			$dt = new DateTime();
			$timestamp = $dt->format('Y-m-d H:i:s');

			$participant = getList_BIB_participant_details($bib_participant_pk,$class);
			$balance = $participant['balance'] - $pay_total;
			$query = "UPDATE list_bib_participant SET balance = '$balance', updated_by = '$username', updated_date = TIMESTAMP '$timestamp' WHERE (id = '$bib_participant_pk' AND class = '$class')";

			$result = pg_query($dbconn, $query);
		}

		function getBIB_week_number($i) {
			if($i == "week_a")
				$week_num = "5";
			else if($i == "week_b")
				$week_num = "7";
			else if($i == "week_c")
				$week_num = "9";
			else if($i == "week_d")
				$week_num = "10";
			else if($i == "week_e")
				$week_num = "11";
			else
				$week_num = "0";

			return $week_num;
		}

		function generateBIB_community($fk_application_pk)
		{
			$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
			$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
			$query = "INSERT INTO list_bib_community
			 (fk_application_pk)
			 VALUES
			 ('$fk_application_pk')";

			$result = pg_query($dbconn, $query);
			if (!$result)
				{
					echo "An error occurred.\n";
					exit;
				}
		}

//report functions
function countClass_data($class,$tag,$group_string,$data,$equal,$data_value) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
					FROM list_transform_participant
					left join log_transform_attendance
					on list_transform_participant.id = log_transform_attendance.fk_participant_pk
					WHERE category = '$class'
					AND list_transform_participant.tag = '$tag'
					AND $data $equal '$data_value'
					AND participant_id ilike '$group_string%'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['count'];
}

function countClass_instances($class,$tag,$group_string) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
					FROM list_transform_participant
					WHERE category = '$class'
					AND tag = '$tag'
					AND participant_id ilike '$group_string%'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['count'];
}

function countTotal_participant($class,$group_string) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT count(*)
					FROM list_transform_participant
					WHERE category = '$class'
					AND participant_id ilike '$group_string%'
					AND tag > 3";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row['count'];
}

function countTotal_visitor_grad($group_string) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$query = "SELECT SUM(visitor_graduate)
					FROM list_transform_application
					WHERE community_id ilike '$group_string%'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row[0];
}

function count_attendance($yearbatch,$base,$instance,$which) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$year = substr($yearbatch,2,-1);
	$batch = substr($yearbatch,-1);
	$base = str_pad($base, 2, 0, STR_PAD_LEFT);
	if($which == "general") {
		$which = "attendance_set";
	}
	else if($which == "h2h") {
		$which = "variable_7";
	}

	$query = "SELECT count(*)
	FROM list_transform_application
	LEFT JOIN log_transform_attendance
	ON list_transform_application.id = log_transform_attendance.fk_application_pk
	WHERE community_id ilike '".$year."".$base."_"."".$batch."%'
	AND $which ilike '%$instance%'";

	$result = pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row[0];
}


function count_attendance_byProgram_byClass($yearbatch,$base,$program,$instance,$which,$class) {
	$conn_string = "host=localhost port=5432 dbname=ProjectTomorrow user=postgres password=password";
	$dbconn = pg_connect($conn_string) or die("Can't connect to database".pg_last_error());
	$year = substr($yearbatch,2,-1);
	$batch = substr($yearbatch,-1);
	$base = str_pad($base, 2, 0, STR_PAD_LEFT);
	$id_string = $year.$base.$program.$batch."%";
	if($which == "general") {
		$which = "attendance_set";
	}
	else if($which == "h2h") {
		$which = "variable_7";
	}

	if($class == "participant") {
		$query = "SELECT count(*)
		FROM list_transform_participant
		LEFT JOIN log_transform_attendance
		ON list_transform_participant.id = log_transform_attendance.fk_participant_pk
		WHERE participant_id ilike '$id_string'
		AND $which ilike '%$instance%'
		AND (category = '1' OR category = '2' OR category = '3' OR category = '4' OR category = '5' OR category = '6')";
	}
	else if($class == "counselor") {
		$query = "SELECT count(*)
		FROM list_transform_participant
		LEFT JOIN log_transform_attendance
		ON list_transform_participant.id = log_transform_attendance.fk_participant_pk
		WHERE participant_id ilike '$id_string'
		AND $which ilike '%$instance%'
		AND (category = '20' OR category = '21' OR category = '22')";
	}

	$result=pg_query($dbconn, $query);
	$row=pg_fetch_array($result,NULL,PGSQL_BOTH);

	return $row[0];
}

function participant_tag_string($tag) {
	if($tag == 0) {
		$string = "Deleted";
	}
	else if($tag == 1) {
		$string = "New";
	}
	else if($tag == 2) {
		$string = "Qualified";
	}
	else if($tag == 3) {
		$string = "Disqualified";
	}
	else if($tag == 4) {
		$string = "Flagged";
	}
	else if($tag == 5) {
		$string = "Selected";
	}
	else if($tag == 6) {
		$string = "Graduate";
	}
	else if($tag == 7) {
		$string = "Non-Graduate";
	}
	else if($tag == 8) {
		$string = "Ghost";
	}
	else if($tag == 9) {
		$string = "Dropped";
	}

	return $string;
}
?>
